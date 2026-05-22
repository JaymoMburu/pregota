<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CreatorAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('creator_id')) {
            return redirect()->route('creator.login');
        }
        return $next($request);
    }
}
