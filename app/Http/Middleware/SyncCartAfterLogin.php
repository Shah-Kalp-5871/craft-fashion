<?php
// app/Http/Middleware/SyncCartAfterLogin.php
namespace App\Http\Middleware;

use Closure;
use App\Helpers\CartHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class SyncCartAfterLogin
{
    protected $cartHelper;

    public function __construct(CartHelper $cartHelper)
    {
        $this->cartHelper = $cartHelper;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if user just logged in
        if (Auth::guard('customer')->check() &&
            $request->session()->get('just_logged_in')) {

            $this->cartHelper->syncCart();

            // Clear the flag
            $request->session()->forget('just_logged_in');
        }

        return $response;
    }
}
