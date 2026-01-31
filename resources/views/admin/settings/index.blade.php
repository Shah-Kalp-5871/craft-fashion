@extends('admin.layouts.master')

@section('title', 'System Settings')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Settings</h1>
            <p class="text-gray-500 mt-1">Configure your store's general settings, payments, and system preferences.</p>
        </div>
        <div class="flex items-center gap-3">
            <button type="button" onclick="resetSettings()" class="px-4 py-2 text-sm font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors flex items-center gap-2">
                <i class="fas fa-undo w-4 h-4"></i>
                Reset to Defaults
            </button>
            <button type="button" onclick="saveAllSettings()" id="saveSettingsBtn" class="px-6 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 shadow-sm transition-all flex items-center gap-2">
                <i class="fas fa-save w-4 h-4"></i>
                Save All Settings
            </button>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row min-h-[600px]">
        <!-- Sidebar Navigation -->
        <aside class="w-full md:w-64 border-r border-gray-100 bg-gray-50/50 p-4">
            <nav class="space-y-1" id="settingsTabs">
                <button data-tab="general" class="tab-btn active w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-store w-5 h-5"></i>
                    General Info
                </button>
                <button data-tab="seo" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-search w-5 h-5"></i>
                    SEO Settings
                </button>
                <button data-tab="payment" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-credit-card w-5 h-5"></i>
                    Payment Gateways
                </button>
                <button data-tab="shipping" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-truck w-5 h-5"></i>
                    Shipping & Tax
                </button>
                <button data-tab="social" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-share-alt w-5 h-5"></i>
                    Social Media
                </button>
                <button data-tab="appearance" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-palette w-5 h-5"></i>
                    Appearance
                </button>
                <div class="py-2">
                    <hr class="border-gray-200">
                </div>
                <button data-tab="profile" class="tab-btn w-full flex items-center gap-3 px-4 py-3 text-sm font-medium rounded-xl transition-all">
                    <i class="fas fa-user w-5 h-5"></i>
                    Admin Account
                </button>
            </nav>
        </aside>

        <!-- Forms Container -->
        <div class="flex-1 p-6 md:p-8">
            <!-- Loading State -->
            <div id="loadingState" class="flex flex-col items-center justify-center py-20">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mx-auto"></div>
                <p class="mt-4 text-gray-500 font-medium tracking-wide">Fetching system settings...</p>
            </div>

            <form id="settingsForm" class="hidden">
                @csrf
                
                <!-- General Settings -->
                <div id="general" class="tab-content space-y-6 active">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">General Information</h2>
                        <p class="text-gray-500 text-sm mt-1">Manage your store contact details and locale.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                Store Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" data-key="store_name" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="e.g. My Awesome Store">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Official Email</label>
                            <input type="email" data-key="store_email" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="contact@example.com">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Phone Number</label>
                            <input type="text" data-key="store_phone" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="+1 (555) 000-0000">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Currency</label>
                            <div class="relative">
                                <select data-key="currency" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 appearance-none transition-all outline-none cursor-pointer">
                                    <option value="INR">Indian Rupee (₹)</option>
                                    <option value="USD">US Dollar ($)</option>
                                    <option value="EUR">Euro (€)</option>
                                    <option value="GBP">British Pound (£)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down w-4 h-4"></i>
                                </div>
                            </div>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Physical Address</label>
                            <textarea data-key="store_address" rows="3" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none" placeholder="Enter store full address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- SEO Settings -->
                <div id="seo" class="tab-content space-y-6 hidden">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">SEO & Tracking</h2>
                        <p class="text-gray-500 text-sm mt-1">Optimize your site for search engines and analytics.</p>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Default Meta Title</label>
                            <input type="text" data-key="meta_title" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Default Meta Description</label>
                            <textarea data-key="meta_description" rows="3" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none resize-none"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Meta Keywords</label>
                            <input type="text" data-key="meta_keywords" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none" placeholder="keyword1, keyword2, keyword3">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center justify-between">
                                Google Analytics Code
                                <span class="text-[10px] text-gray-400 font-mono">gtag.js / GTM</span>
                            </label>
                            <textarea data-key="google_analytics" rows="4" class="setting-input w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none font-mono text-xs" placeholder="Paste your script here..."></textarea>
                        </div>
                    </div>
                </div>

                <!-- Payment Gateways -->
                <div id="payment" class="tab-content space-y-6 hidden">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">Payment Gateways</h2>
                        <p class="text-gray-500 text-sm mt-1">Configure how your customers pay for their orders.</p>
                    </div>
                    <div class="space-y-8">
                        <!-- Razorpay -->
                        <div class="p-4 bg-indigo-50/30 border border-indigo-100 rounded-2xl space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center">
                                        <i class="fas fa-bolt w-5 h-5 text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-900">Razorpay</h4>
                                        <p class="text-xs text-gray-500">Accept UPI, Credit Cards, Netbanking</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" data-key="razorpay_enabled" name="razorpay_enabled" class="setting-input sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            
                            <div id="razorpayFields" class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2 hidden">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-600">Key ID</label>
                                    <input type="text" data-key="razorpay_key_id" class="setting-input w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none">
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-xs font-semibold text-gray-600">Key Secret</label>
                                    <input type="password" data-key="razorpay_key_secret" class="setting-input w-full px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg focus:ring-1 focus:ring-indigo-500 outline-none">
                                </div>
                            </div>
                        </div>

                        <!-- Cash on Delivery -->
                        <div class="p-4 bg-gray-50 border border-gray-200 rounded-2xl flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg border border-gray-200 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave w-5 h-5 text-green-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900">Cash on Delivery</h4>
                                    <p class="text-xs text-gray-500">Pay when order is received</p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" data-key="cod_enabled" class="setting-input sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Shipping & Tax -->
                <div id="shipping" class="tab-content space-y-6 hidden">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">Shipping & Tax</h2>
                        <p class="text-gray-500 text-sm mt-1">Define your logistics and tax rules.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Default Shipping Rate (<span class="currency-symbol">₹</span>)</label>
                            <input type="number" step="0.01" data-key="default_shipping_rate" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Free Shipping Minimum (<span class="currency-symbol">₹</span>)</label>
                            <input type="number" step="0.01" data-key="free_shipping_min" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Tax Rate (GST %)</label>
                            <div class="relative">
                                <input type="number" step="0.1" data-key="tax_rate" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400 font-medium">%</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div id="social" class="tab-content space-y-6 hidden">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">Social Connect</h2>
                        <p class="text-gray-500 text-sm mt-1">Manage links shown on your website footer and contact page.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fab fa-facebook w-4 h-4 text-blue-600"></i> Facebook
                            </label>
                            <input type="url" data-key="social_facebook" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="https://facebook.com/yourpage">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fab fa-instagram w-4 h-4 text-pink-600"></i> Instagram
                            </label>
                            <input type="url" data-key="social_instagram" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 outline-none" placeholder="https://instagram.com/yourprofile">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fab fa-twitter w-4 h-4 text-sky-500"></i> Twitter / X
                            </label>
                            <input type="url" data-key="social_twitter" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700 flex items-center gap-2">
                                <i class="fab fa-linkedin w-4 h-4 text-blue-700"></i> LinkedIn
                            </label>
                            <input type="url" data-key="social_linkedin" class="setting-input w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500/20 outline-none">
                        </div>
                    </div>
                </div>

                <!-- Appearance -->
                <div id="appearance" class="tab-content space-y-6 hidden">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">Appearance</h2>
                        <p class="text-gray-500 text-sm mt-1">Customize the branding colors and assets.</p>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Primary Theme Color</label>
                            <div class="flex items-center gap-4">
                                <input type="color" data-key="theme_color" name="theme_color" class="setting-input w-12 h-12 rounded-lg cursor-pointer border-0 p-0 overflow-hidden">
                                <input type="text" name="theme_color_text" class="px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg w-32 font-mono text-sm uppercase outline-none" placeholder="#000000">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Logo URL</label>
                                <div class="flex gap-2">
                                    <input type="text" data-key="logo_url" id="logo_url" readonly class="setting-input w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg outline-none cursor-default" placeholder="/assets/logo.png">
                                    <div class="flex gap-1">
                                        <button type="button" onclick="document.getElementById('logo_file').click()" class="upload-btn px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2 shrink-0">
                                            <i class="fas fa-upload w-4 h-4"></i>
                                            Upload
                                        </button>
                                        <button type="button" onclick="clearToDefault('logo_url', '/storage/assets/images/logo.png')" class="px-3 py-2.5 bg-white border border-gray-300 text-gray-400 rounded-lg hover:text-red-600 hover:border-red-100 transition-colors" title="Reset to Default">
                                            <i class="fas fa-trash w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" id="logo_file" class="hidden" accept="image/*" onchange="handleFileUpload(event, 'logo_url')">
                                <div class="mt-2 shrink-0">
                                    <img id="logo_preview" src="" alt="Logo Preview" class="img-preview h-12 w-auto object-contain rounded border border-gray-200 hidden">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm font-semibold text-gray-700">Favicon URL</label>
                                <div class="flex gap-2">
                                    <input type="text" data-key="favicon_url" id="favicon_url" readonly class="setting-input w-full px-4 py-2.5 bg-gray-50 border border-gray-300 rounded-lg outline-none cursor-default" placeholder="/favicon.ico">
                                    <div class="flex gap-1">
                                        <button type="button" onclick="document.getElementById('favicon_file').click()" class="upload-btn px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors flex items-center gap-2 shrink-0">
                                            <i class="fas fa-upload w-4 h-4"></i>
                                            Upload
                                        </button>
                                        <button type="button" onclick="clearToDefault('favicon_url', '/storage/assets/images/favicon.ico')" class="px-3 py-2.5 bg-white border border-gray-300 text-gray-400 rounded-lg hover:text-red-600 hover:border-red-100 transition-colors" title="Reset to Default">
                                            <i class="fas fa-trash w-4 h-4"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="file" id="favicon_file" class="hidden" accept="image/x-icon,image/png,image/gif" onchange="handleFileUpload(event, 'favicon_url')">
                                <div class="mt-2 shrink-0">
                                    <img id="favicon_preview" src="" alt="Favicon Preview" class="img-preview h-8 w-8 object-contain rounded border border-gray-200 hidden">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Admin Profile Section (Non-Setting) -->
                <div id="profile" class="tab-content space-y-6 hidden">
                    <div class="border-b border-gray-100 pb-4 mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 tracking-tight">Admin Account</h2>
                        <p class="text-gray-500 text-sm mt-1">Update your login information and personal details.</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pb-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Display Name</label>
                            <input type="text" id="profileName" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Login Email</label>
                            <input type="email" id="profileEmail" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500/20">
                        </div>
                    </div>
                    
                    <div class="p-4 bg-amber-50 rounded-xl border border-amber-100 mb-6">
                        <div class="flex gap-3">
                            <i class="fas fa-shield-alt w-5 h-5 text-amber-600 mt-1"></i>
                            <div>
                                <h4 class="text-sm font-bold text-amber-800">Security Note</h4>
                                <p class="text-xs text-amber-700 mt-1">Only fill the password fields if you intend to change your current password. Leave blank otherwise.</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">New Password</label>
                            <div class="relative">
                                <input type="password" id="profilePassword" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500/20 pr-11">
                                <button type="button" onclick="togglePasswordVisibility('profilePassword', this)" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-400 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-semibold text-gray-700">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="profilePasswordConfirm" class="w-full px-4 py-2.5 bg-white border border-gray-300 rounded-lg outline-none focus:ring-2 focus:ring-indigo-500/20 pr-11">
                                <button type="button" onclick="togglePasswordVisibility('profilePasswordConfirm', this)" class="absolute inset-y-0 right-0 px-3.5 flex items-center text-gray-400 hover:text-indigo-600 transition-colors">
                                    <i class="fas fa-eye w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-6 border-t border-gray-100">
                        <button type="button" onclick="updateProfile()" class="px-6 py-2.5 bg-gray-900 text-white rounded-lg hover:bg-black transition-colors font-medium text-sm flex items-center gap-2">
                            <i class="fas fa-sync w-4 h-4"></i>
                            Update Account Details
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .tab-btn {
        color: #64748b;
        background: transparent;
    }
    .tab-btn:hover {
        background: rgba(243, 244, 246, 1);
        color: #1e293b;
    }
    .tab-btn.active {
        background: #ffffff;
        color: #4f46e5;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
    .tab-content.active {
        display: block !important;
        animation: fadeIn 0.3s ease-out;
    }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection

@push('scripts')
<script>
// Axios Configuration
const axiosInstance = axios.create({
    baseURL: '{{ url('') }}/admin/api',
    headers: {
        'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
});

axiosInstance.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            toastr.error('Session expired. Redirecting...');
            setTimeout(() => window.location.href = '{{ url('/admin/login') }}', 1500);
        }
        return Promise.reject(error);
    }
);

