<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BusinessAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('business_id')) {
            return redirect()->route('business.login');
        }
        return $next($request);
    }
}
