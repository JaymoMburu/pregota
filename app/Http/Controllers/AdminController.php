<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Collection;
use App\Models\Investor;
use App\Models\LedgerEntry;
use App\Models\Partner;
use App\Models\SchoolCollection;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate(['password' => 'required']);

        if ($request->password === config('pregota.admin_password')) {
            Session::put('pregota_admin', true);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }

    public function logout()
    {
        Session::forget('pregota_admin');
        return redirect()->route('admin.login');
    }

    public function dashboard(Request $request)
    {
        $vouchers = Voucher::latest()->paginate(30);

        $stats = [
            'total_vouchers'   => Voucher::count(),
            'active'           => Voucher::where('status', 'active')->count(),
            'redeemed'         => Voucher::where('status', 'redeemed')->count(),
            'pending'          => Voucher::where('status', 'pending')->count(),
            'expired'          => Voucher::where('status', 'expired')->count(),
            'gross_volume'     => Voucher::where('status', 'redeemed')->sum('gross_amount'),
            'total_fees'       => Voucher::where('status', 'redeemed')->selectRaw('SUM(fee_in + fee_out) as total')->value('total') ?? 0,
            'total_payout'     => Voucher::where('status', 'redeemed')->sum('payout_amount'),
        ];

        $frozenSchoolCollections = SchoolCollection::where('is_frozen', true)->latest()->get();
        $frozenCollections       = Collection::where('is_frozen', true)->latest()->get();

        return view('admin.dashboard', compact('vouchers', 'stats', 'frozenSchoolCollections', 'frozenCollections'));
    }

    public function unfreezeSchoolCollection(SchoolCollection $schoolCollection)
    {
        $schoolCollection->update(['is_frozen' => false, 'freeze_reason' => null]);
        return back()->with('success', 'School collection "' . $schoolCollection->school_name . '" unfrozen.');
    }

    public function unfreezeCollection(Collection $collection)
    {
        $collection->update(['is_frozen' => false, 'freeze_reason' => null]);
        return back()->with('success', 'Collection "' . $collection->title . '" unfrozen.');
    }

    public function voucher(Voucher $voucher)
    {
        $voucher->load('ledger');
        return view('admin.voucher', compact('voucher'));
    }

    public function activateVoucher(Voucher $voucher)
    {
        if ($voucher->status !== 'pending') {
            return back()->with('error', 'Only pending vouchers can be manually activated.');
        }

        $voucher->update([
            'status'       => 'active',
            'activated_at' => now(),
        ]);

        LedgerEntry::record($voucher, 'admin_activated', [
            'note' => 'Manually activated by admin',
        ]);

        return back()->with('success', "Voucher {$voucher->code} activated.");
    }

    public function partners()
    {
        $partners = Partner::orderBy('category')->orderBy('sort_order')->get();
        return view('admin.partners', compact('partners'));
    }

    public function createPartner(Request $request)
    {
        $data = $request->validate([
            'name'        => ['required', 'string', 'max:80'],
            'category'    => ['required', 'in:shop,save,invest'],
            'tagline'     => ['nullable', 'string', 'max:120'],
            'logo_emoji'  => ['nullable', 'string', 'max:10'],
            'brand_color' => ['nullable', 'string', 'max:20'],
            'cta_text'    => ['required', 'string', 'max:40'],
            'url'         => ['required', 'url'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $data['slug']      = Str::slug($data['name']);
        $data['is_active'] = true;

        Partner::create($data);

        return redirect()->route('admin.partners')->with('success', 'Partner added.');
    }

    public function togglePartner(Partner $partner)
    {
        $partner->update(['is_active' => ! $partner->is_active]);
        return back()->with('success', $partner->name . ' ' . ($partner->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function deletePartner(Partner $partner)
    {
        $partner->delete();
        return back()->with('success', 'Partner removed.');
    }

    public function businesses()
    {
        $businesses = Business::withCount('staff')->latest()->get();
        return view('admin.businesses', compact('businesses'));
    }

    public function subscribeBusiness(Request $request, Business $business)
    {
        $data = $request->validate([
            'plan'   => ['required', 'in:starter,growth,business,enterprise'],
            'months' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $base = ($business->isSubscribed() && $business->plan_expires_at)
            ? $business->plan_expires_at
            : now();

        $expiresAt = $base->copy()->addMonths((int) $data['months']);

        $business->update([
            'plan'            => $data['plan'],
            'plan_expires_at' => $expiresAt,
        ]);

        return back()->with('success', "{$business->name} → {$data['plan']} until {$expiresAt->format('M j, Y')}.");
    }

    public function cancelSubscription(Business $business)
    {
        $business->update(['plan' => 'free', 'plan_expires_at' => null]);
        return back()->with('success', "{$business->name} subscription cancelled.");
    }

    // ── Investor management ───────────────────────────────────────────────

    public function investors()
    {
        $investors = Investor::latest()->get();
        return view('admin.investors', compact('investors'));
    }

    public function createInvestor(Request $request)
    {
        $data = $request->validate([
            'name'                 => ['required', 'string', 'max:100'],
            'email'                => ['required', 'email', 'unique:investors,email'],
            'password'             => ['required', 'string', 'min:8'],
            'investor_type'        => ['required', 'in:angel,vc,strategic,grant'],
            'equity_pct'           => ['nullable', 'numeric', 'min:0', 'max:100'],
            'amount_invested_kes'  => ['nullable', 'numeric', 'min:0'],
            'notes'                => ['nullable', 'string', 'max:500'],
        ]);

        Investor::create($data);

        return redirect()->route('admin.investors')->with('success', "Investor {$data['name']} created.");
    }

    public function toggleInvestor(Investor $investor)
    {
        $investor->update(['is_active' => ! $investor->is_active]);
        return back()->with('success', $investor->name . ' ' . ($investor->is_active ? 'activated' : 'deactivated') . '.');
    }

    public function resetInvestorPassword(Request $request, Investor $investor)
    {
        $data = $request->validate(['password' => ['required', 'string', 'min:8']]);
        $investor->update(['password' => $data['password']]);
        return back()->with('success', "Password reset for {$investor->name}.");
    }

    public function cancelVoucher(Voucher $voucher)
    {
        if (in_array($voucher->status, ['redeemed', 'cancelled'])) {
            return back()->with('error', 'Cannot cancel a redeemed or already cancelled voucher.');
        }

        $voucher->update(['status' => 'cancelled']);

        LedgerEntry::record($voucher, 'admin_cancelled', [
            'note' => 'Manually cancelled by admin',
        ]);

        return back()->with('success', "Voucher {$voucher->code} cancelled.");
    }
}