let settingsData = {};
let isSaving = false;

document.addEventListener('DOMContentLoaded', () => {
    // Initial Load
    loadSettings();
    prefillProfile();

    // Tab Switching Logic
    const tabBtns = document.querySelectorAll('.tab-btn');
    const tabContents = document.querySelectorAll('.tab-content');

    tabBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.tab;
            
            // Toggle active classes
            tabBtns.forEach(b => b.classList.remove('active'));
            tabContents.forEach(c => {
                c.classList.add('hidden');
                c.classList.remove('active');
            });

            btn.classList.add('active');
            const targetContent = document.getElementById(target);
            targetContent.classList.remove('hidden');
            targetContent.classList.add('active');
        });
    });

    // Mirror Color Inputs
    const colorInput = document.querySelector('input[name="theme_color"]');
    const colorText = document.querySelector('input[name="theme_color_text"]');

    if(colorInput && colorText) {
        colorInput.addEventListener('input', (e) => colorText.value = e.target.value.toUpperCase());
        colorText.addEventListener('change', (e) => {
            if(/^#[0-9A-F]{6}$/i.test(e.target.value)) {
                colorInput.value = e.target.value;
            }
        });
    }

    // Payment Toggle UI
    const razorEnabled = document.querySelector('input[name="razorpay_enabled"]');
    if(razorEnabled) {
        razorEnabled.addEventListener('change', (e) => {
            document.getElementById('razorpayFields').classList.toggle('hidden', !e.target.checked);
        });
    }

    // Manual URL Input Sync for Previews
    ['logo_url', 'favicon_url'].forEach(key => {
        const input = document.getElementById(key);
        if (input) {
            input.addEventListener('blur', (e) => {
                const previewId = key.replace('url', 'preview');
                const preview = document.getElementById(previewId);
                if (preview) {
                    if (e.target.value) {
                        preview.src = e.target.value;
                        preview.classList.remove('hidden');
                    } else {
                        preview.classList.add('hidden');
                    }
                }
            });
        }
    });
});

