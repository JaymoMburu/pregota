<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SellerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('seller_id')) {
            return redirect()->route('seller.login');
        }
        return $next($request);
    }
}
