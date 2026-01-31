@extends('customer.layouts.minimal')

@section('title', 'Verify Email - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-amber-50 flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <a href="{{ route('customer.home.index') }}" class="inline-block">
                <h1 class="text-4xl font-bold text-amber-800">{{ config('app.name') }}</h1>
            </a>
            <p class="text-gray-600 mt-2">Verify your email address</p>
        </div>

        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-envelope-open-text text-amber-600 text-2xl"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-800">Email Verification</h2>
                <p class="text-gray-600 mt-2">Enter the OTP sent to your email address</p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                    <p class="text-green-600 text-sm">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ session('success') }}
                    </p>
                </div>
            @endif

            <!-- Error Messages -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <h3 class="font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i> Please fix the following:
                    </h3>
                    <ul class="text-sm text-red-600">
                        @foreach($errors->all() as $error)
                            <li class="mb-1 flex items-start">
                                <i class="fas fa-circle text-xs mt-1 mr-2"></i>
                                <span>{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-600 text-sm">
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        {{ session('error') }}
                    </p>
                </div>
            @endif

            <!-- Demo OTPs for Development -->
            @if(session('email_otp'))
                <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <h3 class="font-semibold text-green-800 mb-2">
                        <i class="fas fa-code mr-1"></i> Demo OTP (Dev Mode)
                    </h3>
                    <div>
                        <p class="text-sm text-green-700">Email OTP:</p>
                        <p class="text-lg font-mono font-bold text-green-800">{{ session('email_otp') }}</p>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('customer.verify.submit') }}" id="verifyForm" class="space-y-6">
                @csrf

                <!-- Hidden fields to preserve session data -->
                <input type="hidden" name="verification_key" value="{{ session('verification_key') }}">
                
                <!-- Email OTP -->
                <div>
                    <label class="block text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-amber-600"></i>
                        Email OTP
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="email_otp"
                               maxlength="6"
                               required
                               value="{{ old('email_otp') }}"
                               class="w-full px-4 py-3 text-center text-xl font-mono rounded-lg border {{ $errors->has('email_otp') ? 'border-red-500' : 'border-gray-300' }} focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none transition-colors"
                               placeholder="123456"
                               autocomplete="off"
                               id="emailOtpInput">
                    </div>
                    <div class="flex justify-between items-center mt-2">
                        <span class="text-sm text-gray-500">
                            @if(session('email'))
                                Sent to: <span class="font-semibold">{{ session('email') }}</span>
                            @endif
                        </span>
                        <button type="button" onclick="openChangeEmailModal()" class="text-xs text-amber-600 hover:text-amber-800 font-medium">
                            <i class="fas fa-edit mr-1"></i>Change Email
                        </button>
                    </div>
                    @if($errors->has('email_otp'))
                        <p class="text-red-500 text-sm mt-1">{{ $errors->first('email_otp') }}</p>
                    @endif
                </div>

                <!-- OTP Timer -->
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-clock mr-1"></i>
                        OTP expires in: <span id="otpTimer" class="font-semibold text-amber-700">05:00</span>
                    </p>
                </div>

                <!-- Resend OTP -->
                <div class="text-center">
                    <button type="button"
                            id="resendOtpBtn"
                            disabled
                            class="text-amber-600 hover:text-amber-800 disabled:text-gray-400 disabled:cursor-not-allowed transition-colors">
                        <i class="fas fa-redo mr-1"></i>
                        <span id="resendText">Resend OTP (60s)</span>
                    </button>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300 transform hover:-translate-y-0.5">
                    Verify Account
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Change Email Modal -->
<div id="changeEmailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-sm mx-4 transform scale-95 transition-transform duration-300">
        <h3 class="text-lg font-bold text-gray-800 mb-4">Change Email Address</h3>
        <p class="text-sm text-gray-600 mb-4">Enter your new email address. We will send a new OTP to this address.</p>
        
        <form id="changeEmailForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">New Email Address</label>
                <input type="email" id="newEmailInput" required 
                    class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 outline-none">
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="closeChangeEmailModal()" class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700">Update & Resend</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
// OTP Input Handling
document.getElementById('emailOtpInput').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
    this.classList.remove('border-red-500');
});

// Modal Handling
const modal = document.getElementById('changeEmailModal');
const modalContent = modal.querySelector('div');