async function loadSettings() {
    try {
        const response = await axiosInstance.get('/settings/groups');

        if (response.data.success) {
            const data = response.data.data;
            populateForms(data);
            
            document.getElementById('loadingState').classList.add('hidden');
            document.getElementById('settingsForm').classList.remove('hidden');
            
            // Sync UI states
            const razorEnabled = document.querySelector('input[data-key="razorpay_enabled"]');
            if(razorEnabled) {
                document.getElementById('razorpayFields').classList.toggle('hidden', !razorEnabled.checked);
            }

            // Sync color text
            const colorInput = document.querySelector('input[data-key="theme_color"]');
            const colorText = document.querySelector('input[name="theme_color_text"]');
            if(colorInput && colorText) colorText.value = colorInput.value.toUpperCase();

            updateCurrencySymbols(document.querySelector('select[data-key="currency"]')?.value);
        }
    } catch (error) {
        console.error('Loader Error:', error);
        toastr.error('Failed to load system settings');
    }
}

function populateForms(groups) {
    Object.values(groups).forEach(settings => {
        settings.forEach(setting => {
            const inputs = document.querySelectorAll(`[data-key="${setting.key}"]`);
            inputs.forEach(input => {
                if (input.type === 'checkbox') {
                    input.checked = !!parseInt(setting.value);
                } else {
                    input.value = setting.value || '';
                    
                    // Show preview for logo and favicon if value exists
                    if (['logo_url', 'favicon_url'].includes(setting.key) && setting.value) {
                        const previewId = setting.key.replace('url', 'preview');
                        const preview = document.getElementById(previewId);
                        if (preview) {
                            preview.src = setting.value;
                            preview.classList.remove('hidden');
                        }
                    }
                }
            });
        });
    });
}

