<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitorTrackingMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->ip();
        $today = date('Y-m-d');

        // Use try-catch or updateOrCreate to handle race conditions if necessary, 
        // but simple check is usually enough for the requirement.
        $exists = \App\Models\Visitor::where('ip_address', $ip)
            ->where('visit_date', $today)
            ->exists();

        if (!$exists) {
            \App\Models\Visitor::create([
                'ip_address' => $ip,
                'visit_date' => $today
            ]);
        }

        return $next($request);
    }
}
