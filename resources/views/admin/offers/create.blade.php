@extends('admin.layouts.master')

@section('title', 'Create New Offer - Admin Panel')

@section('content')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create New Offer</h2>
                <p class="text-gray-600">Create special offers and promotions for your products</p>
            </div>
            <a href="{{ route('admin.offers.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Offers
            </a>
        </div>
    </div>

    <!-- Offer Form -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Offer Information</h3>
        </div>

        <form id="offerForm" class="p-6" method="POST" action="javascript:void(0);">
            <input type="hidden" id="offerId" name="id">

            <!-- Basic Information -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Offer Name *</label>
                    <input type="text" id="name" name="name" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Summer Sale 2024">
                    <div id="nameError" class="hidden mt-1 text-sm text-rose-600"></div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Offer Code</label>
                    <div class="flex space-x-2">
                        <input type="text" id="code" name="code"
                            class="flex-1 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="SUMMER24">
                        <button type="button" onclick="generateOfferCode()" class="btn-secondary whitespace-nowrap">
                            Generate
                        </button>
                    </div>
                    <div id="codeError" class="hidden mt-1 text-sm text-rose-600"></div>
                    <p class="text-xs text-gray-500 mt-1">Leave empty for auto-generated code</p>
                </div>
            </div>

            <!-- Offer Type -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Offer Type *</label>
                <select id="offer_type" name="offer_type" required
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                    onchange="updateOfferFields()">
                    <option value="">Select Offer Type</option>
                    <option value="percentage">Percentage Discount</option>
                    <option value="fixed">Fixed Amount Discount</option>
                    <!-- <option value="bogo">Buy One Get One (BOGO)</option>
                    <option value="buy_x_get_y">Buy X Get Y</option>
                    <option value="free_shipping">Free Shipping</option>
                    <option value="tiered">Tiered Discount</option> -->
                </select>
                <div id="offer_typeError" class="hidden mt-1 text-sm text-rose-600"></div>
            </div>

            <!-- Dynamic Fields based on Offer Type -->
            <div id="percentageFields" class="hidden mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Discount Percentage *
                        </label>
                        <div class="relative">
                            <input type="number" id="discount_value" name="discount_value" min="0" max="100"
                                step="0.01" required
                                class="w-full border border-gray-300 rounded-lg pl-4 pr-10 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="10">
                            <span class="absolute right-3 top-3.5 text-gray-500">%</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Maximum Discount Amount
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-3.5 text-gray-500">₹</span>
                            <input type="number" id="max_discount" name="max_discount" min="0" step="0.01"
                                class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="50">
                        </div>
                    </div>
                </div>
            </div>

            <div id="fixedFields" class="hidden mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Discount Amount *
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5 text-gray-500">₹</span>
                        <input type="number" id="discount_value_fixed" name="discount_value" min="0" step="0.01"
                            required
                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="10">
                    </div>
                </div>
            </div>

            <div id="bogoFields" class="hidden mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Buy Quantity *
                        </label>
                        <input type="number" id="buy_qty" name="buy_qty" min="1" value="1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Get Quantity *
                        </label>
                        <input type="number" id="get_qty" name="get_qty" min="1" value="1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <div id="buyXGetYFields" class="hidden mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Buy Quantity *
                        </label>
                        <input type="number" id="buy_qty_xy" name="buy_qty" min="1" value="2" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Get Quantity *
                        </label>
                        <input type="number" id="get_qty_xy" name="get_qty" min="1" value="1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Cart Amount Limits -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Minimum Cart Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5 text-gray-500">₹</span>
                        <input type="number" id="min_cart_amount" name="min_cart_amount" min="0" step="0.01"
                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="0.00">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Cart Amount
                    </label>
                    <div class="relative">
                        <span class="absolute left-3 top-3.5 text-gray-500">₹</span>
                        <input type="number" id="max_cart_amount" name="max_cart_amount" min="0" step="0.01"
                            class="w-full border border-gray-300 rounded-lg pl-8 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="0.00">
                    </div>
                </div>
            </div>

            <!-- Validity Period -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Start Date</label>
                    <input type="datetime-local" id="starts_at" name="starts_at"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">End Date</label>
                    <input type="datetime-local" id="ends_at" name="ends_at"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <!-- Usage Limits -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Maximum Total Uses
                    </label>
                    <input type="number" id="max_uses" name="max_uses" min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Unlimited">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Uses Per Customer
                    </label>
                    <input type="number" id="uses_per_customer" name="uses_per_customer" min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                        placeholder="Unlimited">
                </div>
            </div>

            <!-- Settings -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="status" name="status" checked
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="status" class="text-sm text-gray-700">Active</label>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_auto_apply" name="is_auto_apply"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_auto_apply" class="text-sm text-gray-700">Auto Apply</label>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="checkbox" id="is_stackable" name="is_stackable"
                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="is_stackable" class="text-sm text-gray-700">Stackable</label>
                </div>
            </div>

            <!-- Categories -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Apply to Categories</label>
                <div class="border border-gray-300 rounded-lg p-4 max-h-64 overflow-y-auto">
                    <div id="categoriesContainer" class="space-y-3">
                        <!-- Categories will be loaded dynamically -->
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin mr-2"></i>Loading categories...
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products/Variants -->
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Apply to Specific Products</label>
                <div class="border border-gray-300 rounded-lg p-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-sm text-gray-600">Select specific product variants</span>
                        <button type="button" onclick="openProductSelector()" class="btn-secondary text-sm">
                            <i class="fas fa-plus mr-1"></i> Add Products
                        </button>
                    </div>
                    <div id="selectedVariants" class="space-y-3">
                        <!-- Selected variants will appear here -->
                        <p class="text-gray-500 text-sm">No products selected</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 border-t pt-6">
                <a href="{{ route('admin.offers.index') }}" class="btn-secondary">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Offer
                </button>
            </div>
        </form>
    </div>

    <!-- Product Selector Modal -->
    <div id="productSelectorModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-6 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Select Products</h3>
                <button onclick="closeProductSelector()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="max-h-96 overflow-y-auto">
                <div id="productsList" class="space-y-2">
                    <!-- Products will be loaded here -->
                </div>
            </div>
            <div class="flex justify-end space-x-4 mt-6 pt-6 border-t">
                <button onclick="closeProductSelector()" class="btn-secondary">Cancel</button>
                <button onclick="addSelectedProducts()" class="btn-primary">Add Selected</button>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            // Axios instance
            const axiosInstance = axios.create({
                baseURL: '{{ url('') }}/admin/api',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
                }
            });

            // Add request interceptor
            // axiosInstance.interceptors.request.use(
            //     config => {
            //         if (config.method !== 'get') {
            //             config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            //         }
            //         return config;
            //     },
            //     error => Promise.reject(error)
            // );

            // Global variables
            let allCategories = [];
            let selectedVariants = new Map();
            let allProducts = [];

            // Initialize page
            document.addEventListener('DOMContentLoaded', function() {
                console.log('Create offer page initialized');

                // Set default dates
                setDefaultDates();

                // Load categories
                loadCategories();

                // Setup event listeners
                setupEventListeners();
            });

            // Set default dates
            function setDefaultDates() {
                const now = new Date();
                const startDate = new Date(now.getTime() + (24 * 60 * 60 * 1000)); // Tomorrow
                const endDate = new Date(now.getTime() + (7 * 24 * 60 * 60 * 1000)); // Next week

                document.getElementById('starts_at').value = startDate.toISOString().slice(0, 16);
                document.getElementById('ends_at').value = endDate.toISOString().slice(0, 16);
            }

            // Generate offer code
            function generateOfferCode() {
                const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                let code = '';
                for (let i = 0; i < 8; i++) {
                    code += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                document.getElementById('code').value = code;
                validateOfferCode(code);
            }

            // Validate offer code
            async function validateOfferCode(code) {
                if (!code) return;

                try {
                    const response = await axiosInstance.get('offers/validate-code', {
                        params: {
                            code: code
                        }
                    });

                    const codeError = document.getElementById('codeError');
                    if (response.data.data.exists) {
                        codeError.textContent = 'This code is already in use';
                        codeError.classList.remove('hidden');
                    } else {
                        codeError.classList.add('hidden');
                    }
                } catch (error) {
                    console.error('Error validating code:', error);
                }
            }

            // Update offer fields based on type
            function updateOfferFields() {
                const offerType = document.getElementById('offer_type').value;

                const sections = {
                    percentage: 'percentageFields',
                    fixed: 'fixedFields',
                    bogo: 'bogoFields',
                    buy_x_get_y: 'buyXGetYFields'
                };

                // Hide all & disable inputs
                Object.values(sections).forEach(id => {
                    const section = document.getElementById(id);
                    if (!section) return;

                    section.classList.add('hidden');

                    section.querySelectorAll('input').forEach(input => {
                        input.disabled = true;
                        input.removeAttribute('required');
                    });
                });

                // Show selected section
                if (sections[offerType]) {
                    const active = document.getElementById(sections[offerType]);
                    active.classList.remove('hidden');

                    active.querySelectorAll('input').forEach(input => {
                        input.disabled = false;
                        input.setAttribute('required', 'required');
                    });
                }
            }


            // Load categories
            async function loadCategories() {
                try {
                    const response = await axiosInstance.get('categories/dropdown');
                    if (response.data.success) {
                        allCategories = response.data.data;
                        renderCategories();
                    }
                } catch (error) {
                    console.error('Error loading categories:', error);
                }
            }

            // Render categories
            function renderCategories() {
                const container = document.getElementById('categoriesContainer');
                if (!container) return;

                if (allCategories.length === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-sm">No categories found</p>';
                    return;
                }

                let html = '';
                allCategories.forEach(category => {
                    html += `
            <div class="flex items-center">
                <input type="checkbox" id="category_${category.id}" name="categories[]" value="${category.id}"
                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="category_${category.id}" class="ml-2 text-sm text-gray-700">
                    ${category.name}
                </label>
            </div>
        `;
                });
                container.innerHTML = html;
            }

            // Open product selector
            function openProductSelector() {
                loadProducts();
                document.getElementById('productSelectorModal').classList.remove('hidden');
            }

            // Close product selector
            function closeProductSelector() {
                document.getElementById('productSelectorModal').classList.add('hidden');
            }

            // Load products
            async function loadProducts() {
                try {
                    const response = await axiosInstance.get('products/dropdown');
                    if (response.data.success) {
                        allProducts = response.data.data;
                        renderProducts();
                    }
                } catch (error) {
                    console.error('Error loading products:', error);
                }
            }

            // Render products
            function renderProducts() {
                const container = document.getElementById('productsList');
                if (!container) return;

                if (allProducts.length === 0) {
                    container.innerHTML = '<p class="text-gray-500">No products found</p>';
                    return;
                }

                let html = '';
                allProducts.forEach(product => {
                    // Check if already selected
                    const isSelected = Array.from(selectedVariants.values()).some(
                        variant => variant.product_id === product.id
                    );

                    html += `
            <div class="variant-item">
                <div class="flex items-center">
                    <input type="checkbox" id="product_${product.id}"
                        value="${product.id}" class="mr-3 h-4 w-4 text-indigo-600"
                        ${isSelected ? 'checked disabled' : ''}>
                    <div>
                        <span class="font-medium">${product.name}</span>
                        <span class="text-sm text-gray-500 ml-2">SKU: ${product.sku}</span>
                    </div>
                </div>
                <span class="text-sm font-semibold text-gray-700">₹${product.price}</span>
            </div>
        `;
                });
                container.innerHTML = html;
            }

            // Add selected products
            function addSelectedProducts() {
                const checkboxes = document.querySelectorAll('#productsList input[type="checkbox"]:checked');
                checkboxes.forEach(checkbox => {
                    const productId = parseInt(checkbox.value);
                    const product = allProducts.find(p => p.id === productId);

                    if (product && !selectedVariants.has(productId)) {
                        selectedVariants.set(productId, {
                            id: productId,
                            product_id: productId,
                            product_name: product.name,
                            sku: product.sku,
                            price: product.price
                        });
                    }
                });

                renderSelectedVariants();
                closeProductSelector();
            }

            // Render selected variants
            function renderSelectedVariants() {
                const container = document.getElementById('selectedVariants');
                // const variantsInput = document.getElementById('variants');

                if (!container) return;

                if (selectedVariants.size === 0) {
                    container.innerHTML = '<p class="text-gray-500 text-sm">No products selected</p>';
                    // variantsInput.value = '';
                    return;
                }

                let html = '';
                const variantIds = [];

                selectedVariants.forEach((variant, id) => {
                    html += `
            <div class="variant-item" data-id="${id}">
                <div>
                    <span class="font-medium">${variant.product_name}</span>
                    <span class="text-sm text-gray-500 ml-2">SKU: ${variant.sku}</span>
                </div>
                <button type="button" onclick="removeVariant(${id})"
                    class="text-rose-600 hover:text-rose-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
                    variantIds.push(id);
                });

                container.innerHTML = html;
                // variantsInput.value = JSON.stringify(variantIds);
            }

            // Remove variant
            function removeVariant(variantId) {
                selectedVariants.delete(variantId);
                renderSelectedVariants();
            }

            // Setup event listeners
            function setupEventListeners() {
                // Form submission
                const offerForm = document.getElementById('offerForm');
                if (offerForm) {
                    offerForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        saveOffer();
                    });
                }

                // Validate code on blur
                const codeInput = document.getElementById('code');
                if (codeInput) {
                    codeInput.addEventListener('blur', function() {
                        validateOfferCode(this.value);
                    });
                }
            }

            // ============================
            // Save Offer
            // ============================
            async function saveOffer() {
                const form = document.getElementById('offerForm');
                const formData = new FormData();

                // ----------------------------
                // Collect normal form fields
                // ----------------------------
                Array.from(form.elements).forEach(el => {
                    if (!el.name || el.type === 'submit' || el.type === 'button') return;

                    // Skip category & variant here (handled separately)
                    if (el.name === 'categories[]' || el.name === 'variants[]') return;

                    if (el.type === 'checkbox') {
                        formData.append(el.name, el.checked ? '1' : '0');
                    } else if (el.type === 'radio') {
                        if (el.checked) {
                            formData.append(el.name, el.value);
                        }
                    } else {
                        formData.append(el.name, el.value ?? '');
                    }
                });

                // ----------------------------
                // Categories (ARRAY)
                // ----------------------------
                document.querySelectorAll('input[name="categories[]"]:checked')
                    .forEach(input => {
                        formData.append('categories[]', input.value);
                    });

                // ----------------------------
                // Variants (ARRAY)
                // ----------------------------
                selectedVariants.forEach((variant, id) => {
                    formData.append('variants[]', id);
                });

                // ----------------------------
                // Loading UI
                // ----------------------------
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                submitBtn.disabled = true;

                // Clear errors
                ['nameError', 'codeError', 'offer_typeError'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) {
                        el.textContent = '';
                        el.classList.add('hidden');
                    }
                });

                try {
                    const response = await axiosInstance.post('offers', formData);

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        setTimeout(() => {
                            window.location.href = "{{ route('admin.offers.index') }}";
                        }, 1200);
                    }

                } catch (error) {
                    if (error.response?.status === 422) {
                        const errors = error.response.data.errors;

                        Object.keys(errors).forEach(field => {
                            const errorEl = document.getElementById(field + 'Error');
                            if (errorEl) {
                                errorEl.textContent = errors[field][0];
                                errorEl.classList.remove('hidden');
                            }
                        });

                        toastr.error('Please fix the validation errors');
                    } else {
                        toastr.error(error.response?.data?.message || 'Something went wrong');
                    }
                } finally {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                }
            }
        </script>
    @endpush


    <style>
        .variant-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 12px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            background-color: #f9fafb;
        }

        .variant-item:hover {
            background-color: #f3f4f6;
        }
    </style>
@endsection
