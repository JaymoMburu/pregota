<?php

namespace App\Http\Controllers;

use App\Models\BuyerStamp;
use App\Models\PayLink;
use App\Models\SellerPayment;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class SellerController extends Controller
{
    public function __construct(private SellerService $seller) {}

    // ── Landing ───────────────────────────────────────────────────────────
    public function landing()
    {
        return view('seller.landing');
    }

    // ── Register ──────────────────────────────────────────────────────────
    public function registerForm()
    {
        return view('seller.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'handle'        => ['required', 'string', 'max:40', 'unique:pay_links,handle', 'regex:/^[a-z0-9._-]+$/'],
            'business_name' => ['required', 'string', 'max:100'],
            'category'      => ['nullable', 'string', 'max:40'],
            'description'   => ['nullable', 'string', 'max:300'],
            'phone'         => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'default_amount'=> ['nullable', 'integer', 'min:10', 'max:150000'],
            'fixed_amount'  => ['nullable', 'boolean'],
            'password'      => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $payLink = PayLink::create([
            'handle'         => strtolower($data['handle']),
            'business_name'  => $data['business_name'],
            'category'       => $data['category'] ?? null,
            'description'    => $data['description'] ?? null,
            'phone_encrypted'=> \Illuminate\Support\Facades\Crypt::encryptString($data['phone']),
            'default_amount' => $data['default_amount'] ?? null,
            'fixed_amount'   => ! empty($data['fixed_amount']),
            'password'       => Hash::make($data['password']),
            'is_active'      => true,
        ]);

        Session::put('seller_id', $payLink->id);
        return redirect()->route('seller.dashboard')->with('success', 'Your pay link is live! Share pregota.com/pay/' . $payLink->handle);
    }

    // ── Login / Logout ────────────────────────────────────────────────────
    public function loginForm()
    {
        return view('seller.login');
    }

    public function login(Request $request)
    {
        $data    = $request->validate(['handle' => 'required', 'password' => 'required']);
        $payLink = PayLink::where('handle', strtolower($data['handle']))->first();

        if (! $payLink || ! Hash::check($data['password'], $payLink->password)) {
            return back()->withErrors(['handle' => 'Invalid handle or password.']);
        }

        Session::put('seller_id', $payLink->id);
        return redirect()->route('seller.dashboard');
    }

    public function logout()
    {
        Session::forget('seller_id');
        return redirect()->route('seller.login');
    }

    // ── Dashboard ─────────────────────────────────────────────────────────
    public function dashboard()
    {
        $payLink  = PayLink::findOrFail(session('seller_id'));
        $payments = $payLink->payments()->latest()->take(50)->get();

        return view('seller.dashboard', compact('payLink', 'payments'));
    }

    // ── Seller: save stamp card settings ─────────────────────────────────
    public function saveStampCard(Request $request)
    {
        $payLink = PayLink::findOrFail(session('seller_id'));

        $data = $request->validate([
            'stamps_required' => ['nullable', 'integer', 'min:2', 'max:50'],
            'stamp_reward'    => ['nullable', 'string', 'max:200'],
        ]);

        $payLink->update([
            'stamps_required' => $data['stamps_required'] ?: null,
            'stamp_reward'    => $data['stamp_reward'] ?: null,
        ]);

        return back()->with('success', 'Stamp card settings saved.');
    }

    // ── Public pay page ───────────────────────────────────────────────────
    public function publicPage(string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();
        $fee     = $this->seller->calculateFee($payLink->default_amount ?? 100);

        return view('seller.public', compact('payLink', 'fee'));
    }

    public function currentInfo(string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();

        return response()->json([
            'current_route' => $payLink->current_route,
            'current_fare'  => $payLink->current_fare,
        ]);
    }

    // ── Stamp info AJAX (called on phone blur before payment) ─────────────
    public function stampInfo(Request $request, string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();
        $phone   = $request->input('phone', '');

        if (! $payLink->stamps_required || ! $phone) {
            return response()->json(['enabled' => false]);
        }

        $info = $this->seller->stampInfo($payLink, $phone);

        return response()->json(['enabled' => true, ...$info]);
    }

    public function pay(Request $request, string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();

        $rules = [
            'phone'         => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'note'          => ['nullable', 'string', 'max:200'],
            'tip_amount'    => ['nullable', 'integer', 'min:0', 'max:5000'],
            'tip_recipient' => ['nullable', 'string', 'in:conductor,driver'],
            'tip_comment'   => ['nullable', 'string', 'max:200'],
        ];

        $hasConductorFare = $payLink->current_fare && $payLink->current_fare > 0;
        $hasFixedFare     = $payLink->fixed_amount && $payLink->default_amount;

        if (! $hasConductorFare && ! $hasFixedFare) {
            $rules['amount'] = ['required', 'integer', 'min:10', 'max:150000'];
        }

        $data = $request->validate($rules);

        if ($hasConductorFare) {
            $amount = (int) $payLink->current_fare;
        } elseif ($hasFixedFare) {
            $amount = (int) $payLink->default_amount;
        } else {
            $amount = (int) $data['amount'];
        }

        $tipAmount    = (int) ($data['tip_amount'] ?? 0);
        $tipRecipient = $data['tip_recipient'] ?? null;
        $tipComment   = $data['tip_comment'] ?? null;

        $payment = $this->seller->initiate(
            $amount, $data['phone'], $payLink,
            $data['note'] ?? null,
            $tipAmount, $tipRecipient, $tipComment
        );

        return response()->json([
            'success'     => true,
            'payment_id'  => $payment->id,
            'checkout_id' => $payment->mpesa_checkout_id,
            'amount'      => $payment->amount,
            'tip_amount'  => $payment->tip_amount,
            'total'       => $payment->amount + $payment->tip_amount,
            'message'     => 'STK Push sent. Enter your M-Pesa PIN.',
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['payment_id' => 'required|integer']);
        $payment = SellerPayment::with('payLink')->find($request->payment_id);

        if (! $payment) return response()->json(['status' => 'not_found']);

        $stampInfo = null;
        if ($payment->status === 'confirmed' && $payment->buyer_phone_hash) {
            $stampInfo = $this->seller->stampInfo($payment->payLink, '');
            // Lookup by hash directly since we stored the hash on payment
            if ($payment->payLink->stamps_required) {
                $stamp = BuyerStamp::where('pay_link_id', $payment->pay_link_id)
                    ->where('phone_hash', $payment->buyer_phone_hash)
                    ->first();
                $stampInfo = [
                    'stamp_count'     => $stamp?->stamp_count ?? 0,
                    'stamps_required' => $payment->payLink->stamps_required,
                    'stamps_left'     => max(0, $payment->payLink->stamps_required - ($stamp?->stamp_count ?? 0)),
                    'reward_pending'  => $stamp?->reward_pending ?? false,
                    'reward'          => $payment->payLink->stamp_reward,
                ];
            }
        }

        return response()->json([
            'status'         => $payment->status,
            'receipt_number' => $payment->receipt_number,
            'receipt_url'    => $payment->receipt_number
                ? route('receipt.show', $payment->receipt_number)
                : null,
            'stamp_info'     => $stampInfo,
        ]);
    }

    // ── Set current route / fare (conductor action) ───────────────────────
    public function setRoute(Request $request, string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();

        $data = $request->validate([
            'password'      => ['required', 'string'],
            'current_route' => ['required', 'string', 'max:100'],
            'current_fare'  => ['required', 'integer', 'min:1', 'max:10000'],
        ]);

        if (! Hash::check($data['password'], $payLink->password)) {
            return response()->json(['success' => false, 'message' => 'Wrong password.'], 403);
        }

        $payLink->update([
            'current_route' => $data['current_route'],
            'current_fare'  => $data['current_fare'],
        ]);

        return response()->json(['success' => true]);
    }

    // ── Conductor live view ───────────────────────────────────────────────
    public function liveView(string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();
        return view('seller.live', compact('payLink'));
    }

    public function recentPayments(string $handle)
    {
        $payLink  = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();
        $payments = $payLink->payments()
            ->where('status', 'confirmed')
            ->where('created_at', '>=', now()->subHours(3))
            ->latest()
            ->take(20)
            ->get();

        return response()->json($payments->map(fn($p) => [
            'id'            => $p->id,
            'amount'        => $p->amount,
            'tip_amount'    => $p->tip_amount,
            'tip_recipient' => $p->tip_recipient,
            'tip_comment'   => $p->tip_comment,
            'note'          => $p->buyer_note,
            'time'          => $p->created_at->diffForHumans(),
            'time_abs'      => $p->created_at->format('H:i:s'),
        ]));
    }

    // ── Seller discovery directory ────────────────────────────────────────
    public function directory(Request $request)
    {
        $category  = $request->query('category');
        $search    = $request->query('q');

        $query = PayLink::where('is_active', true);

        if ($category) {
            $query->where('category', $category);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', '%' . $search . '%')
                  ->orWhere('handle', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $sellers = $query->orderByDesc('payment_count')->get();

        $categories = [
            'transport'   => ['label' => 'Matatu / Transport', 'emoji' => '🚐'],
            'food'        => ['label' => 'Food & Restaurant',  'emoji' => '🍱'],
            'fashion'     => ['label' => 'Fashion & Clothing', 'emoji' => '👗'],
            'salon'       => ['label' => 'Salon & Beauty',     'emoji' => '💇'],
            'electronics' => ['label' => 'Electronics',        'emoji' => '📱'],
            'services'    => ['label' => 'Services & Freelance','emoji' => '🛠'],
            'groceries'   => ['label' => 'Groceries & Kiosk',  'emoji' => '🛒'],
            'other'       => ['label' => 'Other',              'emoji' => '🏪'],
        ];

        return view('seller.directory', compact('sellers', 'categories', 'category', 'search'));
    }

    // ── Buyer spending history ────────────────────────────────────────────
    public function me()
    {
        return view('seller.me');
    }

    public function meLookup(Request $request)
    {
        $data  = $request->validate(['phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/']]);
        $hash  = $this->seller->hashPhone($data['phone']);

        $payments = SellerPayment::where('buyer_phone_hash', $hash)
            ->where('status', 'confirmed')
            ->with('payLink')
            ->latest()
            ->take(100)
            ->get();

        // Group by pay_link for a cleaner UI
        $grouped = $payments->groupBy('pay_link_id')->map(function ($items) {
            $link  = $items->first()->payLink;
            return [
                'business_name' => $link->business_name,
                'handle'        => $link->handle,
                'category'      => $link->category,
                'identifier'    => $link->displayIdentifier(),
                'pay_url'       => route('seller.public', $link->handle),
                'total_spent'   => $items->sum('amount'),
                'count'         => $items->count(),
                'payments'      => $items->map(fn($p) => [
                    'amount'         => $p->amount,
                    'tip_amount'     => $p->tip_amount,
                    'receipt_number' => $p->receipt_number,
                    'receipt_url'    => $p->receipt_number ? route('receipt.show', $p->receipt_number) : null,
                    'date'           => $p->updated_at->format('D d M Y · H:i'),
                    'note'           => $p->buyer_note,
                ]),
            ];
        })->values();

        // Stamp cards
        $stamps = \App\Models\BuyerStamp::where('phone_hash', $hash)
            ->with('payLink')
            ->get()
            ->map(fn($s) => [
                'business_name'   => $s->payLink->business_name,
                'handle'          => $s->payLink->handle,
                'stamp_count'     => $s->stamp_count,
                'stamps_required' => $s->payLink->stamps_required,
                'reward'          => $s->payLink->stamp_reward,
                'reward_pending'  => $s->reward_pending,
            ]);

        return response()->json([
            'found'    => $payments->count() > 0,
            'grouped'  => $grouped,
            'stamps'   => $stamps,
            'total_kes'=> $payments->sum('amount'),
        ]);
    }
}