function openChangeEmailModal() {
    modal.classList.remove('hidden');
    // Trigger reflow
    void modal.offsetWidth;
    modal.classList.remove('opacity-0');
    modalContent.classList.remove('scale-95');
    modalContent.classList.add('scale-100');
    document.getElementById('newEmailInput').value = '{{ session('email') }}';
    document.getElementById('newEmailInput').focus();
}

function closeChangeEmailModal() {
    modal.classList.add('opacity-0');
    modalContent.classList.remove('scale-100');
    modalContent.classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

// Change Email Submit
document.getElementById('changeEmailForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const email = document.getElementById('newEmailInput').value;
    const btn = this.querySelector('button[type="submit"]');
    const originalText = btn.textContent;
    
    btn.disabled = true;
    btn.textContent = 'Updating...';
    
    fetch('{{ route("customer.auth.change-email") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showSuccessToast('Email updated and OTP resent!');
            closeChangeEmailModal();
            // Update displayed email
            const emailSpan = document.querySelector('div.flex span.font-semibold');
            if(emailSpan) emailSpan.textContent = email;
            
            // Allow resend button reset logic if needed
            // Reset resend timer
            resendTimeLeft = 60;
            updateResendTimer();
            if(!resendTimerInterval) resendTimerInterval = setInterval(updateResendTimer, 1000);
            
        } else {
            alert(data.message || 'Failed to update email');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred');
    })
    .finally(() => {
        btn.disabled = false;
        btn.textContent = originalText;
    });
});

// OTP Timer
let otpTimeLeft = 300; // 5 minutes in seconds
const otpTimer = document.getElementById('otpTimer');
let timerInterval;

function updateOTPTimer() {
    const minutes = Math.floor(otpTimeLeft / 60);
    const seconds = otpTimeLeft % 60;

    otpTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

    if (otpTimeLeft <= 0) {
        clearInterval(timerInterval);
        otpTimer.textContent = "Expired";
        otpTimer.classList.remove('text-amber-700');
        otpTimer.classList.add('text-red-600');
        document.getElementById('resendOtpBtn').disabled = false;
        document.getElementById('resendText').textContent = 'Resend OTP';
    } else {
        otpTimeLeft--;
    }
}

timerInterval = setInterval(updateOTPTimer, 1000);

// Resend OTP Functionality
let resendTimeLeft = 60;
const resendBtn = document.getElementById('resendOtpBtn');
const resendText = document.getElementById('resendText');
let resendTimerInterval;

function updateResendTimer() {
    if (resendTimeLeft > 0) {
        resendBtn.disabled = true;
        resendText.textContent = `Resend OTP (${resendTimeLeft}s)`;
        resendTimeLeft--;
    } else {
        resendBtn.disabled = false;
        resendText.textContent = 'Resend OTP';
        clearInterval(resendTimerInterval);
        resendTimerInterval = null;
    }
}

resendTimerInterval = setInterval(updateResendTimer, 1000);

resendBtn.addEventListener('click', function() {
    if (this.disabled) return;

    this.disabled = true;
    resendTimeLeft = 60;
    updateResendTimer();
    resendTimerInterval = setInterval(updateResendTimer, 1000);

    // AJAX request to resend OTP
    fetch('{{ route("customer.otp.resend") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update demo OTP if visible
            const demoDiv = document.querySelector('.bg-green-50 .text-lg');
            if (demoDiv && data.email_otp) {
                demoDiv.textContent = data.email_otp;
            }

            // Reset main timer
            otpTimeLeft = 300;
            clearInterval(timerInterval);
            otpTimer.textContent = "05:00";
            otpTimer.classList.remove('text-red-600');
            otpTimer.classList.add('text-amber-700');
            timerInterval = setInterval(updateOTPTimer, 1000);

            showSuccessToast('OTP has been resent!');
        } else {
            showErrorToast(data.message || 'Failed to resend OTP');
            this.disabled = false;
            resendText.textContent = 'Resend OTP';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorToast('An error occurred');
        this.disabled = false;
        resendText.textContent = 'Resend OTP';
    });
});

function showSuccessToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 p-4 bg-green-100 text-green-800 border border-green-200 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    toast.innerHTML = `<div class="flex items-center"><i class="fas fa-check-circle mr-2"></i><span>${message}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-x-full'), 10);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}

function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 p-4 bg-red-100 text-red-800 border border-red-200 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
    toast.innerHTML = `<div class="flex items-center"><i class="fas fa-exclamation-circle mr-2"></i><span>${message}</span></div>`;
    document.body.appendChild(toast);
    setTimeout(() => toast.classList.remove('translate-x-full'), 10);
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>
@endpush
