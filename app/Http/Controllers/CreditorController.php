<?php

namespace App\Http\Controllers;

use App\Models\CreditorAuthSession;
use App\Models\CreditorPreset;
use App\Models\Deni;
use App\Models\PregotaPass;
use App\Services\DarajaService;
use App\Services\SellerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class CreditorController extends Controller
{
    public function __construct(
        private DarajaService $daraja,
        private SellerService $seller,
    ) {}

    public function loginPage()
    {
        if (session()->has('creditor_phone_hash')) {
            return redirect()->route('creditor.dashboard');
        }
        return view('creditor.login');
    }

    public function initiateAuth(Request $request)
    {
        $data = $request->validate([
            'phone'        => ['required', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'display_name' => ['required', 'string', 'max:100'],
        ]);

        $hash       = $this->seller->hashPhone($data['phone']);
        $activePass = PregotaPass::activeFor($hash);
        $loginFee   = $activePass ? 1 : 2;

        $stk = $this->daraja->stkPush(
            phone: $data['phone'],
            amount: $loginFee,
            accountRef: 'KREDITOR',
            description: 'Pregota Deni Access',
        );

        if (! isset($stk['CheckoutRequestID'])) {
            return response()->json(['success' => false, 'message' => 'STK Push failed. Please try again.'], 422);
        }

        CreditorAuthSession::create([
            'checkout_request_id' => $stk['CheckoutRequestID'],
            'phone_hash'          => $hash,
            'phone_encrypted'     => Crypt::encryptString($data['phone']),
            'display_name'        => $data['display_name'],
        ]);

        return response()->json([
            'success'             => true,
            'checkout_request_id' => $stk['CheckoutRequestID'],
        ]);
    }

    public function pollAuth(Request $request)
    {
        $auth = CreditorAuthSession::where('checkout_request_id', $request->query('checkout_request_id'))->first();

        if (! $auth) {
            return response()->json(['status' => 'not_found']);
        }

        if ($auth->status === 'confirmed') {
            session([
                'creditor_phone_hash'      => $auth->phone_hash,
                'creditor_phone_encrypted' => $auth->phone_encrypted,
                'creditor_name'            => $auth->display_name,
                'creditor_verified_at'     => now()->timestamp,
            ]);
            return response()->json(['status' => 'confirmed', 'redirect' => route('creditor.dashboard')]);
        }

        if ($auth->status === 'failed') {
            return response()->json(['status' => 'failed']);
        }

        return response()->json(['status' => 'pending']);
    }

    public function dashboard()
    {
        $verifiedAt = session('creditor_verified_at', 0);
        if (! session()->has('creditor_phone_hash') || $verifiedAt <= now()->subHours(12)->timestamp) {
            session()->forget(['creditor_phone_hash', 'creditor_phone_encrypted', 'creditor_name', 'creditor_verified_at', 'creditor_payout_till']);
            return redirect()->route('creditor.login');
        }

        $hash = session('creditor_phone_hash');

        $allDeni = Deni::where('lender_phone_hash', $hash)
            ->with(['payments', 'items'])
            ->latest()
            ->get();

        // Restore Till payout preference from most recent deni that has one
        if (! session()->has('creditor_payout_till')) {
            $savedTill = $allDeni->whereNotNull('lender_till')->first()?->lender_till;
            if ($savedTill) session(['creditor_payout_till' => $savedTill]);
        }

        $totalOutstanding = $allDeni->where('status', '!=', 'settled')->sum(fn($d) => $d->balance());
        $totalCollected   = $allDeni->sum('amount_paid');
        $openCount        = $allDeni->whereIn('status', ['open', 'partial'])->count();

        // Build unique customer list from deni that have a known debtor phone
        $customers = $allDeni
            ->filter(fn($d) => $d->debtor_phone_hash && $d->debtor_phone_encrypted)
            ->groupBy('debtor_phone_hash')
            ->map(function ($group) {
                $latest  = $group->sortByDesc('created_at')->first();
                $phone   = Crypt::decryptString($latest->debtor_phone_encrypted);
                $masked  = preg_replace('/^(0\d{3}|\+?254\d{3})(\d{3})(\d{3})$/', '$1 $2 $3', $phone);
                $name = $group->whereNotNull('debtor_name')->sortByDesc('created_at')->first()?->debtor_name;
                return [
                    'phone_hash'      => $latest->debtor_phone_hash,
                    'phone_encrypted' => $latest->debtor_phone_encrypted,
                    'phone_masked'    => $masked,
                    'name'            => $name,
                    'display'         => $name ?? $masked,
                    'outstanding'     => $group->whereIn('status', ['open', 'partial'])->sum(fn($d) => $d->balance()),
                    'open_tabs'       => $group->whereIn('status', ['open', 'partial'])->count(),
                ];
            })
            ->values();

        $openDeni    = $allDeni->whereIn('status', ['open', 'partial']);
        $settledDeni = $allDeni->where('status', 'settled');

        return view('creditor.dashboard', compact('openDeni', 'settledDeni', 'totalOutstanding', 'totalCollected', 'openCount', 'customers'));
    }

    public function setPayoutTill(Request $request)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $data = $request->validate([
            'till' => ['nullable', 'string', 'regex:/^\d{5,7}$/'],
        ]);

        if ($data['till']) {
            session(['creditor_payout_till' => $data['till']]);
        } else {
            session()->forget('creditor_payout_till');
        }

        return response()->json(['saved' => true, 'till' => $data['till'] ?? null]);
    }

    public function savePreset(Request $request)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $data = $request->validate([
            'label'  => ['required', 'string', 'max:80'],
            'amount' => ['required', 'integer', 'min:1', 'max:50000'],
        ]);

        $hash   = session('creditor_phone_hash');
        $count  = CreditorPreset::where('creditor_phone_hash', $hash)->count();

        if ($count >= 20) {
            return response()->json(['error' => 'Maximum 20 fares allowed.'], 422);
        }

        $preset = CreditorPreset::create([
            'creditor_phone_hash' => $hash,
            'label'               => $data['label'],
            'amount'              => $data['amount'],
            'sort_order'          => $count,
        ]);

        return response()->json(['id' => $preset->id, 'label' => $preset->label, 'amount' => $preset->amount]);
    }

    public function deletePreset(int $id)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        CreditorPreset::where('id', $id)
            ->where('creditor_phone_hash', session('creditor_phone_hash'))
            ->delete();

        return response()->json(['deleted' => true]);
    }

    public function logout()
    {
        session()->forget(['creditor_phone_hash', 'creditor_phone_encrypted', 'creditor_name', 'creditor_verified_at']);
        return redirect()->route('creditor.login');
    }
}
