<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('customer')->check()) {

            // 🔁 store intended URL
            session(['url.intended' => $request->fullUrl()]);

            return redirect()->route('customer.login')
                ->with('warning', 'Please login to continue.');
        }

        // Optional future safety (recommended)
        $customer = Auth::guard('customer')->user();

        if ($customer->status != 1) {
            Auth::guard('customer')->logout();

            return redirect()->route('customer.login')
                ->with('error', 'Your account is inactive.');
        }

        return $next($request);
    }
}