function updateCurrencySymbols(currency) {
    const symbols = { 'INR': '₹', 'USD': '$', 'EUR': '€', 'GBP': '£', 'CAD': 'C$' };
    const sym = symbols[currency] || '₹';
    document.querySelectorAll('.currency-symbol').forEach(el => el.textContent = sym);
}

document.querySelector('select[data-key="currency"]')?.addEventListener('change', (e) => {
    updateCurrencySymbols(e.target.value);
});

async function saveAllSettings() {
    if (isSaving) return;
    isSaving = true;

    const btn = document.getElementById('saveSettingsBtn');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `<i class="fas fa-spinner fa-spin w-4 h-4"></i> Saving...`;

    try {
        const settingsToUpdate = [];
        document.querySelectorAll('.setting-input').forEach(input => {
            const key = input.dataset.key;
            if(!key) return;

            let value = input.value;
            if (input.type === 'checkbox') {
                value = input.checked ? '1' : '0';
            }

            settingsToUpdate.push({ key, value });
        });

        const response = await axiosInstance.post('/settings/bulk-update', {
            settings: settingsToUpdate
        });

        if (response.data.success) {
            toastr.success('All settings synchronized successfully!');
        } else {
            toastr.error(response.data.message || 'Synchronization failed');
        }
    } catch (error) {
        console.error('Save Error:', error);
        toastr.error('Failed to save settings. Please check console for details.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = originalContent;
        isSaving = false;
    }
}

async function updateProfile() {
    const data = {
        name: document.getElementById('profileName').value,
        email: document.getElementById('profileEmail').value,
        password: document.getElementById('profilePassword').value,
        password_confirmation: document.getElementById('profilePasswordConfirm').value
    };

    if (!data.name || !data.email) {
        return toastr.warning('Name and Email are required for standard account operation.');
    }

    try {
        const response = await axiosInstance.post('/profile/update', data);
        toastr.success(response.data.message || 'Account synchronized!');
        
        // Clear sensitive fields
        document.getElementById('profilePassword').value = '';
        document.getElementById('profilePasswordConfirm').value = '';
    } catch (error) {
        const errors = error.response?.data?.errors;
        if(errors) {
            Object.values(errors).forEach(err => toastr.error(err[0]));
        } else {
            toastr.error('Failed to update account credentials');
        }
    }
}

function prefillProfile() {
    document.getElementById('profileName').value = "{{ Auth::guard('admin')->user()->name ?? '' }}";
    document.getElementById('profileEmail').value = "{{ Auth::guard('admin')->user()->email ?? '' }}";
}

async function resetSettings() {
    const confirmed = await Swal.fire({
        title: 'Factory Reset?',
        text: 'This will revert all system configuration to initial defaults. Current customizations will be lost.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reset to defaults'
    });

    if (confirmed.isConfirmed) {
        try {
            await axiosInstance.post('/settings/reset');
            toastr.success('System configuration restored to defaults');
            loadSettings();
        } catch (error) {
            toastr.error('Failed to restore defaults');
        }
    }
}

function togglePasswordVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

async function handleFileUpload(event, key) {
    const file = event.target.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('files[]', file);

    const container = event.target.closest('.space-y-2');
    const uploadBtn = container.querySelector('.upload-btn');
    const originalContent = uploadBtn.innerHTML;

    uploadBtn.disabled = true;
    uploadBtn.innerHTML = `<i class="fas fa-spinner fa-spin w-4 h-4"></i>`;

    try {
        const response = await axiosInstance.post('/media/upload', formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        if (response.data.success && response.data.data.uploaded.length > 0) {
            const url = response.data.data.uploaded[0].url;
            const input = document.getElementById(key);
            if (input) {
                input.value = url;
            } else {
                const dataInput = document.querySelector(`[data-key="${key}"]`);
                if (dataInput) dataInput.value = url;
            }
            
            // Update preview
            const previewId = key.replace('url', 'preview');
            const preview = document.getElementById(previewId);
            if (preview) {
                preview.src = url;
                preview.classList.remove('hidden');
            }
            
            toastr.success('File uploaded successfully!');
        } else {
            toastr.error(response.data.message || 'Upload failed');
        }
    } catch (error) {
        console.error('Upload Error:', error);
        toastr.error('Failed to upload file. Check file size or type.');
    } finally {
        uploadBtn.disabled = false;
        uploadBtn.innerHTML = originalContent;
        event.target.value = '';
    }
}

function clearToDefault(key, defaultPath) {
    const input = document.getElementById(key);
    if (input) {
        input.value = defaultPath;
        
        // Update preview
        const previewId = key.replace('url', 'preview');
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.src = defaultPath;
            preview.classList.remove('hidden');
        }
        
        toastr.info('Reset to default path. Save settings to apply.');
    }
}
</script>
@endpush
