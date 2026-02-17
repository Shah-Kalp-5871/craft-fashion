@extends('admin.layouts.master')

@section('title', 'Tax Management - Admin Panel')

@section('content')
    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button id="ratesTab"
                    class="tax-tab active border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Tax Rates
                </button>
                <button id="classesTab"
                    class="tax-tab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Tax Classes
                </button>
            </nav>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Tax Rates</p>
                    <p id="activeTaxesCount" class="text-2xl font-bold text-gray-800 mt-1">0</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <i class="fas fa-percentage text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Standard Tax Rate</p>
                    <p id="standardTaxRate" class="text-2xl font-bold text-gray-800 mt-1">0%</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-xl">
                    <i class="fas fa-star text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Average Tax Rate</p>
                    <p id="avgTaxRate" class="text-2xl font-bold text-gray-800 mt-1">0%</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <i class="fas fa-chart-line text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tax Groups</p>
                    <p id="taxGroupsCount" class="text-2xl font-bold text-gray-800 mt-1">0</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl">
                    <i class="fas fa-layer-group text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Tax Rates Section -->
    <div id="ratesSection" class="tax-section active">
        <!-- Existing tax rates table content (keep as is) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">All Tax Rates</h3>
            </div>
            <div class="p-6">
                <!-- Tabulator Toolbar -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                    <div class="order-2 sm:order-1">
                        <div class="relative" style="width: 260px;">
                            <input type="text" id="taxSearchInput" placeholder="Search tax rates..."
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                        <button onclick="openAddTaxRateModal()" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Add Tax Rate
                        </button>
                        <button id="taxBulkDeleteBtn" class="btn-danger">
                            <i class="fas fa-trash mr-2"></i>Bulk Delete
                        </button>
                        <button id="taxBulkToggleBtn" class="btn-secondary">
                            <i class="fas fa-toggle-on mr-2"></i>Bulk Status
                        </button>

                        <div class="relative group">
                            <button id="taxExportBtn" class="btn-primary">
                                <i class="fas fa-file-export mr-2"></i>Export
                            </button>
                            <div
                                class="absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden group-hover:block right-0 md:right-0 md:left-auto left-0 md:left-auto">
                                <button data-export="csv"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                                    <i class="fas fa-file-csv mr-2"></i>CSV
                                </button>
                                <button data-export="xlsx"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                                    <i class="fas fa-file-excel mr-2"></i>Excel
                                </button>
                                <button data-export="print"
                                    class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                                    <i class="fas fa-print mr-2"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tabulator Container -->
                <div id="taxesTable"></div>
            </div>
        </div>
    </div>

    <!-- Tax Classes Section -->
    <div id="classesSection" class="tax-section hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">All Tax Classes</h3>
            </div>
            <div class="p-6">
                <!-- Tabulator Toolbar -->
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                    <div class="order-2 sm:order-1">
                        <div class="relative" style="width: 260px;">
                            <input type="text" id="taxClassesSearchInput" placeholder="Search tax classes..."
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                        <button onclick="openAddTaxClassModal()" class="btn-primary">
                            <i class="fas fa-plus mr-2"></i>Add Tax Class
                        </button>
                        <button id="taxClassesColumnVisibilityBtn" class="btn-secondary">
                            <i class="fas fa-columns mr-2"></i>Columns
                        </button>
                    </div>
                </div>
                <!-- Tabulator Container -->
                <div id="taxClassesTable"></div>
            </div>
        </div>
    </div>

    <!-- Tax Calculator Modal -->
    <div id="taxCalculatorModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full relative" onclick="event.stopPropagation()">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 relative">
                <h2 class="text-xl font-semibold text-gray-800">Tax Calculator</h2>
                <!-- Close Button -->
                <button onclick="closeTaxCalculator()"
                    class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-3xl font-bold leading-none">
                    &times;
                </button>
            </div>

            <!-- Content -->
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <div class="relative">
                            <span class="absolute left-3 top-3 text-gray-500">₹</span>
                            <input type="number" id="calcAmount"
                                class="w-full pl-8 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                placeholder="0.00" step="0.01">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tax Rate (%)</label>
                        <input type="number" id="calcRate"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="18" step="0.01">
                    </div>

                    <button onclick="calculateTax()" class="w-full btn-primary">
                        Calculate Tax
                    </button>

                    <!-- Result Section -->
                    <div id="calcResult" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Original Amount:</span>
                                <span id="originalAmount" class="font-medium">₹0.00</span>
                            </div>

                            <div class="flex justify-between">
                                <span class="text-gray-600">Tax Amount:</span>
                                <span id="taxAmount" class="font-medium text-emerald-600">₹0.00</span>
                            </div>

                            <div class="flex justify-between pt-2 border-t border-gray-200">
                                <span class="text-gray-800 font-medium">Total Amount:</span>
                                <span id="totalAmount" class="font-bold text-lg text-gray-900">₹0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Tax Rate Modal -->
    <div id="taxRateModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="taxRateModalTitle" class="text-xl font-bold text-gray-800">Add New Tax Rate</h3>
                    <button onclick="closeTaxRateModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="taxRateForm">
                    <input type="hidden" id="taxRateId" name="id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax Name *</label>
                            <input type="text" id="taxRateName" name="name" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., GST, SGST, CGST">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax Code</label>
                            <input type="text" id="taxRateCode" name="code"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., GST18, SGST9">
                            <p class="text-xs text-gray-500 mt-1">Unique identifier (optional)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="taxRateDescription" name="description"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                rows="2" placeholder="Brief description of this tax rate"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Rate (%) *</label>
                                <input type="number" id="taxRateRate" name="rate" required step="0.01"
                                    min="0" max="100"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="18.00">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                                <select id="taxRateType" name="type" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select Type</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scope *</label>
                                <select id="taxRateScope" name="scope" required onchange="toggleTaxRateState()"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select Scope</option>
                                </select>
                            </div>

                            <div id="taxRateStateContainer" class="hidden">
                                <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                                <input type="text" id="taxRateState" name="state"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="e.g., Maharashtra, Delhi">
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <input type="number" id="taxRatePriority" name="priority" min="1"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    value="1">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                                <input type="number" id="taxRateSortOrder" name="sort_order" min="0"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    value="0">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax Classes</label>
                            <select id="taxRateClassIds" name="tax_class_id"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple classes</p>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="taxRateIsCompound" name="is_compound"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="taxRateIsCompound" class="ml-2 text-sm text-gray-700">Compound Tax</label>
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="taxRateIsActive" name="is_active"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" checked>
                                <label for="taxRateIsActive" class="ml-2 text-sm text-gray-700">Active</label>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeTaxRateModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            <span id="taxRateSubmitText">Save Tax Rate</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Tax Class Modal -->
    <div id="taxClassModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 id="taxClassModalTitle" class="text-xl font-bold text-gray-800">Create New Tax Class</h3>
                    <button onclick="closeTaxClassModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="taxClassForm">
                    <input type="hidden" id="taxClassId" name="id">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Class Name *</label>
                            <input type="text" id="taxClassName" name="name" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., Standard, Reduced, Zero">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax Code</label>
                            <input type="text" id="taxClassCode" name="code"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., STANDARD, REDUCED">
                            <p class="text-xs text-gray-500 mt-1">Unique identifier (auto-generated if empty)</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="taxClassDescription" name="description"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                rows="3" placeholder="Brief description of this tax class"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tax Rates</label>
                            <select id="taxClassRateIds" name="tax_rate_ids[]"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple tax rates</p>
                        </div>



                        <div class="flex items-center">
                            <input type="checkbox" id="taxClassIsDefault" name="is_default"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="taxClassIsDefault" class="ml-2 text-sm text-gray-700">Set as Default Class</label>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeTaxClassModal()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                            <i class="fas fa-save mr-2"></i>
                            <span id="taxClassSubmitText">Create Tax Class</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        // Axios instance with auth token
        const axiosInstance = axios.create({
            baseURL: '{{ url('') }}/admin/api',
            headers: {
                'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
            }
        });

        // Global variables
        let taxesTable = null;
        let taxClassesTable = null;
        let currentTab = 'rates';
        let isEditingRate = false;
        let isEditingClass = false;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing tax management...');

            // Load all data from APIs
            Promise.all([
                loadTaxRates(),
                loadTaxClasses(),
                loadStatistics()
            ]).then(() => {
                console.log('All tax data loaded successfully');
            }).catch(error => {
                console.error('Error loading tax data:', error);
                toastr.error('Failed to load tax data');
            });

            setupEventListeners();
            setupTabSwitching();
        });

        // Load tax rates from API
        async function loadTaxRates() {
            console.log('Loading tax rates from API...');

            try {
                const response = await axiosInstance.get('tax-rates');
                console.log('Tax rates API Response:', response.data);

                if (response.data.success) {
                    const taxRates = response.data.data.data || [];
                    console.log('Loaded', taxRates.length, 'tax rates');

                    // Initialize or update Tabulator
                    if (!taxesTable) {
                        initializeTaxesTable(taxRates);
                    } else {
                        taxesTable.setData(taxRates);
                    }
                } else {
                    toastr.error('Failed to load tax rates: ' + (response.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading tax rates:', error);
                toastr.error('Failed to load tax rates. Check console for details.');

                // Initialize table with empty data if error
                if (!taxesTable) {
                    initializeTaxesTable([]);
                }
            }
        }

        // Load tax classes from API
        async function loadTaxClasses() {
            console.log('Loading tax classes from API...');

            try {
                const response = await axiosInstance.get('tax-classes');
                console.log('Tax classes API Response:', response.data);

                if (response.data.success) {
                    const taxClasses = response.data.data.data || [];
                    console.log('Loaded', taxClasses.length, 'tax classes');

                    // Initialize or update Tabulator
                    if (!taxClassesTable) {
                        initializeTaxClassesTable(taxClasses);
                    } else {
                        taxClassesTable.setData(taxClasses);
                    }
                } else {
                    toastr.error('Failed to load tax classes: ' + (response.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error loading tax classes:', error);
                toastr.error('Failed to load tax classes. Check console for details.');

                // Initialize table with empty data if error
                if (!taxClassesTable) {
                    initializeTaxClassesTable([]);
                }
            }
        }

        // Load statistics from API
        async function loadStatistics() {
            console.log('Loading tax statistics from API...');

            try {
                const ratesResponse = await axiosInstance.get('tax-rates/statistics');
                const classesResponse = await axiosInstance.get('tax-classes/statistics');

                if (ratesResponse.data.success && classesResponse.data.success) {
                    const ratesStats = ratesResponse.data.data;
                    const classesStats = classesResponse.data.data;

                    // Update stats cards
                    document.getElementById('activeTaxesCount').textContent = ratesStats.active_rates || 0;
                    document.getElementById('standardTaxRate').textContent = `${ratesStats.average_rate || '0.00'}%`;
                    document.getElementById('avgTaxRate').textContent = `${ratesStats.average_rate || '0.00'}%`;
                    document.getElementById('taxGroupsCount').textContent = classesStats.active_classes || 0;
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
                toastr.error('Failed to load statistics');
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            // Tax rate form submission
            document.getElementById('taxRateForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                saveTaxRate();
            });

            // Tax class form submission
            document.getElementById('taxClassForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                saveTaxClass();
            });

            // Tax calculator form submission
            document.getElementById('taxCalculatorForm')?.addEventListener('submit', function(e) {
                e.preventDefault();
                calculateTax();
            });
        }

        // Setup tab switching
        function setupTabSwitching() {
            const ratesTab = document.getElementById('ratesTab');
            const classesTab = document.getElementById('classesTab');
            const ratesSection = document.getElementById('ratesSection');
            const classesSection = document.getElementById('classesSection');

            if (ratesTab && classesTab && ratesSection && classesSection) {
                ratesTab.addEventListener('click', function() {
                    switchTab('rates');
                });

                classesTab.addEventListener('click', function() {
                    switchTab('classes');
                });
            }
        }

        // Switch between tabs
        function switchTab(tab) {
            currentTab = tab;

            // Remove active classes
            document.querySelectorAll('.tax-tab').forEach(t => {
                t.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });

            // Hide all sections
            document.querySelectorAll('.tax-section').forEach(s => {
                s.classList.remove('active');
                s.classList.add('hidden');
            });

            if (tab === 'rates') {
                document.getElementById('ratesTab').classList.add('active', 'border-indigo-500', 'text-indigo-600');
                document.getElementById('ratesSection').classList.add('active');
                document.getElementById('ratesSection').classList.remove('hidden');
            } else {
                document.getElementById('classesTab').classList.add('active', 'border-indigo-500', 'text-indigo-600');
                document.getElementById('classesSection').classList.add('active');
                document.getElementById('classesSection').classList.remove('hidden');
            }
        }

        // Initialize tax rates table
        function initializeTaxesTable(data) {
            console.log('Initializing tax rates table with data:', data);

            taxesTable = new Tabulator("#taxesTable", {
                data: data,
                layout: "fitColumns",
                responsiveLayout: "hide",
                pagination: "local",
                paginationSize: 10,
                movableColumns: true,
                paginationSizeSelector: [10, 20, 50, 100],
                selectable: true,
                selectableRangeMode: "click",
                placeholder: data.length === 0 ? "No tax rates found" : "",
                columns: [{
                        title: "<input type='checkbox' id='taxSelectAll'>",
                        field: "id",
                        formatter: "rowSelection",
                        titleFormatter: "rowSelection",
                        hozAlign: "center",
                        headerSort: false,
                        width: 50,
                        cssClass: "select-checkbox",
                        responsive: 0
                    },
                    {
                        title: "ID",
                        field: "id",
                        width: 70,
                        sorter: "number",
                        hozAlign: "center",
                        responsive: 0,
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search ID..."
                    },
                    {
                        title: "Tax Name",
                        field: "name",
                        sorter: "string",
                        responsive: 0,
                        width: 200,
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search name...",
                        formatter: function(cell) {
                            const rowData = cell.getRow().getData();
                            return `
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-percentage text-indigo-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">${rowData.name}</p>
                                        <p class="text-sm text-gray-500">${rowData.tax_class_name || 'No class'}</p>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Rate",
                        field: "rate",
                        sorter: "number",
                        width: 120,
                        responsive: 0,
                        formatter: function(cell) {
                            const rowData = cell.getRow().getData();
                            const rate = parseFloat(cell.getValue()) || 0;
                            const color = rate >= 20 ? 'text-rose-600' : rate >= 10 ? 'text-amber-600' :
                                'text-emerald-600';
                            return `<span class="${color} font-bold">${rowData.formatted_rate || '0.00%'}</span>`;
                        },
                        hozAlign: "right"
                    },
                    {
                        title: "Location",
                        field: "location",
                        width: 180,
                        responsive: 0,
                        formatter: function(cell) {
                            const location = cell.getValue() || 'All Locations';
                            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${location}</span>`;
                        }
                    },
                    {
                        title: "Status",
                        field: "is_active",
                        width: 100,
                        responsive: 0,
                        hozAlign: "center",
                        headerFilter: "select",
                        headerFilterParams: {
                            values: {
                                "": "All",
                                "true": "Active",
                                "false": "Inactive",
                            }
                        },
                        formatter: function(cell) {
                            const isActive = cell.getValue();
                            return isActive ?
                                '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Active</span>' :
                                '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Inactive</span>';
                        }
                    },
                    {
                        title: "Priority",
                        field: "priority",
                        width: 100,
                        responsive: 1,
                        sorter: "number",
                        formatter: function(cell) {
                            const priority = cell.getValue() || 0;
                            return `<span class="inline-flex items-center justify-center w-6 h-6 rounded-full ${priority <= 3 ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800'} font-medium text-xs">${priority}</span>`;
                        },
                        hozAlign: "center"
                    },
                    {
                        title: "Created",
                        field: "created_at_formatted",
                        width: 150,
                        responsive: 1,
                        sorter: "string",
                        formatter: function(cell) {
                            const date = cell.getValue();
                            return date || 'N/A';
                        }
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 150,
                        hozAlign: "center",
                        responsive: 0,
                        headerSort: false,
                        formatter: function(cell) {
                            const id = cell.getValue();
                            const rowData = cell.getRow().getData();
                            return `
                                <div class="flex space-x-2 justify-center">
                                    <button onclick="toggleTaxRateStatus(${id})"
                                            class="w-8 h-8 flex items-center justify-center ${rowData.is_active ? 'bg-gray-50 text-gray-600 hover:bg-gray-100' : 'bg-green-50 text-green-600 hover:bg-green-100'} rounded-lg transition-colors"
                                            title="${rowData.is_active ? 'Deactivate' : 'Activate'}">
                                        <i class="fas ${rowData.is_active ? 'fa-toggle-on' : 'fa-toggle-off'} text-sm"></i>
                                    </button>
                                    <button onclick="editTaxRate(${id})"
                                            class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors"
                                            title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="deleteTaxRate(${id})"
                                            class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                rowFormatter: function(row) {
                    const rowEl = row.getElement();
                    rowEl.classList.add('hover:bg-gray-50');
                }
            });

            // Initialize table controls
            initTaxRatesControls();
        }

        // Initialize tax classes table
        function initializeTaxClassesTable(data) {
            console.log('Initializing tax classes table with data:', data);

            taxClassesTable = new Tabulator("#taxClassesTable", {
                data: data,
                layout: "fitColumns",
                responsiveLayout: "hide",
                pagination: "local",
                paginationSize: 10,
                movableColumns: true,
                paginationSizeSelector: [10, 20, 50, 100],
                placeholder: data.length === 0 ? "No tax classes found" : "",
                columns: [{
                        title: "ID",
                        field: "id",
                        width: 70,
                        sorter: "number",
                        hozAlign: "center",
                        responsive: 0,
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search ID..."
                    },
                    {
                        title: "Class Name",
                        field: "name",
                        sorter: "string",
                        responsive: 0,
                        width: 200,
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search name...",
                        formatter: function(cell) {
                            const rowData = cell.getRow().getData();
                            return `
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-layer-group text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">${rowData.name}</p>
                                        <p class="text-sm text-gray-500">${rowData.code || 'No code'}</p>
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        title: "Description",
                        field: "description",
                        width: 250,
                        responsive: 0,
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search description...",
                        formatter: function(cell) {
                            const description = cell.getValue();
                            return description ?
                                `<div class="text-sm text-gray-600 truncate">${description}</div>` :
                                '<span class="text-gray-400 text-sm">No description</span>';
                        }
                    },
                    {
                        title: "Total Rate",
                        field: "total_rate",
                        width: 120,
                        responsive: 0,
                        hozAlign: "right",
                        formatter: function(cell) {
                            const rate = cell.getValue();
                            return `<span class="font-bold text-emerald-600">${rate}%</span>`;
                        }
                    },
                    {
                        title: "Tax Rates",
                        field: "tax_rates_count",
                        width: 120,
                        responsive: 0,
                        hozAlign: "center",
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search count...",
                        formatter: function(cell, formatterParams, onRendered) {
                            const rowData = cell.getRow().getData();
                            const count = rowData.tax_rates_count || 0;

                            if (count === 0) {
                                return '<span class="text-gray-400 text-sm">No rates</span>';
                            }

                            return `
                                <button onclick="viewTaxClass(${rowData.id})"
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-colors">
                                    <i class="fas fa-list mr-1"></i>
                                    ${count} rate${count !== 1 ? 's' : ''}
                                </button>
                            `;
                        }
                    },
                    {
                        title: "Products",
                        field: "products_count",
                        width: 120,
                        responsive: 1,
                        hozAlign: "center",
                        formatter: function(cell) {
                            const count = cell.getValue() || 0;
                            return count > 0 ?
                                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">${count} products</span>` :
                                '<span class="text-gray-400 text-sm">No products</span>';
                        }
                    },
                    {
                        title: "Status",
                        field: "is_default",
                        width: 100,
                        responsive: 0,
                        hozAlign: "center",
                        formatter: function(cell) {
                            const isDefault = cell.getValue();
                            return isDefault ?
                                '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Default</span>' :
                                '<span class="text-gray-400 text-sm">-</span>';
                        }
                    },
                    {
                        title: "Created",
                        field: "created_at_formatted",
                        width: 150,
                        responsive: 1,
                        sorter: "string",
                        formatter: function(cell) {
                            const date = cell.getValue();
                            return date || 'N/A';
                        }
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 150,
                        hozAlign: "center",
                        responsive: 0,
                        headerSort: false,
                        formatter: function(cell) {
                            const id = cell.getValue();
                            const rowData = cell.getRow().getData();

                            return `
                                <div class="flex space-x-2 justify-center">
                                    <button onclick="toggleTaxClassDefault(${id})"
                                            class="w-8 h-8 flex items-center justify-center ${rowData.is_default ? 'bg-emerald-50 text-emerald-600 hover:bg-emerald-100' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'} rounded-lg transition-colors"
                                            title="${rowData.is_default ? 'Remove Default' : 'Set as Default'}">
                                        <i class="fas ${rowData.is_default ? 'fa-star' : 'fa-star-o'} text-sm"></i>
                                    </button>
                                    <button onclick="editTaxClass(${id})"
                                            class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors"
                                            title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </button>
                                    <button onclick="deleteTaxClass(${id})"
                                            class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                rowFormatter: function(row) {
                    const rowEl = row.getElement();
                    rowEl.classList.add('hover:bg-gray-50');
                }
            });

            initTaxClassesControls();
        }

        // Initialize tax rates controls
        function initTaxRatesControls() {
            // Search functionality
            const searchInput = document.getElementById('taxSearchInput');
            let searchTimeout;

            searchInput?.addEventListener('keyup', function() {
                const searchTerm = this.value;
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (searchTerm.length >= 2 || searchTerm === '') {
                        taxesTable.setFilter([{
                                field: "name",
                                type: "like",
                                value: searchTerm
                            },
                            {
                                field: "tax_class_name",
                                type: "like",
                                value: searchTerm
                            },
                            {
                                field: "location",
                                type: "like",
                                value: searchTerm
                            }
                        ]);
                    }
                }, 500);
            });



            // Export functionality
            const exportBtns = document.querySelectorAll('#taxExportBtn ~ [data-export]');
            exportBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const format = this.getAttribute('data-export');
                    switch (format) {
                        case 'csv':
                            taxesTable.download("csv", "tax_rates.csv");
                            break;
                        case 'xlsx':
                            taxesTable.download("xlsx", "tax_rates.xlsx", {
                                sheetName: "Tax Rates"
                            });
                            break;
                        case 'print':
                            window.print();
                            break;
                    }
                });
            });

            // Bulk actions
            const bulkDeleteBtn = document.getElementById('taxBulkDeleteBtn');
            const bulkToggleBtn = document.getElementById('taxBulkToggleBtn');

            bulkDeleteBtn?.addEventListener('click', handleTaxBulkDelete);
            bulkToggleBtn?.addEventListener('click', handleTaxBulkToggle);
        }

        // Initialize tax classes controls
        function initTaxClassesControls() {
            // Search functionality
            const searchInput = document.getElementById('taxClassesSearchInput');
            let searchTimeout;

            searchInput?.addEventListener('keyup', function() {
                const searchTerm = this.value;
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (searchTerm.length >= 2 || searchTerm === '') {
                        taxClassesTable.setFilter([{
                                field: "name",
                                type: "like",
                                value: searchTerm
                            },
                            {
                                field: "description",
                                type: "like",
                                value: searchTerm
                            },
                            {
                                field: "code",
                                type: "like",
                                value: searchTerm
                            }
                        ]);
                    }
                }, 500);
            });

            // Column visibility
            const columnVisibilityBtn = document.getElementById('taxClassesColumnVisibilityBtn');
            if (columnVisibilityBtn) {
                const columnMenu = document.createElement('div');
                columnMenu.className =
                    'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

                const columns = taxClassesTable.getColumnDefinitions();

                columns.forEach((column) => {
                    const field = column.field;
                    if (!field) return;

                    const columnBtn = document.createElement('button');
                    columnBtn.className =
                        'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
                    columnBtn.innerHTML = `
                        <input type="checkbox" class="mr-2" ${taxClassesTable.getColumn(field).isVisible() ? 'checked' : ''}>
                        ${column.title}
                    `;

                    columnBtn.addEventListener('click', function(e) {
                        e.stopPropagation();
                        const col = taxClassesTable.getColumn(field);
                        const checkbox = this.querySelector('input');
                        col.toggle();
                        setTimeout(() => {
                            checkbox.checked = col.isVisible();
                        }, 10);
                    });

                    columnMenu.appendChild(columnBtn);
                });

                columnVisibilityBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    columnMenu.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
                        columnMenu.classList.add('hidden');
                    }
                });

                columnVisibilityBtn.parentElement.appendChild(columnMenu);
            }
        }

        // Open add tax rate modal
        async function openAddTaxRateModal() {
            isEditingRate = false;

            try {
                // Load tax types dropdown
                const typesResponse = await axiosInstance.get('tax-rates/types');
                if (typesResponse.data.success) {
                    const typeSelect = document.getElementById('taxRateType');
                    typeSelect.innerHTML = '<option value="">Select Type</option>';

                    typesResponse.data.data.forEach(type => {
                        const option = document.createElement('option');
                        option.value = type.value;
                        option.textContent = type.label;
                        typeSelect.appendChild(option);
                    });
                }

                // Load tax scopes dropdown
                const scopesResponse = await axiosInstance.get('tax-rates/scopes');
                if (scopesResponse.data.success) {
                    const scopeSelect = document.getElementById('taxRateScope');
                    scopeSelect.innerHTML = '<option value="">Select Scope</option>';

                    scopesResponse.data.data.forEach(scope => {
                        const option = document.createElement('option');
                        option.value = scope.value;
                        option.textContent = scope.label;
                        scopeSelect.appendChild(option);
                    });
                }

                // Load available tax classes for selection
                await loadAvailableTaxClasses();

            } catch (error) {
                console.error('Error loading dropdown data:', error);
                toastr.error('Failed to load form data');
            }

            // Reset form
            const form = document.getElementById('taxRateForm');
            if (form) {
                form.reset();
            }

            document.getElementById('taxRateId').value = '';
            document.getElementById('taxRateIsActive').checked = true;
            document.getElementById('taxRatePriority').value = 0;

            // Clear tax class selection
            const classesSelect = document.getElementById('taxRateClassIds');
            if (classesSelect) {
                Array.from(classesSelect.options).forEach(option => {
                    option.selected = false;
                });
            }

            document.getElementById('taxRateModalTitle').textContent = 'Add New Tax Rate';
            document.getElementById('taxRateSubmitText').textContent = 'Save Tax Rate';

            document.getElementById('taxRateModal').classList.remove('hidden');
        }

        // Load available tax classes for selection
        async function loadAvailableTaxClasses() {
            try {
                const response = await axiosInstance.get('tax-classes/dropdown');
                if (response.data.success) {
                    const classesSelect = document.getElementById('taxRateClassIds');
                    if (classesSelect) {
                        classesSelect.innerHTML = '<option value="">Select Tax Class</option>';

                        response.data.data.forEach(taxClass => {
                            const option = document.createElement('option');
                            option.value = taxClass.id;
                            option.textContent = `${taxClass.name} (${taxClass.code})`;
                            if (taxClass.is_default) {
                                option.textContent += ' (Default)';
                            }
                            classesSelect.appendChild(option);
                        });

                        // Enable single selection for tax class
                        classesSelect.multiple = false;
                    }
                }
            } catch (error) {
                console.error('Error loading tax classes:', error);
            }
        }

        // Toggle tax rate status
        async function toggleTaxRateStatus(id) {
            try {
                const response = await axiosInstance.post(`tax-rates/${id}/toggle-status`);

                if (response.data.success) {
                    toastr.success('Tax rate status updated successfully');
                    await loadTaxRates();
                    await loadStatistics();
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || 'Failed to update status';
                toastr.error(errorMsg);
            }
        }

        // Edit tax rate
        async function editTaxRate(id) {
            try {
                const response = await axiosInstance.get(`tax-rates/${id}`);

                if (response.data.success) {
                    const taxRate = response.data.data;
                    isEditingRate = true;

                    // Load dropdowns first
                    const typesResponse = await axiosInstance.get('tax-rates/types');
                    const scopesResponse = await axiosInstance.get('tax-rates/scopes');

                    if (typesResponse.data.success) {
                        const typeSelect = document.getElementById('taxRateType');
                        typeSelect.innerHTML = '<option value="">Select Type</option>';
                        typesResponse.data.data.forEach(type => {
                            const option = document.createElement('option');
                            option.value = type.value;
                            option.textContent = type.label;
                            typeSelect.appendChild(option);
                        });
                    }

                    if (scopesResponse.data.success) {
                        const scopeSelect = document.getElementById('taxRateScope');
                        scopeSelect.innerHTML = '<option value="">Select Scope</option>';
                        scopesResponse.data.data.forEach(scope => {
                            const option = document.createElement('option');
                            option.value = scope.value;
                            option.textContent = scope.label;
                            scopeSelect.appendChild(option);
                        });
                    }

                    // Fill form with current data
                    document.getElementById('taxRateId').value = taxRate.id;
                    document.getElementById('taxRateName').value = taxRate.name || '';
                    document.getElementById('taxRateRate').value = taxRate.rate || 0;
                    document.getElementById('taxRateIsActive').checked = taxRate.is_active || false;
                    document.getElementById('taxRatePriority').value = taxRate.priority || 0;

                    // Set type if available
                    if (document.getElementById('taxRateType')) {
                        document.getElementById('taxRateType').value = 'percentage'; // Default type
                    }

                    // Set scope if available
                    if (document.getElementById('taxRateScope')) {
                        document.getElementById('taxRateScope').value = taxRate.country_code ? 'national' : 'local';
                    }

                    // Load and select tax class
                    await loadAvailableTaxClasses();
                    const classesSelect = document.getElementById('taxRateClassIds');
                    if (classesSelect && taxRate.tax_class_id) {
                        classesSelect.value = taxRate.tax_class_id;
                    }

                    // Update UI
                    document.getElementById('taxRateModalTitle').textContent = 'Edit Tax Rate';
                    document.getElementById('taxRateSubmitText').textContent = 'Update Tax Rate';

                    document.getElementById('taxRateModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error editing tax rate:', error);
                toastr.error('Failed to load tax rate details');
            }
        }

        // Save tax rate (create or update)
        async function saveTaxRate() {
            const form = document.getElementById('taxRateForm');
            const formData = new FormData(form);
            const taxRateData = Object.fromEntries(formData.entries());


            // Convert data types
            taxRateData.is_active = document.getElementById('taxRateIsActive')?.checked ? true : false;
            taxRateData.rate = parseFloat(taxRateData.rate) || 0;
            taxRateData.priority = parseInt(taxRateData.priority) || 0;
taxRateData.tax_class_id = taxRateData.tax_class_id
    ? parseInt(taxRateData.tax_class_id)
    : null;



            // Validate required fields
            if (!taxRateData.name || !taxRateData.rate) {
                toastr.error('Name and Rate are required fields');
                return;
            }

            // Remove empty fields
            Object.keys(taxRateData).forEach(key => {
                if (taxRateData[key] === '' || taxRateData[key] === null || taxRateData[key] === undefined) {
                    delete taxRateData[key];
                }
            });

            const method = isEditingRate ? 'put' : 'post';
            const url = isEditingRate ? `tax-rates/${taxRateData.id}` : 'tax-rates';

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            try {
                const response = await axiosInstance[method](url, taxRateData);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeTaxRateModal();

                    // Refresh data
                    await Promise.all([
                        loadTaxRates(),
                        loadTaxClasses(),
                        loadStatistics()
                    ]);
                } else {
                    toastr.error(response.data.message || 'Failed to save tax rate');
                }
            } catch (error) {
                console.error('Save tax rate error:', error);

                if (error.response && error.response.status === 422) {
                    // Validation errors
                    const errors = error.response.data.errors;
                    let errorMessages = [];
                    Object.keys(errors).forEach(field => {
                        errorMessages = errorMessages.concat(errors[field]);
                    });
                    toastr.error(errorMessages.join('<br>'));
                } else {
                    const errorMsg = error.response?.data?.message || 'Failed to save tax rate';
                    toastr.error(errorMsg);
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Close tax rate modal
        function closeTaxRateModal() {
            document.getElementById('taxRateModal').classList.add('hidden');
        }

        // Delete tax rate
        async function deleteTaxRate(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the tax rate. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.delete(`tax-rates/${id}`);

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        // Refresh data
                        await Promise.all([
                            loadTaxRates(),
                            loadTaxClasses(),
                            loadStatistics()
                        ]);
                    }
                } catch (error) {
                    const errorMsg = error.response?.data?.message || 'Failed to delete tax rate';
                    toastr.error(errorMsg);
                }
            }
        }

        // Open add tax class modal
        async function openAddTaxClassModal() {
            isEditingClass = false;

            // Reset form
            const form = document.getElementById('taxClassForm');
            if (form) {
                form.reset();
            }

            document.getElementById('taxClassId').value = '';
            document.getElementById('taxClassIsDefault').checked = false;
            document.getElementById('taxClassModalTitle').textContent = 'Create New Tax Class';
            document.getElementById('taxClassSubmitText').textContent = 'Create Tax Class';

            // Load available tax rates for selection
            await loadAvailableTaxRates();

            document.getElementById('taxClassModal').classList.remove('hidden');
        }

        // Load available tax rates
        async function loadAvailableTaxRates() {
            try {
                const response = await axiosInstance.get('tax-rates');
                if (response.data.success) {
                    const ratesSelect = document.getElementById('taxClassRateIds');
                    if (ratesSelect) {
                        ratesSelect.innerHTML = '<option value="">Select Tax Rates (Optional)</option>';

                        const taxRates = response.data.data.data || [];
                        taxRates.forEach(rate => {
                            if (rate.is_active) {
                                const option = document.createElement('option');
                                option.value = rate.id;
                                option.textContent = `${rate.name} (${rate.formatted_rate})`;
                                ratesSelect.appendChild(option);
                            }
                        });

                        // Enable multiple selection
                        ratesSelect.multiple = true;
                    }
                }
            } catch (error) {
                console.error('Error loading tax rates:', error);
            }
        }

        // Edit tax class
        async function editTaxClass(id) {
            try {
                const response = await axiosInstance.get(`tax-classes/${id}`);

                if (response.data.success) {
                    const taxClass = response.data.data;
                    isEditingClass = true;

                    // Fill form
                    document.getElementById('taxClassId').value = taxClass.id;
                    document.getElementById('taxClassName').value = taxClass.name || '';
                    document.getElementById('taxClassCode').value = taxClass.code || '';
                    document.getElementById('taxClassDescription').value = taxClass.description || '';
                    document.getElementById('taxClassIsDefault').checked = !!taxClass.is_default;

                    // Load tax rates and set selected ones
                    await loadAvailableTaxRates();
                    const ratesSelect = document.getElementById('taxClassRateIds');
                    if (ratesSelect && taxClass.tax_rates) {
                        Array.from(ratesSelect.options).forEach(option => {
                            const rateId = parseInt(option.value);
                            if (taxClass.tax_rates.some(rate => rate.id === rateId)) {
                                option.selected = true;
                            }
                        });
                    }

                    // Update UI
                    document.getElementById('taxClassModalTitle').textContent = 'Edit Tax Class';
                    document.getElementById('taxClassSubmitText').textContent = 'Update Tax Class';

                    document.getElementById('taxClassModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error editing tax class:', error);
                toastr.error('Failed to load tax class details');
            }
        }

        // Save tax class (create or update)
        async function saveTaxClass() {
            const form = document.getElementById('taxClassForm');
            const formData = new FormData(form);
            const taxClassData = Object.fromEntries(formData.entries());
            taxClassData.is_default = document.getElementById('taxClassIsDefault')?.checked ? true : false;


            // Validate required fields
            if (!taxClassData.name) {
                toastr.error('Name is required');
                return;
            }


            // Convert values
            taxClassData.is_default = document.getElementById('taxClassIsDefault')?.checked || false;

            // Process tax rate IDs (multiple select)
            const ratesSelect = document.getElementById('taxClassRateIds');
            let selectedRates = [];
            if (ratesSelect) {
                selectedRates = Array.from(ratesSelect.selectedOptions)
                    .map(option => option.value)
                    .filter(val => val)
                    .map(id => parseInt(id));
            }

            taxClassData.tax_rate_ids = selectedRates;

            // Remove empty fields
            Object.keys(taxClassData).forEach(key => {
                if (taxClassData[key] === '' || taxClassData[key] === null || taxClassData[key] === undefined) {
                    delete taxClassData[key];
                }
            });

            const method = isEditingClass ? 'put' : 'post';
            const url = isEditingClass ? `tax-classes/${taxClassData.id}` : 'tax-classes';

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            try {
                const response = await axiosInstance[method](url, taxClassData);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeTaxClassModal();

                    // Refresh data
                    await Promise.all([
                        loadTaxRates(),
                        loadTaxClasses(),
                        loadStatistics()
                    ]);
                } else {
                    toastr.error(response.data.message || 'Failed to save tax class');
                }
            } catch (error) {
                console.error('Save tax class error:', error);

                if (error.response && error.response.status === 422) {
                    // Validation errors
                    const errors = error.response.data.errors;
                    let errorMessages = [];
                    Object.keys(errors).forEach(field => {
                        errorMessages = errorMessages.concat(errors[field]);
                    });
                    toastr.error(errorMessages.join('<br>'));
                } else {
                    const errorMsg = error.response?.data?.message || 'Failed to save tax class';
                    toastr.error(errorMsg);
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Close tax class modal
        function closeTaxClassModal() {
            document.getElementById('taxClassModal').classList.add('hidden');
        }

        // View tax class details
        async function viewTaxClass(id) {
            try {
                const response = await axiosInstance.get(`tax-classes/${id}`);

                if (response.data.success) {
                    const taxClass = response.data.data;

                    // Build tax rates list HTML
                    let ratesHtml = '';
                    if (taxClass.tax_rates && taxClass.tax_rates.length > 0) {
                        ratesHtml = taxClass.tax_rates.map(rate => `
                            <div class="flex items-center justify-between p-2 bg-gray-50 rounded mb-1">
                                <div>
                                    <span class="font-medium">${rate.name}</span>
                                    <span class="text-sm text-gray-500 ml-2">${rate.formatted_rate}</span>
                                </div>
                                <span class="text-xs ${rate.is_active ? 'text-emerald-600' : 'text-gray-500'}">
                                    ${rate.is_active ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                        `).join('');
                    } else {
                        ratesHtml = '<p class="text-gray-500 text-sm">No tax rates in this class</p>';
                    }

                    // Show details in modal
                    Swal.fire({
                        title: taxClass.name,
                        html: `
                            <div class="text-left">
                                <p class="text-gray-600 mb-4">${taxClass.description || 'No description'}</p>
                                <div class="mb-4">
                                    <p class="text-sm text-gray-500 mb-2">Total Tax Rate:</p>
                                    <p class="text-2xl font-bold text-emerald-600">${taxClass.total_rate}%</p>
                                </div>
                                <h4 class="font-medium text-gray-700 mb-2">Tax Rates:</h4>
                                <div class="max-h-60 overflow-y-auto">
                                    ${ratesHtml}
                                </div>
                                <div class="mt-4 text-xs text-gray-500">
                                    <p>Code: ${taxClass.code}</p>
                                    <p>Created: ${taxClass.created_at}</p>
                                </div>
                            </div>
                        `,
                        icon: 'info',
                        confirmButtonText: 'Close',
                        width: '500px'
                    });
                }
            } catch (error) {
                toastr.error('Failed to load tax class details');
            }
        }

        // Toggle tax class default status
        async function toggleTaxClassDefault(id) {
            try {
                const response = await axiosInstance.post(`tax-classes/${id}/toggle-default`);

                if (response.data.success) {
                    toastr.success('Tax class default status updated successfully');
                    await loadTaxClasses();
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || 'Failed to update default status';
                toastr.error(errorMsg);
            }
        }

        // Delete tax class
        async function deleteTaxClass(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the tax class. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.delete(`tax-classes/${id}`);

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        // Refresh data
                        await Promise.all([
                            loadTaxRates(),
                            loadTaxClasses(),
                            loadStatistics()
                        ]);
                    }
                } catch (error) {
                    const errorMsg = error.response?.data?.message || 'Failed to delete tax class';
                    toastr.error(errorMsg);
                }
            }
        }

        // Handle bulk delete for tax rates
        async function handleTaxBulkDelete() {
            const selectedRows = taxesTable.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                toastr.warning('Please select at least one tax rate to delete.');
                return;
            }

            const result = await Swal.fire({
                title: 'Confirm Bulk Delete',
                text: `Are you sure you want to delete ${selectedIds.length} tax rate(s)? This action cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete them!',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#ef4444'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post('tax-rates/bulk-delete', {
                        ids: selectedIds
                    });

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        // Refresh data
                        await Promise.all([
                            loadTaxRates(),
                            loadTaxClasses(),
                            loadStatistics()
                        ]);
                    }
                } catch (error) {
                    const errorMsg = error.response?.data?.message || 'Failed to delete tax rates';
                    toastr.error(errorMsg);
                }
            }
        }

        // Handle bulk toggle for tax rates
        async function handleTaxBulkToggle() {
            const selectedRows = taxesTable.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                toastr.warning('Please select at least one tax rate to toggle.');
                return;
            }

            Swal.fire({
                title: 'Bulk Status Change',
                html: `
                    <div class="text-left space-y-4">
                        <p class="text-gray-700">Change status for <strong>${selectedIds.length}</strong> tax rate(s)</p>

                        <div class="grid grid-cols-2 gap-4">
                            <button id="bulkActivateBtn" class="p-4 border-2 border-emerald-200 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="fas fa-toggle-on text-2xl text-emerald-600"></i>
                                </div>
                                <p class="font-medium text-emerald-800">Activate</p>
                                <p class="text-sm text-emerald-600">Set all to Active</p>
                            </button>

                            <button id="bulkDeactivateBtn" class="p-4 border-2 border-rose-200 bg-rose-50 rounded-lg hover:bg-rose-100 transition">
                                <div class="flex items-center justify-center mb-2">
                                    <i class="fas fa-toggle-off text-2xl text-rose-600"></i>
                                </div>
                                <p class="font-medium text-rose-800">Deactivate</p>
                                <p class="text-sm text-rose-600">Set all to Inactive</p>
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Cancel',
                width: '500px'
            });

            document.getElementById('bulkActivateBtn').addEventListener('click', async () => {
                Swal.close();
                await performTaxBulkStatus(selectedIds, true);
            });

            document.getElementById('bulkDeactivateBtn').addEventListener('click', async () => {
                Swal.close();
                await performTaxBulkStatus(selectedIds, false);
            });
        }

        // Perform bulk status update
        async function performTaxBulkStatus(selectedIds, activate) {
            try {
                const response = await axiosInstance.post('/tax-rates/bulk-status', {
                    ids: selectedIds,
                    is_active: activate
                });

                if (response.data.success) {
                    toastr.success(
                        `Successfully ${activate ? 'activated' : 'deactivated'} ${response.data.data.updated_count} tax rate(s)`
                    );

                    // Refresh data
                    await Promise.all([
                        loadTaxRates(),
                        loadStatistics()
                    ]);
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || 'Failed to update tax rates status';
                toastr.error(errorMsg);
            }
        }

        // Tax calculator functions
        function showTaxCalculator() {
            document.getElementById('taxCalculatorModal').classList.remove('hidden');
        }

        function closeTaxCalculator() {
            document.getElementById('taxCalculatorModal').classList.add('hidden');
        }

        document.getElementById('taxCalculatorModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTaxCalculator();
            }
        });

        async function calculateTax() {
            const amount = parseFloat(document.getElementById('calcAmount').value);
            const rate = parseFloat(document.getElementById('calcRate').value);

            if (!amount || isNaN(amount)) {
                toastr.error('Please enter a valid amount');
                return;
            }

            if (!rate || isNaN(rate)) {
                toastr.error('Please enter a valid tax rate');
                return;
            }

            const taxAmount = (amount * rate) / 100;
            const totalAmount = amount + taxAmount;

            document.getElementById('originalAmount').textContent = `₹${amount.toFixed(2)}`;
            document.getElementById('taxAmount').textContent = `₹${taxAmount.toFixed(2)}`;
            document.getElementById('totalAmount').textContent = `₹${totalAmount.toFixed(2)}`;
            document.getElementById('calcResult').classList.remove('hidden');
        }

        // Export taxes
        function exportTaxes() {
            const format = confirm('Export as CSV? Click OK for CSV, Cancel for Excel') ? 'csv' : 'xlsx';

            if (format === 'csv') {
                taxesTable.download("csv", "tax_rates.csv");
            } else {
                taxesTable.download("xlsx", "tax_rates.xlsx", {
                    sheetName: "Tax Rates"
                });
            }
        }

        // Refresh all data
        async function refreshAll() {
            try {
                await Promise.all([
                    loadTaxRates(),
                    loadTaxClasses(),
                    loadStatistics()
                ]);
                toastr.info('Tax data refreshed');
            } catch (error) {
                toastr.error('Failed to refresh tax data');
            }
        }

        function toggleTaxRateState() {
            const scope = document.getElementById('taxRateScope')?.value;
            const stateContainer = document.getElementById('taxRateStateContainer');
            const stateInput = document.getElementById('taxRateState');

            if (!stateContainer) return;

            // Show state field only for local/state scope
            if (scope === 'state' || scope === 'local') {
                stateContainer.classList.remove('hidden');
                stateInput?.setAttribute('required', 'required');
            } else {
                stateContainer.classList.add('hidden');
                stateInput?.removeAttribute('required');
                if (stateInput) stateInput.value = '';
            }
        }
    </script>
@endpush
