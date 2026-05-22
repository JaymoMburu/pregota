<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaffAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('solo_staff_id')) {
            return redirect()->route('staff.login');
        }

        return $next($request);
    }
}
