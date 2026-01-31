@extends('customer.layouts.master')

@section('title', 'Verify OTP - Craft Fashion')

@section('content')
<div class="min-h-screen pt-32 pb-12 flex items-center justify-center bg-gray-50/50">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-3xl shadow-xl p-8 transform transition-all hover:scale-[1.01] duration-300">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce-slow">
                    <i class="fas fa-shield-alt text-3xl text-primary"></i>
                </div>
                <h1 class="text-3xl font-bold text-dark mb-2">Verify OTP</h1>
                <p class="text-secondary">Please enter the 6-digit code sent to your email</p>
                <div class="mt-2 text-sm font-medium text-dark bg-gray-50 py-2 px-4 rounded-full inline-block">
                    {{ session('email') ?? 'your email' }}
                </div>
            </div>

            <!-- Verification Form -->
            <form action="{{ route('customer.verify.submit') }}" method="POST" class="space-y-6">
                @csrf
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Enter OTP</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-key text-gray-400"></i>
                        </div>
                        <input type="text" 
                               name="email_otp" 
                               class="w-full pl-11 pr-4 py-3.5 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-center tracking-widest text-lg font-bold"
                               placeholder="123456"
                               maxlength="6"
                               required
                               autofocus>
                    </div>
                    @error('email_otp')
                        <p class="mt-2 text-sm text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                    @error('otp')
                        <p class="mt-2 text-sm text-red-500 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" 
                        class="w-full bg-primary text-white py-4 rounded-2xl font-semibold shadow-lg shadow-primary/30 hover:shadow-primary/50 hover:-translate-y-1 transition-all duration-300">
                    Verify Account
                </button>
            </form>

            <!-- Actions -->
            <div class="mt-8 pt-6 border-t border-gray-100/80 text-center space-y-4">
                <div>
                    <p class="text-secondary text-sm mb-2">Didn't receive the code?</p>
                    <button type="button" 
                            id="resend-otp"
                            onclick="resendOTP()"
                            class="text-primary font-semibold hover:text-primary-dark transition-colors inline-flex items-center">
                        <i class="fas fa-redo-alt mr-2 text-xs"></i> Resend OTP
                    </button>
                    <span id="timer" class="text-secondary text-sm ml-2 hidden"></span>
                </div>
                
                <div class="text-xs text-secondary">
                    Wrong email address? 
                    <button onclick="changeEmail()" class="text-primary hover:underline ml-1">Change Email</button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function resendOTP() {
        const btn = document.getElementById('resend-otp');
        const timerSpan = document.getElementById('timer');
        
        // Disable button
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
        
        // Call API
        fetch('{{ route("customer.otp.resend") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showToast(data.message, 'success');
                startTimer(60);
            } else {
                showToast(data.message, 'error');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        })
        .catch(error => {
            showToast('Failed to resend OTP', 'error');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        });
    }

    function startTimer(duration) {
        let timer = duration, minutes, seconds;
        const btn = document.getElementById('resend-otp');
        const timerSpan = document.getElementById('timer');
        
        btn.classList.add('hidden');
        timerSpan.classList.remove('hidden');
        
        const interval = setInterval(function () {
            minutes = parseInt(timer / 60, 10);
            seconds = parseInt(timer % 60, 10);

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            timerSpan.textContent = "Resend in " + minutes + ":" + seconds;

            if (--timer < 0) {
                clearInterval(interval);
                timerSpan.classList.add('hidden');
                btn.classList.remove('hidden');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            }
        }, 1000);
    }
    
    function changeEmail() {
        Swal.fire({
            title: 'Change Email Address',
            input: 'email',
            inputLabel: 'Enter your new email address',
            inputValue: '{{ session("email") }}',
            showCancelButton: true,
            confirmButtonText: 'Update & Resend OTP',
            showLoaderOnConfirm: true,
            preConfirm: (email) => {
                return fetch('{{ route("customer.auth.change-email") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: email })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(error => { throw new Error(error.message) })
                    }
                    return response.json()
                })
                .catch(error => {
                    Swal.showValidationMessage(
                        `Request failed: ${error}`
                    )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Email Updated!',
                    text: result.value.message,
                    icon: 'success'
                }).then(() => {
                    location.reload();
                });
            }
        })
    }
</script>
@endpush
@endsection
