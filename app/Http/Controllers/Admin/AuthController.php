<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function loginPage()
    {
        return view('admin.auth.login');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (!Auth::guard('admin')->attempt($credentials)) {
            return back()->withErrors(['Invalid credentials']);
        }

        $admin = Auth::guard('admin')->user();

        $admin->tokens()->delete();

        $token = $admin->createToken('admin_api', ['admin'])->plainTextToken;

        session([
            'admin_api_token' => $token,
        ]);

        return redirect()->route('admin.dashboard');
    }



    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            $admin = Auth::guard('admin')->user();
            $admin->tokens()->where('name', 'admin_api')->delete();
        }

        // Clear session
        session()->forget('admin_api_token');

        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
