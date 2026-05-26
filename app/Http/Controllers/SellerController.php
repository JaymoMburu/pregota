<?php

namespace App\Http\Controllers;

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

    // ── Public pay page ───────────────────────────────────────────────────
    public function publicPage(string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();
        $fee     = $this->seller->calculateFee($payLink->default_amount ?? 100);

        return view('seller.public', compact('payLink', 'fee'));
    }

    public function pay(Request $request, string $handle)
    {
        $payLink = PayLink::where('handle', $handle)->where('is_active', true)->firstOrFail();

        $rules = [
            'phone' => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'note'  => ['nullable', 'string', 'max:200'],
        ];

        if (! $payLink->fixed_amount) {
            $rules['amount'] = ['required', 'integer', 'min:10', 'max:150000'];
        }

        $data   = $request->validate($rules);
        $amount = $payLink->fixed_amount ? (int) $payLink->default_amount : (int) $data['amount'];

        $payment = $this->seller->initiate($amount, $data['phone'], $payLink, $data['note'] ?? null);

        return response()->json([
            'success'     => true,
            'payment_id'  => $payment->id,
            'checkout_id' => $payment->mpesa_checkout_id,
            'amount'      => $payment->amount,
            'message'     => 'STK Push sent. Enter your M-Pesa PIN.',
        ]);
    }

    public function checkStatus(Request $request)
    {
        $request->validate(['payment_id' => 'required|integer']);
        $payment = SellerPayment::find($request->payment_id);

        if (! $payment) return response()->json(['status' => 'not_found']);

        return response()->json(['status' => $payment->status]);
    }
}
