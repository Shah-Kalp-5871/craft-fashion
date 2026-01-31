@extends('admin.layouts.master')

@section('title', 'Product Attributes Management')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Product Attributes Management</h2>
            <p class="text-gray-600">Manage attributes and values for product variants</p>
        </div>
        <button onclick="openAddAttributeModal()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Add Attribute
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Attributes</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="totalAttributes">0</p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-xl">
                <i class="fas fa-tags text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Variant Attributes</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="variantAttributes">0</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-xl">
                <i class="fas fa-palette text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Filterable</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="filterableAttributes">0</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <i class="fas fa-filter text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Popular Attribute</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="popularAttribute">-</p>
            </div>
            <div class="p-3 bg-rose-50 rounded-xl">
                <i class="fas fa-star text-rose-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">All Attributes</h3>
            <div class="flex space-x-2">
                <button id="attributesBulkActionsBtn" class="btn-secondary">
                    <i class="fas fa-bolt mr-2"></i>Bulk Actions
                </button>
                <button id="attributesColumnVisibilityBtn" class="btn-secondary">
                    <i class="fas fa-columns mr-2"></i>Columns
                </button>
                <div class="relative group">
                    <button id="attributesExportBtn" class="btn-primary">
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
                <input type="text" id="attributesSearchInput" placeholder="Search attributes..."
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Tabulator Table Container -->
        <div id="attributesTable" class="w-full overflow-x-auto"></div>
    </div>
</div>

<!-- Attribute Values Section (Initially Hidden) -->
<div id="attributeValuesSection" class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800" id="valuesTitle">Attribute Values</h3>
                <p class="text-sm text-gray-500" id="valuesSubtitle"></p>
            </div>
            <div class="flex space-x-2">
                <button onclick="goBackToAttributes()" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Attributes
                </button>
                <button onclick="openAddValueModal()" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add Value
                </button>
                <button id="valuesBulkActionsBtn" class="btn-secondary">
                    <i class="fas fa-bolt mr-2"></i>Bulk Actions
                </button>
            </div>
        </div>
    </div>
    <div class="p-6">
        <!-- Search Bar for Values -->
        <div class="mb-4">
            <div class="relative">
                <input type="text" id="valuesSearchInput" placeholder="Search values..."
                    class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>

        <!-- Values Table -->
        <div id="attributeValuesTable" class="w-full overflow-x-auto"></div>
    </div>
</div>

<!-- Add/Edit Attribute Modal -->
<div id="attributeModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Add New Attribute</h3>
            <button onclick="closeAttributeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 120px)">
            <form id="attributeForm">
                <input type="hidden" id="attributeId" name="id" value="">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Attribute Name *</label>
                        <input type="text" id="attributeName" name="name"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="e.g., Color, Size, Material" required>
                        <p class="text-xs text-red-500 mt-1 hidden" id="nameError"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Code *</label>
                        <input type="text" id="attributeCode" name="code"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="e.g., color, size, material" required>
                        <p class="text-xs text-gray-500 mt-1">Unique identifier (lowercase, underscores only)</p>
                        <p class="text-xs text-red-500 mt-1 hidden" id="codeError"></p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type *</label>
                            <select id="attributeType" name="type"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" required>
                                <option value="">Select type</option>
                                <option value="select">Select</option>
                                <option value="color">Color</option>
                                <option value="image">Image</option>
                                <option value="text">Text</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Sort Order</label>
                            <input type="number" id="attributeSortOrder" name="sort_order" value="0"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="0" min="0">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center">
                            <input type="checkbox" id="attributeIsVariant" name="is_variant" checked
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="attributeIsVariant" class="ml-2 text-sm text-gray-700">Use for Variants</label>
                        </div>
                        <p class="text-xs text-gray-500 ml-6">This attribute can be used to create product variants</p>

                        <div class="flex items-center">
                            <input type="checkbox" id="attributeIsFilterable" name="is_filterable" checked
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="attributeIsFilterable" class="ml-2 text-sm text-gray-700">Use in Filters</label>
                        </div>
                        <p class="text-xs text-gray-500 ml-6">Show in product filter options</p>

                        <div class="flex items-center">
                            <input type="checkbox" id="attributeStatus" name="status" checked
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="attributeStatus" class="ml-2 text-sm text-gray-700">Active</label>
                        </div>
                        <p class="text-xs text-gray-500 ml-6">Attribute will be available for use</p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeAttributeModal()" class="btn-secondary">
                                Cancel
                            </button>
                            <button type="submit" class="btn-primary">
                                <i class="fas fa-save mr-2"></i>
                                <span id="submitText">Save Attribute</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add/Edit Attribute Value Modal -->
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
                <input type="hidden" id="valueAttributeId" name="attribute_id" value="">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Value *</label>
                        <input type="text" id="valueValue" name="value"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="e.g., red, large, cotton" required>
                        <p class="text-xs text-red-500 mt-1 hidden" id="valueError"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Label *</label>
                        <input type="text" id="valueLabel" name="label"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            placeholder="e.g., Red, Large, Cotton" required>
                    </div>

                    <div id="colorCodeField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Color Code</label>
                        <div class="flex items-center space-x-4">
                            <input type="color" id="valueColorPicker" value="#3b82f6"
                                class="w-16 h-16 cursor-pointer rounded-lg border border-gray-300">
                            <div class="flex-1">
                                <input type="text" id="valueColorCode" name="color_code" value="#3B82F6"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                    placeholder="#FF0000">
                                <p class="text-xs text-gray-500 mt-1">Hex color code (e.g., #FF0000 for red)</p>
                            </div>
                        </div>
                    </div>

                    <div id="quickAddValues" class="hidden">
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
                <button onclick="applyBulkAction('variant')" class="w-full btn-secondary text-left">
                    <i class="fas fa-palette mr-2 text-blue-600"></i>Mark as Variant
                </button>
                <button onclick="applyBulkAction('not-variant')" class="w-full btn-secondary text-left">
                    <i class="fas fa-palette mr-2 text-gray-500"></i>Mark as Not Variant
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

<!-- View Values Modal -->
<div id="viewValuesModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4" id="viewValuesTitle"></h3>
            <div id="valuesList" class="space-y-2 max-h-96 overflow-y-auto">
                <!-- Values will be listed here -->
            </div>
            <div class="mt-6 flex justify-center">
                <button onclick="closeViewValuesModal()" class="btn-secondary">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Quick Add Modal -->
<div id="quickAddModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 text-center" id="quickAddTitle">Quick Add Values</h3>
            <div id="quickAddContent" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- Quick add options will be inserted here -->
            </div>
            <div class="mt-6 flex justify-center">
                <button onclick="closeQuickAddModal()" class="btn-secondary">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 16px;
        width: 16px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: .4s;
    }

    input:checked+.slider {
        background-color: #10b981;
    }

    input:focus+.slider {
        box-shadow: 0 0 1px #10b981;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
    }

    .slider.round {
        border-radius: 24px;
    }

    .slider.round:before {
        border-radius: 50%;
    }

    /* Tabulator responsive styles */
    .tabulator {
        width: 100%;
        min-height: 300px;
        max-height: 600px;
        overflow: auto;
    }

    .tabulator .tabulator-tableholder {
        overflow: auto !important;
    }

    .tabulator-row.tabulator-selected {
        background-color: #e0e7ff !important;
    }

    /* Color swatch */
    .color-swatch {
        width: 24px;
        height: 24px;
        border-radius: 4px;
        display: inline-block;
        border: 1px solid #e5e7eb;
    }

    /* Responsive table container */
    @media (max-width: 768px) {
        .tabulator {
            font-size: 14px;
        }

        .tabulator .tabulator-header .tabulator-col {
            min-width: 80px;
        }
    }

    /* Quick add buttons */
    .quick-add-btn {
        transition: all 0.2s ease;
    }

    .quick-add-btn:hover {
        transform: translateY(-2px);
    }

    /* Bulk actions modal */
    .bulk-action-btn {
        transition: all 0.2s ease;
    }

    .bulk-action-btn:hover {
        background-color: #f3f4f6 !important;
    }
