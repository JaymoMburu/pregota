<?php

namespace App\Http\Controllers;

use App\Models\BillSplit;
use App\Models\CustomerOptIn;
use App\Models\StaffMember;
use App\Models\TipFeedback;
use App\Services\BillSplitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StaffAuthController extends Controller
{
    public function registerForm()
    {
        return view('staff.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:60'],
            'handle'       => ['required', 'string', 'max:30', 'alpha_num', 'unique:staff_members,handle'],
            'role'         => ['required', 'string', 'max:60'],
            'login_phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/', 'unique:staff_members,login_phone'],
            'payout_phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'avatar_emoji' => ['nullable', 'string', 'max:10'],
        ]);

        $staff = new StaffMember([
            'business_id'  => null,
            'handle'       => strtolower($data['handle']),
            'name'         => $data['name'],
            'role'         => $data['role'],
            'login_phone'  => $data['login_phone'],
            'password'     => Hash::make($data['password']),
            'avatar_emoji' => $data['avatar_emoji'] ?? '😊',
            'alert_token'  => Str::random(32),
            'active'       => true,
            'is_solo'      => true,
        ]);

        $staff->setPayoutPhone($data['payout_phone']);
        $staff->save();

        session(['solo_staff_id' => $staff->id]);

        return redirect()->route('staff.dashboard')
            ->with('success', 'Welcome to Pregota! Share your tip page link to start receiving tips.');
    }

    public function loginForm()
    {
        return view('staff.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login_phone' => ['required', 'string'],
            'password'    => ['required', 'string'],
        ]);

        // Normalise: 07xx → 254, strip +
        $phone = preg_replace('/^\+/', '', $data['login_phone']);
        if (str_starts_with($phone, '0')) {
            $phone = '254' . substr($phone, 1);
        }

        $staff = StaffMember::where('is_solo', true)
            ->where(function ($q) use ($data, $phone) {
                $q->where('login_phone', $data['login_phone'])
                  ->orWhere('login_phone', '0' . substr($phone, 3))
                  ->orWhere('login_phone', '+' . $phone)
                  ->orWhere('login_phone', $phone);
            })
            ->first();

        if (! $staff || ! Hash::check($data['password'], $staff->password)) {
            return back()->withErrors(['login_phone' => 'Invalid phone number or password.']);
        }

        session(['solo_staff_id' => $staff->id]);

        return redirect()->route('staff.dashboard');
    }

    public function logout(Request $request)
    {
        $request->session()->forget('solo_staff_id');
        return redirect()->route('staff.landing');
    }

    public function dashboard(Request $request)
    {
        $staff = StaffMember::findOrFail(session('solo_staff_id'));

        $recentTips = $staff->tips()
            ->where('status', 'paid')
            ->latest('paid_at')
            ->limit(20)
            ->get();

        $recentFeedback = $staff->feedback()
            ->with('tip')
            ->latest()
            ->limit(10)
            ->get();

        $todaySplits = \App\Models\BillSplit::where('tip_handle', $staff->handle)
            ->whereDate('created_at', today())
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'today'              => $staff->todayTips(),
            'month'              => $staff->monthTips(),
            'total'              => $staff->tips()->where('status', 'paid')->sum('tip_amount'),
            'count'              => $staff->tipCount(),
            'avg_rating'         => $staff->averageRating(),
            'rating_count'       => $staff->feedback()->count(),
            'today_splits_total' => $todaySplits->where('status', 'settled')->sum('total_amount'),
            'today_splits_count' => $todaySplits->count(),
            'today_optins'       => CustomerOptIn::whereHas('billSplit', fn($q) =>
                                        $q->where('tip_handle', $staff->handle)
                                          ->whereDate('created_at', today())
                                    )->count(),
        ];

        $tipUrl = route('tip.page', $staff->handle);

        return view('staff.dashboard', compact('staff', 'recentTips', 'recentFeedback', 'stats', 'tipUrl', 'todaySplits'));
    }

    public function updateProfile(Request $request)
    {
        $staff = StaffMember::findOrFail(session('solo_staff_id'));

        $data = $request->validate([
            'name'              => ['required', 'string', 'max:60'],
            'role'              => ['required', 'string', 'max:60'],
            'avatar_emoji'      => ['nullable', 'string', 'max:10'],
            'payout_phone'      => ['nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'password'          => ['nullable', 'string', 'min:6', 'confirmed'],
            'till_type'         => ['nullable', 'in:paybill,till'],
            'till_number'       => ['nullable', 'digits_between:5,7'],
        ]);

        $staff->name         = $data['name'];
        $staff->role         = $data['role'];
        $staff->avatar_emoji = $data['avatar_emoji'] ?? $staff->avatar_emoji;

        if (! empty($data['payout_phone'])) {
            $staff->setPayoutPhone($data['payout_phone']);
        }

        if (! empty($data['till_number']) && ! empty($data['till_type'])) {
            $staff->setTill($data['till_number'], $data['till_type']);
        }

        if (! empty($data['password'])) {
            $staff->password = Hash::make($data['password']);
        }

        $staff->save();

        return back()->with('success', 'Profile updated.');
    }

    public function leads()
    {
        $staff = StaffMember::findOrFail(session('solo_staff_id'));

        $optIns = CustomerOptIn::whereHas('billSplit', fn($q) =>
                        $q->where('tip_handle', $staff->handle)
                    )
                    ->with('billSplit')
                    ->latest()
                    ->get();

        return view('staff.leads', compact('staff', 'optIns'));
    }

    public function chargeForm()
    {
        $staff = StaffMember::findOrFail(session('solo_staff_id'));
        return view('staff.charge', compact('staff'));
    }

    public function chargeStore(Request $request, BillSplitService $service)
    {
        $staff = StaffMember::findOrFail(session('solo_staff_id'));

        $data = $request->validate([
            'amount'      => ['required', 'integer', 'min:10', 'max:150000'],
            'description' => ['nullable', 'string', 'max:60'],
            'till_type'   => ['required_without:use_saved', 'nullable', 'in:paybill,till'],
            'till_number' => ['required_without:use_saved', 'nullable', 'digits_between:5,7'],
        ]);

        if ($request->boolean('use_saved') && $staff->hasTill()) {
            $tillNumber = $staff->getTill();
            $tillType   = $staff->till_type;
        } else {
            $tillNumber = $data['till_number'];
            $tillType   = $data['till_type'];

            if ($request->boolean('save_till')) {
                $staff->setTill($tillNumber, $tillType);
                $staff->save();
            }
        }

        $bill = $service->create([
            'business_name'      => $staff->name . ' (' . $staff->role . ')',
            'label'              => $data['description'] ?? null,
            'total_amount'       => (int) $data['amount'],
            'payout_destination' => $tillNumber,
            'payout_type'        => $tillType,
            'tip_handle'         => $staff->handle,
        ]);

        return redirect()->route('bill-split.manage', $bill->waiter_token);
    }
}
