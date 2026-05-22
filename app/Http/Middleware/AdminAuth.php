<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAuth
{
    public function handle(Request $request, Closure $next)
    :mixed
    {
        if (! session('pregota_admin')) {
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
