<?php

namespace App\Http\Middleware;

use App\Models\Investor;
use Closure;
use Illuminate\Http\Request;

class InvestorAuth
{
    public function handle(Request $request, Closure $next): mixed
    {
        $investorId = session('pregota_investor_id');

        if (! $investorId) {
            return redirect()->route('investor.login');
        }

        $investor = Investor::where('id', $investorId)->where('is_active', true)->first();

        if (! $investor) {
            session()->forget('pregota_investor_id');
            return redirect()->route('investor.login')->with('error', 'Session expired.');
        }

        $request->attributes->set('investor', $investor);

        return $next($request);
    }
}
