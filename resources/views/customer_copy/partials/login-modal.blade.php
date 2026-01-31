<!-- Login Modal -->
<div
    id="loginModal"
    class="fixed inset-0 z-[9999] hidden flex items-center justify-center"
>
    <!-- Backdrop -->
    <div
        class="absolute inset-0 bg-black/50"
        onclick="closeLoginModal()"
    ></div>

    <!-- Modal Wrapper -->
    <div class="relative z-10 w-full max-w-md px-4">
        <div
            id="modalContent"
            class="bg-white rounded-xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300"
        >
            <!-- Close Button -->
            <button
                onclick="closeLoginModal()"
                class="absolute right-3 top-3 w-9 h-9 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center"
            >
                <i class="fas fa-times text-sm"></i>
            </button>

            <!-- Header -->
            <div class="p-6 border-b">
                <h3 class="text-xl font-bold text-gray-800">
                    Login Required
                </h3>
            </div>

            <!-- Body -->
            <div class="p-6 text-center">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-user-lock text-2xl"></i>
                </div>

                <h4 class="font-bold text-lg mb-2">Login to Continue</h4>
                <p class="text-gray-600 mb-6">
                    You need to login to proceed with checkout
                </p>

                <div class="bg-amber-50 rounded-xl p-4 mb-6">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600">2 items</span>
                        <span class="font-bold text-amber-700">₹637.64</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="p-6 border-t space-y-3">
                <a
                    href="/login"
                    class="block w-full bg-gradient-to-r from-amber-600 to-amber-800 text-white py-3 rounded-full font-bold text-center"
                >
                    <i class="fas fa-sign-in-alt mr-2"></i> Login to Checkout
                </a>

                <button
                    onclick="closeLoginModal()"
                    class="w-full border py-3 rounded-full text-gray-700"
                >
                    Continue Shopping
                </button>

                <p class="text-xs text-gray-600 mt-3">
                    Don’t have an account?
                    <a href="/register" class="text-amber-600 font-semibold">
                        Sign up
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
