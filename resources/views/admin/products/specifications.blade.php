@extends('admin.layouts.master')

@section('title', 'Product Specifications Management')

@section('content')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Product Specifications Management</h2>
                <p class="text-gray-600">Manage specifications and create groups for product details</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="openAddSpecificationModal()" class="btn-secondary">
                    <i class="fas fa-plus mr-2"></i>New Specification
                </button>
                <button onclick="openAddGroupModal()" class="btn-primary">
                    <i class="fas fa-layer-group mr-2"></i>Create Group
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Specifications</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="totalSpecs">0</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-xl">
                    <i class="fas fa-list-alt text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Required Specs</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="requiredSpecs">0</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl">
                    <i class="fas fa-exclamation-circle text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Filterable</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="filterableSpecs">0</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <i class="fas fa-filter text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Groups</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="totalGroups">0</p>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl">
                    <i class="fas fa-layer-group text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="mb-6">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <button id="specsTab"
                    class="tab-button active border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-list-alt mr-2"></i>Specifications
                </button>
                <button id="groupsTab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-layer-group mr-2"></i>Groups
                </button>
                <button id="valuesTab"
                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    <i class="fas fa-list mr-2"></i>Values
                </button>
            </nav>
        </div>
    </div>

    <!-- Specifications Section -->
    <div id="specificationsSection" class="tab-content active">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">All Specifications</h3>
                    <div class="flex space-x-2">
                        <button id="specsBulkActionsBtn" class="btn-secondary">
                            <i class="fas fa-bolt mr-2"></i>Bulk Actions
                        </button>
                        <button id="specsColumnVisibilityBtn" class="btn-secondary">
                            <i class="fas fa-columns mr-2"></i>Columns
                        </button>
                        <div class="relative group">
                            <button id="specsExportBtn" class="btn-primary">
                                <i class="fas fa-file-export mr-2"></i>Export
                            </button>
                            <div class="absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden group-hover:block right-0">
                                <button data-export="csv" class="export-btn w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                                    <i class="fas fa-file-csv mr-2"></i>CSV
                                </button>
                                <button data-export="xlsx" class="export-btn w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                                    <i class="fas fa-file-excel mr-2"></i>Excel
                                </button>
                                <button data-export="print" class="export-btn w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                                    <i class="fas fa-print mr-2"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="searchSpecsInput" placeholder="Search specifications..."
                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Tabulator Table Container -->
                <div id="specificationsTable" class="w-full overflow-x-auto"></div>
            </div>
        </div>
    </div>

    <!-- Groups Section -->
    <div id="groupsSection" class="tab-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">All Specification Groups</h3>
            </div>
            <div class="p-6">
                <!-- Search Bar -->
                <div class="mb-4">
                    <div class="relative">
                        <input type="text" id="searchGroupsInput" placeholder="Search groups..."
                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Tabulator Table Container -->
                <div id="groupsTable" class="w-full overflow-x-auto"></div>
            </div>
        </div>
    </div>

    <!-- Values Section -->
    <div id="valuesSection" class="tab-content hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800" id="valuesTitle">Specification Values</h3>
                        <p class="text-sm text-gray-500" id="valuesSubtitle">Select a specification to view its values</p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="goBackToSpecs()" class="btn-secondary" id="backToSpecsBtn" style="display: none;">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Specifications
                        </button>
                        <button onclick="openAddValueModal()" class="btn-primary" id="addValueBtn" style="display: none;">
                            <i class="fas fa-plus mr-2"></i>Add Value
                        </button>
                        <button id="valuesBulkActionsBtn" class="btn-secondary" style="display: none;">
                            <i class="fas fa-bolt mr-2"></i>Bulk Actions
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <!-- Specification Selector -->
                <div id="specSelector" class="mb-6">
                    <div class="bg-gray-50 rounded-xl p-6">
                        <h4 class="text-lg font-medium text-gray-900 mb-4">Select a Specification</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="md:col-span-2">
                                <select id="specificationSelector" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">Select a specification to view its values</option>
                                </select>
                            </div>
                            <div>
                                <button onclick="loadSpecificationValues()" class="btn-primary w-full h-full">
                                    <i class="fas fa-search mr-2"></i>Load Values
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Values Table -->
                <div id="valuesContent" class="hidden">
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="searchValuesInput" placeholder="Search values..."
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div id="valuesTable" class="w-full overflow-x-auto"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Specification Modal -->
    <div id="specificationModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Add New Specification</h3>
                <button onclick="closeSpecificationModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 120px)">
                <form id="specificationForm">
                    <input type="hidden" id="specificationId" name="id" value="">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Specification Name *</label>
                            <input type="text" id="specificationName" name="name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., Gender, Material, Warranty" required>
                            <p class="text-xs text-red-500 mt-1 hidden" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code *</label>
                            <input type="text" id="specificationCode" name="code"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., gender, material, warranty" required>
                            <p class="text-xs text-gray-500 mt-1">Unique identifier (lowercase, underscores only)</p>
                            <p class="text-xs text-red-500 mt-1 hidden" id="codeError"></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Input Type *</label>
                                <select id="specificationInputType" name="input_type"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                    <option value="">Select type</option>
                                    <option value="text">Text</option>
                                    <option value="textarea">Text Area</option>
                                    <option value="select">Select</option>
                                    <option value="multiselect">Multi Select</option>
                                    <option value="radio">Radio Buttons</option>
                                    <option value="checkbox">Checkbox</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input type="number" id="specificationSortOrder" name="sort_order" value="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="0" min="0">
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="specificationIsRequired" name="is_required"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="specificationIsRequired" class="ml-2 text-sm text-gray-700">Required Field</label>
                            </div>
                            <p class="text-xs text-gray-500 ml-6">This specification is required for products</p>

                            <div class="flex items-center">
                                <input type="checkbox" id="specificationIsFilterable" name="is_filterable"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="specificationIsFilterable" class="ml-2 text-sm text-gray-700">Use in Filters</label>
                            </div>
                            <p class="text-xs text-gray-500 ml-6">Show in product filter options</p>

                            <div class="flex items-center">
                                <input type="checkbox" id="specificationStatus" name="status" checked
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <label for="specificationStatus" class="ml-2 text-sm text-gray-700">Active</label>
                            </div>
                            <p class="text-xs text-gray-500 ml-6">Specification will be available for use</p>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeSpecificationModal()" class="btn-secondary">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    <span id="submitText">Save Specification</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Specification Value Modal -->
    <div id="valueModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800" id="valueModalTitle">Add Value</h3>
                <button onclick="closeValueModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 120px)">
                <form id="valueForm">
                    <input type="hidden" id="valueId" name="id" value="">
                    <input type="hidden" id="valueSpecificationId" name="specification_id" value="">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                            <input type="text" id="valueValue" name="value"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., Male, Cotton, 1 Year" required>
                            <p class="text-xs text-red-500 mt-1 hidden" id="valueError"></p>
                        </div>

                        <div id="quickAddCommonValues" class="hidden">
                            <!-- Quick add buttons will be inserted here -->
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input type="number" id="valueSortOrder" name="sort_order" value="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="0" min="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" id="valueStatus" name="status" checked
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="valueStatus" class="ml-2 text-sm text-gray-700">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeValueModal()" class="btn-secondary">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    <span id="valueSubmitText">Save Value</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add/Edit Group Modal -->
    <div id="groupModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800" id="groupModalTitle">Create New Group</h3>
                <button onclick="closeGroupModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 120px)">
                <form id="groupForm">
                    <input type="hidden" id="groupId" name="id" value="">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Group Name *</label>
                            <input type="text" id="groupName" name="name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., General Specifications" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Specifications</label>
                            <select id="groupSpecificationIds" name="specification_ids[]" multiple
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <!-- Specifications will be loaded dynamically -->
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple specifications</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                                <input type="number" id="groupSortOrder" name="sort_order" value="0"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="0" min="0">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <div class="flex items-center mt-2">
                                    <input type="checkbox" id="groupStatus" name="status" checked
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="groupStatus" class="ml-2 text-sm text-gray-700">Active</label>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <button type="button" onclick="closeGroupModal()" class="btn-secondary">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    <span id="groupSubmitText">Create Group</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Bulk Actions</h3>
                <div class="space-y-3">
                    <button onclick="applyBulkAction('activate')" class="w-full btn-secondary text-left">
                        <i class="fas fa-toggle-on mr-2 text-emerald-600"></i>Activate Selected
                    </button>
                    <button onclick="applyBulkAction('deactivate')" class="w-full btn-secondary text-left">
                        <i class="fas fa-toggle-off mr-2 text-rose-600"></i>Deactivate Selected
                    </button>
                    <button onclick="applyBulkAction('required')" class="w-full btn-secondary text-left">
                        <i class="fas fa-check-circle mr-2 text-blue-600"></i>Mark as Required
                    </button>
                    <button onclick="applyBulkAction('not-required')" class="w-full btn-secondary text-left">
                        <i class="fas fa-times-circle mr-2 text-gray-500"></i>Mark as Not Required
                    </button>
                    <button onclick="applyBulkAction('filterable')" class="w-full btn-secondary text-left">
                        <i class="fas fa-filter mr-2 text-indigo-600"></i>Mark as Filterable
                    </button>
                    <button onclick="applyBulkAction('not-filterable')" class="w-full btn-secondary text-left">
                        <i class="fas fa-filter mr-2 text-gray-500"></i>Mark as Not Filterable
                    </button>
                    <button onclick="applyBulkAction('delete')" class="w-full btn-secondary text-left border-rose-200 text-rose-600 hover:bg-rose-50">
                        <i class="fas fa-trash mr-2"></i>Delete Selected
                    </button>
                </div>
                <div class="mt-6 flex justify-center">
                    <button onclick="closeBulkActions()" class="btn-secondary">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Group Details Modal -->
    <div id="viewGroupModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4" id="viewGroupTitle"></h3>
                <div id="groupSpecsList" class="space-y-2 max-h-96 overflow-y-auto">
                    <!-- Specifications will be listed here -->
                </div>
                <div class="mt-6 flex justify-center">
                    <button onclick="closeViewGroupModal()" class="btn-secondary">
                        Close
                    </button>
                </div>
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
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
        }
    });

    // Global variables
    let specsTable = null;
    let groupsTable = null;
    let valuesTable = null;
    let isEditingSpec = false;
    let isEditingValue = false;
    let isEditingGroup = false;
    let currentSpecificationId = null;
    let currentSpecificationName = null;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing specifications...');

        // Initialize Tabulator tables
        initializeSpecsTable([]);
        initializeGroupsTable([]);

        // Load initial data
        refreshAllData();

        setupEventListeners();
        setupModals();
    });

    // ==================== DATA LOADING FUNCTIONS ====================

    // Refresh all data
    async function refreshAllData() {
        console.log('Refreshing all data...');

        try {
            await Promise.all([
                loadSpecificationsData(),
                loadGroupsData(),
                loadStatistics(),
                loadSpecificationsForSelector()
            ]);
            console.log('All data refreshed successfully');
        } catch (error) {
            console.error('Error refreshing data:', error);
            toastr.error('Failed to load data');
        }
    }

    // Load specifications data
    async function loadSpecificationsData() {
        console.log('Loading specifications data...');

        try {
            const response = await axiosInstance.get('specifications');

            if (response.data.success) {
                const specs = response.data.data.data || [];
                console.log('Loaded', specs.length, 'specifications');

                if (specsTable) {
                    specsTable.setData(specs);
                }
            }
        } catch (error) {
            console.error('Error loading specifications:', error);
            toastr.error('Failed to load specifications');
        }
    }

    // Load groups data
    async function loadGroupsData() {
        console.log('Loading groups data...');

        try {
            const response = await axiosInstance.get('specification-groups');

            if (response.data.success) {
                const groups = response.data.data.data || [];
                console.log('Loaded', groups.length, 'groups');

                if (groupsTable) {
                    groupsTable.setData(groups);
                }
            }
        } catch (error) {
            console.error('Error loading groups:', error);
            toastr.error('Failed to load groups');
        }
    }

    // Load statistics
    async function loadStatistics() {
        console.log('Loading statistics...');

        try {
            const response = await axiosInstance.get('specifications/statistics');
            const groupsResponse = await axiosInstance.get('specification-groups/statistics');

            if (response.data.success && groupsResponse.data.success) {
                const stats = response.data.data;
                const groupsStats = groupsResponse.data.data;

                updateElementText('totalSpecs', stats.total_specifications || 0);
                updateElementText('requiredSpecs', stats.required_specifications || 0);
                updateElementText('filterableSpecs', stats.filterable_specifications || 0);
                updateElementText('totalGroups', groupsStats.total_groups || 0);
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
            toastr.error('Failed to load statistics');
        }
    }

    // Load specifications for selector
    async function loadSpecificationsForSelector() {
        console.log('Loading specifications for selector...');

        try {
            const response = await axiosInstance.get('specifications/dropdown');

            if (response.data.success) {
                const selector = document.getElementById('specificationSelector');
                selector.innerHTML = '<option value="">Select a specification to view its values</option>';

                response.data.data.forEach(spec => {
                    const option = document.createElement('option');
                    option.value = spec.id;
                    option.textContent = `${spec.name} (${spec.code})`;
                    selector.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading specifications for selector:', error);
        }
    }

    // Load specification values
    async function loadSpecificationValues() {
        const specId = document.getElementById('specificationSelector').value;

        if (!specId) {
            toastr.warning('Please select a specification first');
            return;
        }

        currentSpecificationId = specId;

        try {
            const response = await axiosInstance.get(`specifications/${specId}/values`);

            if (response.data.success) {
                const values = response.data.data.data || [];
                const spec = response.data.data.specification;

                currentSpecificationName = spec.name;

                // Update UI
                updateElementText('valuesTitle', `${spec.name} Values`);
                updateElementText('valuesSubtitle', `Code: ${spec.code} | Input Type: ${spec.input_type} | Total: ${values.length} values`);

                // Show values content
                document.getElementById('specSelector').classList.add('hidden');
                document.getElementById('valuesContent').classList.remove('hidden');
                document.getElementById('backToSpecsBtn').style.display = 'block';
                document.getElementById('addValueBtn').style.display = 'block';
                document.getElementById('valuesBulkActionsBtn').style.display = 'block';

                // Initialize or refresh values table
                if (!valuesTable) {
                    initializeValuesTable(values);
                } else {
                    valuesTable.setData(values);
                }
            }
        } catch (error) {
            console.error('Error loading specification values:', error);
            toastr.error('Failed to load specification values');
        }
    }

    // ==================== TABULATOR INITIALIZATION ====================

    // Initialize specifications table
    function initializeSpecsTable(data) {
        console.log('Initializing specifications table...');

        specsTable = new Tabulator("#specificationsTable", {
            data: data,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            selectable: true,
            selectableRangeMode: "click",
            placeholder: "No specifications found",
            columns: [
                {
                    title: "<input type='checkbox' id='selectAllSpecs'>",
                    field: "id",
                    formatter: "rowSelection",
                    titleFormatter: "rowSelection",
                    hozAlign: "center",
                    headerSort: false,
                    width: 50,
                    cssClass: "select-checkbox"
                },
                {
                    title: "ID",
                    field: "id",
                    width: 70,
                    sorter: "number",
                    hozAlign: "center",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search ID"
                },
                {
                    title: "Name",
                    field: "name",
                    width: 180,
                    sorter: "string",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search name",
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        return `
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-r from-indigo-400 to-purple-500">
                                    <i class="fas fa-tag text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${rowData.name}</p>
                                    <p class="text-sm text-gray-500">${rowData.code}</p>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    title: "Input Type",
                    field: "input_type",
                    width: 120,
                    sorter: "string",
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "text": "Text",
                            "textarea": "Text Area",
                            "select": "Select",
                            "multiselect": "Multi Select",
                            "radio": "Radio",
                            "checkbox": "Checkbox"
                        }
                    },
                    formatter: function(cell) {
                        const type = cell.getValue();
                        const typeConfig = {
                            'text': { class: 'bg-blue-100 text-blue-800', icon: 'fa-font' },
                            'textarea': { class: 'bg-indigo-100 text-indigo-800', icon: 'fa-align-left' },
                            'select': { class: 'bg-emerald-100 text-emerald-800', icon: 'fa-list' },
                            'multiselect': { class: 'bg-amber-100 text-amber-800', icon: 'fa-list-check' },
                            'radio': { class: 'bg-purple-100 text-purple-800', icon: 'fa-dot-circle' },
                            'checkbox': { class: 'bg-pink-100 text-pink-800', icon: 'fa-check-square' }
                        };
                        const config = typeConfig[type] || typeConfig['text'];
                        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${config.class}">
                            <i class="fas ${config.icon} mr-1"></i>
                            <span class="capitalize">${type}</span>
                        </span>`;
                    }
                },
                {
                    title: "Values",
                    field: "values_count",
                    width: 100,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell, formatterParams, onRendered) {
                        const rowData = cell.getRow().getData();
                        const count = rowData.values_count || 0;

                        if (count === 0) {
                            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                No values
                            </span>`;
                        }

                        return `
                            <button onclick="viewSpecValues(${rowData.id}, '${rowData.name}')"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-list mr-1"></i>
                                ${count}
                            </button>
                        `;
                    }
                },
                {
                    title: "Required",
                    field: "is_required",
                    width: 100,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Yes",
                            "false": "No"
                        }
                    },
                    formatter: function(cell) {
                        const isRequired = cell.getValue();
                        return isRequired ?
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-100 text-rose-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>Required
                            </span>` :
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times mr-1"></i>Optional
                            </span>`;
                    }
                },
                {
                    title: "Filterable",
                    field: "is_filterable",
                    width: 100,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Yes",
                            "false": "No"
                        }
                    },
                    formatter: function(cell) {
                        const isFilterable = cell.getValue();
                        return isFilterable ?
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                <i class="fas fa-filter mr-1"></i>Yes
                            </span>` :
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times mr-1"></i>No
                            </span>`;
                    }
                },
                {
                    title: "Status",
                    field: "status",
                    width: 100,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Active",
                            "false": "Inactive"
                        }
                    },
                    formatter: function(cell) {
                        const isActive = cell.getValue();
                        const row = cell.getRow();
                        const data = row.getData();

                        return isActive ?
                            `<button onclick="toggleSpecStatus(${data.id}, false)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-colors">
                                <i class="fas fa-toggle-on mr-1"></i>Active
                            </button>` :
                            `<button onclick="toggleSpecStatus(${data.id}, true)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors">
                                <i class="fas fa-toggle-off mr-1"></i>Inactive
                            </button>`;
                    }
                },
                {
                    title: "Order",
                    field: "sort_order",
                    width: 80,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell) {
                        return `<span class="font-semibold">${cell.getValue() || 0}</span>`;
                    }
                },
                {
                    title: "Actions",
                    field: "actions",
                    width: 120,
                    hozAlign: "center",
                    headerSort: false,
                    formatter: function(cell) {
                        const row = cell.getRow();
                        const data = row.getData();

                        return `
                            <div class="flex space-x-2 justify-center">
                                <button onclick="editSpecification(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                        title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="deleteSpecification(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                        title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Setup table events
        specsTable.on("tableBuilt", function() {
            console.log("Specifications table built successfully");
            setupSpecsTableFunctionality();
        });
    }

    // Initialize groups table
    function initializeGroupsTable(data) {
        console.log('Initializing groups table...');

        groupsTable = new Tabulator("#groupsTable", {
            data: data,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            placeholder: "No groups found",
            columns: [
                {
                    title: "ID",
                    field: "id",
                    width: 70,
                    sorter: "number",
                    hozAlign: "center",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search ID"
                },
                {
                    title: "Group Name",
                    field: "name",
                    width: 200,
                    sorter: "string",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search name",
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        return `
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center bg-gradient-to-r from-blue-400 to-cyan-500">
                                    <i class="fas fa-layer-group text-white text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${rowData.name}</p>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    title: "Specifications",
                    field: "specifications_count",
                    width: 120,
                    hozAlign: "center",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search count...",
                    formatter: function(cell, formatterParams, onRendered) {
                        const rowData = cell.getRow().getData();
                        const count = rowData.specifications_count || 0;

                        if (count === 0) {
                            return `<span class="text-gray-400 text-sm">No specifications</span>`;
                        }

                        return `
                            <button onclick="viewGroup(${rowData.id})"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-colors">
                                <i class="fas fa-list mr-1"></i>
                                ${count}
                            </button>
                        `;
                    }
                },
                {
                    title: "Status",
                    field: "status",
                    width: 100,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Active",
                            "false": "Inactive"
                        }
                    },
                    formatter: function(cell) {
                        const isActive = cell.getValue();
                        const row = cell.getRow();
                        const data = row.getData();

                        return isActive ?
                            `<button onclick="toggleGroupStatus(${data.id}, false)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-colors">
                                <i class="fas fa-toggle-on mr-1"></i>Active
                            </button>` :
                            `<button onclick="toggleGroupStatus(${data.id}, true)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors">
                                <i class="fas fa-toggle-off mr-1"></i>Inactive
                            </button>`;
                    }
                },
                {
                    title: "Order",
                    field: "sort_order",
                    width: 80,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell) {
                        return `<span class="font-semibold">${cell.getValue() || 0}</span>`;
                    }
                },
                {
                    title: "Actions",
                    field: "actions",
                    width: 120,
                    hozAlign: "center",
                    headerSort: false,
                    formatter: function(cell) {
                        const row = cell.getRow();
                        const data = row.getData();

                        return `
                            <div class="flex space-x-2 justify-center">
                                <button onclick="viewGroup(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                        title="View">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                                <button onclick="editGroup(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors"
                                        title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="deleteGroup(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                        title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        groupsTable.on("tableBuilt", function() {
            console.log("Groups table built successfully");
            setupGroupsTableFunctionality();
        });
    }

    // Initialize values table
    function initializeValuesTable(data) {
        console.log('Initializing values table...');

        valuesTable = new Tabulator("#valuesTable", {
            data: data,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            selectable: true,
            selectableRangeMode: "click",
            placeholder: "No values found",
            columns: [
                {
                    title: "<input type='checkbox' id='selectAllValues'>",
                    field: "id",
                    formatter: "rowSelection",
                    titleFormatter: "rowSelection",
                    hozAlign: "center",
                    headerSort: false,
                    width: 50,
                    cssClass: "select-checkbox"
                },
                {
                    title: "ID",
                    field: "id",
                    width: 70,
                    sorter: "number",
                    hozAlign: "center",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search ID"
                },
                {
                    title: "Value",
                    field: "value",
                    width: 200,
                    sorter: "string",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search value",
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        return `<span class="font-medium text-gray-900">${rowData.value}</span>`;
                    }
                },
                {
                    title: "Order",
                    field: "sort_order",
                    width: 80,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell) {
                        return `<span class="font-semibold">${cell.getValue() || 0}</span>`;
                    }
                },
                {
                    title: "Status",
                    field: "status",
                    width: 100,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Active",
                            "false": "Inactive"
                        }
                    },
                    formatter: function(cell) {
                        const isActive = cell.getValue();
                        const row = cell.getRow();
                        const data = row.getData();

                        return isActive ?
                            `<button onclick="toggleValueStatus(${data.id}, false)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-colors">
                                <i class="fas fa-toggle-on mr-1"></i>Active
                            </button>` :
                            `<button onclick="toggleValueStatus(${data.id}, true)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800 hover:bg-rose-200 transition-colors">
                                <i class="fas fa-toggle-off mr-1"></i>Inactive
                            </button>`;
                    }
                },
                {
                    title: "Created",
                    field: "created_at",
                    width: 120,
                    sorter: "date",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search date..."
                },
                {
                    title: "Actions",
                    field: "actions",
                    width: 100,
                    hozAlign: "center",
                    headerSort: false,
                    formatter: function(cell) {
                        const row = cell.getRow();
                        const data = row.getData();

                        return `
                            <div class="flex space-x-2 justify-center">
                                <button onclick="editValue(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                        title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="deleteValue(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                        title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        valuesTable.on("tableBuilt", function() {
            console.log("Values table built successfully");
            setupValuesTableFunctionality();
        });
    }

    // ==================== SETUP FUNCTIONS ====================

    // Setup event listeners
    function setupEventListeners() {
        // Tab switching
        document.getElementById('specsTab').addEventListener('click', () => switchTab('specs'));
        document.getElementById('groupsTab').addEventListener('click', () => switchTab('groups'));
        document.getElementById('valuesTab').addEventListener('click', () => switchTab('values'));

        // Form submissions
        document.getElementById('specificationForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveSpecification();
        });

        document.getElementById('valueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveValue();
        });

        document.getElementById('groupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveGroup();
        });
    }

    // Setup modals
    function setupModals() {
        // Close modals when clicking outside
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modal.id === 'specificationModal') closeSpecificationModal();
                    if (modal.id === 'valueModal') closeValueModal();
                    if (modal.id === 'groupModal') closeGroupModal();
                    if (modal.id === 'bulkActionsModal') closeBulkActions();
                    if (modal.id === 'viewGroupModal') closeViewGroupModal();
                }
            });
        });

        // Escape key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSpecificationModal();
                closeValueModal();
                closeGroupModal();
                closeBulkActions();
                closeViewGroupModal();
            }
        });
    }

    // Setup specs table functionality
    function setupSpecsTableFunctionality() {
        // Search input
        const searchInput = document.getElementById('searchSpecsInput');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (specsTable) {
                    const term = this.value.toLowerCase();
                    specsTable.setFilter([
                        { field: "name", type: "like", value: term },
                        { field: "code", type: "like", value: term }
                    ]);
                }
            }, 300);
        });

        // Bulk actions
        const bulkActionsBtn = document.getElementById('specsBulkActionsBtn');
        if (bulkActionsBtn) {
            bulkActionsBtn.addEventListener('click', function() {
                const selectedRows = specsTable.getSelectedRows();
                if (selectedRows.length === 0) {
                    toastr.warning('Please select at least one specification');
                    return;
                }
                document.getElementById('bulkActionsModal').classList.remove('hidden');
            });
        }
    }

    // Setup groups table functionality
    function setupGroupsTableFunctionality() {
        // Search input
        const searchInput = document.getElementById('searchGroupsInput');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (groupsTable) {
                    const term = this.value.toLowerCase();
                    groupsTable.setFilter([
                        { field: "name", type: "like", value: term }
                    ]);
                }
            }, 300);
        });
    }

    // Setup values table functionality
    function setupValuesTableFunctionality() {
        // Search input
        const searchInput = document.getElementById('searchValuesInput');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (valuesTable) {
                        const term = this.value.toLowerCase();
                        valuesTable.setFilter([
                            { field: "value", type: "like", value: term }
                        ]);
                    }
                }, 300);
            });
        }

        // Values bulk actions
        const valuesBulkActionsBtn = document.getElementById('valuesBulkActionsBtn');
        if (valuesBulkActionsBtn) {
            valuesBulkActionsBtn.addEventListener('click', function() {
                if (!valuesTable) {
                    toastr.warning('No values table found');
                    return;
                }

                const selectedRows = valuesTable.getSelectedRows();
                if (selectedRows.length === 0) {
                    toastr.warning('Please select at least one value');
                    return;
                }

                openValuesBulkActionsModal();
            });
        }
    }

    // ==================== TAB FUNCTIONS ====================

    // Switch tab
    function switchTab(tab) {
        // Update tab buttons
        document.getElementById('specsTab').classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        document.getElementById('specsTab').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('groupsTab').classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        document.getElementById('groupsTab').classList.add('border-transparent', 'text-gray-500');
        document.getElementById('valuesTab').classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        document.getElementById('valuesTab').classList.add('border-transparent', 'text-gray-500');

        // Hide all sections
        document.getElementById('specificationsSection').classList.remove('active');
        document.getElementById('specificationsSection').classList.add('hidden');
        document.getElementById('groupsSection').classList.remove('active');
        document.getElementById('groupsSection').classList.add('hidden');
        document.getElementById('valuesSection').classList.remove('active');
        document.getElementById('valuesSection').classList.add('hidden');

        // Show selected tab
        if (tab === 'specs') {
            document.getElementById('specsTab').classList.add('active', 'border-indigo-500', 'text-indigo-600');
            document.getElementById('specificationsSection').classList.add('active');
            document.getElementById('specificationsSection').classList.remove('hidden');
        } else if (tab === 'groups') {
            document.getElementById('groupsTab').classList.add('active', 'border-indigo-500', 'text-indigo-600');
            document.getElementById('groupsSection').classList.add('active');
            document.getElementById('groupsSection').classList.remove('hidden');
        } else if (tab === 'values') {
            document.getElementById('valuesTab').classList.add('active', 'border-indigo-500', 'text-indigo-600');
            document.getElementById('valuesSection').classList.add('active');
            document.getElementById('valuesSection').classList.remove('hidden');
        }
    }

    // Go back to specs from values
    function goBackToSpecs() {
        document.getElementById('specSelector').classList.remove('hidden');
        document.getElementById('valuesContent').classList.add('hidden');
        document.getElementById('backToSpecsBtn').style.display = 'none';
        document.getElementById('addValueBtn').style.display = 'none';
        document.getElementById('valuesBulkActionsBtn').style.display = 'none';
    }

    // View spec values
    function viewSpecValues(specId, specName) {
        currentSpecificationId = specId;
        currentSpecificationName = specName;

        // Switch to values tab and load values
        switchTab('values');

        // Set the selector value
        document.getElementById('specificationSelector').value = specId;

        // Load values
        setTimeout(() => {
            loadSpecificationValues();
        }, 100);
    }

    // ==================== SPECIFICATION FUNCTIONS ====================

    // Open add specification modal
    function openAddSpecificationModal() {
        isEditingSpec = false;
        currentSpecificationId = null;

        updateElementText('modalTitle', 'Add New Specification');
        updateElementText('submitText', 'Save Specification');

        // Reset form
        const form = document.getElementById('specificationForm');
        form.reset();
        document.getElementById('specificationId').value = '';
        document.getElementById('specificationSortOrder').value = 0;
        document.getElementById('specificationIsRequired').checked = false;
        document.getElementById('specificationIsFilterable').checked = false;
        document.getElementById('specificationStatus').checked = true;

        // Clear errors
        clearFormErrors('specificationForm');

        // Show modal
        showElement('specificationModal');
    }

    // Close specification modal
    function closeSpecificationModal() {
        hideElement('specificationModal');
    }

    // Edit specification
    async function editSpecification(id) {
        try {
            console.log('Loading specification for edit:', id);
            const response = await axiosInstance.get(`specifications/${id}`);

            if (response.data.success) {
                isEditingSpec = true;
                currentSpecificationId = id;
                const spec = response.data.data;

                // Fill form
                document.getElementById('specificationId').value = spec.id;
                document.getElementById('specificationName').value = spec.name;
                document.getElementById('specificationCode').value = spec.code;
                document.getElementById('specificationInputType').value = spec.input_type;
                document.getElementById('specificationSortOrder').value = spec.sort_order || 0;
                document.getElementById('specificationIsRequired').checked = spec.is_required;
                document.getElementById('specificationIsFilterable').checked = spec.is_filterable;
                document.getElementById('specificationStatus').checked = spec.status;

                // Update UI
                updateElementText('modalTitle', 'Edit Specification');
                updateElementText('submitText', 'Update Specification');

                showElement('specificationModal');
            }
        } catch (error) {
            console.error('Error editing specification:', error);
            toastr.error('Failed to load specification details');
        }
    }

    // Save specification
    async function saveSpecification() {
        const form = document.getElementById('specificationForm');
        const formData = new FormData(form);
        const specData = Object.fromEntries(formData.entries());

        // Convert checkbox values
        specData.is_required = document.getElementById('specificationIsRequired').checked ? 1 : 0;
        specData.is_filterable = document.getElementById('specificationIsFilterable').checked ? 1 : 0;
        specData.status = document.getElementById('specificationStatus').checked ? 1 : 0;

        const method = isEditingSpec ? 'put' : 'post';
        const url = isEditingSpec ? `specifications/${currentSpecificationId}` : 'specifications';

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;

        try {
            const response = await axiosInstance[method](url, specData);

            if (response.data.success) {
                toastr.success(response.data.message);
                closeSpecificationModal();

                // Refresh data
                await Promise.all([
                    loadSpecificationsData(),
                    loadStatistics(),
                    loadSpecificationsForSelector()
                ]);
            }
        } catch (error) {
            handleFormError(error, 'specificationForm');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Delete specification
    async function deleteSpecification(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the specification and all associated values. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.delete(`specifications/${id}`);

                if (response.data.success) {
                    toastr.success(response.data.message);

                    // Refresh data
                    await Promise.all([
                        loadSpecificationsData(),
                        loadStatistics(),
                        loadSpecificationsForSelector()
                    ]);
                }
            } catch (error) {
                toastr.error(error.response?.data?.message || 'Failed to delete specification');
            }
        }
    }

    // Toggle specification status
    async function toggleSpecStatus(id, activate) {
        try {
            const response = await axiosInstance.post(`specifications/${id}/toggle-status`, {
                status: activate ? 1 : 0
            });

            if (response.data.success) {
                toastr.success(activate ? 'Specification activated' : 'Specification deactivated');
                await loadSpecificationsData();
            }
        } catch (error) {
            toastr.error('Failed to update specification status');
        }
    }

    // ==================== SPECIFICATION VALUE FUNCTIONS ====================

    // Open add value modal
    function openAddValueModal() {
        if (!currentSpecificationId) {
            toastr.warning('Please select a specification first');
            return;
        }

        isEditingValue = false;

        updateElementText('valueModalTitle', `Add Value to ${currentSpecificationName}`);
        updateElementText('valueSubmitText', 'Save Value');

        // Reset form
        const form = document.getElementById('valueForm');
        form.reset();
        document.getElementById('valueId').value = '';
        document.getElementById('valueSpecificationId').value = currentSpecificationId;
        document.getElementById('valueSortOrder').value = 0;
        document.getElementById('valueStatus').checked = true;

        // Load quick add common values based on specification name
        loadQuickAddValues();

        // Clear errors
        clearFormErrors('valueForm');

        // Show modal
        showElement('valueModal');
    }

    // Load quick add values
    async function loadQuickAddValues() {
        try {
            const response = await axiosInstance.get(`specifications/${currentSpecificationId}`);

            if (response.data.success) {
                const spec = response.data.data;
                const quickAddSection = document.getElementById('quickAddCommonValues');

                const commonValues = getCommonValuesForSpec(spec.name);
                if (commonValues.length > 0) {
                    quickAddSection.innerHTML = `
                        <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Quick Add Common Values</h4>
                            <div class="flex flex-wrap gap-2">
                                ${commonValues.map(value => `
                                    <button type="button" onclick="prefillValueForm('${value}')"
                                            class="quick-add-btn inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-all">
                                        <i class="fas fa-plus mr-1"></i>${value}
                                    </button>
                                `).join('')}
                            </div>
                        </div>
                    `;
                    quickAddSection.classList.remove('hidden');
                } else {
                    quickAddSection.classList.add('hidden');
                }
            }
        } catch (error) {
            console.error('Error loading quick add values:', error);
        }
    }

    // Get common values for specification
    function getCommonValuesForSpec(specName) {
        const commonValues = {
            'gender': ['Male', 'Female', 'Unisex', 'Kids'],
            'material': ['Cotton', 'Polyester', 'Wool', 'Silk', 'Leather', 'Denim', 'Linen', 'Nylon'],
            'occasion': ['Casual', 'Formal', 'Wedding', 'Party', 'Sports', 'Business', 'Everyday'],
            'size': ['XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
            'color': ['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow', 'Pink', 'Purple'],
            'warranty': ['1 Year', '2 Years', '3 Years', '5 Years', 'Lifetime', 'No Warranty'],
            'pattern': ['Solid', 'Striped', 'Checked', 'Floral', 'Abstract', 'Geometric'],
            'fit': ['Regular', 'Slim', 'Loose', 'Athletic', 'Relaxed'],
            'sleeve': ['Full Sleeve', 'Half Sleeve', 'Short Sleeve', 'Sleeveless'],
            'neck': ['Round Neck', 'V-Neck', 'Polo Neck', 'Collar', 'Hooded']
        };

        const specNameLower = specName.toLowerCase();
        for (const [key, values] of Object.entries(commonValues)) {
            if (specNameLower.includes(key)) {
                return values;
            }
        }

        return [];
    }

    // Prefill value form
    function prefillValueForm(value) {
        document.getElementById('valueValue').value = value;
        toastr.info(`Pre-filled "${value}". Click Save to add this value.`);
    }

    // Close value modal
    function closeValueModal() {
        hideElement('valueModal');
    }

    // Edit value
    async function editValue(id) {
        try {
            console.log('Loading value for edit:', id);
            const response = await axiosInstance.get(`specifications/${currentSpecificationId}/values/${id}`);

            if (response.data.success) {
                isEditingValue = true;
                const value = response.data.data;

                // Fill form
                document.getElementById('valueId').value = value.id;
                document.getElementById('valueSpecificationId').value = value.specification_id;
                document.getElementById('valueValue').value = value.value;
                document.getElementById('valueSortOrder').value = value.sort_order || 0;
                document.getElementById('valueStatus').checked = value.status;

                // Hide quick add section
                document.getElementById('quickAddCommonValues').classList.add('hidden');

                // Update UI
                updateElementText('valueModalTitle', 'Edit Value');
                updateElementText('valueSubmitText', 'Update Value');

                showElement('valueModal');
            }
        } catch (error) {
            console.error('Error editing value:', error);
            toastr.error('Failed to load value details');
        }
    }

    // Save value
    async function saveValue() {
        const form = document.getElementById('valueForm');
        const formData = new FormData(form);
        const valueData = Object.fromEntries(formData.entries());

        // Convert checkbox values
        valueData.status = document.getElementById('valueStatus').checked ? 1 : 0;

        const method = isEditingValue ? 'put' : 'post';
        const url = isEditingValue ?
            `specifications/${currentSpecificationId}/values/${valueData.id}` :
            `specifications/${currentSpecificationId}/values`;

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;

        try {
            const response = await axiosInstance[method](url, valueData);

            if (response.data.success) {
                toastr.success(response.data.message);
                closeValueModal();

                // Refresh values
                await loadSpecificationValues();
            }
        } catch (error) {
            handleFormError(error, 'valueForm');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Delete value
    async function deleteValue(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the specification value. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.delete(`specifications/${currentSpecificationId}/values/${id}`);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    await loadSpecificationValues();
                }
            } catch (error) {
                toastr.error(error.response?.data?.message || 'Failed to delete value');
            }
        }
    }

    // Toggle value status
    async function toggleValueStatus(id, activate) {
        try {
            const response = await axiosInstance.post(`specifications/${currentSpecificationId}/values/${id}/toggle-status`, {
                status: activate ? 1 : 0
            });

            if (response.data.success) {
                toastr.success(activate ? 'Value activated' : 'Value deactivated');
                await loadSpecificationValues();
            }
        } catch (error) {
            toastr.error('Failed to update value status');
        }
    }

    // ==================== GROUP FUNCTIONS ====================

    // Open add group modal
    async function openAddGroupModal() {
        isEditingGroup = false;

        updateElementText('groupModalTitle', 'Create New Group');
        updateElementText('groupSubmitText', 'Create Group');

        // Reset form
        const form = document.getElementById('groupForm');
        form.reset();
        document.getElementById('groupId').value = '';
        document.getElementById('groupSortOrder').value = 0;
        document.getElementById('groupStatus').checked = true;

        // Load specifications for selection
        await loadSpecificationsForGroup();

        // Show modal
        showElement('groupModal');
    }

    // Load specifications for group
    async function loadSpecificationsForGroup() {
        try {
            const response = await axiosInstance.get('specifications/dropdown');

            if (response.data.success) {
                const specsSelect = document.getElementById('groupSpecificationIds');
                specsSelect.innerHTML = '<option value="">Select Specifications</option>';

                response.data.data.forEach(spec => {
                    const option = document.createElement('option');
                    option.value = spec.id;
                    option.textContent = `${spec.name} (${spec.code})`;
                    specsSelect.appendChild(option);
                });

                // Enable multiple selection
                specsSelect.multiple = true;
            }
        } catch (error) {
            console.error('Error loading specifications for group:', error);
        }
    }

    // Close group modal
    function closeGroupModal() {
        hideElement('groupModal');
    }

    // Edit group
    async function editGroup(id) {
        try {
            const response = await axiosInstance.get(`/specification-groups/${id}`);

            if (response.data.success) {
                isEditingGroup = true;
                const group = response.data.data;

                // Load specifications for selection
                await loadSpecificationsForGroup();

                // Fill form
                document.getElementById('groupId').value = group.id;
                document.getElementById('groupName').value = group.name;
                document.getElementById('groupSortOrder').value = group.sort_order || 0;
                document.getElementById('groupStatus').checked = group.status;

                // Set selected specifications
                const specsSelect = document.getElementById('groupSpecificationIds');
                if (group.specifications && group.specifications.length > 0) {
                    Array.from(specsSelect.options).forEach(option => {
                        if (group.specifications.find(spec => spec.id === parseInt(option.value))) {
                            option.selected = true;
                        }
                    });
                }

                // Update UI
                updateElementText('groupModalTitle', 'Edit Group');
                updateElementText('groupSubmitText', 'Update Group');

                showElement('groupModal');
            }
        } catch (error) {
            console.error('Error editing group:', error);
            toastr.error('Failed to load group details');
        }
    }

    // Save group
    async function saveGroup() {
        const form = document.getElementById('groupForm');
        const formData = new FormData(form);
        const groupData = Object.fromEntries(formData.entries());

        // Convert checkbox values
        groupData.status = document.getElementById('groupStatus').checked ? 1 : 0;

        // Get selected specification IDs
        const specsSelect = document.getElementById('groupSpecificationIds');
        const selectedSpecs = Array.from(specsSelect.selectedOptions).map(option => option.value);
        groupData.specification_ids = selectedSpecs.map(id => parseInt(id));

        const method = isEditingGroup ? 'put' : 'post';
        const url = isEditingGroup ? `specification-groups/${groupData.id}` : 'specification-groups';

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;

        try {
            const response = await axiosInstance[method](url, groupData);

            if (response.data.success) {
                toastr.success(response.data.message);
                closeGroupModal();

                // Refresh data
                await Promise.all([
                    loadGroupsData(),
                    loadStatistics()
                ]);
            }
        } catch (error) {
            handleFormError(error, 'groupForm');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // View group
    async function viewGroup(id) {
        try {
            const response = await axiosInstance.get(`/specification-groups/${id}`);

            if (response.data.success) {
                const group = response.data.data;

                updateElementText('viewGroupTitle', `${group.name} (${group.specifications_count} specifications)`);

                const specsList = document.getElementById('groupSpecsList');
                if (group.specifications && group.specifications.length > 0) {
                    specsList.innerHTML = group.specifications.map(spec => `
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3">
                                    <div>
                                        <span class="font-medium text-gray-900">${spec.name}</span>
                                        <span class="ml-2 text-sm text-gray-500">(${spec.code})</span>
                                    </div>
                                    <span class="text-xs px-2 py-1 rounded ${spec.is_required ? 'bg-rose-100 text-rose-800' : 'bg-gray-100 text-gray-800'}">
                                        ${spec.is_required ? 'Required' : 'Optional'}
                                    </span>
                                    <span class="text-xs px-2 py-1 rounded ${spec.is_filterable ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800'}">
                                        ${spec.is_filterable ? 'Filterable' : 'Not Filterable'}
                                    </span>
                                </div>
                                <div class="mt-1">
                                    <span class="text-xs text-gray-500">Type: ${spec.input_type}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    specsList.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No specifications in this group.</p>
                        </div>
                    `;
                }

                showElement('viewGroupModal');
            }
        } catch (error) {
            toastr.error('Failed to load group details');
        }
    }

    // Close view group modal
    function closeViewGroupModal() {
        hideElement('viewGroupModal');
    }

    // Delete group
    async function deleteGroup(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the group. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.delete(`specification-groups/${id}`);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    await loadGroupsData();
                }
            } catch (error) {
                toastr.error(error.response?.data?.message || 'Failed to delete group');
            }
        }
    }

    // Toggle group status
    async function toggleGroupStatus(id, activate) {
        try {
            const response = await axiosInstance.post(`specification-groups/${id}/toggle-status`, {
                status: activate ? 1 : 0
            });

            if (response.data.success) {
                toastr.success(activate ? 'Group activated' : 'Group deactivated');
                await loadGroupsData();
            }
        } catch (error) {
            toastr.error('Failed to update group status');
        }
    }

    // ==================== BULK ACTIONS ====================

    // Close bulk actions modal
    function closeBulkActions() {
        hideElement('bulkActionsModal');
    }

    // Apply bulk action
    async function applyBulkAction(action) {
        const selectedRows = specsTable.getSelectedRows();
        const selectedIds = selectedRows.map(row => row.getData().id);

        if (selectedIds.length === 0) {
            toastr.warning('No specifications selected');
            return;
        }

        let field, value, message;

        switch (action) {
            case 'activate':
                field = 'status';
                value = true;
                message = 'activate';
                break;
            case 'deactivate':
                field = 'status';
                value = false;
                message = 'deactivate';
                break;
            case 'required':
                field = 'is_required';
                value = true;
                message = 'mark as required';
                break;
            case 'not-required':
                field = 'is_required';
                value = false;
                message = 'mark as not required';
                break;
            case 'filterable':
                field = 'is_filterable';
                value = true;
                message = 'mark as filterable';
                break;
            case 'not-filterable':
                field = 'is_filterable';
                value = false;
                message = 'mark as not filterable';
                break;
            case 'delete':
                await handleBulkDelete(selectedIds);
                return;
            default:
                return;
        }

        const result = await Swal.fire({
            title: 'Confirm Bulk Action',
            text: `Are you sure you want to ${message} ${selectedIds.length} specification(s)?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: `Yes, ${message}`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post('specifications/bulk-update', {
                    ids: selectedIds,
                    field: field,
                    value: value
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeBulkActions();
                    await loadSpecificationsData();
                }
            } catch (error) {
                toastr.error(`Failed to ${message} specifications`);
            }
        }
    }

    // Handle bulk delete
    async function handleBulkDelete(selectedIds) {
        const result = await Swal.fire({
            title: 'Confirm Bulk Delete',
            text: `Are you sure you want to delete ${selectedIds.length} specification(s)? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Yes, delete ${selectedIds.length} specification(s)`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post('specifications/bulk-delete', {
                    ids: selectedIds
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeBulkActions();

                    await Promise.all([
                        loadSpecificationsData(),
                        loadStatistics(),
                        loadSpecificationsForSelector()
                    ]);
                }
            } catch (error) {
                toastr.error('Failed to delete specifications');
            }
        }
    }

    // Values bulk actions modal
    function openValuesBulkActionsModal() {
        const modalHtml = `
            <div id="valuesBulkActionsModal" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center">Bulk Actions for Values</h3>
                        <div class="space-y-3">
                            <button onclick="applyValuesBulkAction('activate')" class="w-full btn-secondary text-left">
                                <i class="fas fa-toggle-on mr-2 text-emerald-600"></i>Activate Selected
                            </button>
                            <button onclick="applyValuesBulkAction('deactivate')" class="w-full btn-secondary text-left">
                                <i class="fas fa-toggle-off mr-2 text-rose-600"></i>Deactivate Selected
                            </button>
                            <button onclick="applyValuesBulkAction('delete')" class="w-full btn-secondary text-left border-rose-200 text-rose-600 hover:bg-rose-50">
                                <i class="fas fa-trash mr-2"></i>Delete Selected
                            </button>
                                                    </div>
                        <div class="mt-6 flex justify-center">
                            <button onclick="closeValuesBulkActionsModal()" class="btn-secondary">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Remove existing modal if any
        const existingModal = document.getElementById('valuesBulkActionsModal');
        if (existingModal) {
            existingModal.remove();
        }

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    // Close values bulk actions modal
    function closeValuesBulkActionsModal() {
        const modal = document.getElementById('valuesBulkActionsModal');
        if (modal) {
            modal.remove();
        }
    }

    // Apply values bulk action
    async function applyValuesBulkAction(action) {
        if (!valuesTable || !currentSpecificationId) {
            toastr.warning('No values selected');
            return;
        }

        const selectedRows = valuesTable.getSelectedRows();
        const selectedIds = selectedRows.map(row => row.getData().id);

        if (selectedIds.length === 0) {
            toastr.warning('No values selected');
            return;
        }

        let field, value, message;

        switch (action) {
            case 'activate':
                field = 'status';
                value = true;
                message = 'activate';
                break;
            case 'deactivate':
                field = 'status';
                value = false;
                message = 'deactivate';
                break;
            case 'delete':
                await handleValuesBulkDelete(selectedIds);
                return;
            default:
                return;
        }

        const result = await Swal.fire({
            title: 'Confirm Bulk Action',
            text: `Are you sure you want to ${message} ${selectedIds.length} value(s)?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: `Yes, ${message}`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post(`specifications/${currentSpecificationId}/values/bulk-update`, {
                    ids: selectedIds,
                    field: field,
                    value: value
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeValuesBulkActionsModal();
                    await loadSpecificationValues();
                }
            } catch (error) {
                toastr.error(`Failed to ${message} values`);
            }
        }
    }

    // Handle values bulk delete
    async function handleValuesBulkDelete(selectedIds) {
        const result = await Swal.fire({
            title: 'Confirm Bulk Delete',
            text: `Are you sure you want to delete ${selectedIds.length} value(s)? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Yes, delete ${selectedIds.length} value(s)`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post(`specifications/${currentSpecificationId}/values/bulk-delete`, {
                    ids: selectedIds
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeValuesBulkActionsModal();
                    await loadSpecificationValues();
                }
            } catch (error) {
                toastr.error('Failed to delete values');
            }
        }
    }

    // ==================== UTILITY FUNCTIONS ====================

    // Handle form errors
    function handleFormError(error, formId) {
        if (error.response && error.response.status === 422) {
            // Validation errors
            const errors = error.response.data.errors;
            const form = document.getElementById(formId);

            // Clear previous errors
            clearFormErrors(formId);

            // Display new errors
            Object.keys(errors).forEach(field => {
                const errorElement = document.getElementById(`${field}Error`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                } else {
                    toastr.error(errors[field][0]);
                }
            });
        } else {
            toastr.error(error.response?.data?.message || 'Operation failed. Please try again.');
        }
    }

    // Clear form errors
    function clearFormErrors(formId) {
        const form = document.getElementById(formId);
        const errorElements = form.querySelectorAll('[id$="Error"]');
        errorElements.forEach(element => {
            element.textContent = '';
            element.classList.add('hidden');
        });
    }

    // Update element text
    function updateElementText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) {
            element.textContent = text;
        }
    }

    // Show element
    function showElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList.remove('hidden');
        }
    }

    // Hide element
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) {
            element.classList.add('hidden');
        }
    }

    // ==================== KEYBOARD SHORTCUTS ====================

    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S to save forms
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();

            if (!document.getElementById('specificationModal').classList.contains('hidden')) {
                document.getElementById('specificationForm').dispatchEvent(new Event('submit'));
            } else if (!document.getElementById('valueModal').classList.contains('hidden')) {
                document.getElementById('valueForm').dispatchEvent(new Event('submit'));
            } else if (!document.getElementById('groupModal').classList.contains('hidden')) {
                document.getElementById('groupForm').dispatchEvent(new Event('submit'));
            }
        }

        // Escape to close modals
        if (e.key === 'Escape') {
            closeSpecificationModal();
            closeValueModal();
            closeGroupModal();
            closeBulkActions();
            closeValuesBulkActionsModal();

            const viewGroupModal = document.getElementById('viewGroupModal');
            if (viewGroupModal && !viewGroupModal.classList.contains('hidden')) {
                closeViewGroupModal();
            }

            const valuesBulkModal = document.getElementById('valuesBulkActionsModal');
            if (valuesBulkModal) {
                closeValuesBulkActionsModal();
            }
        }

        // Ctrl/Cmd + N to add new specification
        if ((e.ctrlKey || e.metaKey) && e.key === 'n' && !e.shiftKey) {
            e.preventDefault();
            if (document.getElementById('specificationsSection').classList.contains('active')) {
                openAddSpecificationModal();
            }
        }

        // Ctrl/Cmd + Shift + N to add new group
        if ((e.ctrlKey || e.metaKey) && e.shiftKey && e.key === 'n') {
            e.preventDefault();
            openAddGroupModal();
        }
    });

    // ==================== EXPORT FUNCTIONS ====================

    // Setup export functionality
    document.addEventListener('DOMContentLoaded', function() {
        setupExportButtons();
    });

    function setupExportButtons() {
        // Specifications export
        const specExportButtons = document.querySelectorAll('#specificationsSection .export-btn');
        specExportButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const format = this.getAttribute('data-export');
                exportSpecifications(format);
            });
        });

        // Groups export
        const groupExportButtons = document.querySelectorAll('#groupsSection .export-btn');
        groupExportButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const format = this.getAttribute('data-export');
                exportGroups(format);
            });
        });
    }

    async function exportSpecifications(format) {
        try {
            const response = await axiosInstance.get('specifications', {
                params: {
                    per_page: 10000 // Get all data
                }
            });

            if (response.data.success) {
                const specifications = response.data.data.data;

                switch (format) {
                    case 'csv':
                        exportToCSV(specifications, 'specifications', [
                            { key: 'id', title: 'ID' },
                            { key: 'name', title: 'Name' },
                            { key: 'code', title: 'Code' },
                            { key: 'input_type', title: 'Input Type' },
                            { key: 'is_required', title: 'Required' },
                            { key: 'is_filterable', title: 'Filterable' },
                            { key: 'status', title: 'Status' },
                            { key: 'sort_order', title: 'Sort Order' },
                            { key: 'created_at', title: 'Created Date' }
                        ]);
                        break;
                    case 'xlsx':
                        exportToExcel(specifications, 'specifications', [
                            { key: 'id', title: 'ID' },
                            { key: 'name', title: 'Name' },
                            { key: 'code', title: 'Code' },
                            { key: 'input_type', title: 'Input Type' },
                            { key: 'is_required', title: 'Required' },
                            { key: 'is_filterable', title: 'Filterable' },
                            { key: 'status', title: 'Status' },
                            { key: 'sort_order', title: 'Sort Order' },
                            { key: 'created_at', title: 'Created Date' }
                        ]);
                        break;
                    case 'print':
                        printTable(specifications, 'Specifications', [
                            'ID', 'Name', 'Code', 'Input Type', 'Required', 'Filterable', 'Status', 'Sort Order', 'Created Date'
                        ]);
                        break;
                }
            }
        } catch (error) {
            console.error('Export error:', error);
            toastr.error('Failed to export specifications');
        }
    }

    async function exportGroups(format) {
        try {
            const response = await axiosInstance.get('specification-groups', {
                params: {
                    per_page: 10000 // Get all data
                }
            });

            if (response.data.success) {
                const groups = response.data.data.data;

                switch (format) {
                    case 'csv':
                        exportToCSV(groups, 'specification_groups', [
                            { key: 'id', title: 'ID' },
                            { key: 'name', title: 'Group Name' },
                            { key: 'specifications_count', title: 'Specifications Count' },
                            { key: 'status', title: 'Status' },
                            { key: 'sort_order', title: 'Sort Order' },
                            { key: 'created_at', title: 'Created Date' }
                        ]);
                        break;
                    case 'xlsx':
                        exportToExcel(groups, 'specification_groups', [
                            { key: 'id', title: 'ID' },
                            { key: 'name', title: 'Group Name' },
                            { key: 'specifications_count', title: 'Specifications Count' },
                            { key: 'status', title: 'Status' },
                            { key: 'sort_order', title: 'Sort Order' },
                            { key: 'created_at', title: 'Created Date' }
                        ]);
                        break;
                    case 'print':
                        printTable(groups, 'Specification Groups', [
                            'ID', 'Group Name', 'Specifications Count', 'Status', 'Sort Order', 'Created Date'
                        ]);
                        break;
                }
            }
        } catch (error) {
            console.error('Export error:', error);
            toastr.error('Failed to export groups');
        }
    }

    function exportToCSV(data, filename, columns) {
        // Convert data to CSV format
        const csvRows = [];

        // Add header row
        const headers = columns.map(col => `"${col.title}"`);
        csvRows.push(headers.join(','));

        // Add data rows
        data.forEach(item => {
            const row = columns.map(col => {
                let value = item[col.key];

                // Format boolean values
                if (typeof value === 'boolean') {
                    value = value ? 'Yes' : 'No';
                }

                // Format dates
                if (col.key.includes('_at') && value) {
                    value = new Date(value).toLocaleDateString();
                }

                // Escape quotes
                value = String(value).replace(/"/g, '""');
                return `"${value}"`;
            });
            csvRows.push(row.join(','));
        });

        // Create and download CSV file
        const csvString = csvRows.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.setAttribute('href', url);
        a.setAttribute('download', `${filename}_${new Date().toISOString().split('T')[0]}.csv`);
        a.click();

        toastr.success('CSV file downloaded successfully');
    }

    function exportToExcel(data, filename, columns) {
        // For Excel export, we'll use a library or fallback to CSV
        // In a real implementation, you would use SheetJS or similar
        toastr.info('Excel export would require additional libraries. Exporting as CSV instead.');
        exportToCSV(data, filename, columns);
    }

    function printTable(data, title, columns) {
        const printWindow = window.open('', '_blank');

        let html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>${title} Report</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    h1 { color: #333; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                    th { background-color: #f5f5f5; font-weight: bold; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    .print-info { margin-bottom: 20px; color: #666; }
                </style>
            </head>
            <body>
                <h1>${title} Report</h1>
                <div class="print-info">
                    Generated: ${new Date().toLocaleString()}<br>
                    Total Records: ${data.length}
                </div>
                <table>
                    <thead>
                        <tr>
                            ${columns.map(col => `<th>${col}</th>`).join('')}
                        </tr>
                    </thead>
                    <tbody>
        `;

        data.forEach(item => {
            html += '<tr>';
            columns.forEach(col => {
                let value = '';
                switch(col) {
                    case 'ID':
                        value = item.id;
                        break;
                    case 'Name':
                        value = item.name;
                        break;
                    case 'Code':
                        value = item.code || '-';
                        break;
                    case 'Group Name':
                        value = item.name;
                        break;
                    case 'Input Type':
                        value = item.input_type ? item.input_type.charAt(0).toUpperCase() + item.input_type.slice(1) : '-';
                        break;
                    case 'Required':
                        value = item.is_required ? 'Yes' : 'No';
                        break;
                    case 'Filterable':
                        value = item.is_filterable ? 'Yes' : 'No';
                        break;
                    case 'Status':
                        value = item.status ? 'Active' : 'Inactive';
                        break;
                    case 'Sort Order':
                        value = item.sort_order || 0;
                        break;
                    case 'Specifications Count':
                        value = item.specifications_count || 0;
                        break;
                    case 'Created Date':
                        value = item.created_at ? new Date(item.created_at).toLocaleDateString() : '-';
                        break;
                }
                html += `<td>${value}</td>`;
            });
            html += '</tr>';
        });

        html += `
                    </tbody>
                </table>
            </body>
            </html>
        `;

        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.print();
    }

    // ==================== AUTO-REFRESH ====================

    // Auto-refresh data every 60 seconds
    setInterval(async () => {
        if (document.getElementById('specificationsSection').classList.contains('active')) {
            await loadSpecificationsData();
        } else if (document.getElementById('groupsSection').classList.contains('active')) {
            await loadGroupsData();
        } else if (document.getElementById('valuesSection').classList.contains('active')) {
            await loadSpecificationValues();
        }
        await loadStatistics();
    }, 60000); // 60 seconds

    // ==================== INITIALIZATION ====================

    // Initialize page when DOM is loaded
    console.log('Specifications management system initialized successfully');
</script>

<style>
    /* Tab styling */
    .tab-button.active {
        border-color: #6366f1;
        color: #6366f1;
    }

    .tab-content {
        display: none;
    }

    .tab-content.active {
        display: block;
    }

    /* Table styling */
    .tabulator {
        border: none;
        font-size: 14px;
    }

    .tabulator .tabulator-header {
        background-color: #f9fafb;
        border-bottom: 2px solid #e5e7eb;
        font-weight: 600;
        color: #374151;
    }

    .tabulator .tabulator-header .tabulator-col {
        border-right: none;
        background-color: transparent;
    }

    .tabulator .tabulator-header .tabulator-col .tabulator-col-content {
        padding: 12px 16px;
    }

    .tabulator-row {
        border-bottom: 1px solid #f3f4f6;
        min-height: 56px;
    }

    .tabulator-row:hover {
        background-color: #f9fafb !important;
    }

    .tabulator-row.tabulator-selected {
        background-color: #e0e7ff !important;
    }

    .tabulator-cell {
        padding: 16px;
        border-right: none;
    }

    .tabulator .tabulator-footer {
        background-color: #f9fafb;
        border-top: 1px solid #e5e7eb;
        padding: 12px 16px;
    }

    /* Modal animations */
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    #specificationModal > div,
    #valueModal > div,
    #groupModal > div,
    #bulkActionsModal > div,
    #viewGroupModal > div {
        animation: modalFadeIn 0.3s ease-out;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #a1a1a1;
    }

    /* Button styles */
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .btn-secondary {
        background: white;
        color: #4b5563;
        padding: 10px 20px;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s ease;
        border: 1px solid #d1d5db;
        cursor: pointer;
    }

    .btn-secondary:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }

    /* Quick add button */
    .quick-add-btn {
        transition: all 0.2s ease;
    }

    .quick-add-btn:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(99, 102, 241, 0.2);
    }

    /* Status badges */
    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }

    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background-color: #fef3c7;
        color: #92400e;
    }

    /* Loading animation */
    @keyframes spin {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .fa-spinner {
        animation: spin 1s linear infinite;
    }

    /* Responsive design */
    @media (max-width: 768px) {
        .tabulator {
            font-size: 12px;
        }

        .tabulator-cell {
            padding: 12px 8px;
        }

        .tabulator .tabulator-header .tabulator-col .tabulator-col-content {
            padding: 8px 12px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 8px 16px;
            font-size: 14px;
        }
    }
</style>
@endpush
