@extends('admin.layouts.master')

@section('title', 'Brand Management - Admin Panel')

@section('content')
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Brand Management</h2>
                <p class="text-gray-600">Manage your product brands and manufacturers</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showCreateBrandModal()" class="btn-primary">
                    <i class="fas fa-plus mr-2"></i>Add New Brand
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-indigo-100 text-indigo-600 mr-4">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Brands</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="totalBrands">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-emerald-100 text-emerald-600 mr-4">
                    <i class="fas fa-check-circle text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Active Brands</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="activeBrands">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-amber-100 text-amber-600 mr-4">
                    <i class="fas fa-star text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Featured Brands</p>
                    <h3 class="text-2xl font-bold text-gray-800" id="featuredBrands">0</h3>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-lg bg-rose-100 text-rose-600 mr-4">
                    <i class="fas fa-crown text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Top Brand</p>
                    <h3 class="text-lg font-semibold text-gray-800 truncate" id="popularBrand">-</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Bar -->
    <div id="bulkActionsBar"
        class="hidden fixed bottom-4 left-1/2 transform -translate-x-1/2 bg-white rounded-xl shadow-lg border border-gray-200 p-4 z-50 w-full max-w-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-indigo-600 mr-2"></i>
                    <span id="selectedCount" class="font-semibold text-gray-800">0</span>
                    <span class="text-gray-600 ml-1">brand(s) selected</span>
                </div>
                <div class="hidden sm:block border-l border-gray-300 h-6"></div>
                <div class="hidden sm:block text-sm text-gray-500">
                    Click bulk action buttons to apply to selected items
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="clearSelection"
                    class="text-sm text-gray-600 hover:text-gray-800 px-3 py-1 rounded-lg hover:bg-gray-100">
                    <i class="fas fa-times mr-1"></i> Clear
                </button>
                <div class="flex space-x-2">
                    <button id="bulkDeleteBtn"
                        class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors text-sm">
                        <i class="fas fa-trash mr-1"></i> Delete Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Brands Table - Tabulator -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">All Brands</h3>
        </div>
        <div class="p-6">
            <!-- Tabulator Toolbar -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
                <div class="order-2 sm:order-1">
                    <div class="relative" style="width: 260px;">
                        <input type="text" id="brandsSearchInput" placeholder="Search brands..."
                            class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                    <!-- Bulk Delete Button -->
                    <button id="tabulatorBulkDeleteBtn" class="btn-danger hidden">
                        <i class="fas fa-trash mr-2"></i>Bulk Delete
                    </button>
                    <!-- Refresh Button -->
                    <button onclick="refreshAll()" class="btn-secondary">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                    <!-- Column Visibility Button -->
                    <button id="brandsColumnVisibilityBtn" class="btn-secondary">
                        <i class="fas fa-columns mr-2"></i>Columns
                    </button>
                    <!-- Export Dropdown -->
                    <div class="relative group">
                        <button id="brandsExportBtn" class="btn-primary">
                            <i class="fas fa-file-export mr-2"></i>Export
                        </button>
                        <div
                            class="absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden group-hover:block
               right-0 md:right-0 md:left-auto
               left-0 md:left-auto">
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

            <!-- Loading State -->
            <div id="loadingState" class="text-center py-8">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <p class="mt-2 text-gray-500">Loading brands...</p>
            </div>

            <!-- Tabulator Table -->
            <div id="brandsTable"></div>

            <!-- Pagination Info -->
            <div id="paginationInfo" class="mt-4 text-sm text-gray-500 text-center"></div>
        </div>
    </div>

    <!-- Create/Edit Brand Modal -->
    <div id="brandModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-6 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Add New Brand</h3>
                <button onclick="closeBrandModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="brandForm" enctype="multipart/form-data">
                <input type="hidden" id="brandId" name="id">

                <div class="space-y-6 max-h-[70vh] overflow-y-auto pr-2">
                    <!-- Brand Logo Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand Logo</label>
                        <div class="flex items-center space-x-6">
                            <div id="logoPreview"
                                class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden border-2 border-dashed border-gray-300">
                                <i class="fas fa-image text-gray-400 text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <input type="hidden" id="logo_url" name="logo_url">
                                <button type="button" onclick="openMediaModal('logo')"
                                    class="btn-secondary mb-2">
                                    <i class="fas fa-upload mr-2"></i>Select from Media
                                </button>
                                <p class="text-xs text-gray-500">Recommended: 300x300px, JPG, PNG, or WebP. Max 2MB.</p>
                                <div id="logoError" class="hidden mt-2 text-sm text-rose-600"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Basic Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Brand Name
                                *</label>
                            <input type="text" id="name" name="name" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <div id="nameError" class="hidden mt-1 text-sm text-rose-600"></div>
                        </div>

                        <div>
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-1">Slug *</label>
                            <input type="text" id="slug" name="slug" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <div id="slugError" class="hidden mt-1 text-sm text-rose-600"></div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" name="email"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="text" id="phone" name="phone"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Website -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                        <input type="url" id="website" name="website"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>

                    <!-- Location -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <input type="text" id="country" name="country"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <input type="text" id="address" name="address"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- SEO Section -->
                    <div class="border-t pt-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-3">SEO Settings</h4>

                        <div class="space-y-4">
                            <div>
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                    Title</label>
                                <input type="text" id="meta_title" name="meta_title"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>

                            <div>
                                <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                    Description</label>
                                <textarea id="meta_description" name="meta_description" rows="2"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                            </div>

                            <div>
                                <label for="meta_keywords" class="block text-sm font-medium text-gray-700 mb-1">Meta
                                    Keywords</label>
                                <input type="text" id="meta_keywords" name="meta_keywords"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>
                    </div>

                    <!-- Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="featured" name="featured"
                                    class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">Featured Brand</span>
                            </label>
                        </div>

                        <div>
                            <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort
                                Order</label>
                            <input type="number" id="sort_order" name="sort_order" value="0" min="0"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div>
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" id="status" name="status" checked
                                    class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-700">Active</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t">
                    <button type="button" onclick="closeBrandModal()" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>
                        <span id="submitText">Save Brand</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
        <div class="relative top-20 mx-auto p-6 border w-96 shadow-lg rounded-2xl bg-white">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold text-gray-800">Bulk Actions</h3>
                <button onclick="closeBulkActions()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <div id="selectedCountInfo" class="mb-4 text-sm text-gray-600"></div>

            <div class="space-y-3">
                <button onclick="applyBulkAction('activate')" class="w-full btn-secondary text-left">
                    <i class="fas fa-toggle-on mr-2 text-emerald-600"></i>Activate Selected
                </button>
                <button onclick="applyBulkAction('deactivate')" class="w-full btn-secondary text-left">
                    <i class="fas fa-toggle-off mr-2 text-rose-600"></i>Deactivate Selected
                </button>
                <button onclick="applyBulkAction('feature')" class="w-full btn-secondary text-left">
                    <i class="fas fa-star mr-2 text-amber-500"></i>Mark as Featured
                </button>
                <button onclick="applyBulkAction('unfeature')" class="w-full btn-secondary text-left">
                    <i class="far fa-star mr-2 text-gray-500"></i>Remove from Featured
                </button>
                <button onclick="applyBulkAction('delete')"
                    class="w-full btn-secondary text-left border-rose-200 text-rose-600 hover:bg-rose-50">
                    <i class="fas fa-trash mr-2"></i>Delete Selected
                </button>
            </div>
        </div>
    </div>

    <!-- Media Modal -->
    <div id="media-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeMediaModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Select Media</h3>
                        <button type="button" onclick="closeMediaModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between mb-4 space-y-2 md:space-y-0">
                        <input type="text" id="media-search" placeholder="Search files..." class="border rounded px-3 py-2 w-full md:w-1/3">
                        <div class="flex items-center space-x-2">
                            <label class="cursor-pointer bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition">
                                <span>Upload New</span>
                                <input type="file" id="media-upload" class="hidden" multiple accept="image/*">
                            </label>
                        </div>
                    </div>

                    <div id="media-grid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4 max-h-96 overflow-y-auto p-2 border rounded">
                        <!-- Loaded dynamically -->
                        <div class="col-span-full text-center py-10 text-gray-500">Loading media...</div>
                    </div>

                    <div id="media-pagination" class="mt-4 flex justify-between items-center">
                        <!-- Pagination links -->
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" id="media-select-btn" onclick="confirmMediaSelection()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        Select
                    </button>
                    <button type="button" onclick="closeMediaModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Brand Tabulator Styles */
        #brandsTable {
            border: none !important;
            background: transparent !important;
            min-height: 400px;
        }

        .tabulator-tableholder {
            background: transparent !important;
            border: none !important;
        }

        .tabulator .tabulator-header {
            border: none !important;
            border-bottom: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
            font-weight: 600;
            color: #374151;
        }

        .tabulator .tabulator-col {
            background-color: #f9fafb !important;
            border-right: 1px solid #e5e7eb !important;
            padding: 12px 8px !important;
        }

        .tabulator .tabulator-col:last-child {
            border-right: none !important;
        }

        .tabulator-row {
            border-bottom: 1px solid #f3f4f6 !important;
            transition: background-color 0.2s ease;
        }

        .tabulator-row.tabulator-selectable:hover {
            background-color: #f9fafb !important;
        }

        .tabulator-row.tabulator-selected {
            background-color: #e0e7ff !important;
        }

        .tabulator-cell {
            padding: 12px 8px !important;
            border-right: 1px solid #f3f4f6 !important;
            vertical-align: middle !important;
        }

        .tabulator-cell:last-child {
            border-right: none !important;
        }

        /* Switch styling */
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

        input:checked + .slider {
            background-color: #10b981;
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }

        .tabulator-footer {
            border-top: 1px solid #e5e7eb !important;
            background-color: #f9fafb !important;
            padding: 12px !important;
        }

        /* Bulk Actions Bar Styles */
        #bulkActionsBar {
            animation: slideUp 0.3s ease-out;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        @keyframes slideUp {
            from {
                transform: translate(-50%, 100%);
                opacity: 0;
            }
            to {
                transform: translate(-50%, 0);
                opacity: 1;
            }
        }

        /* Loading state */
        #loadingState {
            display: none;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .tabulator .tabulator-col {
                min-width: 100px !important;
            }

            .tabulator-cell {
                padding: 8px 4px !important;
            }

            #bulkActionsBar {
                width: 95%;
                padding: 12px;
            }

            #bulkActionsBar .flex {
                flex-direction: column;
                gap: 8px;
            }

            #bulkActionsBar .space-x-4 {
                justify-content: center;
                width: 100%;
            }

            #bulkActionsBar .space-x-2 {
                justify-content: center;
                width: 100%;
            }

            .mobile-swal {
                width: 95% !important;
                margin: 0 auto;
            }
        }

        /* Selection styles */
        .tabulator-row.tabulator-selected {
            background-color: #e0e7ff !important;
        }

        .tabulator-row.tabulator-selected:hover {
            background-color: #c7d2fe !important;
        }

        /* Checkbox styling for select all */
        .tabulator-col.select-checkbox .tabulator-col-content {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .tabulator-col.select-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .tabulator-cell.select-checkbox input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Axios instance with interceptors
        const axiosInstance = axios.create({
            baseURL: '{{ url('') }}/admin/api',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
            }
        });

        // Add request interceptor for token refresh if needed
        axiosInstance.interceptors.request.use(
            config => {
                // Add CSRF token for non-GET requests
                if (config.method !== 'get') {
                    config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                }
                return config;
            },
            error => Promise.reject(error)
        );

        // Add response interceptor for error handling
        axiosInstance.interceptors.response.use(
            response => response,
            error => {
                if (error.response?.status === 401) {
                    // Token expired, redirect to login
                    window.location.href = '{{ route("admin.login") }}';
                }
                return Promise.reject(error);
            }
        );

        // Global variables
        let brandsTable = null;
        let isEditing = false;
        let currentPage = 1;
        let perPage = 10;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Brands module initialized');

            // Load data
            loadBrandsData();
            loadStatistics();

            // Setup event listeners
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Auto-generate slug from name
            const nameInput = document.getElementById('name');
            const slugInput = document.getElementById('slug');

            if (nameInput && slugInput) {
                nameInput.addEventListener('blur', function() {
                    if (!isEditing && this.value && !slugInput.value) {
                        const slug = this.value.toLowerCase()
                            .replace(/[^a-z0-9 -]/g, '')
                            .replace(/\s+/g, '-')
                            .replace(/-+/g, '-');
                        slugInput.value = slug;
                    }
                });
            }

            // Form submission
            const brandForm = document.getElementById('brandForm');
            if (brandForm) {
                brandForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveBrand();
                });
            }

            // Clear errors on input
            ['name', 'slug'].forEach(fieldId => {
                const element = document.getElementById(fieldId);
                if (element) {
                    element.addEventListener('input', function() {
                        const errorElement = document.getElementById(fieldId + 'Error');
                        if (errorElement) {
                            errorElement.classList.add('hidden');
                            errorElement.textContent = '';
                        }
                    });
                }
            });
        }

        // Initialize Tabulator table
        function initializeBrandsTable(data = []) {
            brandsTable = new Tabulator("#brandsTable", {
                data: data,
                layout: "fitDataFill",
                height: "100%",
                responsiveLayout: "hide",
                pagination: true,
                paginationSize: perPage,
                paginationSizeSelector: [10, 25, 50, 100],
                paginationCounter: "rows",
                ajaxURL: "{{ url('') }}/admin/api/brands",
                ajaxParams: {
                    sort: 'created_at',
                    direction: 'desc'
                },
                ajaxResponse: function(url, params, response) {
                    if (response.success) {
                        // Hide loading state
                        document.getElementById('loadingState').style.display = 'none';

                        // Ensure we're returning the correct data structure
                        if (response.data && response.data.data) {
                            updatePaginationInfo(response.data.meta);
                            return response.data.data;
                        }
                        return [];
                    }
                    return [];
                },
                ajaxError: function(xhr, textStatus, errorThrown) {
                    console.error('Ajax error:', xhr, textStatus, errorThrown);
                    document.getElementById('loadingState').style.display = 'none';
                    toastr.error('Failed to load brands data');
                },
                columns: [{
                        title: "<input type='checkbox' id='selectAllBrands'>",
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
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search ID…",
                        responsive: 0
                    },
                    {
                        title: "Brand",
                        field: "name",
                        widthGrow: 2,
                        sorter: "string",
                        headerFilter: "input",
                        headerFilterPlaceholder: "Search Brand…",
                        formatter: function(cell, formatterParams, onRendered) {
                            const row = cell.getRow();
                            const data = row.getData();

                            return `
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden flex-shrink-0">
                                ${data.logo ?
                                    `<img src="${data.logo}" alt="${data.name}" class="w-full h-full object-cover">` :
                                    `<i class="fas fa-tag text-gray-400"></i>`
                                }
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate">${data.name}</p>
                                ${data.description ?
                                    `<p class="text-sm text-gray-500 truncate">${data.description}</p>` : ''
                                }
                            </div>
                        </div>
                    `;
                        }
                    },
                    {
                        title: "Products",
                        field: "product_count",
                        width: 120,
                        sorter: "number",
                        hozAlign: "center",
                        formatter: function(cell) {
                            const count = cell.getValue() || 0;
                            return `<div class="text-center">
                                <span class="font-semibold text-gray-900">${count}</span>
                                <div class="text-xs text-gray-500">products</div>
                            </div>`;
                        },
                        responsive: 1
                    },
                    {
                        title: "Status",
                        field: "status",
                        width: 120,
                        hozAlign: "center",
                        headerFilter: "select",
                        headerFilterParams: {
                            values: {
                                "": "All",
                                "active": "Active",
                                "inactive": "Inactive",
                            }
                        },
                        formatter: function(cell) {
                            const row = cell.getRow();
                            const data = row.getData();
                            const isActive = data.status === 'active';
                            return `
                        <label class="switch">
                            <input type="checkbox" class="toggle-brand-status"
                                   data-id="${data.id}" ${isActive ? 'checked' : ''}>
                            <span class="slider round"></span>
                        </label>
                    `;
                        },
                        responsive: 1
                    },
                    {
                        title: "Featured",
                        field: "featured",
                        width: 120,
                        hozAlign: "center",
                        headerFilter: "select",
                        headerFilterParams: {
                            values: {
                                "": "All",
                                "true": "Featured",
                                "false": "Not Featured",
                            }
                        },
                        formatter: function(cell) {
                            const row = cell.getRow();
                            const data = row.getData();
                            const isFeatured = data.featured === true || data.featured === 'true';
                            return `
                        <button onclick="toggleFeatured(${data.id})"
                                class="text-2xl ${isFeatured ? 'text-amber-500' : 'text-gray-300'} hover:text-amber-600 transition-colors">
                            ${isFeatured ? '<i class="fas fa-star"></i>' : '<i class="far fa-star"></i>'}
                        </button>
                    `;
                        },
                        responsive: 2
                    },
                    {
                        title: "Created",
                        field: "created_at_formatted",
                        width: 150,
                        sorter: "date",
                        hozAlign: "center",
                        formatter: function(cell) {
                            const dateStr = cell.getValue();
                            return dateStr || '';
                        },
                        responsive: 2
                    },
                    {
                        title: "Actions",
                        field: "id",
                        width: 150,
                        hozAlign: "center",
                        headerSort: false,
                        formatter: function(cell) {
                            const id = cell.getValue();
                            return `
                        <div class="flex space-x-2 justify-center">
                            <button onclick="editBrand(${id})"
                                    class="w-8 h-8 flex items-center justify-center bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                <i class="fas fa-edit text-sm"></i>
                            </button>
                            <button onclick="viewBrand(${id})"
                                    class="w-8 h-8 flex items-center justify-center bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition-colors">
                                <i class="fas fa-eye text-sm"></i>
                            </button>
                            <button onclick="deleteBrand(${id})"
                                    class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    `;
                        },
                        responsive: 0
                    }
                ],
                rowFormatter: function(row) {
                    const rowEl = row.getElement();
                    rowEl.classList.add('hover:bg-gray-50');
                },
                rowSelectionChanged: function(data, rows) {
                    updateBulkActions(data.length);
                }
            });

            // Fix layout after table is built
            brandsTable.on("tableBuilt", function(){
                // Redraw table to ensure proper layout
                setTimeout(() => {
                    brandsTable.redraw(true);
                }, 100);

                // Initialize table functionality
                initBrandsSearch();
                initBrandsExport();
                initBrandsColumnVisibility();
                initBulkActions();

                // Add click event for select all checkbox
                $(document).on('click', '#selectAllBrands', function() {
                    if ($(this).is(':checked')) {
                        brandsTable.selectRow();
                    } else {
                        brandsTable.deselectRow();
                    }
                });

                // Status toggle event delegation
                $(document).on('change', '.toggle-brand-status', function(e) {
                    const brandId = $(this).data('id');
                    const isActive = $(this).is(':checked');
                    toggleBrandStatus(brandId, isActive);
                });

                // Featured toggle event delegation
                $(document).on('click', '.fa-star, .far.fa-star', function(e) {
                    if (e.target.closest('button[onclick^="toggleFeatured"]')) {
                        const button = e.target.closest('button');
                        const onclickAttr = button.getAttribute('onclick');
                        const brandId = onclickAttr.match(/toggleFeatured\((\d+)\)/)[1];

                        const row = brandsTable.getRow(parseInt(brandId));
                        if (row) {
                            const data = row.getData();
                            const currentFeatured = data.featured === true || data.featured === 'true';
                            toggleFeatured(brandId, !currentFeatured);
                        }
                    }
                });
            });
        }

        // Load brands data and initialize Tabulator
        async function loadBrandsData(page = 1, perPage = 10) {
            try {
                // Show loading state
                document.getElementById('loadingState').style.display = 'block';

                const response = await axiosInstance.get('brands', {
                    params: {
                        page: page,
                        per_page: perPage,
                        sort: 'created_at',
                        direction: 'desc'
                    }
                });

                console.log('Brands API Response:', response.data);

                if (response.data.success) {
                    // CORRECTED: Access data from response.data.data
                    const brands = response.data.data.data || [];
                    const meta = response.data.data.meta || {};

                    // Update pagination info
                    currentPage = meta.current_page || 1;
                    perPage = meta.per_page || 10;

                    // Initialize or update Tabulator
                    if (!brandsTable) {
                        initializeBrandsTable(brands);
                    } else {
                        brandsTable.setData(brands);
                        updatePaginationInfo(meta);
                    }

                    // Hide loading state
                    document.getElementById('loadingState').style.display = 'none';
                } else {
                    toastr.error('Failed to load brands: ' + (response.data.message || 'Unknown error'));
                    document.getElementById('loadingState').style.display = 'none';
                }
            } catch (error) {
                console.error('Error loading brands:', error);
                toastr.error('Failed to load brands. Check console for details.');
                document.getElementById('loadingState').style.display = 'none';

                // Initialize table with empty data if error
                if (!brandsTable) {
                    initializeBrandsTable([]);
                }
            }
        }

        // Update pagination info
        function updatePaginationInfo(meta) {
            const paginationInfo = document.getElementById('paginationInfo');
            if (paginationInfo && meta) {
                paginationInfo.innerHTML = `
                    Showing ${meta.from || 0} to ${meta.to || 0} of ${meta.total || 0} brands
                `;
            }
        }

        // Load statistics
        async function loadStatistics() {
            try {
                const response = await axiosInstance.get('brands/statistics');

                if (response.data.success) {
                    const stats = response.data.data;
                    document.getElementById('totalBrands').textContent = stats.total_brands || 0;
                    document.getElementById('activeBrands').textContent = stats.active_brands || 0;

                    // Handle featured brands (might not exist yet)
                    document.getElementById('featuredBrands').textContent = stats.featured_brands || 0;

                    document.getElementById('popularBrand').textContent = stats.popular_brand ? stats.popular_brand.name : '-';
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
                toastr.error('Failed to load statistics');
            }
        }

        // ============================
        // BULK ACTIONS SYSTEM
        // ============================

        function initBulkActions() {
            const bulkActionsBar = document.getElementById('bulkActionsBar');
            const selectedCount = document.getElementById('selectedCount');
            const selectAllBrands = document.getElementById('selectAllBrands');
            const clearSelection = document.getElementById('clearSelection');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            const tabulatorBulkDeleteBtn = document.getElementById('tabulatorBulkDeleteBtn');
            const bulkActionsBtn = document.getElementById('brandsBulkActionsBtn');

            // Update selected count and show/hide bulk actions bar
            function updateBulkActions(selectedCountNum) {
                if (selectedCount) {
                    selectedCount.textContent = selectedCountNum;
                }

                if (bulkActionsBar) {
                    if (selectedCountNum > 0) {
                        bulkActionsBar.classList.remove('hidden');
                        bulkActionsBar.classList.add('flex');
                        // Scroll to bottom for mobile
                        if (window.innerWidth < 768) {
                            setTimeout(() => {
                                bulkActionsBar.scrollIntoView({ behavior: 'smooth', block: 'end' });
                            }, 100);
                        }
                    } else {
                        bulkActionsBar.classList.remove('flex');
                        bulkActionsBar.classList.add('hidden');
                    }
                }

                // Update select all checkbox
                const totalRows = brandsTable.getDataCount();
                if (selectAllBrands) {
                    selectAllBrands.checked = selectedCountNum === totalRows && totalRows > 0;
                    selectAllBrands.indeterminate = selectedCountNum > 0 && selectedCountNum < totalRows;
                }

                // Update tabulator bulk delete button
                if (tabulatorBulkDeleteBtn) {
                    if (selectedCountNum > 0) {
                        tabulatorBulkDeleteBtn.classList.remove('hidden');
                        tabulatorBulkDeleteBtn.innerHTML = `<i class="fas fa-trash mr-2"></i>Delete (${selectedCountNum})`;
                    } else {
                        tabulatorBulkDeleteBtn.classList.add('hidden');
                    }
                }
            }

            // Select All functionality
            if (selectAllBrands) {
                selectAllBrands.addEventListener('click', function() {
                    if (this.checked) {
                        brandsTable.selectRow();
                    } else {
                        brandsTable.deselectRow();
                    }
                });
            }

            // Row selection event
            brandsTable.on("rowSelectionChanged", function(data, rows) {
                updateBulkActions(data.length);
            });

            // Clear selection
            if (clearSelection) {
                clearSelection.addEventListener('click', function() {
                    brandsTable.deselectRow();
                    if (selectAllBrands) {
                        selectAllBrands.checked = false;
                        selectAllBrands.indeterminate = false;
                    }
                    updateBulkActions(0);
                    toastr.info('Selection cleared');
                });
            }

            // Bulk Delete Function for both buttons
            async function handleBulkDelete() {
                const selectedRows = brandsTable.getSelectedRows();
                const selectedIds = selectedRows.map(row => row.getData().id);

                if (selectedIds.length === 0) {
                    toastr.warning('Please select at least one brand to delete.');
                    return;
                }

                const itemName = 'brand';
                const itemCount = selectedIds.length;

                // Get selected brands data
                const selectedBrands = selectedRows.map(row => row.getData());

                Swal.fire({
                    title: `Delete ${itemCount} ${itemName}${itemCount > 1 ? 's' : ''}?`,
                    html: `
                    <div class="text-left space-y-4">
                        <p class="text-gray-700">You are about to delete <strong>${itemCount}</strong> ${itemName}${itemCount > 1 ? 's' : ''}.</p>

                        <div class="bg-rose-50 border border-rose-200 rounded-lg p-4">
                            <div class="flex items-center text-rose-800 mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-semibold">Warning</span>
                            </div>
                            <ul class="text-sm text-rose-700 space-y-1 list-disc pl-5">
                                <li>Products will lose brand association</li>
                                <li>Brand logos will be permanently deleted</li>
                                <li>This action cannot be undone</li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Selected brand${itemCount > 1 ? 's' : ''}:</p>
                            <div class="max-h-32 overflow-y-auto">
                                ${getSelectedBrandsPreview(selectedBrands)}
                            </div>
                        </div>

                        <div class="flex items-center p-3 bg-amber-50 rounded-lg border border-amber-200">
                            <input type="checkbox" id="confirmDelete" class="w-4 h-4 text-rose-600 bg-white border-gray-300 rounded focus:ring-rose-500">
                            <label for="confirmDelete" class="ml-3 text-sm font-medium text-amber-800">
                                I understand this action cannot be undone
                            </label>
                        </div>
                    </div>
                `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: `Delete ${itemCount} ${itemName}${itemCount > 1 ? 's' : ''}`,
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    width: '600px',
                    customClass: {
                        popup: 'mobile-swal',
                        actions: 'flex gap-2',
                        confirmButton: 'btn-danger',
                        cancelButton: 'btn-secondary'
                    },
                    preConfirm: () => {
                        if (!document.getElementById('confirmDelete').checked) {
                            Swal.showValidationMessage('Please confirm that you understand this action cannot be undone.');
                            return false;
                        }
                        return { ids: selectedIds };
                    }
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        await performBulkDelete(selectedIds, itemName);
                    }
                });
            }

            // Helper: Get selected brands preview HTML
            function getSelectedBrandsPreview(selectedBrands) {
                if (selectedBrands.length === 0) return '<p class="text-sm text-gray-500">No brands selected</p>';

                return selectedBrands.map(brand => `
                <div class="flex items-center justify-between py-2 border-b border-gray-100 last:border-0">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                            ${brand.logo ?
                                `<img src="${brand.logo}" alt="${brand.name}" class="w-full h-full object-cover">` :
                                `<i class="fas fa-tag text-gray-400 text-xs"></i>`
                            }
                        </div>
                        <div class="min-w-0">
                            <span class="text-sm text-gray-900 truncate block">${brand.name || 'Unnamed'}</span>
                            <span class="text-xs text-gray-500">${brand.product_count || 0} products</span>
                        </div>
                    </div>
                    <span class="text-xs text-gray-500">ID: ${brand.id}</span>
                </div>
            `).join('');
            }

            // Helper: Perform bulk delete
            async function performBulkDelete(selectedIds, itemName) {
                const ids = selectedIds.map(id => parseInt(id));

                Swal.fire({
                    title: 'Deleting...',
                    text: `Please wait while we delete ${ids.length} ${itemName}${ids.length > 1 ? 's' : ''}`,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => { Swal.showLoading(); }
                });

                try {
                    const response = await axiosInstance.post('brands/bulk-delete', { ids: ids });

                    if (response.data.success) {
                        const deletedCount = response.data.data.deleted_count;

                        // Clear selection
                        brandsTable.deselectRow();
                        if (selectAllBrands) {
                            selectAllBrands.checked = false;
                            selectAllBrands.indeterminate = false;
                        }
                        if (bulkActionsBar) {
                            bulkActionsBar.classList.add('hidden');
                        }

                        // Refresh data
                        await Promise.all([
                            loadBrandsData(),
                            loadStatistics()
                        ]);

                        Swal.close();
                        toastr.success(`Successfully deleted ${deletedCount} brand${deletedCount > 1 ? 's' : ''}`);

                        const remainingCount = brandsTable.getDataCount();
                        if (remainingCount === 0) {
                            toastr.info('All brands have been deleted.');
                        }
                    } else {
                        Swal.close();
                        toastr.error(response.data.message || 'Failed to delete brands');
                    }
                } catch (error) {
                    Swal.close();
                    if (error.response?.status === 400) {
                        toastr.error(error.response.data.message || 'Cannot delete brands with associated products');
                    } else {
                        toastr.error('Failed to delete brands');
                    }
                }
            }

            // Attach bulk delete to both buttons
            if (bulkDeleteBtn) {
                bulkDeleteBtn.addEventListener('click', handleBulkDelete);
            }
            if (tabulatorBulkDeleteBtn) {
                tabulatorBulkDeleteBtn.addEventListener('click', handleBulkDelete);
            }

            // Bulk actions modal
            if (bulkActionsBtn) {
                bulkActionsBtn.addEventListener('click', function() {
                    const selectedRows = brandsTable.getSelectedRows();

                    if (selectedRows.length === 0) {
                        toastr.warning('Please select at least one brand');
                        return;
                    }

                    const selectedCountInfo = document.getElementById('selectedCountInfo');
                    if (selectedCountInfo) {
                        selectedCountInfo.textContent = `${selectedRows.length} brand(s) selected`;
                    }

                    const bulkActionsModal = document.getElementById('bulkActionsModal');
                    if (bulkActionsModal) {
                        bulkActionsModal.classList.remove('hidden');
                    }
                });
            }

            // Keyboard shortcuts for bulk actions
            document.addEventListener('keydown', function(e) {
                // Ctrl/Cmd + A to select all
                if ((e.ctrlKey || e.metaKey) && e.key === 'a') {
                    e.preventDefault();
                    brandsTable.selectRow();
                }

                // Escape to clear selection
                if (e.key === 'Escape') {
                    brandsTable.deselectRow();
                    if (selectAllBrands) {
                        selectAllBrands.checked = false;
                        selectAllBrands.indeterminate = false;
                    }
                    updateBulkActions(0);
                }

                // Delete key to trigger bulk delete (when selection exists)
                if (e.key === 'Delete' || e.key === 'Backspace') {
                    const selectedRows = brandsTable.getSelectedRows();
                    if (selectedRows.length > 0) {
                        e.preventDefault();
                        handleBulkDelete();
                    }
                }
            });

            // Close bulk actions when clicking outside
            document.addEventListener('click', function(e) {
                if (bulkActionsBar && !bulkActionsBar.contains(e.target)) {
                    // Don't close if clicking on the bulk delete button in toolbar
                    const toolbarBulkBtn = document.getElementById('tabulatorBulkDeleteBtn');
                    if (!toolbarBulkBtn || !toolbarBulkBtn.contains(e.target)) {
                        // Optional: Uncomment if you want clicking outside to clear selection
                        // brandsTable.deselectRow();
                        // updateBulkActions(0);
                    }
                }
            });

            // Initialize
            updateBulkActions(0);
        }

        // Search functionality
        function initBrandsSearch() {
            const searchInput = document.getElementById('brandsSearchInput');
            let searchTimeout;

            if (searchInput) {
                searchInput.addEventListener('keyup', function() {
                    const searchTerm = this.value;

                    // Clear previous timeout
                    clearTimeout(searchTimeout);

                    // Set new timeout
                    searchTimeout = setTimeout(() => {
                        if (searchTerm.length >= 2 || searchTerm === '') {
                            brandsTable.setFilter([
                                [{
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
                                        field: "email",
                                        type: "like",
                                        value: searchTerm
                                    }
                                ]
                            ]);
                        }
                    }, 500);
                });
            }
        }

        // Column visibility
        function initBrandsColumnVisibility() {
            const columnVisibilityBtn = document.getElementById('brandsColumnVisibilityBtn');
            if (!columnVisibilityBtn || !brandsTable) return;

            const columnMenu = document.createElement('div');
            columnMenu.className =
                'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

            const columns = brandsTable.getColumnDefinitions();

            columns.forEach((column, index) => {
                if (index === 0) return; // skip checkbox column

                const field = column.field;
                const columnBtn = document.createElement('button');
                columnBtn.className =
                    'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
                columnBtn.innerHTML = `
            <input type="checkbox" class="mr-2" ${brandsTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;

                columnBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const col = brandsTable.getColumn(field);
                    const checkbox = this.querySelector('input');
                    col.toggle();
                    setTimeout(() => {
                        checkbox.checked = col.isVisible();
                    }, 10);
                });

                columnMenu.appendChild(columnBtn);
            });

            // Toggle menu
            columnVisibilityBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                columnMenu.classList.toggle('hidden');
            });

            // Close menu when clicking outside
            document.addEventListener('click', function(e) {
                if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
                    columnMenu.classList.add('hidden');
                }
            });

            columnVisibilityBtn.parentElement.appendChild(columnMenu);
        }

        // Export functionality
        function initBrandsExport() {
            const exportBtns = document.querySelectorAll('[data-export]');

            exportBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    const format = this.getAttribute('data-export');

                    switch (format) {
                        case 'csv':
                            brandsTable.download("csv", "brands.csv");
                            break;
                        case 'xlsx':
                            brandsTable.download("xlsx", "brands.xlsx", {
                                sheetName: "Brands"
                            });
                            break;
                        case 'print':
                            window.print();
                            break;
                    }
                });
            });
        }

        // Close bulk actions modal
        function closeBulkActions() {
            document.getElementById('bulkActionsModal').classList.add('hidden');
        }

        // Show create brand modal
        function showCreateBrandModal() {
            isEditing = false;
            document.getElementById('modalTitle').textContent = 'Add New Brand';
            document.getElementById('submitText').textContent = 'Save Brand';
            document.getElementById('brandForm').reset();
            document.getElementById('brandId').value = '';
            document.getElementById('logoPreview').innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
            document.getElementById('status').checked = true;
            document.getElementById('featured').checked = false;

            // Clear errors
            ['nameError', 'slugError', 'logoError'].forEach(errorId => {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.textContent = '';
                }
            });

            document.getElementById('brandModal').classList.remove('hidden');
        }

        // Close brand modal
        function closeBrandModal() {
            document.getElementById('brandModal').classList.add('hidden');
        }

        // Media Modal Variables
        let currentMediaMode = 'logo';
        let selectedMediaImage = null;
        let currentMediaData = null;

        // Open media modal
        function openMediaModal(mode = 'logo') {
            currentMediaMode = mode;
            selectedMediaImage = null;

            // Set modal title based on mode
            const modalTitle = 'Select Brand Logo Image';
            document.getElementById('modal-title').textContent = modalTitle;

            // Show modal
            document.getElementById('media-modal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');

            // Load media
            loadMediaFiles(1);
        }

        // Close media modal
        function closeMediaModal() {
            document.getElementById('media-modal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
            selectedMediaImage = null;
            currentMediaMode = 'logo';
        }

        // Load media files
        async function loadMediaFiles(page = 1, search = '') {
            const grid = document.getElementById('media-grid');
            const pagination = document.getElementById('media-pagination');

            grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">Loading media...</div>';

            try {
                const response = await axiosInstance.get('{{ route('admin.media.data') }}', {
                    params: { page, search }
                });

                if (response.data.success) {
                    const mediaItems = response.data.data?.data || [];
                    const meta = response.data.data?.meta || response.data.meta || {};
                    
                    currentMediaData = response.data.data;
                    renderMediaGrid(mediaItems);
                    renderMediaPagination(meta);
                }
            } catch (error) {
                console.error('Media load error:', error);
                grid.innerHTML = '<div class="col-span-full text-center py-10 text-red-500">Error loading media.</div>';
                toastr.error('Failed to load media');
            }
        }

        // Render media grid
        function renderMediaGrid(media) {
            const grid = document.getElementById('media-grid');

            if (!media || media.length === 0) {
                grid.innerHTML = '<div class="col-span-full text-center py-10 text-gray-500">No media found.</div>';
                return;
            }

            let html = '';
            media.forEach(item => {
                const isSelected = selectedMediaImage && selectedMediaImage.id === item.id;
                html += `
                <div class="relative border rounded-lg overflow-hidden cursor-pointer group ${isSelected ? 'ring-2 ring-blue-500' : ''}"
                     onclick="toggleMediaSelection(${item.id}, '${item.url || item.path}')">
                    <img src="${item.url || item.path}" class="w-full h-32 object-cover">
                    <div class="p-2 text-xs truncate">${item.filename || item.name}</div>
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition"></div>
                    ${isSelected ?
                        '<div class="absolute top-2 right-2 bg-blue-500 text-white rounded-full w-6 h-6 flex items-center justify-center">✓</div>'
                        : ''}
                </div>
                `;
            });

            grid.innerHTML = html;
        }

        // Render media pagination
        function renderMediaPagination(meta) {
            const pagination = document.getElementById('media-pagination');
            if (!meta || meta.last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let html = '<div class="flex gap-2">';
            const currentPage = meta.current_page;
            const lastPage = meta.last_page;

            for (let i = 1; i <= lastPage; i++) {
                const active = (i === currentPage) ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700';
                html += `
                <button type="button" onclick="loadMediaFiles(${i}, document.getElementById('media-search').value)"
                        class="px-3 py-1 rounded ${active} hover:bg-blue-600 hover:text-white transition">
                    ${i}
                </button>
                `;
            }

            html += '</div>';
            pagination.innerHTML = html;
        }

        // Toggle media selection
        function toggleMediaSelection(id, url) {
            // Single selection mode
            selectedMediaImage = { id, url };

            // Re-render grid with updated selection
            if (currentMediaData && currentMediaData.data) {
                renderMediaGrid(currentMediaData.data);
            }
        }

        // Confirm media selection
        function confirmMediaSelection() {
            if (!selectedMediaImage) {
                toastr.warning('Please select an image');
                return;
            }

            const { id, url } = selectedMediaImage;

            // Update the hidden input and preview
            document.getElementById('logo_url').value = url;
            const preview = document.getElementById('logoPreview');
            if (preview) {
                preview.innerHTML = `<img src="${url}" alt="Logo preview" class="w-full h-full object-cover">`;
            }

            closeMediaModal();
            toastr.success('Image selected successfully');
        }

        // Handle media upload
        async function handleMediaUpload(event) {
            const files = event.target.files;
            if (!files.length) return;

            const formData = new FormData();
            for (let i = 0; i < files.length; i++) {
                formData.append('files[]', files[i]);
            }

            try {
                const response = await axiosInstance.post('{{ route('admin.media.upload') }}', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });

                toastr.success('Files uploaded successfully');
                loadMediaFiles(1); // Reload media grid
                event.target.value = ''; // Reset file input
            } catch (error) {
                console.error('Upload error:', error);
                toastr.error('Failed to upload files');
            }
        }

        // Setup media upload listener
        document.addEventListener('DOMContentLoaded', function() {
            const mediaUploadInput = document.getElementById('media-upload');
            if (mediaUploadInput) {
                mediaUploadInput.addEventListener('change', handleMediaUpload);
            }

            // Debounced search
            const mediaSearch = document.getElementById('media-search');
            if (mediaSearch) {
                let searchTimeout;
                mediaSearch.addEventListener('input', function(e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => {
                        loadMediaFiles(1, e.target.value);
                    }, 500);
                });

                // Handle Enter key in search
                mediaSearch.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        loadMediaFiles(1, this.value);
                    }
                });
            }
        });


        // Save brand (create or update)
        async function saveBrand() {
            const form = document.getElementById('brandForm');
            const formData = new FormData(form);

            // Convert checkbox values
            const featured = document.getElementById('featured').checked;
            const status = document.getElementById('status').checked;

            // Add checkbox values
            formData.set('featured', featured);
            formData.set('status', status ? 'active' : 'inactive');

            const method = isEditing ? 'put' : 'post';
            const brandId = document.getElementById('brandId').value;
            const url = isEditing ? `brands/${brandId}` : 'brands';

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            // Clear previous errors
            ['nameError', 'slugError', 'logoError'].forEach(errorId => {
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.textContent = '';
                }
            });

            try {
                const response = await axiosInstance({
                    method: method,
                    url: url,
                    data: formData,
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeBrandModal();

                    // Refresh all data from APIs
                    await Promise.all([
                        loadBrandsData(),
                        loadStatistics()
                    ]);
                }
            } catch (error) {
                if (error.response?.status === 422) {
                    // Validation errors
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const errorElement = document.getElementById(field + 'Error');
                        if (errorElement) {
                            errorElement.textContent = errors[field][0];
                            errorElement.classList.remove('hidden');
                        }
                    });
                    toastr.error('Please fix the validation errors');
                } else {
                    toastr.error(error.response?.data?.message || 'Failed to save brand');
                }
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Edit brand
        async function editBrand(id) {
            try {
                const response = await axiosInstance.get(`brands/${id}`);

                if (response.data.success) {
                    const brand = response.data.data;
                    isEditing = true;

                    // Fill form
                    document.getElementById('brandId').value = brand.id;
                    document.getElementById('name').value = brand.name;
                    document.getElementById('slug').value = brand.slug;
                    document.getElementById('description').value = brand.description || '';
                    document.getElementById('website').value = brand.website || '';
                    document.getElementById('email').value = brand.email || '';
                    document.getElementById('phone').value = brand.phone || '';
                    document.getElementById('country').value = brand.country || '';
                    document.getElementById('address').value = brand.address || '';
                    document.getElementById('meta_title').value = brand.meta_title || '';
                    document.getElementById('meta_description').value = brand.meta_description || '';
                    document.getElementById('meta_keywords').value = brand.meta_keywords || '';
                    document.getElementById('sort_order').value = brand.sort_order || 0;
                    document.getElementById('featured').checked = brand.featured === true || brand.featured === 'true';
                    document.getElementById('status').checked = brand.status === 'active';

                    // Set logo preview
                    const preview = document.getElementById('logoPreview');
                    if (brand.logo) {
                        document.getElementById('logo_url').value = brand.logo;
                        preview.innerHTML =
                            `<img src="${brand.logo}" alt="${brand.name}" class="w-full h-full object-cover">`;
                    } else {
                        document.getElementById('logo_url').value = '';
                        preview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
                    }

                    // Update UI
                    document.getElementById('modalTitle').textContent = 'Edit Brand';
                    document.getElementById('submitText').textContent = 'Update Brand';
                    document.getElementById('brandModal').classList.remove('hidden');
                }
            } catch (error) {
                console.error('Error editing brand:', error);
                toastr.error('Failed to load brand details');
            }
        }

        // View brand details
        async function viewBrand(id) {
            try {
                const response = await axiosInstance.get(`brands/${id}`);

                if (response.data.success) {
                    const brand = response.data.data;

                    let logoHtml = brand.logo ?
                        `<img src="${brand.logo}" alt="${brand.name}" class="w-24 h-24 rounded-lg object-cover mb-4">` :
                        '';

                    Swal.fire({
                        title: brand.name,
                        html: `
                    <div class="text-left">
                        ${logoHtml}
                        <p class="mb-3"><strong>Description:</strong> ${brand.description || 'No description'}</p>
                        <p class="mb-3"><strong>Website:</strong> ${brand.website ? `<a href="${brand.website}" target="_blank" class="text-indigo-600">${brand.website}</a>` : 'N/A'}</p>
                        <p class="mb-3"><strong>Email:</strong> ${brand.email || 'N/A'}</p>
                        <p class="mb-3"><strong>Phone:</strong> ${brand.phone || 'N/A'}</p>
                        <p class="mb-3"><strong>Location:</strong> ${brand.country || 'N/A'} ${brand.address ? `- ${brand.address}` : ''}</p>
                        <p class="mb-1"><strong>Status:</strong> <span class="capitalize">${brand.status}</span></p>
                        <p class="mb-3"><strong>Featured:</strong> ${brand.featured ? 'Yes' : 'No'}</p>
                        <p class="mb-3"><strong>Products:</strong> ${brand.product_count}</p>
                        <p class="mb-3"><strong>Created:</strong> ${new Date(brand.created_at).toLocaleDateString()}</p>
                    </div>
                `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Edit',
                        cancelButtonText: 'Close',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            editBrand(id);
                        }
                    });
                }
            } catch (error) {
                toastr.error('Failed to load brand details');
            }
        }

        // Delete brand
        async function deleteBrand(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will delete the brand. Products will not be deleted but will lose brand association.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.delete(`brands/${id}`);

                    if (response.data.success) {
                        toastr.success(response.data.message);

                        // Refresh all data from APIs
                        await Promise.all([
                            loadBrandsData(),
                            loadStatistics()
                        ]);
                    } else {
                        toastr.error(response.data.message || 'Failed to delete brand');
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Failed to delete brand');
                }
            }
        }

        // Toggle brand status
        async function toggleBrandStatus(id, isActive) {
            const result = await Swal.fire({
                title: 'Confirm Status Change',
                text: `Are you sure you want to ${isActive ? 'activate' : 'deactivate'} this brand?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: `Yes, ${isActive ? 'activate' : 'deactivate'}`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: isActive ? '#10b981' : '#ef4444'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(`brands/${id}/status`, {
                        status: isActive ? 'active' : 'inactive'
                    });

                    if (response.data.success) {
                        toastr.success(`Brand ${isActive ? 'activated' : 'deactivated'} successfully!`);
                        loadBrandsData();
                    } else {
                        toastr.error('Failed to update brand status');
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Failed to update brand status');
                }
            } else {
                // Revert the switch by reloading data
                loadBrandsData();
            }
        }

        // Toggle featured status
        async function toggleFeatured(id, newFeatured = null) {
            // Get current featured status from table
            const row = brandsTable.getRow(parseInt(id));
            if (!row) return;

            const data = row.getData();
            const currentFeatured = data.featured === true || data.featured === 'true';

            // If newFeatured is not provided, toggle it
            if (newFeatured === null) {
                newFeatured = !currentFeatured;
            }

            const action = newFeatured ? 'feature' : 'unfeature';

            const result = await Swal.fire({
                title: 'Update Featured Status',
                text: `Are you sure you want to ${action} "${data.name}"?`,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: `Yes, ${action}`,
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(`brands/${id}/featured`, {
                        featured: newFeatured
                    });

                    if (response.data.success) {
                        toastr.success(`Brand ${action}ed successfully!`);
                        loadBrandsData();
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || 'Failed to update featured status');
                }
            }
        }

        // Apply bulk action
        async function applyBulkAction(action) {
            const selectedRows = brandsTable.getSelectedRows();
            const selectedIds = selectedRows.map(row => row.getData().id);

            if (selectedIds.length === 0) {
                toastr.warning('No brands selected');
                return;
            }

            let endpoint, payload, message;

            switch (action) {
                case 'activate':
                    endpoint = 'brands/bulk-status';
                    payload = {
                        ids: selectedIds,
                        status: 'active'
                    };
                    message = 'Activate';
                    break;
                case 'deactivate':
                    endpoint = 'brands/bulk-status';
                    payload = {
                        ids: selectedIds,
                        status: 'inactive'
                    };
                    message = 'Deactivate';
                    break;
                case 'feature':
                    endpoint = 'brands/bulk-featured';
                    payload = {
                        ids: selectedIds,
                        featured: true
                    };
                    message = 'Mark as featured';
                    break;
                case 'unfeature':
                    endpoint = 'brands/bulk-featured';
                    payload = {
                        ids: selectedIds,
                        featured: false
                    };
                    message = 'Remove from featured';
                    break;
                case 'delete':
                    endpoint = 'brands/bulk-delete';
                    payload = {
                        ids: selectedIds
                    };
                    message = 'Delete';
                    break;
                default:
                    return;
            }

            const result = await Swal.fire({
                title: 'Confirm Bulk Action',
                text: `Are you sure you want to ${message.toLowerCase()} ${selectedIds.length} brand(s)?`,
                icon: action === 'delete' ? 'warning' : 'info',
                showCancelButton: true,
                confirmButtonText: `Yes, ${message}`,
                cancelButtonText: 'Cancel',
                confirmButtonColor: action === 'delete' ? '#ef4444' : '#3b82f6'
            });

            if (result.isConfirmed) {
                try {
                    const response = await axiosInstance.post(endpoint, payload);

                    if (response.data.success) {
                        toastr.success(response.data.message);
                        closeBulkActions();
                        await loadBrandsData();
                    }
                } catch (error) {
                    toastr.error(error.response?.data?.message || `Failed to ${action === 'delete' ? 'delete' : 'update'} brands`);
                }
            }
        }

        // Refresh all data
        async function refreshAll() {
            try {
                await Promise.all([
                    loadBrandsData(),
                    loadStatistics()
                ]);
                toastr.info('Data refreshed');
            } catch (error) {
                toastr.error('Failed to refresh data');
            }
        }
    </script>
@endpush
