<!-- Top Navigation -->
<nav class="bg-white shadow-sm border-b border-gray-200 px-4 py-3  z-100">
    <div class="flex justify-between items-center  gap-4">
        <div class="flex items-center">
            <button id="sidebarToggle" class="text-gray-500 focus:outline-none md:hidden">
                <i class="fas fa-bars text-xl"></i>
            </button>
            <h1 class="text-xl font-semibold text-gray-800 ml-4 capitalize">
                {{ str_replace('_', ' ', Request::segment(2) ?? 'Dashboard') }}
            </h1>
        </div>
        <div class="flex items-center space-x-4">
            <div class="relative hidden sm:block">
                <input type="text" placeholder="Search..."
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
            <!-- <div class="relative block">
                <a href="{{ route('admin.notifications.index') }}"
                    class="text-gray-500 hover:text-gray-700 relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-rose-500 text-white rounded-full w-5 h-5 text-xs flex items-center justify-center">3</span>
                </a>
            </div> -->
            <div class="relative block">
                <div class="relative group">
                    <button
                        class="flex items-center space-x-2 text-gray-700 hover:text-indigo-600 admin-menu-toggle">
                        <div
                            class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(Auth::guard('admin')->user()->name ?? 'A', 0, 1)) }}
                        </div>
                        <span>{{ Auth::guard('admin')->user()->name ?? 'Admin' }}</span>
                        <i class="fas fa-chevron-down text-sm"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden admin-menu">
                        <a href="{{ route('admin.settings.index') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                            <i class="fas fa-user mr-2"></i> Profile
                        </a>
                        <a href="{{ route('admin.settings.index') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600">
                            <i class="fas fa-cog mr-2"></i> Settings
                        </a>
                        <div class="border-t border-gray-200 my-2"></div>
                        <form method="POST" action="{{ route('admin.logout') }}" id="logoutForm">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2 text-rose-600 hover:bg-rose-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
