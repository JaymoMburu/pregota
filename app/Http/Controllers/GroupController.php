<?php

namespace App\Http\Controllers;

use App\Models\ContributionGroup;
use App\Models\GroupPayment;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GroupController extends Controller
{
    public function __construct(private SellerService $seller, private DarajaService $daraja) {}

    // ── Create group form ────────────────────────────────────────────────
    public function createForm()
    {
        return view('group.create');
    }

    public function create(Request $request)
    {
        $data = $request->validate([
            'name'              => ['required', 'string', 'max:120'],
            'description'       => ['nullable', 'string', 'max:500'],
            'admin_phone'       => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'amount_per_member' => ['nullable', 'integer', 'min:10', 'max:500000'],
            'frequency'         => ['required', 'in:once,monthly,quarterly,annually'],
            'next_due'          => ['nullable', 'date', 'after:today'],
            'pin'               => ['required', 'digits:4', 'confirmed'],
        ]);

        $slug = Str::slug($data['name']) . '-' . Str::random(5);

        $group = ContributionGroup::create([
            'slug'               => $slug,
            'name'               => $data['name'],
            'description'        => $data['description'] ?? null,
            'admin_phone_encrypted' => Crypt::encryptString($data['admin_phone']),
            'admin_pin_hash'     => Hash::make($data['pin']),
            'amount_per_member'  => $data['amount_per_member'] ?? null,
            'frequency'          => $data['frequency'],
            'next_due'           => $data['next_due'] ?? null,
        ]);

        session(['group_admin_' . $group->id => true]);
        return redirect()->route('group.admin', $group->slug)
            ->with('success', 'Group created! Share the member link to start collecting.');
    }

    // ── Public pay page ─────────────────────────────────────────────────
    public function show(string $slug)
    {
        $group = ContributionGroup::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view('group.pay', compact('group'));
    }

    public function pay(Request $request, string $slug)
    {
        $group = ContributionGroup::where('slug', $slug)->where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'phone'  => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'amount' => ['nullable', 'integer', 'min:10', 'max:500000'],
        ]);

        $amount = $group->amount_per_member ?? (int) $data['amount'];
        $hash   = $this->seller->hashPhone($data['phone']);
        $period = $group->currentPeriod();

        // Prevent duplicate confirmed payment for same period
        $existing = GroupPayment::where('group_id', $group->id)
            ->where('phone_hash', $hash)
            ->where('period', $period)
            ->where('status', 'confirmed')
            ->exists();

        if ($existing) {
            return response()->json(['error' => 'already_paid', 'message' => 'You have already paid for this period.'], 422);
        }

        $stk = $this->daraja->stkPush($amount, $data['phone'], 'GRP-' . $group->id, 'Contribution: ' . $group->name);

        if (empty($stk['CheckoutRequestID'])) {
            return response()->json(['error' => 'stk_failed', 'message' => 'Could not send M-Pesa prompt.'], 502);
        }

        $checkoutId = $stk['CheckoutRequestID'];

        GroupPayment::updateOrCreate(
            ['group_id' => $group->id, 'phone_hash' => $hash, 'period' => $period],
            [
                'amount'              => $amount,
                'checkout_request_id' => $checkoutId,
                'status'              => 'pending',
                'reminder_token'      => Str::random(64),
            ]
        );

        return response()->json(['checkout_request_id' => $checkoutId]);
    }

    public function status(Request $request, string $slug)
    {
        $checkoutId = $request->query('checkout_request_id');
        $payment    = GroupPayment::where('checkout_request_id', $checkoutId)->first();

        if (! $payment) return response()->json(['status' => 'pending']);

        return response()->json([
            'status'         => $payment->status,
            'receipt_number' => $payment->receipt_number,
        ]);
    }

    // ── Reminder link ────────────────────────────────────────────────────
    public function reminder(string $token)
    {
        $payment = GroupPayment::where('reminder_token', $token)->with('group')->firstOrFail();
        return view('group.reminder', compact('payment'));
    }

    // ── Admin view ───────────────────────────────────────────────────────
    public function adminLogin(Request $request, string $slug)
    {
        $group = ContributionGroup::where('slug', $slug)->firstOrFail();
        $data  = $request->validate([
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'pin'   => ['required', 'digits:4'],
        ]);

        try {
            $adminPhone = Crypt::decryptString($group->admin_phone_encrypted);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error verifying credentials.'], 500);
        }

        $phoneMatch = $this->seller->hashPhone($data['phone']) === $this->seller->hashPhone($adminPhone);
        if (! $phoneMatch || ! Hash::check($data['pin'], $group->admin_pin_hash)) {
            return response()->json(['success' => false, 'message' => 'Incorrect phone or PIN.'], 401);
        }

        session(['group_admin_' . $group->id => true]);
        return response()->json(['success' => true]);
    }

    public function admin(string $slug)
    {
        $group   = ContributionGroup::where('slug', $slug)->firstOrFail();
        $period  = $group->currentPeriod();
        $payments = $group->payments()
            ->where('period', $period)
            ->orderByDesc('updated_at')
            ->get();

        $isAdmin = session('group_admin_' . $group->id);
        return view('group.admin', compact('group', 'payments', 'period', 'isAdmin'));
    }
}
