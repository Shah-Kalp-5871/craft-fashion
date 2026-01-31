<!-- Login Modal -->
<div
    id="loginModal"
    class="fixed inset-0 z-[9999] hidden flex items-center justify-center p-4"
>
    <!-- Backdrop -->
    <div
        class="absolute inset-0 bg-black/60 backdrop-blur-sm"
        onclick="closeLoginModal()"
    ></div>

    <!-- Modal Wrapper -->
    <div class="relative z-10 w-full max-w-md">
        <div
            id="modalContent"
            class="bg-white rounded-[2rem] shadow-2xl overflow-hidden transform scale-95 opacity-0 transition-all duration-300"
        >
            <!-- Close Button -->
            <button
                onclick="closeLoginModal()"
                class="absolute right-6 top-6 w-10 h-10 rounded-full bg-gray-50 hover:bg-gray-100 flex items-center justify-center transition-colors border border-gray-100"
            >
                <i class="fas fa-times text-gray-500"></i>
            </button>

            <!-- Body -->
            <div class="p-10 text-center">
                <div class="w-20 h-20 bg-primary/10 text-primary rounded-3xl flex items-center justify-center mx-auto mb-8 transform -rotate-12">
                    <i class="fas fa-user-lock text-3xl"></i>
                </div>

                <h3 class="text-2xl font-black text-gray-900 mb-4 uppercase tracking-tight">Login Required</h3>
                <p class="text-gray-500 font-medium mb-10 leading-relaxed">
                    Please log in to your account to continue with your purchase and enjoy a personalized shopping experience.
                </p>

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <a
                        href="{{ route('customer.login') }}"
                        class="block w-full bg-gray-900 text-white py-5 rounded-2xl font-black text-center shadow-xl hover:bg-primary transition-all transform active:scale-95"
                    >
                        Sign In Now
                    </a>

                    <button
                        onclick="closeLoginModal()"
                        class="w-full bg-gray-50 text-gray-400 py-5 rounded-2xl font-bold transition-all hover:text-gray-900"
                    >
                        Continue Guest Browsing
                    </button>
                </div>

                <p class="text-sm text-gray-400 mt-10">
                    New to {{ config('constants.SITE_NAME') }}?
                    <a href="{{ route('customer.register') }}" class="text-primary font-black hover:underline ml-1">
                        Create Account
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    function openLoginModal() {
        const modal = document.getElementById('loginModal');
        const content = document.getElementById('modalContent');
        if(!modal || !content) return;

        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeLoginModal() {
        const modal = document.getElementById('loginModal');
        const content = document.getElementById('modalContent');
        if(!modal || !content) return;

        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 300);
    }
</script>
