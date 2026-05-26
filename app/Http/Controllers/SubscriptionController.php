<?php

namespace App\Http\Controllers;

use App\Models\PayLink;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    public function __construct(private SellerService $seller, private DarajaService $daraja) {}

    // ── Seller creates plan (from dashboard) ────────────────────────────
    public function savePlan(Request $request)
    {
        $payLink = PayLink::findOrFail(session('seller_id'));

        $data = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:300'],
            'amount'      => ['required', 'integer', 'min:10', 'max:500000'],
            'frequency'   => ['required', 'in:monthly,quarterly,annually'],
        ]);

        $payLink->subscriptionPlans()->create($data + ['is_active' => true]);
        return back()->with('success', 'Subscription plan created.');
    }

    public function togglePlan(Request $request, SubscriptionPlan $plan)
    {
        if ($plan->pay_link_id !== session('seller_id')) abort(403);
        $plan->update(['is_active' => ! $plan->is_active]);
        return back()->with('success', 'Plan updated.');
    }

    // ── Public subscribe page ────────────────────────────────────────────
    public function show(SubscriptionPlan $plan)
    {
        if (! $plan->is_active) abort(404);
        $plan->load('payLink:id,business_name,handle');
        return view('subscription.subscribe', compact('plan'));
    }

    public function pay(Request $request, SubscriptionPlan $plan)
    {
        if (! $plan->is_active) abort(404);

        $data = $request->validate([
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
        ]);

        $hash = $this->seller->hashPhone($data['phone']);

        // Check if already active subscriber
        $existing = Subscription::where('plan_id', $plan->id)
            ->where('phone_hash', $hash)
            ->where('status', 'active')
            ->first();

        if ($existing && ! $existing->isDue()) {
            return response()->json(['error' => 'already_active', 'message' => 'You already have an active subscription. Next due: ' . $existing->next_due_at->format('d M Y')], 422);
        }

        $stk = $this->daraja->stkPush(
            $plan->amount,
            $data['phone'],
            'SUB-' . $plan->id,
            $plan->payLink->business_name . ' ' . ucfirst($plan->frequency) . ' Plan'
        );

        if (empty($stk['CheckoutRequestID'])) {
            return response()->json(['error' => 'stk_failed', 'message' => 'Could not send M-Pesa prompt.'], 502);
        }

        $checkoutId = $stk['CheckoutRequestID'];

        Subscription::updateOrCreate(
            ['plan_id' => $plan->id, 'phone_hash' => $hash],
            [
                'status'              => 'active',
                'checkout_request_id' => $checkoutId,
                'reminder_token'      => Str::random(64),
                'next_due_at'         => $plan->nextDueDate(),
            ]
        );

        return response()->json(['checkout_request_id' => $checkoutId]);
    }

    public function status(Request $request, SubscriptionPlan $plan)
    {
        $checkoutId  = $request->query('checkout_request_id');
        $subscription = Subscription::where('checkout_request_id', $checkoutId)->first();

        if (! $subscription) return response()->json(['status' => 'pending']);

        return response()->json([
            'status'         => $subscription->status === 'active' && $subscription->last_paid_at ? 'confirmed' : 'pending',
            'receipt_number' => $subscription->receipt_number,
            'next_due'       => $subscription->next_due_at?->format('d M Y'),
        ]);
    }

    // ── Subscriber reminder link ─────────────────────────────────────────
    public function reminder(string $token)
    {
        $subscription = Subscription::where('reminder_token', $token)->with('plan.payLink')->firstOrFail();
        return view('subscription.reminder', compact('subscription'));
    }

    // ── Seller subscribers list ─────────────────────────────────────────
    public function subscribers(SubscriptionPlan $plan)
    {
        if ($plan->pay_link_id !== session('seller_id')) abort(403);
        $subscribers = $plan->subscriptions()->latest()->paginate(50);
        return view('subscription.subscribers', compact('plan', 'subscribers'));
    }
}
