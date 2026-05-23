<?php

namespace App\Http\Controllers;

use App\Models\Creator;
use App\Models\CreatorGift;
use App\Services\DarajaService;
use App\Services\VoucherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CreatorController extends Controller
{
    public function __construct(
        private DarajaService $daraja,
        private VoucherService $vouchers,
    ) {}

    // ── Public: registration ──────────────────────────────────────────────

    public function registerForm()
    {
        return view('creator.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'handle'       => ['required', 'string', 'max:30', 'unique:creators,handle', 'regex:/^[a-z0-9._-]+$/'],
            'display_name' => ['required', 'string', 'max:60'],
            'bio'          => ['nullable', 'string', 'max:200'],
            'phone'        => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'password'     => ['required', 'string', 'min:6', 'confirmed'],
            'goal_title'   => ['nullable', 'string', 'max:80'],
            'goal_amount'  => ['nullable', 'numeric', 'min:100'],
            'min_gift'     => ['nullable', 'numeric', 'min:50'],
        ]);

        $creator = Creator::create([
            'handle'                 => strtolower($data['handle']),
            'display_name'           => $data['display_name'],
            'bio'                    => $data['bio'] ?? null,
            'payout_phone_encrypted' => \Illuminate\Support\Facades\Crypt::encryptString($data['phone']),
            'password'               => Hash::make($data['password']),
            'goal_title'             => $data['goal_title'] ?? null,
            'goal_amount'            => $data['goal_amount'] ?? null,
            'min_gift_amount'        => $data['min_gift'] ?? 50,
            'alert_token'            => Creator::generateAlertToken(),
        ]);

        Session::put('creator_id', $creator->id);
        return redirect()->route('creator.dashboard');
    }

    // ── Auth ──────────────────────────────────────────────────────────────

    public function loginForm()
    {
        return view('creator.login');
    }

    public function login(Request $request)
    {
        $data    = $request->validate(['handle' => 'required', 'password' => 'required']);
        $creator = Creator::where('handle', strtolower($data['handle']))->first();

        if (! $creator || ! Hash::check($data['password'], $creator->password)) {
            return back()->withErrors(['handle' => 'Invalid handle or password.']);
        }

        Session::put('creator_id', $creator->id);
        return redirect()->route('creator.dashboard');
    }

    public function logout()
    {
        Session::forget('creator_id');
        return redirect()->route('creator.login');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────

    public function dashboard()
    {
        $creator = Creator::findOrFail(Session::get('creator_id'));
        $gifts   = $creator->gifts()->where('status', 'paid')->latest()->take(20)->get();

        $stats = [
            'today'   => $creator->gifts()->where('status', 'paid')->whereDate('created_at', today())->sum('payout_amount'),
            'month'   => $creator->gifts()->where('status', 'paid')->whereMonth('created_at', now()->month)->sum('payout_amount'),
            'total'   => $creator->total_received,
            'count'   => $creator->gifts()->where('status', 'paid')->count(),
        ];

        return view('creator.dashboard', compact('creator', 'gifts', 'stats'));
    }

    public function updateProfile(Request $request)
    {
        $creator = Creator::findOrFail(Session::get('creator_id'));
        $data    = $request->validate([
            'display_name' => ['required', 'string', 'max:60'],
            'bio'          => ['nullable', 'string', 'max:200'],
            'goal_title'   => ['nullable', 'string', 'max:80'],
            'goal_amount'  => ['nullable', 'numeric', 'min:100'],
            'min_gift'     => ['nullable', 'numeric', 'min:50'],
        ]);

        $creator->update([
            'display_name'   => $data['display_name'],
            'bio'            => $data['bio'] ?? null,
            'goal_title'     => $data['goal_title'] ?? null,
            'goal_amount'    => $data['goal_amount'] ?? null,
            'min_gift_amount'=> $data['min_gift'] ?? 50,
        ]);

        return back()->with('success', 'Profile updated.');
    }

    // ── Public gift page ──────────────────────────────────────────────────

    public function publicPage(string $handle)
    {
        $creator = Creator::where('handle', $handle)->where('is_active', true)->firstOrFail();
        return view('creator.public', compact('creator'));
    }

    public function sendGift(Request $request, string $handle)
    {
        $creator = Creator::where('handle', $handle)->where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'amount'   => ['required', 'numeric', 'min:' . $creator->min_gift_amount, 'max:150000'],
            'phone'    => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'fan_name' => ['nullable', 'string', 'max:60'],
            'message'  => ['nullable', 'string', 'max:200'],
        ]);

        $fees = $this->vouchers->calculateFees((float) $data['amount']);

        $gift = CreatorGift::create([
            'creator_id'    => $creator->id,
            'gross_amount'  => $fees['gross'],
            'payout_amount' => $data['amount'],
            'fee_in'        => $fees['feeIn'],
            'fee_out'       => $fees['feeOut'],
            'fan_name'      => $data['fan_name'] ?? null,
            'message'       => $data['message'] ?? null,
            'status'        => 'pending',
        ]);

        $ref = 'CRG-' . $creator->handle . '-' . $gift->id;
        $stk = $this->daraja->stkPush($fees['gross'], $data['phone'], $ref, 'Pregota Gift');

        if (isset($stk['CheckoutRequestID'])) {
            $gift->update(['mpesa_checkout_id' => $stk['CheckoutRequestID']]);
            return response()->json(['success' => true, 'message' => 'STK Push sent. Enter your M-Pesa PIN.']);
        }

        $gift->delete();
        return response()->json(['success' => false, 'message' => 'Could not initiate payment. Please try again.'], 422);
    }

    // ── Creator search ────────────────────────────────────────────────────

    public function search(Request $request)
    {
        $q = trim($request->input('q', ''));
        if (strlen($q) < 2) {
            return response()->json([]);
        }

        $creators = Creator::where('is_active', true)
            ->where(function ($query) use ($q) {
                $query->where('handle', 'like', '%' . $q . '%')
                      ->orWhere('display_name', 'like', '%' . $q . '%');
            })
            ->select('handle', 'display_name', 'bio', 'photo_url', 'min_gift_amount', 'total_received')
            ->withCount(['gifts' => fn ($q) => $q->where('status', 'paid')])
            ->orderByDesc('total_received')
            ->limit(8)
            ->get();

        return response()->json($creators);
    }

    // ── OBS alert overlay ─────────────────────────────────────────────────

    public function alertOverlay(string $handle, string $token)
    {
        $creator = Creator::where('handle', $handle)->where('alert_token', $token)->firstOrFail();
        return view('creator.alert', compact('creator'));
    }

    public function alertPoll(string $handle, string $token)
    {
        $creator = Creator::where('handle', $handle)->where('alert_token', $token)->firstOrFail();

        $gift = $creator->gifts()
            ->where('status', 'paid')
            ->where('alert_shown', false)
            ->latest()
            ->first();

        if (! $gift) {
            return response()->json(['gift' => null]);
        }

        $gift->update(['alert_shown' => true]);

        return response()->json([
            'gift' => [
                'amount'   => $gift->payout_amount,
                'fan_name' => $gift->fan_name ?: 'Anonymous',
                'message'  => $gift->message,
            ],
        ]);
    }

    // ── Called by MpesaController on STK confirmation ─────────────────────

    public function confirmGift(string $checkoutRequestId, string $mpesaCode, float $amount): bool
    {
        $gift = CreatorGift::where('mpesa_checkout_id', $checkoutRequestId)->first();
        if (! $gift) return false;

        $creator = $gift->creator;
        $gift->update([
            'status'                  => 'paid',
            'mpesa_confirmation_code' => $mpesaCode,
        ]);

        $creator->increment('total_received', $gift->payout_amount);

        // Auto B2C payout to creator's encrypted phone
        $phone = $creator->getPayoutPhone();
        $b2c   = $this->daraja->b2cPayout(
            (int) $gift->payout_amount,
            $phone,
            'Pregota Creator Gift'
        );

        if (isset($b2c['ConversationID'])) {
            $gift->update(['b2c_conversation_id' => $b2c['ConversationID']]);
        }

        return true;
    }
}
