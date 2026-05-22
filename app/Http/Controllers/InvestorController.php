<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionContribution;
use App\Models\DirectGift;
use App\Models\Investor;
use App\Models\SchoolCollection;
use App\Models\SchoolPayment;
use App\Models\TipTransaction;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvestorController extends Controller
{
    public function loginForm()
    {
        if (session('pregota_investor_id')) {
            return redirect()->route('investor.dashboard');
        }
        return view('investor.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $investor = Investor::where('email', $request->email)
            ->where('is_active', true)
            ->first();

        if (! $investor || ! $investor->verifyPassword($request->password)) {
            return back()->withInput(['email' => $request->email])
                ->with('error', 'Invalid email or password.');
        }

        $investor->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        session(['pregota_investor_id' => $investor->id]);
        $request->session()->regenerate();

        return redirect()->route('investor.dashboard');
    }

    public function logout(Request $request)
    {
        session()->forget('pregota_investor_id');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('investor.login')->with('success', 'Logged out.');
    }

    public function dashboard(Request $request)
    {
        $investor = $request->attributes->get('investor');

        // ── Platform-wide KPIs ────────────────────────────────────────────────

        // Revenue = all fees collected across every module
        $voucherRevenue     = (float) Voucher::where('status', 'redeemed')
                                ->selectRaw('SUM(fee_in + fee_out) as t')->value('t');
        $tipRevenue         = (float) TipTransaction::where('status', 'paid')
                                ->selectRaw('SUM(fee_in + fee_out) as t')->value('t');
        $collectionRevenue  = (float) CollectionContribution::where('status', 'paid')
                                ->selectRaw('SUM(fee) as t')->value('t');
        $directGiftRevenue  = (float) DirectGift::where('status', 'paid')
                                ->selectRaw('SUM(fee) as t')->value('t');
        $schoolRevenue      = (float) SchoolPayment::where('status', 'paid')
                                ->selectRaw('SUM(fee) as t')->value('t');
        $totalRevenue = $voucherRevenue + $tipRevenue + $collectionRevenue + $directGiftRevenue + $schoolRevenue;

        // KES disbursed directly to recipients (Pregota never touches this)
        $voucherDisbursed    = (float) Voucher::where('status', 'redeemed')->sum('payout_amount');
        $tipDisbursed        = (float) TipTransaction::where('status', 'paid')->sum('tip_amount');
        $collectionDisbursed = (float) Collection::whereNotNull('paid_out_at')->sum('total_raised');
        $directGiftDisbursed = (float) DirectGift::where('status', 'paid')->sum('gift_amount');
        $schoolDisbursed     = (float) SchoolPayment::where('status', 'paid')->sum('amount');
        $totalDisbursed = $voucherDisbursed + $tipDisbursed + $collectionDisbursed + $directGiftDisbursed + $schoolDisbursed;

        // Transaction counts
        $txVouchers    = Voucher::where('status', 'redeemed')->count();
        $txTips        = TipTransaction::where('status', 'paid')->count();
        $txCollections = CollectionContribution::where('status', 'paid')->count();
        $txDirect      = DirectGift::where('status', 'paid')->count();
        $txSchool      = SchoolPayment::where('status', 'paid')->count();
        $totalTx = $txVouchers + $txTips + $txCollections + $txDirect + $txSchool;

        // Gross volume (what users paid in total, including our fees)
        $grossVolume = (float) Voucher::where('status', 'redeemed')->sum('gross_amount')
            + (float) TipTransaction::where('status', 'paid')->sum('gross_amount')
            + (float) CollectionContribution::where('status', 'paid')->sum('gross_amount')
            + (float) DirectGift::where('status', 'paid')->sum('gross_amount')
            + (float) SchoolPayment::where('status', 'paid')->sum('gross_amount');

        $grossMarginPct = $grossVolume > 0 ? round($totalRevenue / $grossVolume * 100, 1) : 0;

        // Active entities
        $activeCollections   = Collection::where('status', 'open')->count();
        $totalCollections    = Collection::count();
        $totalSchools        = SchoolCollection::count();

        // ── Module breakdown ──────────────────────────────────────────────────
        $modules = [
            ['name' => 'Welfare Collections', 'tx' => $txCollections,  'revenue' => $collectionRevenue,  'disbursed' => $collectionDisbursed,  'fee' => 'KES 30/contribution'],
            ['name' => 'Gift Vouchers',        'tx' => $txVouchers,    'revenue' => $voucherRevenue,     'disbursed' => $voucherDisbursed,     'fee' => 'KES 75/voucher'],
            ['name' => 'Staff Tips',           'tx' => $txTips,        'revenue' => $tipRevenue,         'disbursed' => $tipDisbursed,         'fee' => 'KES 15/tip'],
            ['name' => 'Direct Gifts',         'tx' => $txDirect,      'revenue' => $directGiftRevenue,  'disbursed' => $directGiftDisbursed,  'fee' => 'KES 75/gift'],
            ['name' => 'School Collections',   'tx' => $txSchool,      'revenue' => $schoolRevenue,      'disbursed' => $schoolDisbursed,      'fee' => 'Per payment'],
        ];

        // ── Monthly revenue trend (last 6 months) ────────────────────────────
        $months = collect();
        for ($i = 5; $i >= 0; $i--) {
            $date  = now()->startOfMonth()->subMonths($i);
            $label = $date->format('M y');
            $start = $date->copy()->startOfMonth();
            $end   = $date->copy()->endOfMonth();

            $rev = 0;
            $rev += (float) Voucher::where('status', 'redeemed')
                        ->whereBetween('updated_at', [$start, $end])
                        ->selectRaw('SUM(fee_in + fee_out) as t')->value('t');
            $rev += (float) TipTransaction::where('status', 'paid')
                        ->whereBetween('paid_at', [$start, $end])
                        ->selectRaw('SUM(fee_in + fee_out) as t')->value('t');
            $rev += (float) CollectionContribution::where('status', 'paid')
                        ->whereBetween('updated_at', [$start, $end])
                        ->selectRaw('SUM(fee) as t')->value('t');
            $rev += (float) DirectGift::where('status', 'paid')
                        ->whereBetween('paid_at', [$start, $end])
                        ->selectRaw('SUM(fee) as t')->value('t');
            $rev += (float) SchoolPayment::where('status', 'paid')
                        ->whereBetween('paid_at', [$start, $end])
                        ->selectRaw('SUM(fee) as t')->value('t');

            $months->push(['label' => $label, 'revenue' => round($rev, 2)]);
        }

        // MoM growth (last 2 months)
        $thisMonth = $months->last()['revenue'];
        $lastMonth = $months->count() >= 2 ? $months->get($months->count() - 2)['revenue'] : 0;
        $momGrowth = $lastMonth > 0 ? round(($thisMonth - $lastMonth) / $lastMonth * 100, 1) : null;

        return view('investor.dashboard', compact(
            'investor',
            'totalRevenue', 'totalDisbursed', 'totalTx', 'grossVolume', 'grossMarginPct',
            'activeCollections', 'totalCollections', 'totalSchools',
            'modules', 'months', 'momGrowth',
            'thisMonth', 'lastMonth'
        ));
    }
}
