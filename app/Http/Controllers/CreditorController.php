<?php

namespace App\Http\Controllers;

use App\Models\BusinessLedgerEntry;
use App\Models\CreditorAuthSession;
use App\Models\CreditorContact;
use App\Models\CreditorPayout;
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

        $hash     = $this->seller->hashPhone($data['phone']);
        $loginFee = 20;

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
                'creditor_verified_day'    => now()->toDateString(),
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
        if (! session()->has('creditor_phone_hash') || session('creditor_verified_day') !== now()->toDateString()) {
            session()->forget(['creditor_phone_hash', 'creditor_phone_encrypted', 'creditor_name', 'creditor_verified_at', 'creditor_verified_day', 'creditor_payout_till']);
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

        // Contacts & recent payouts
        $contacts      = CreditorContact::where('creditor_phone_hash', $hash)->orderBy('name')->get();
        $recentPayouts = CreditorPayout::where('creditor_phone_hash', $hash)->latest()->limit(10)->get();

        // Ledger — last 30 days
        $ledger = BusinessLedgerEntry::where('creditor_phone_hash', $hash)
            ->where('entry_date', '>=', now()->subDays(30)->toDateString())
            ->orderByDesc('entry_date')->orderByDesc('id')
            ->get();

        $todayIncome   = $ledger->where('type', 'income')->where('entry_date', now()->toDateString())->sum('amount');
        $todayExpense  = $ledger->where('type', 'expense')->where('entry_date', now()->toDateString())->sum('amount');
        $monthIncome   = $ledger->where('type', 'income')->sum('amount');
        $monthExpense  = $ledger->where('type', 'expense')->sum('amount');

        // Unread notifications: confirmed payments today
        $todayPayments = \App\Models\DeniPayment::whereHas('deni', fn($q) => $q->where('lender_phone_hash', $hash))
            ->where('status', 'confirmed')
            ->whereDate('updated_at', today())
            ->count();

        return view('creditor.dashboard', compact(
            'openDeni', 'settledDeni', 'totalOutstanding', 'totalCollected', 'openCount',
            'customers', 'ledger', 'todayIncome', 'todayExpense', 'monthIncome', 'monthExpense',
            'todayPayments', 'contacts', 'recentPayouts'
        ));
    }

    public function notifications()
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $hash     = session('creditor_phone_hash');
        $payments = \App\Models\DeniPayment::with('deni')
            ->whereHas('deni', fn($q) => $q->where('lender_phone_hash', $hash))
            ->where('status', 'confirmed')
            ->orderByDesc('updated_at')
            ->limit(20)
            ->get()
            ->map(fn($p) => [
                'id'          => $p->id,
                'amount'      => $p->face_value ?: $p->amount,
                'description' => $p->deni?->description,
                'debtor_name' => $p->deni?->debtor_name,
                'receipt'     => $p->receipt_number,
                'paid_at'     => $p->updated_at->diffForHumans(),
                'today'       => $p->updated_at->isToday(),
            ]);

        return response()->json(['payments' => $payments]);
    }

    public function saveLedgerEntry(Request $request)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $data = $request->validate([
            'type'        => ['required', 'in:income,expense'],
            'category'    => ['required', 'string', 'max:60'],
            'amount'      => ['required', 'integer', 'min:1', 'max:10000000'],
            'description' => ['nullable', 'string', 'max:300'],
            'entry_date'  => ['required', 'date'],
        ]);

        $entry = BusinessLedgerEntry::create([
            'creditor_phone_hash' => session('creditor_phone_hash'),
            'type'                => $data['type'],
            'category'            => $data['category'],
            'amount'              => $data['amount'],
            'description'         => $data['description'] ?? null,
            'source'              => 'manual',
            'entry_date'          => $data['entry_date'],
        ]);

        return response()->json(['success' => true, 'id' => $entry->id]);
    }

    public function deleteLedgerEntry(int $id)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        BusinessLedgerEntry::where('id', $id)
            ->where('creditor_phone_hash', session('creditor_phone_hash'))
            ->where('source', 'manual')
            ->delete();

        return response()->json(['deleted' => true]);
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

    public function saveContact(Request $request)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'regex:/^(\+?254|0)[17]\d{8}$/'],
            'till'  => ['nullable', 'string', 'regex:/^\d{5,7}$/'],
        ]);

        if (empty($data['phone']) && empty($data['till'])) {
            return response()->json(['error' => 'Phone or Till number required.'], 422);
        }

        $hash  = session('creditor_phone_hash');
        $count = CreditorContact::where('creditor_phone_hash', $hash)->count();
        if ($count >= 50) {
            return response()->json(['error' => 'Maximum 50 contacts reached.'], 422);
        }

        $contact = CreditorContact::create([
            'creditor_phone_hash' => $hash,
            'name'                => $data['name'],
            'phone_encrypted'     => ! empty($data['phone']) ? Crypt::encryptString($data['phone']) : null,
            'till'                => $data['till'] ?? null,
        ]);

        $masked = null;
        if (! empty($data['phone'])) {
            $masked = preg_replace('/^(0\d{3}|\+?254\d{3})(\d{3})(\d{3})$/', '$1 $2 $3', $data['phone']);
        }

        return response()->json([
            'id'     => $contact->id,
            'name'   => $contact->name,
            'till'   => $contact->till,
            'masked' => $masked,
        ]);
    }

    public function deleteContact(int $id)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        CreditorContact::where('id', $id)
            ->where('creditor_phone_hash', session('creditor_phone_hash'))
            ->delete();

        return response()->json(['deleted' => true]);
    }

    public function initiatePayout(Request $request)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $data = $request->validate([
            'contact_id'  => ['required', 'integer'],
            'amount'      => ['required', 'integer', 'min:10', 'max:150000'],
            'category'    => ['required', 'in:salary,stock,utilities,rent,other'],
            'description' => ['nullable', 'string', 'max:200'],
        ]);

        $hash    = session('creditor_phone_hash');
        $contact = CreditorContact::where('id', $data['contact_id'])
            ->where('creditor_phone_hash', $hash)
            ->firstOrFail();

        $ownerPhone = Crypt::decryptString(session('creditor_phone_encrypted'));

        $stk = $this->daraja->stkPush(
            phone: $ownerPhone,
            amount: $data['amount'],
            accountRef: 'PAYOUT',
            description: ($data['description'] ?: ('Pay ' . $contact->name)),
        );

        if (! isset($stk['CheckoutRequestID'])) {
            return response()->json(['success' => false, 'message' => 'STK Push failed. Please try again.'], 422);
        }

        CreditorPayout::create([
            'creditor_phone_hash'     => $hash,
            'contact_id'              => $contact->id,
            'recipient_name'          => $contact->name,
            'recipient_phone_encrypted' => $contact->phone_encrypted,
            'recipient_till'          => $contact->till,
            'amount'                  => $data['amount'],
            'category'                => $data['category'],
            'description'             => $data['description'] ?? null,
            'checkout_request_id'     => $stk['CheckoutRequestID'],
            'status'                  => 'pending',
        ]);

        return response()->json([
            'success'             => true,
            'checkout_request_id' => $stk['CheckoutRequestID'],
        ]);
    }

    public function pollPayout(Request $request)
    {
        if (! session()->has('creditor_phone_hash')) return response()->json(['error' => 'Unauthorised'], 403);

        $payout = CreditorPayout::where('checkout_request_id', $request->query('checkout_request_id'))
            ->where('creditor_phone_hash', session('creditor_phone_hash'))
            ->first();

        if (! $payout) return response()->json(['status' => 'not_found']);

        return response()->json([
            'status'  => $payout->status,
            'receipt' => $payout->receipt_number,
            'amount'  => $payout->amount,
            'name'    => $payout->recipient_name,
        ]);
    }

    public function logout()
    {
        session()->forget(['creditor_phone_hash', 'creditor_phone_encrypted', 'creditor_name', 'creditor_verified_at']);
        return redirect()->route('creditor.login');
    }
}
