<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use Carbon\Carbon;
use App\Helpers\CartHelper;
use Illuminate\Support\Facades\Mail;
use App\Mail\OTPVerify;

class AuthController extends Controller
{
    public function __construct(protected CartHelper $cartHelper)
    {
    }
    public function loginPage()
    {
        return view('customer.auth.login');
    }

    public function registerPage()
    {
        return view('customer.auth.register');
    }

    public function showForgotPassword()
    {
        return view('customer.auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email',
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.exists' => 'We can\'t find a user with that email address.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $token = str_replace('/', '', bcrypt(\Str::random(40)));
        
        // Save token to DB (using standard password_reset_tokens table)
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Send Email
        try {
            Mail::to($request->email)->send(new \App\Mail\CustomerResetPassword($token, $request->email));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send reset link. Please try again.');
        }

        return back()->with('success', 'We have e-mailed your password reset link!');
    }

    public function showResetPasswordForm(Request $request, $token = null)
    {
        return view('customer.auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character',
        ]);

        $record = \DB::table('password_reset_tokens')
                    ->where('email', $request->email)
                    ->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
             return back()->withInput()->with('error', 'Invalid token!');
        }

        // Check if token is expired (e.g. 60 mins)
        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
             \DB::table('password_reset_tokens')->where('email', $request->email)->delete();
             return back()->with('error', 'Token expired!');
        }

        // Update Password
        $customer = Customer::where('email', $request->email)->first();
        if ($customer) {
            $customer->update([
                'password' => Hash::make($request->password),
                'password_changed_at' => now(),
            ]);

            // Delete token
            \DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('customer.login')
                ->with('success', 'Your password has been reset!');
        }

        return back()->with('error', 'User not found.');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8'
        ], [
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except('password'))
                ->with('form', 'login');
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $customer = Auth::guard('customer')->user();
            $request->session()->put('just_logged_in', true);
            $request->session()->put('customer_logged_in', true);
            $request->session()->put('is_logged_in', true);



            // Check if email is verified
            // if (!$customer->email_verified_at) {
            //     Auth::guard('customer')->logout();
            //     return redirect()->route('customer.verify')
            //         ->with([
            //             'customer_id' => $customer->id,
            //             'email' => $customer->email,
            //             'mobile' => $customer->mobile,
            //             'error' => 'Please verify your email and mobile before logging in.'
            //         ]);
            // }

            // Check if account is active
            // Check if account is active
            // if ($customer->status != 1) {
            //     Auth::guard('customer')->logout();
            //     return redirect()->back()
            //         ->withErrors(['email' => 'Your account is inactive. Please contact support.'])
            //         ->withInput($request->except('password'));
            // }

            // Update last login
            $customer->update([
                'last_login_at' => now(),
                'last_login_ip' => $request->ip()
            ]);

            // Sync cart from session to database
            $this->cartHelper->syncCart();

            return redirect()->route('customer.home.index')
                ->with('success', 'Welcome back, ' . $customer->name . '!');
        }

        return redirect()->back()
            ->withErrors(['email' => 'Invalid email or password. Please try again.'])
            ->withInput($request->except('password'))
            ->with('form', 'login');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|max:150|unique:customers,email',
            'mobile' => 'required|string|max:20|unique:customers,mobile|regex:/^[0-9]{10,15}$/',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/'
            ],
            'terms' => 'required|accepted'
        ], [
            'name.required' => 'Full name is required',
            'name.regex' => 'Name can only contain letters and spaces',
            'email.required' => 'Email address is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already registered',
            'mobile.required' => 'Mobile number is required',
            'mobile.regex' => 'Please enter a valid 10-15 digit mobile number',
            'mobile.unique' => 'This mobile number is already registered',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Passwords do not match',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number and one special character',
            'terms.required' => 'You must accept the terms and conditions',
            'terms.accepted' => 'You must accept the terms and conditions'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->except(['password', 'password_confirmation']))
                ->with('form', 'register');
        }

        // Direct Customer Creation (Skipping Email OTP)
        $customer = Customer::create([
            'name' => ucwords(strtolower(trim($request->name))),
            'email' => strtolower(trim($request->email)),
            'mobile' => trim($request->mobile),
            'password' => Hash::make($request->password),
            'status' => 1,
            'email_verified_at' => now(), // Auto-verify
            'mobile_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Login immediately
        Auth::guard('customer')->login($customer);
        $this->cartHelper->syncCart();

        // Optional: Attempt to send welcome email (can fail silently)
        // try {
        //     Mail::to($customer->email)->send(new WelcomeEmail($customer));
        // } catch (\Exception $e) {}

        return redirect()->route('customer.home.index')
            ->with('success', 'Account created successfully! Welcome to ' . config('app.name'));
    }

    public function verifyPage()
    {
        // 1. If Logged In, redirect if already verified
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            if ($customer->email_verified_at) {
                return redirect()->route('customer.home.index');
            }
            // If logged in but not verified (legacy/existing user case), show verify page
             if (!session()->has('email')) {
                session(['email' => $customer->email]);
            }
            return view('customer.auth.verify');
        }

        // 2. If Guest (New Registration Flow)
        // Check if we have a pending registration in session
        if (!session()->has('verification_key') || !session()->has('email')) {
            return redirect()->route('customer.register')
                ->with('error', 'Session expired. Please register again.');
        }

        return view('customer.auth.verify');
    }

    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_otp' => 'required|numeric|digits:6'
        ], [
            'email_otp.required' => 'Email OTP is required',
            'email_otp.numeric' => 'Email OTP must be a number',
            'email_otp.digits' => 'Email OTP must be 6 digits'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('form', 'verify');
        }

        $verificationKey = session('verification_key');
        $email = session('email');

        // CASE 1: New Registration (Data in Cache)
        if ($verificationKey && Cache::has($verificationKey)) {
            $data = Cache::get($verificationKey);
            
            // Validate OTP
            if ($data['email_otp'] != $request->email_otp) {
                return redirect()->back()
                    ->withErrors(['email_otp' => 'Invalid OTP. Please try again.'])
                    ->withInput();
            }

            // Create Customer NOW
            $customer = Customer::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'mobile' => $data['mobile'],
                'password' => $data['password'],
                'status' => 1,
                'email_verified_at' => now(),
                'mobile_verified_at' => now(), // Assuming mobile is verified implicitly or skipped
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Clear Cache & Session
            Cache::forget($verificationKey);
            Cache::forget('email_otp_' . $email);
            session()->forget(['verification_key', 'email', 'mobile']);

            // Login
            Auth::guard('customer')->login($customer);
            $this->cartHelper->syncCart();

            return redirect()->route('customer.home.index')
                ->with('success', 'Account created successfully! Welcome to ' . config('app.name'));
        }

        // CASE 2: Existing User (Logged in but unverified)
        if (Auth::guard('customer')->check()) {
            $customer = Auth::guard('customer')->user();
            $cachedEmailOTP = Cache::get('email_otp_' . $customer->email);

            if (!$cachedEmailOTP || $cachedEmailOTP != $request->email_otp) {
                return redirect()->back()
                    ->withErrors(['email_otp' => 'Invalid OTP. Please try again.'])
                    ->withInput();
            }

            $customer->update([
                'email_verified_at' => now(),
                'status' => 1
            ]);
            
            Cache::forget('email_otp_' . $customer->email);

            return redirect()->route('customer.home.index')
                ->with('success', 'Email verified successfully!');
        }

        return redirect()->route('customer.register')
            ->with('error', 'Session expired or invalid request. Please register again.');
    }

    public function resendOTP(Request $request)
    {
        $verificationKey = session('verification_key');
        $email = session('email');
        
        // Check if pending registration exists
        if ($verificationKey && Cache::has($verificationKey)) {
            $data = Cache::get($verificationKey);
            $email = $data['email']; // Ensure we use the cached email
            
            $newEmailOTP = rand(100000, 999999);
            
            // Update OTP in cached data
            $data['email_otp'] = $newEmailOTP;
            Cache::put($verificationKey, $data, 1800);
            Cache::put('email_otp_' . $email, $newEmailOTP, 1800);

            try {
                Mail::to($email)->send(new OTPVerify($newEmailOTP));
                return response()->json(['success' => true, 'message' => 'OTP resent to ' . $email]);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
            }
        }

        // Fallback for existing users
        if (session('customer_id') || Auth::guard('customer')->check()) {
             $userEmail = session('email');
             if(Auth::guard('customer')->check()) {
                 $userEmail = Auth::guard('customer')->user()->email;
             }

             if ($userEmail) {
                 $newEmailOTP = rand(100000, 999999);
                 Cache::put('email_otp_' . $userEmail, $newEmailOTP, 300);
                 try {
                    Mail::to($userEmail)->send(new OTPVerify($newEmailOTP));
                    return response()->json(['success' => true, 'message' => 'OTP resent to ' . $userEmail]);
                } catch (\Exception $e) {
                    return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
                }
             }
        }

        return response()->json([
            'success' => false,
            'message' => 'Session expired. Please register again.'
        ], 400);
    }

    public function changeEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:150|unique:customers,email'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);
        }

        $verificationKey = session('verification_key');
        
        // Update pending registration
        if ($verificationKey && Cache::has($verificationKey)) {
            $data = Cache::get($verificationKey);
            $oldEmail = $data['email'];
            
            // Update email in data
            $data['email'] = strtolower(trim($request->email));
            $newEmailOTP = rand(100000, 999999);
            $data['email_otp'] = $newEmailOTP;
            
            // Save with NEW key (email changed) or same key? 
            // Better to keep same key but update content.
            // But cache key depended on hash... let's just update the content for the existing key 
            // because strict "verificationKey" link is in session.
            
            Cache::put($verificationKey, $data, 1800);
            Cache::forget('email_otp_' . $oldEmail);
            Cache::put('email_otp_' . $data['email'], $newEmailOTP, 1800);
            
            session(['email' => $data['email']]);

            try {
                Mail::to($data['email'])->send(new OTPVerify($newEmailOTP));
                return response()->json(['success' => true, 'message' => 'Email updated & OTP sent!']);
            } catch (\Exception $e) {
                return response()->json(['success' => false, 'message' => 'Failed to send OTP.'], 500);
            }
        }
        
        return response()->json(['success' => false, 'message' => 'Cannot change email for this session.'], 400);
    }

    public function logout()
    {
        Auth::guard('customer')->logout();
        return redirect()->route('customer.home.index')
            ->with('success', 'Logged out successfully.');
    }
}