</style>
@endpush

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
    let attributesTable = null;
    let valuesTable = null;
    let isEditingAttribute = false;
    let isEditingValue = false;
    let currentAttributeId = null;
    let currentAttributeType = null;
    let currentAttributeCode = null;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing attributes...');

        // Initialize Tabulator tables
        initializeAttributesTable([]);

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
                loadAttributesData(),
                loadStatistics()
            ]);
            console.log('All data refreshed successfully');
        } catch (error) {
            console.error('Error refreshing data:', error);
            toastr.error('Failed to load data');
        }
    }

    // Load attributes data
    async function loadAttributesData() {
        console.log('Loading attributes data...');

        try {
            const response = await axiosInstance.get('attributes');

            if (response.data.success) {
                const attributes = response.data.data.data || [];
                console.log('Loaded', attributes.length, 'attributes');

                if (attributesTable) {
                    attributesTable.setData(attributes);
                }
            }
        } catch (error) {
            console.error('Error loading attributes:', error);
            toastr.error('Failed to load attributes');
        }
    }

    // Load statistics
    async function loadStatistics() {
        console.log('Loading statistics...');

        try {
            const response = await axiosInstance.get('attributes/statistics');

            if (response.data.success) {
                const stats = response.data.data;
                updateElementText('totalAttributes', stats.total_attributes || 0);
                updateElementText('variantAttributes', stats.variant_attributes || 0);
                updateElementText('filterableAttributes', stats.filterable_attributes || 0);
                updateElementText('popularAttribute', stats.popular_attribute ? stats.popular_attribute.name : '-');
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
            toastr.error('Failed to load statistics');
        }
    }

    // Load attribute values
    async function loadAttributeValues(attributeId) {
        console.log('Loading values for attribute:', attributeId);

        try {
            const response = await axiosInstance.get(`attributes/${attributeId}/values`);

            if (response.data.success) {
                const values = response.data.data.data || [];
                console.log('Loaded', values.length, 'values');

                // Update attribute info
                const attribute = response.data.data.attribute;
                updateElementText('valuesTitle', `Manage ${attribute.name} Values`);
                updateElementText('valuesSubtitle', `Code: ${attribute.code} | Type: ${attribute.type} | Total: ${values.length} values`);

                // Show values section
                showElement('attributeValuesSection');
                hideElement('attributesTable');

                // Initialize or refresh values table
                if (!valuesTable) {
                    initializeValuesTable(values, attribute.type);
                } else {
                    valuesTable.setData(values);
                }
            }
        } catch (error) {
            console.error('Error loading attribute values:', error);
            toastr.error('Failed to load attribute values');
        }
    }

    // ==================== TABULATOR INITIALIZATION ====================

    // Initialize attributes table
    function initializeAttributesTable(data) {
        console.log('Initializing attributes table...');

        attributesTable = new Tabulator("#attributesTable", {
            data: data,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            selectable: true,
            selectableRangeMode: "click",
            placeholder: "No attributes found",
            columns: [
                {
                    title: "<input type='checkbox' id='selectAllAttributes'>",
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
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center
                                    ${rowData.type === 'color' ? 'bg-gradient-to-r from-rose-400 to-pink-500' :
                                      rowData.type === 'image' ? 'bg-gradient-to-r from-blue-400 to-cyan-500' :
                                      rowData.type === 'select' ? 'bg-gradient-to-r from-emerald-400 to-teal-500' :
                                      'bg-gradient-to-r from-indigo-400 to-purple-500'}">
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
                    title: "Type",
                    field: "type",
                    width: 100,
                    sorter: "string",
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "select": "Select",
                            "color": "Color",
                            "image": "Image",
                            "text": "Text"
                        }
                    },
                    formatter: function(cell) {
                        const type = cell.getValue();
                        const typeConfig = {
                            'select': { class: 'bg-emerald-100 text-emerald-800', icon: 'fa-list' },
                            'color': { class: 'bg-rose-100 text-rose-800', icon: 'fa-palette' },
                            'image': { class: 'bg-blue-100 text-blue-800', icon: 'fa-image' },
                            'text': { class: 'bg-indigo-100 text-indigo-800', icon: 'fa-font' }
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
                    width: 140,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell, formatterParams, onRendered) {
                        const rowData = cell.getRow().getData();
                        const count = rowData.values_count || 0;

                        return `
                            <div class="flex flex-col items-center space-y-2">
                                <span class="text-sm font-medium ${count > 0 ? 'text-emerald-600' : 'text-rose-600'}">
                                    ${count} ${count === 1 ? 'value' : 'values'}
                                </span>
                                <div class="flex space-x-2">
                                    <button onclick="addValuesForAttribute(${rowData.id}, '${rowData.name}', '${rowData.type}', '${rowData.code}')"
                                            class="inline-flex items-center px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 rounded-full transition-colors">
                                        <i class="fas fa-plus mr-1"></i>Add
                                    </button>
                                    ${count > 0 ? `
                                        <button onclick="manageAttributeValues(${rowData.id}, '${rowData.name}', '${rowData.type}')"
                                                class="inline-flex items-center px-3 py-1 text-xs font-medium bg-emerald-100 text-emerald-800 hover:bg-emerald-200 rounded-full transition-colors">
                                            <i class="fas fa-list mr-1"></i>Manage
                                        </button>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    title: "Variant",
                    field: "is_variant",
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
                        const isVariant = cell.getValue();
                        return isVariant ?
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-palette mr-1"></i>Variant
                            </span>` :
                            `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <i class="fas fa-times mr-1"></i>No
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
                            `<button onclick="toggleAttributeStatus(${data.id}, false)"
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 hover:bg-emerald-200 transition-colors">
                                <i class="fas fa-toggle-on mr-1"></i>Active
                            </button>` :
                            `<button onclick="toggleAttributeStatus(${data.id}, true)"
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
                    width: 100,
                    hozAlign: "center",
                    headerSort: false,
                    formatter: function(cell) {
                        const row = cell.getRow();
                        const data = row.getData();

                        return `
                            <div class="flex space-x-2 justify-center">
                                <button onclick="editAttribute(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors"
                                        title="Edit Attribute">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="deleteAttribute(${data.id})"
                                        class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                        title="Delete Attribute">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Setup table events
        attributesTable.on("tableBuilt", function() {
            console.log("Attributes table built successfully");
            setupTableFunctionality();
        });
    }

    // Initialize values table
    function initializeValuesTable(data, attributeType) {
        console.log('Initializing values table for type:', attributeType);

        const columns = [
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
                width: 150,
                sorter: "string",
                headerFilter: "input",
                headerFilterPlaceholder: "Search value",
                formatter: function(cell) {
                    const rowData = cell.getRow().getData();
                    return `<span class="font-medium text-gray-900">${rowData.value}</span>`;
                }
            },
            {
                title: "Display Label",
                field: "label",
                width: 200,
                sorter: "string",
                headerFilter: "input",
                headerFilterPlaceholder: "Search label",
                formatter: function(cell) {
                    const rowData = cell.getRow().getData();
                    return `<span class="font-medium text-gray-900">${rowData.label}</span>`;
                }
            }
        ];

        // Add color/image column based on attribute type
        if (attributeType === 'color') {
            columns.push({
                title: "Color",
                field: "color_code",
                width: 120,
                hozAlign: "center",
                formatter: function(cell) {
                    const color = cell.getValue();
                    if (!color) return '<span class="text-gray-400">No color</span>';

                    return `
                        <div class="flex items-center space-x-2 justify-center">
                            <div class="color-swatch" style="background-color: ${color};"></div>
                            <span class="text-xs font-mono">${color}</span>
                        </div>
                    `;
                }
            });
        } else if (attributeType === 'image') {
            columns.push({
                title: "Image",
                field: "image_id",
                width: 120,
                hozAlign: "center",
                formatter: function(cell) {
                    const imageId = cell.getValue();
                    if (!imageId) return '<span class="text-gray-400">No image</span>';

                    return `
                        <div class="flex items-center space-x-2 justify-center">
                            <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                            <span class="text-xs text-gray-600">ID: ${imageId}</span>
                        </div>
                    `;
                }
            });
        }

        // Add remaining columns
        columns.push(
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
                                    title="Edit Value">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="deleteValue(${data.id})"
                                    class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors"
                                    title="Delete Value">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    `;
                }
            }
        );

        valuesTable = new Tabulator("#attributeValuesTable", {
            data: data,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            selectable: true,
            selectableRangeMode: "click",
            placeholder: "No values found. Click 'Add Value' to create new values.",
            columns: columns
        });

        valuesTable.on("tableBuilt", function() {
            console.log("Values table built successfully");
            setupValuesTableFunctionality();
        });
    }

    // ==================== SETUP FUNCTIONS ====================

    // Setup event listeners
    function setupEventListeners() {
        // Search input
        const searchInput = document.getElementById('attributesSearchInput');
        let searchTimeout;

        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (attributesTable) {
                    const term = this.value.toLowerCase();
                    attributesTable.setFilter([
                        { field: "name", type: "like", value: term },
                        { field: "code", type: "like", value: term }
                    ]);
                }
            }, 300);
        });

        // Attribute form submission
        document.getElementById('attributeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveAttribute();
        });

        // Value form submission
        document.getElementById('valueForm').addEventListener('submit', function(e) {
            e.preventDefault();
            saveValue();
        });

        // Color picker
        const colorPicker = document.getElementById('valueColorPicker');
        const colorCodeInput = document.getElementById('valueColorCode');

        if (colorPicker && colorCodeInput) {
            colorPicker.addEventListener('input', function() {
                colorCodeInput.value = this.value.toUpperCase();
            });

            colorCodeInput.addEventListener('input', function() {
                const color = this.value;
                if (color.match(/^#[0-9A-F]{6}$/i)) {
                    colorPicker.value = color;
                }
            });
        }

        // Attribute type change
        document.getElementById('attributeType').addEventListener('change', function() {
            const type = this.value;
            currentAttributeType = type;
        });
    }

    // Setup modals
    function setupModals() {
        // Close modals when clicking outside
        document.querySelectorAll('[id$="Modal"]').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    if (modal.id === 'attributeModal') closeAttributeModal();
                    if (modal.id === 'valueModal') closeValueModal();
                    if (modal.id === 'bulkActionsModal') closeBulkActions();
                    if (modal.id === 'viewValuesModal') closeViewValuesModal();
                    if (modal.id === 'quickAddModal') closeQuickAddModal();
                }
            });
        });

        // Escape key to close modals
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAttributeModal();
                closeValueModal();
                closeBulkActions();
                closeViewValuesModal();
                closeQuickAddModal();
            }
        });
    }

    // Setup table functionality
    function setupTableFunctionality() {
        // Column visibility
        const columnVisibilityBtn = document.getElementById('attributesColumnVisibilityBtn');
        if (columnVisibilityBtn) {
            columnVisibilityBtn.addEventListener('click', function() {
                createColumnVisibilityMenu(this, attributesTable);
            });
        }

        // Export functionality
        document.querySelectorAll('.export-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const format = this.getAttribute('data-export');
                exportTable(attributesTable, 'attributes', format);
            });
        });

        // Bulk actions
        const bulkActionsBtn = document.getElementById('attributesBulkActionsBtn');
        if (bulkActionsBtn) {
            bulkActionsBtn.addEventListener('click', function() {
                const selectedRows = attributesTable.getSelectedRows();
                if (selectedRows.length === 0) {
                    toastr.warning('Please select at least one attribute');
                    return;
                }
                document.getElementById('bulkActionsModal').classList.remove('hidden');
            });
        }
    }

    // Setup values table functionality
    function setupValuesTableFunctionality() {
        // Search for values
        const searchInput = document.getElementById('valuesSearchInput');
        let searchTimeout;

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    if (valuesTable) {
                        const term = this.value.toLowerCase();
                        valuesTable.setFilter([
                            { field: "value", type: "like", value: term },
                            { field: "label", type: "like", value: term }
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

    // ==================== ATTRIBUTE FUNCTIONS ====================

    // Open add attribute modal
    function openAddAttributeModal() {
        isEditingAttribute = false;
        currentAttributeId = null;

        updateElementText('modalTitle', 'Add New Attribute');
        updateElementText('submitText', 'Save Attribute');

        // Reset form
        const form = document.getElementById('attributeForm');
        form.reset();
        document.getElementById('attributeId').value = '';
        document.getElementById('attributeSortOrder').value = 0;
        document.getElementById('attributeIsVariant').checked = true;
        document.getElementById('attributeIsFilterable').checked = true;
        document.getElementById('attributeStatus').checked = true;

        // Clear errors
        clearFormErrors('attributeForm');

        // Show modal
        showElement('attributeModal');
    }

    // Close attribute modal
    function closeAttributeModal() {
        hideElement('attributeModal');
    }

    // Edit attribute
    async function editAttribute(id) {
        try {
            console.log('Loading attribute for edit:', id);
            const response = await axiosInstance.get(`attributes/${id}`);

            if (response.data.success) {
                isEditingAttribute = true;
                currentAttributeId = id;
                const attribute = response.data.data;

                // Fill form
                document.getElementById('attributeId').value = attribute.id;
                document.getElementById('attributeName').value = attribute.name;
                document.getElementById('attributeCode').value = attribute.code;
                document.getElementById('attributeType').value = attribute.type;
                document.getElementById('attributeSortOrder').value = attribute.sort_order || 0;
                document.getElementById('attributeIsVariant').checked = attribute.is_variant;
                document.getElementById('attributeIsFilterable').checked = attribute.is_filterable;
                document.getElementById('attributeStatus').checked = attribute.status;

                currentAttributeType = attribute.type;

                // Update UI
                updateElementText('modalTitle', 'Edit Attribute');
                updateElementText('submitText', 'Update Attribute');

                showElement('attributeModal');
            }
        } catch (error) {
            console.error('Error editing attribute:', error);
            toastr.error('Failed to load attribute details');
        }
    }

    // Save attribute
    async function saveAttribute() {
        const form = document.getElementById('attributeForm');
        const formData = new FormData(form);
        const attributeData = Object.fromEntries(formData.entries());

        // Convert checkbox values
        attributeData.is_variant = document.getElementById('attributeIsVariant').checked ? 1 : 0;
        attributeData.is_filterable = document.getElementById('attributeIsFilterable').checked ? 1 : 0;
        attributeData.status = document.getElementById('attributeStatus').checked ? 1 : 0;

        const method = isEditingAttribute ? 'put' : 'post';
        const url = isEditingAttribute ? `attributes/${currentAttributeId}` : 'attributes';

        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;

        try {
            const response = await axiosInstance[method](url, attributeData);

            if (response.data.success) {
                toastr.success(response.data.message);
                closeAttributeModal();

                // Refresh data
                await Promise.all([
                    loadAttributesData(),
                    loadStatistics()
                ]);
            }
        } catch (error) {
            handleFormError(error, 'attributeForm');
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Delete attribute
    async function deleteAttribute(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the attribute and all associated values. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.delete(`attributes/${id}`);

                if (response.data.success) {
                    toastr.success(response.data.message);

                    // Refresh data
                    await Promise.all([
                        loadAttributesData(),
                        loadStatistics()
                    ]);
                }
            } catch (error) {
                toastr.error(error.response?.data?.message || 'Failed to delete attribute');
            }
        }
    }

    // Toggle attribute status
    async function toggleAttributeStatus(id, activate) {
        try {
            const response = await axiosInstance.post(`attributes/${id}/toggle-status`, {
                status: activate ? 1 : 0
            });

            if (response.data.success) {
                toastr.success(activate ? 'Attribute activated' : 'Attribute deactivated');
                await loadAttributesData();
            }
        } catch (error) {
            toastr.error('Failed to update attribute status');
        }
    }

    // ==================== ATTRIBUTE VALUE FUNCTIONS ====================

    // Function to add values for an attribute
    function addValuesForAttribute(attributeId, attributeName, attributeType, attributeCode) {
        currentAttributeId = attributeId;
        currentAttributeType = attributeType;
        currentAttributeCode = attributeCode;

        // Show quick add modal for common values
        openQuickAddModal(attributeId, attributeName, attributeType, attributeCode);
    }

    // Function to manage attribute values (show values table)
    function manageAttributeValues(attributeId, attributeName, attributeType) {
        currentAttributeId = attributeId;
        currentAttributeType = attributeType;

        // Load values in the main section (not modal)
        loadAttributeValues(attributeId);
    }

    // Open add value modal
    function openAddValueModal() {
        isEditingValue = false;

        updateElementText('valueModalTitle', `Add Value to ${getAttributeName()}`);
        updateElementText('valueSubmitText', 'Save Value');

        // Reset form
        const form = document.getElementById('valueForm');
        form.reset();
        document.getElementById('valueId').value = '';
        document.getElementById('valueAttributeId').value = currentAttributeId;
        document.getElementById('valueSortOrder').value = 0;
        document.getElementById('valueStatus').checked = true;
        document.getElementById('valueColorCode').value = '#3B82F6';
        document.getElementById('valueColorPicker').value = '#3b82f6';

        // Handle color field based on attribute type
        const colorField = document.getElementById('colorCodeField');
        const quickAddSection = document.getElementById('quickAddValues');

        if (currentAttributeType === 'color') {
            showElement(colorField);
        } else {
            hideElement(colorField);
        }

        // Show quick add buttons for common values
        const quickAddContent = getQuickAddButtons(currentAttributeCode);
        if (quickAddContent) {
            quickAddSection.innerHTML = quickAddContent;
            showElement(quickAddSection);
        } else {
            hideElement(quickAddSection);
        }

        // Clear errors
        clearFormErrors('valueForm');

        // Show modal
        showElement('valueModal');
    }

    // Get attribute name for display
    function getAttributeName() {
        // This would ideally fetch from current context
        return 'Attribute';
    }

    // Get quick add buttons for common values
    function getQuickAddButtons(attributeCode) {
        const commonValues = {
            'size': [
                { value: 'xs', label: 'Extra Small' },
                { value: 's', label: 'Small' },
                { value: 'm', label: 'Medium' },
                { value: 'l', label: 'Large' },
                { value: 'xl', label: 'Extra Large' },
                { value: 'xxl', label: 'Double Extra Large' }
            ],
            'color': [
                { value: 'red', label: 'Red', color_code: '#FF0000' },
                { value: 'blue', label: 'Blue', color_code: '#0000FF' },
                { value: 'green', label: 'Green', color_code: '#00FF00' },
                { value: 'black', label: 'Black', color_code: '#000000' },
                { value: 'white', label: 'White', color_code: '#FFFFFF' },
                { value: 'yellow', label: 'Yellow', color_code: '#FFFF00' },
                { value: 'pink', label: 'Pink', color_code: '#FFC0CB' },
                { value: 'purple', label: 'Purple', color_code: '#800080' }
            ],
            'material': [
                { value: 'cotton', label: '100% Cotton' },
                { value: 'polyester', label: 'Polyester' },
                { value: 'wool', label: 'Wool' },
                { value: 'silk', label: 'Silk' },
                { value: 'leather', label: 'Leather' },
                { value: 'denim', label: 'Denim' },
                { value: 'linen', label: 'Linen' },
                { value: 'nylon', label: 'Nylon' }
            ],
            'pattern': [
                { value: 'solid', label: 'Solid' },
                { value: 'striped', label: 'Striped' },
                { value: 'checked', label: 'Checked' },
                { value: 'floral', label: 'Floral' },
                { value: 'abstract', label: 'Abstract' },
                { value: 'geometric', label: 'Geometric' }
            ]
        };

        const values = commonValues[attributeCode];
        if (!values || values.length === 0) return '';

        return `
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-sm font-medium text-gray-700 mb-2">Quick Add Common Values</h4>
                <div class="flex flex-wrap gap-2">
                    ${values.map(item => `
                        <button type="button" onclick="prefillValueForm('${item.value}', '${item.label}', '${item.color_code || ''}')"
                                class="quick-add-btn inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-medium bg-indigo-100 text-indigo-800 hover:bg-indigo-200 transition-all">
                            ${currentAttributeType === 'color' && item.color_code ?
                                `<div class="w-3 h-3 rounded-full mr-2 border border-gray-300" style="background-color: ${item.color_code};"></div>` :
                                `<i class="fas fa-plus mr-1"></i>`}
                            ${item.label}
                        </button>
                    `).join('')}
                </div>
            </div>
        `;
    }

    // Prefill value form
    function prefillValueForm(value, label, colorCode = '') {
        document.getElementById('valueValue').value = value;
        document.getElementById('valueLabel').value = label;

        if (colorCode && currentAttributeType === 'color') {
            document.getElementById('valueColorCode').value = colorCode;
            document.getElementById('valueColorPicker').value = colorCode.toLowerCase();
        }

        toastr.info(`Pre-filled ${label}. Click Save to add this value.`);
    }

    // Close value modal
    function closeValueModal() {
        hideElement('valueModal');
    }

    // Edit value
    async function editValue(id) {
        try {
            console.log('Loading value for edit:', id);
            const response = await axiosInstance.get(`attributes/${currentAttributeId}/values/${id}`);

            if (response.data.success) {
                isEditingValue = true;
                const value = response.data.data;

                // Fill form
                document.getElementById('valueId').value = value.id;
                document.getElementById('valueAttributeId').value = value.attribute_id;
                document.getElementById('valueValue').value = value.value;
                document.getElementById('valueLabel').value = value.label;
                document.getElementById('valueSortOrder').value = value.sort_order || 0;
                document.getElementById('valueStatus').checked = value.status;

                // Handle color
                if (value.color_code) {
                    document.getElementById('valueColorCode').value = value.color_code;
                    document.getElementById('valueColorPicker').value = value.color_code.toLowerCase();
                }

                // Show/hide color field
                const colorField = document.getElementById('colorCodeField');
                const quickAddSection = document.getElementById('quickAddValues');
                if (currentAttributeType === 'color') {
                    showElement(colorField);
                } else {
                    hideElement(colorField);
                }
                hideElement(quickAddSection);

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

        // Remove color_code if not color attribute
        if (currentAttributeType !== 'color') {
            delete valueData.color_code;
        }

        const method = isEditingValue ? 'put' : 'post';
        const url = isEditingValue ?
            `attributes/${currentAttributeId}/values/${valueData.id}` :
            `attributes/${currentAttributeId}/values`;

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
                await loadAttributeValues(currentAttributeId);
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
            text: "This will delete the attribute value. This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.delete(`attributes/${currentAttributeId}/values/${id}`);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    await loadAttributeValues(currentAttributeId);
                }
            } catch (error) {
                toastr.error(error.response?.data?.message || 'Failed to delete value');
            }
        }
    }

    // Toggle value status
    async function toggleValueStatus(id, activate) {
        try {
            const response = await axiosInstance.post(`attributes/${currentAttributeId}/values/${id}/toggle-status`, {
                status: activate ? 1 : 0
            });

            if (response.data.success) {
                toastr.success(activate ? 'Value activated' : 'Value deactivated');
                await loadAttributeValues(currentAttributeId);
            }
        } catch (error) {
            toastr.error('Failed to update value status');
        }
    }

    // Go back to attributes
    function goBackToAttributes() {
        hideElement('attributeValuesSection');
        showElement('attributesTable');
        currentAttributeId = null;
        currentAttributeType = null;
        currentAttributeCode = null;
    }

    // ==================== QUICK ADD MODAL ====================

    // Open quick add modal
    function openQuickAddModal(attributeId, attributeName, attributeType, attributeCode) {
        currentAttributeId = attributeId;
        currentAttributeType = attributeType;
        currentAttributeCode = attributeCode;

        updateElementText('quickAddTitle', `Quick Add Values to ${attributeName}`);

        const commonValues = getCommonValues(attributeCode);
        const quickAddContent = document.getElementById('quickAddContent');

        if (commonValues.length > 0) {
            quickAddContent.innerHTML = `
                <div class="space-y-3">
                    ${commonValues.map(group => `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">${group.name}</h4>
                            <div class="flex flex-wrap gap-2">
                                ${group.values.map(item => `
                                    <button onclick="quickAddValue('${item.value}', '${item.label}', '${item.color_code || ''}')"
                                            class="quick-add-btn inline-flex items-center px-3 py-2 rounded-lg text-sm font-medium bg-white border border-gray-300 hover:bg-gray-50 transition-all">
                                        ${attributeType === 'color' && item.color_code ?
                                            `<div class="w-4 h-4 rounded-full mr-2 border border-gray-300" style="background-color: ${item.color_code};"></div>` :
                                            ''}
                                        ${item.label}
                                    </button>
                                `).join('')}
                            </div>
                        </div>
                    `).join('')}
                    <div class="pt-4 border-t border-gray-200">
                        <button onclick="openCustomValueModal()" class="w-full btn-primary">
                            <i class="fas fa-plus-circle mr-2"></i>Add Custom Value
                        </button>
                    </div>
                </div>
            `;
        } else {
            quickAddContent.innerHTML = `
                <div class="text-center py-8">
                    <i class="fas fa-sliders-h text-4xl text-gray-300 mb-3"></i>
                    <p class="text-gray-500">No predefined values for this attribute type.</p>
                    <button onclick="openCustomValueModal()" class="mt-4 btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i>Add Custom Value
                    </button>
                </div>
            `;
        }

        showElement('quickAddModal');
    }

    // Get common values for attribute
    function getCommonValues(attributeCode) {
        const commonValues = {
            'size': [
                {
                    name: 'Standard Sizes',
                    values: [
                        { value: 'xs', label: 'Extra Small' },
                        { value: 's', label: 'Small' },
                        { value: 'm', label: 'Medium' },
                        { value: 'l', label: 'Large' },
                        { value: 'xl', label: 'Extra Large' },
                        { value: 'xxl', label: 'Double Extra Large' },
                        { value: 'xxxl', label: 'Triple Extra Large' }
                    ]
                },
                {
                    name: 'Numeric Sizes',
                    values: [
                        { value: '28', label: '28' },
                        { value: '30', label: '30' },
                        { value: '32', label: '32' },
                        { value: '34', label: '34' },
                        { value: '36', label: '36' },
                        { value: '38', label: '38' },
                        { value: '40', label: '40' },
                        { value: '42', label: '42' }
                    ]
                }
            ],
            'color': [
                {
                    name: 'Basic Colors',
                    values: [
                        { value: 'red', label: 'Red', color_code: '#FF0000' },
                        { value: 'blue', label: 'Blue', color_code: '#0000FF' },
                        { value: 'green', label: 'Green', color_code: '#00FF00' },
                        { value: 'yellow', label: 'Yellow', color_code: '#FFFF00' },
                        { value: 'black', label: 'Black', color_code: '#000000' },
                        { value: 'white', label: 'White', color_code: '#FFFFFF' }
                    ]
                },
                {
                    name: 'Popular Colors',
                    values: [
                        { value: 'navy', label: 'Navy Blue', color_code: '#000080' },
                        { value: 'gray', label: 'Gray', color_code: '#808080' },
                        { value: 'pink', label: 'Pink', color_code: '#FFC0CB' },
                        { value: 'purple', label: 'Purple', color_code: '#800080' },
                        { value: 'orange', label: 'Orange', color_code: '#FFA500' },
                        { value: 'brown', label: 'Brown', color_code: '#A52A2A' }
                    ]
                }
            ],
            'material': [
                {
                    name: 'Common Materials',
                    values: [
                        { value: 'cotton', label: '100% Cotton' },
                        { value: 'polyester', label: 'Polyester' },
                        { value: 'wool', label: 'Wool' },
                        { value: 'silk', label: 'Silk' },
                        { value: 'leather', label: 'Leather' },
                        { value: 'denim', label: 'Denim' },
                        { value: 'linen', label: 'Linen' },
                        { value: 'nylon', label: 'Nylon' }
                    ]
                }
            ]
        };

        return commonValues[attributeCode] || [];
    }

    // Quick add value
    async function quickAddValue(value, label, colorCode = '') {
        const valueData = {
            attribute_id: currentAttributeId,
            value: value,
            label: label,
            sort_order: 0,
            status: 1
        };

        if (colorCode && currentAttributeType === 'color') {
            valueData.color_code = colorCode;
        }

        try {
            const response = await axiosInstance.post(`attributes/${currentAttributeId}/values`, valueData);

            if (response.data.success) {
                toastr.success(`Added ${label} successfully`);
                closeQuickAddModal();

                // If we're in values management, refresh the table
                if (document.getElementById('attributeValuesSection').classList.contains('hidden')) {
                    // We're in attributes table, just refresh attributes
                    await loadAttributesData();
                } else {
                    // We're in values management, refresh values
                    await loadAttributeValues(currentAttributeId);
                }
            }
        } catch (error) {
            toastr.error('Failed to add value: ' + (error.response?.data?.message || 'Unknown error'));
        }
    }

    // Open custom value modal
    function openCustomValueModal() {
        closeQuickAddModal();
        openAddValueModal();
    }

    // Close quick add modal
    function closeQuickAddModal() {
        hideElement('quickAddModal');
    }

    // ==================== BULK ACTIONS ====================

    // Close bulk actions modal
    function closeBulkActions() {
        hideElement('bulkActionsModal');
    }

    // Apply bulk action
    async function applyBulkAction(action) {
        const selectedRows = attributesTable.getSelectedRows();
        const selectedIds = selectedRows.map(row => row.getData().id);

        if (selectedIds.length === 0) {
            toastr.warning('No attributes selected');
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
            case 'variant':
                field = 'is_variant';
                value = true;
                message = 'mark as variant';
                break;
            case 'not-variant':
                field = 'is_variant';
                value = false;
                message = 'mark as not variant';
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
            text: `Are you sure you want to ${message} ${selectedIds.length} attribute(s)?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: `Yes, ${message}`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post('attributes/bulk-update', {
                    ids: selectedIds,
                    field: field,
                    value: value
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeBulkActions();
                    await loadAttributesData();
                }
            } catch (error) {
                toastr.error(`Failed to ${message} attributes`);
            }
        }
    }

    // Handle bulk delete
    async function handleBulkDelete(selectedIds) {
        const result = await Swal.fire({
            title: 'Confirm Bulk Delete',
            text: `Are you sure you want to delete ${selectedIds.length} attribute(s)? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: `Yes, delete ${selectedIds.length} attribute(s)`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post('attributes/bulk-delete', {
                    ids: selectedIds
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeBulkActions();

                    await Promise.all([
                        loadAttributesData(),
                        loadStatistics()
                    ]);
                }
            } catch (error) {
                toastr.error('Failed to delete attributes');
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
                            <button onclick="applyValuesBulkAction('activate')" class="w-full bulk-action-btn btn-secondary text-left">
                                <i class="fas fa-toggle-on mr-2 text-emerald-600"></i>Activate Selected
                            </button>
                            <button onclick="applyValuesBulkAction('deactivate')" class="w-full bulk-action-btn btn-secondary text-left">
                                <i class="fas fa-toggle-off mr-2 text-rose-600"></i>Deactivate Selected
                            </button>
                            <button onclick="applyValuesBulkAction('delete')" class="w-full bulk-action-btn btn-secondary text-left border-rose-200 text-rose-600 hover:bg-rose-50">
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
        if (existingModal) existingModal.remove();

        // Add modal to body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }

    // Close values bulk actions modal
    function closeValuesBulkActionsModal() {
        const modal = document.getElementById('valuesBulkActionsModal');
        if (modal) modal.remove();
    }

    // Apply values bulk action
    async function applyValuesBulkAction(action) {
        if (!valuesTable) {
            toastr.error('Values table not found');
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
                const response = await axiosInstance.post(`attributes/${currentAttributeId}/values/bulk-update`, {
                    ids: selectedIds,
                    field: field,
                    value: value
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeValuesBulkActionsModal();
                    await loadAttributeValues(currentAttributeId);
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
                const response = await axiosInstance.post(`attributes/${currentAttributeId}/values/bulk-delete`, {
                    ids: selectedIds
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeValuesBulkActionsModal();
                    await loadAttributeValues(currentAttributeId);
                }
            } catch (error) {
                toastr.error('Failed to delete values');
            }
        }
    }

    // ==================== UTILITY FUNCTIONS ====================

    // Update element text
    function updateElementText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) element.textContent = text;
    }

    // Show element
    function showElement(elementId) {
        const element = typeof elementId === 'string' ? document.getElementById(elementId) : elementId;
        if (element) element.classList.remove('hidden');
    }

    // Hide element
    function hideElement(elementId) {
        const element = typeof elementId === 'string' ? document.getElementById(elementId) : elementId;
        if (element) element.classList.add('hidden');
    }

    // Clear form errors
    function clearFormErrors(formId) {
        const form = document.getElementById(formId);
        form.querySelectorAll('.text-red-500').forEach(error => {
            error.classList.add('hidden');
            error.textContent = '';
        });
    }

    // Handle form error
    function handleFormError(error, formId) {
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            const form = document.getElementById(formId);

            Object.keys(errors).forEach(field => {
                const errorElement = form.querySelector(`#${field}Error`);
                if (errorElement) {
                    errorElement.textContent = errors[field][0];
                    errorElement.classList.remove('hidden');
                }
            });
            toastr.error('Please fix the validation errors');
        } else {
            toastr.error(error.response?.data?.message || 'An error occurred');
        }
    }

    // Close view values modal
    function closeViewValuesModal() {
        hideElement('viewValuesModal');
    }

    // Create column visibility menu
    function createColumnVisibilityMenu(button, table) {
        // Remove existing menu if any
        const existingMenu = document.querySelector('.column-visibility-menu');
        if (existingMenu) existingMenu.remove();

        const columns = table.getColumnDefinitions();
        const menu = document.createElement('div');
        menu.className = 'column-visibility-menu absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50';

        columns.forEach((column, index) => {
            if (index === 0 || !column.field) return;

            const menuItem = document.createElement('button');
            menuItem.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
            menuItem.innerHTML = `
                <input type="checkbox" class="mr-2" ${table.getColumn(column.field).isVisible() ? 'checked' : ''}>
                ${column.title}
            `;

            menuItem.addEventListener('click', function(e) {
                e.stopPropagation();
                const col = table.getColumn(column.field);
                const checkbox = this.querySelector('input');
                col.toggle();
                setTimeout(() => {
                    checkbox.checked = col.isVisible();
                }, 10);
            });

            menu.appendChild(menuItem);
        });

        // Position and show menu
        const rect = button.getBoundingClientRect();
        menu.style.position = 'fixed';
        menu.style.top = (rect.bottom + window.scrollY) + 'px';
        menu.style.left = rect.left + 'px';

        document.body.appendChild(menu);

        // Close menu when clicking outside
        function closeMenu(e) {
            if (!menu.contains(e.target) && e.target !== button) {
                menu.remove();
                document.removeEventListener('click', closeMenu);
            }
        }

        setTimeout(() => {
            document.addEventListener('click', closeMenu);
        }, 0);
    }

    // Export table
    function exportTable(table, filename, format) {
        const exportConfig = {
            csv: {
                delimiter: ",",
                bom: true
            },
            xlsx: {
                sheetName: filename,
                documentProcessing: function(workbook) {
                    // Customize workbook if needed
                    return workbook;
                }
            }
        };

        switch (format) {
            case 'csv':
                table.download("csv", `${filename}.csv`, exportConfig.csv);
                break;
            case 'xlsx':
                table.download("xlsx", `${filename}.xlsx`, exportConfig.xlsx);
                break;
            case 'print':
                window.print();
                break;
        }
    }

    // View values in modal (for quick view)
    async function viewAttributeValues(attributeId, attributeName) {
        try {
            const response = await axiosInstance.get(`attributes/${attributeId}`);

            if (response.data.success) {
                const attribute = response.data.data;
                const values = attribute.values || [];

                updateElementText('viewValuesTitle', `${attributeName} Values (${values.length})`);

                const valuesList = document.getElementById('valuesList');
                if (values.length > 0) {
                    valuesList.innerHTML = values.map(value => {
                        let colorHtml = '';
                        if (attribute.type === 'color' && value.color_code) {
                            colorHtml = `
                                <div class="flex items-center space-x-2">
                                    <div class="color-swatch" style="background-color: ${value.color_code};"></div>
                                    <span class="text-sm text-gray-600">${value.color_code}</span>
                                </div>
                            `;
                        }

                        return `
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3">
                                        <div>
                                            <span class="font-medium text-gray-900">${value.label}</span>
                                            <span class="ml-2 text-sm text-gray-500">(${value.value})</span>
                                        </div>
                                        ${colorHtml}
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-xs px-2 py-1 rounded ${value.status ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800'}">
                                        ${value.status ? 'Active' : 'Inactive'}
                                    </span>
                                    <span class="text-xs font-medium text-gray-500">Order: ${value.sort_order || 0}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    valuesList.innerHTML = `
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-inbox text-4xl mb-2"></i>
                            <p>No values defined for this attribute.</p>
                        </div>
                    `;
                }

                showElement('viewValuesModal');
            }
        } catch (error) {
            toastr.error('Failed to load attribute values');
        }
    }
</script>
@endpush
