@extends('admin.layouts.master')

@section('title', 'Product Tags Management')

@section('content')
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Product Tags Management</h2>
                <p class="text-gray-600">Manage tags for categorizing and organizing products</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Products
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Tags</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="totalTags">0</p>
                </div>
                <div class="p-3 bg-indigo-50 rounded-xl">
                    <i class="fas fa-tags text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Tags</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="activeTags">0</p>
                </div>
                <div class="p-3 bg-emerald-50 rounded-xl">
                    <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Featured Tags</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="featuredTags">0</p>
                </div>
                <div class="p-3 bg-amber-50 rounded-xl">
                    <i class="fas fa-star text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Popular Tag</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1" id="popularTag">-</p>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl">
                    <i class="fas fa-fire text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-800">All Tags</h3>
                        <div class="flex space-x-2">
                            <button id="tagsBulkActionsBtn" class="btn-secondary">
                                <i class="fas fa-bolt mr-2"></i>Bulk Actions
                            </button>
                            <button id="tagsColumnVisibilityBtn" class="btn-secondary">
                                <i class="fas fa-columns mr-2"></i>Columns
                            </button>
                            <div class="relative group">
                                <button id="tagsExportBtn" class="btn-primary">
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
                </div>
                <div class="p-6">
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="tagsSearchInput" placeholder="Search tags..."
                                class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>

                    <!-- Tabulator Table Container -->
                    <div id="tagsTable" class="w-full overflow-x-auto"></div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800" id="formTitle">Add New Tag</h3>
                </div>
                <form id="tagForm" class="p-6">
                    <input type="hidden" id="tagId" name="id" value="">

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tag Name *</label>
                            <input type="text" id="tagName" name="name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="e.g., Summer Sale, New Arrival, Bestseller" required>
                            <p class="text-xs text-red-500 mt-1 hidden" id="nameError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Slug *</label>
                            <input type="text" id="tagSlug" name="slug"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="summer-sale" required>
                            <p class="text-xs text-red-500 mt-1 hidden" id="slugError"></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="tagDescription" name="description" rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                                placeholder="Brief description about this tag"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Color</label>
                                <div class="flex space-x-2">
                                    <div class="relative">
                                        <input type="color" id="tagColor" name="color" value="#3b82f6"
                                            class="w-12 h-12 cursor-pointer rounded-lg border border-gray-300">
                                    </div>
                                    <div class="flex-1">
                                        <input type="text" id="colorHex" value="#3b82f6"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                            placeholder="#3b82f6">
                                        <p class="text-xs text-gray-500 mt-1">Hex code</p>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Icon</label>
                                <select id="tagIcon" name="icon"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="">No Icon</option>
                                    <option value="fas fa-tag">Tag</option>
                                    <option value="fas fa-star">Star</option>
                                    <option value="fas fa-fire">Fire</option>
                                    <option value="fas fa-bolt">Bolt</option>
                                    <option value="fas fa-percentage">Percentage</option>
                                    <option value="fas fa-gift">Gift</option>
                                    <option value="fas fa-heart">Heart</option>
                                    <option value="fas fa-shopping-cart">Cart</option>
                                    <option value="fas fa-truck">Truck</option>
                                    <option value="fas fa-award">Award</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="tagFeatured" name="featured"
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Featured Tag</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1 ml-6">Show in featured section</p>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="tagStatus" name="status" checked
                                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <span class="ml-2 text-sm text-gray-700">Active</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1 ml-6">Tag will be visible</p>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-200">
                            <div class="flex justify-end space-x-3">
                                <button type="button" id="cancelBtn" onclick="resetForm()"
                                    class="btn-secondary hidden">
                                    Cancel
                                </button>
                                <button type="submit" class="btn-primary">
                                    <i class="fas fa-save mr-2"></i>
                                    <span id="submitText">Save Tag</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Popular Tags</h3>
                </div>
                <div class="p-6">
                    <div id="popularTags" class="flex flex-wrap gap-2">
                        <div class="text-center py-4 text-gray-500 w-full">
                            <div class="animate-spin inline-block w-8 h-8 border-4 border-indigo-600 border-t-transparent rounded-full mb-2"></div>
                            <p class="text-sm">Loading popular tags...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bulk Actions Modal -->
    <div id="bulkActionsModal"
        class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
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
                <div class="mt-6 flex justify-center">
                    <button onclick="closeBulkActions()" class="btn-secondary">
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

        /* Responsive table container */
        @media (max-width: 768px) {
            .tabulator {
                font-size: 14px;
            }

            .tabulator .tabulator-header .tabulator-col {
                min-width: 80px;
            }
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
    let tagsTable = null;
    let isEditing = false;

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing tags module...');

        // Initialize Tabulator first with empty data
        initializeTagsTable([]);

        // Then load all data
        refreshAllData();

        setupFormEventListeners();
        setupTableControls();
        setupBulkActionsModal();
    });

    // ==================== DATA LOADING FUNCTIONS ====================

    // Refresh all data
    async function refreshAllData() {
        console.log('Refreshing all data...');

        try {
            await Promise.all([
                loadTagsData(),
                loadStatistics(),
                loadPopularTags()
            ]);
            console.log('All data refreshed successfully');
        } catch (error) {
            console.error('Error refreshing data:', error);
        }
    }

    // Load tags data and update Tabulator
    async function loadTagsData() {
        console.log('Loading tags data from API...');

        try {
            const response = await axiosInstance.get('tags');
            console.log('Tags API Response:', response.data);

            if (response.data.success) {
                const tags = response.data.data?.data || response.data.data || [];
                console.log('Loaded', tags.length, 'tags');

                // Update Tabulator data
                if (tagsTable) {
                    tagsTable.setData(tags);
                }
            } else {
                console.error('Failed to load tags:', response.data.message);
            }
        } catch (error) {
            console.error('Error loading tags:', error);
        }
    }

    // Load statistics from API
    async function loadStatistics() {
        console.log('Loading statistics from API...');

        try {
            const response = await axiosInstance.get('tags/statistics');
            console.log('Statistics API Response:', response.data);

            if (response.data.success) {
                const stats = response.data.data;
                updateElementText('totalTags', stats.total_tags || 0);
                updateElementText('activeTags', stats.active_tags || 0);
                updateElementText('featuredTags', stats.featured_tags || 0);
                updateElementText('popularTag', stats.popular_tag ? stats.popular_tag.name : '-');
            }
        } catch (error) {
            console.error('Error loading statistics:', error);
        }
    }

    // Load popular tags from API
    async function loadPopularTags() {
        console.log('Loading popular tags from API...');
        const container = document.getElementById('popularTags');

        try {
            const response = await axiosInstance.get('tags/popular');

            if (response.data.success && response.data.data.length > 0) {
                container.innerHTML = '';
                response.data.data.forEach(tag => {
                    const tagBadge = document.createElement('span');
                    tagBadge.className = 'inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium cursor-pointer hover:opacity-90 transition-opacity mb-2';
                    tagBadge.style.backgroundColor = `${tag.color || '#3b82f6'}22`;
                    tagBadge.style.color = tag.color || '#3b82f6';
                    tagBadge.innerHTML = `
                        ${tag.icon ? `<i class="${tag.icon} mr-1.5"></i>` : ''}
                        ${tag.name}
                        <span class="ml-1.5 text-xs opacity-75">${tag.product_count}</span>
                    `;
                    tagBadge.onclick = () => viewTagDetails(tag.id);
                    container.appendChild(tagBadge);
                });
            } else {
                container.innerHTML = `
                    <div class="text-center py-4 text-gray-500 w-full">
                        <p class="text-sm">No popular tags yet</p>
                    </div>
                `;
            }
        } catch (error) {
            console.error('Error loading popular tags:', error);
            container.innerHTML = `
                <div class="text-center py-4 text-gray-500 w-full">
                    <p class="text-sm text-red-500">Failed to load popular tags</p>
                </div>
            `;
        }
    }

    // ==================== TABULATOR INITIALIZATION ====================

    // Initialize Tabulator table
    function initializeTagsTable(data) {
        console.log('Initializing Tabulator table...');

        if (tagsTable) {
            tagsTable.destroy();
        }

        tagsTable = new Tabulator("#tagsTable", {
            data: data,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            paginationSizeSelector: [10, 20, 50, 100],
            movableColumns: true,
            selectable: true,
            selectableRangeMode: "click",
            placeholder: "No tags found",
            columns: [
                {
                    title: "<input type='checkbox' id='selectAllTags'>",
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
                    title: "Tag",
                    field: "name",
                    sorter: "string",
                    width: 250,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Tag…",
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        const color = rowData.color || '#3b82f6';

                        return `
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center"
                                     style="background-color: ${color}22">
                                    ${rowData.icon ?
                                        `<i class="${rowData.icon}" style="color: ${color}"></i>` :
                                        `<i class="fas fa-tag" style="color: ${color}"></i>`
                                    }
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">${rowData.name}</p>
                                    <p class="text-sm text-gray-500 truncate max-w-xs">${rowData.description || ''}</p>
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    title: "Slug",
                    field: "slug",
                    width: 150,
                    sorter: "string",
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Slug…",
                    responsive: 1
                },
                {
                    title: "Products",
                    field: "product_count",
                    width: 120,
                    sorter: "number",
                    hozAlign: "center",
                    formatter: function(cell) {
                        const count = cell.getValue() || 0;
                        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            ${count}
                        </span>`;
                    },
                    responsive: 1
                },
                {
                    title: "Status",
                    field: "status",
                    width: 120,
                    hozAlign: "center",
                    headerFilter: "list",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Active",
                            "false": "Inactive",
                        }
                    },
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        const isActive = rowData.status === true || rowData.status === 1;
                        return `
                            <label class="switch">
                                <input type="checkbox" class="toggle-tag-status"
                                       data-id="${rowData.id}" ${isActive ? 'checked' : ''}>
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
                    headerFilter: "list",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "true": "Featured",
                            "false": "Not Featured",
                        }
                    },
                    formatter: function(cell) {
                        const rowData = cell.getRow().getData();
                        const isFeatured = rowData.featured === true || rowData.featured === 1;
                        return `
                            <button onclick="toggleFeatured(${rowData.id})"
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
                    sorter: "string",
                    formatter: function(cell) {
                        const date = cell.getValue();
                        return date || 'N/A';
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
                                <button onclick="editTag(${id})"
                                        class="w-8 h-8 flex items-center justify-center bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteTag(${id})"
                                        class="w-8 h-8 flex items-center justify-center bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-colors">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        `;
                    },
                    responsive: 0
                }
            ]
        });

        // Set up table events
        tagsTable.on("tableBuilt", function() {
            console.log("Tabulator table built successfully");
            setupTabulatorEvents();
        });
    }

    // ==================== FORM HANDLING ====================

    // Setup form event listeners
    function setupFormEventListeners() {
        // Auto-generate slug from name
        safeAddEventListener('tagName', 'blur', function() {
            if (!isEditing && this.value) {
                const slugInput = document.getElementById('tagSlug');
                if (slugInput && !slugInput.value) {
                    const slug = this.value.toLowerCase()
                        .replace(/[^a-z0-9 -]/g, '')
                        .replace(/\s+/g, '-')
                        .replace(/-+/g, '-');
                    slugInput.value = slug;
                }
            }
        });

        // Color picker synchronization
        safeAddEventListener('tagColor', 'input', function() {
            const colorHex = document.getElementById('colorHex');
            if (colorHex) colorHex.value = this.value;
        });

        safeAddEventListener('colorHex', 'input', function() {
            const color = this.value;
            if (/^#[0-9A-F]{6}$/i.test(color)) {
                const tagColor = document.getElementById('tagColor');
                if (tagColor) tagColor.value = color;
            }
        });

        // Form submission
        const tagForm = document.getElementById('tagForm');
        if (tagForm) {
            tagForm.addEventListener('submit', function(e) {
                e.preventDefault();
                saveTag();
            });
        }

        // Clear errors on input
        ['tagName', 'tagSlug'].forEach(fieldId => {
            safeAddEventListener(fieldId, 'input', function() {
                const errorId = fieldId.replace('tag', '').toLowerCase() + 'Error';
                const errorElement = document.getElementById(errorId);
                if (errorElement) {
                    errorElement.classList.add('hidden');
                    errorElement.textContent = '';
                }
            });
        });
    }

    // Save tag (create or update)
    async function saveTag() {
        // Get form elements
        const tagName = getElementValue('tagName');
        const tagSlug = getElementValue('tagSlug');
        const tagDescription = getElementValue('tagDescription');
        const tagColor = getElementValue('tagColor') || '#3b82f6';
        const tagIcon = getElementValue('tagIcon') || '';
        const tagFeatured = getCheckboxValue('tagFeatured');
        const tagStatus = getCheckboxValue('tagStatus');
        const tagId = getElementValue('tagId');

        // Clear previous errors
        clearErrors(['nameError', 'slugError']);

        // Validation
        if (!tagName) {
            showError('nameError', 'Tag name is required');
            toastr.error('Tag name is required');
            return;
        }

        if (!tagSlug) {
            showError('slugError', 'Slug is required');
            toastr.error('Slug is required');
            return;
        }

        // Prepare payload
        const dataToSend = {
            name: tagName,
            slug: tagSlug,
            description: tagDescription,
            color: tagColor,
            icon: tagIcon,
            featured: tagFeatured,
            status: tagStatus
        };

        // Remove empty values
        Object.keys(dataToSend).forEach(key => {
            if (dataToSend[key] === '' || dataToSend[key] === null || dataToSend[key] === undefined) {
                delete dataToSend[key];
            }
        });

        const method = tagId ? 'put' : 'post';
        const url = tagId ? `tags/${tagId}` : 'tags';

        // Button loading state
        const submitBtn = document.querySelector('#tagForm button[type="submit"]');
        if (!submitBtn) {
            toastr.error('Submit button not found');
            return;
        }

        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        submitBtn.disabled = true;

        try {
            const response = await axiosInstance[method](url, dataToSend);

            if (response.data.success) {
                toastr.success(response.data.message || 'Tag saved successfully');
                resetForm();
                await refreshAllData();
            } else {
                toastr.error(response.data.message || 'Failed to save tag');
            }
        } catch (error) {
            console.error('Save tag error:', error);

            // Check for validation errors
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                Object.keys(errors).forEach(field => {
                    showError(field + 'Error', errors[field][0]);
                });
                toastr.error('Please fix the validation errors');
            } else {
                const errorMsg = error.response?.data?.message || 'Failed to save tag. Please try again.';
                toastr.error(errorMsg);
            }
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    }

    // Edit tag
    async function editTag(id) {
        try {
            const response = await axiosInstance.get(`/tags/${id}`);

            if (response.data.success) {
                const tag = response.data.data;
                isEditing = true;

                // Fill form
                setElementValue('tagId', tag.id);
                setElementValue('tagName', tag.name);
                setElementValue('tagSlug', tag.slug);
                setElementValue('tagDescription', tag.description || '');
                setElementValue('tagColor', tag.color || '#3b82f6');
                setElementValue('colorHex', tag.color || '#3b82f6');
                setElementValue('tagIcon', tag.icon || '');
                setCheckboxValue('tagFeatured', tag.featured === true || tag.featured === 1);
                setCheckboxValue('tagStatus', tag.status === true || tag.status === 1);

                // Update UI
                updateElementText('formTitle', 'Edit Tag');
                updateElementText('submitText', 'Update Tag');
                showElement('cancelBtn');

                // Scroll to form
                const tagForm = document.querySelector('#tagForm');
                if (tagForm) {
                    tagForm.scrollIntoView({ behavior: 'smooth' });
                }
            }
        } catch (error) {
            console.error('Error editing tag:', error);
            toastr.error('Failed to load tag details');
        }
    }

    // Reset form
    function resetForm() {
        const form = document.getElementById('tagForm');
        if (form) form.reset();

        setElementValue('tagId', '');
        setElementValue('tagColor', '#3b82f6');
        setElementValue('colorHex', '#3b82f6');
        setCheckboxValue('tagStatus', true);
        setCheckboxValue('tagFeatured', false);
        setElementValue('tagIcon', '');

        // Update UI
        updateElementText('formTitle', 'Add New Tag');
        updateElementText('submitText', 'Save Tag');
        hideElement('cancelBtn');
        isEditing = false;

        // Clear errors
        clearErrors(['nameError', 'slugError']);
    }

    // ==================== TABLE CONTROLS ====================

    // Setup table controls
    function setupTableControls() {
        // Search functionality
        const searchInput = document.getElementById('tagsSearchInput');
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('keyup', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value;
                    if (searchTerm.length >= 2 || searchTerm === '') {
                        tagsTable.setFilter([
                            { field: "name", type: "like", value: searchTerm },
                            { field: "slug", type: "like", value: searchTerm },
                            { field: "description", type: "like", value: searchTerm }
                        ]);
                    }
                }, 500);
            });
        }
    }

    // Setup Tabulator events
    function setupTabulatorEvents() {
        // Status toggle event delegation
        document.addEventListener('change', function(e) {
            if (e.target && e.target.classList.contains('toggle-tag-status')) {
                const tagId = e.target.getAttribute('data-id');
                const isActive = e.target.checked;
                toggleTagStatus(tagId, isActive);
            }
        });

        // Select all checkbox
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'selectAllTags') {
                if (e.target.checked) {
                    tagsTable.selectRow();
                } else {
                    tagsTable.deselectRow();
                }
            }
        });
    }

    // Setup column visibility
    function setupColumnVisibility() {
        const columnBtn = document.getElementById('tagsColumnVisibilityBtn');
        if (!columnBtn) return;

        // Remove existing menu if any
        const existingMenu = document.getElementById('columnVisibilityMenu');
        if (existingMenu) existingMenu.remove();

        // Create menu
        const columnMenu = document.createElement('div');
        columnMenu.id = 'columnVisibilityMenu';
        columnMenu.className = 'absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden';
        columnMenu.style.right = '0';

        const columns = tagsTable.getColumnDefinitions();

        columns.forEach((column, index) => {
            if (index === 0) return; // Skip checkbox column

            const field = column.field;
            if (!field) return;

            const columnBtn = document.createElement('button');
            columnBtn.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm flex items-center';
            columnBtn.innerHTML = `
                <input type="checkbox" class="mr-2" ${tagsTable.getColumn(field).isVisible() ? 'checked' : ''}>
                ${column.title}
            `;

            columnBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                const col = tagsTable.getColumn(field);
                const checkbox = this.querySelector('input');
                col.toggle();
                setTimeout(() => {
                    checkbox.checked = col.isVisible();
                }, 10);
            });

            columnMenu.appendChild(columnBtn);
        });

        // Toggle menu
        columnBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            columnMenu.classList.toggle('hidden');

            // Position menu
            const rect = columnBtn.getBoundingClientRect();
            columnMenu.style.top = `${rect.bottom + window.scrollY}px`;
            columnMenu.style.left = `${rect.left + window.scrollX}px`;
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!columnMenu.contains(e.target) && e.target !== columnBtn) {
                columnMenu.classList.add('hidden');
            }
        });

        document.body.appendChild(columnMenu);
    }

    // Setup export functionality
    function setupExportFunctionality() {
        const exportBtn = document.getElementById('tagsExportBtn');
        if (!exportBtn) return;

        // Remove existing dropdown if any
        const existingDropdown = document.getElementById('exportDropdown');
        if (existingDropdown) existingDropdown.remove();

        // Create dropdown
        const exportDropdown = document.createElement('div');
        exportDropdown.id = 'exportDropdown';
        exportDropdown.className = 'absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden';
        exportDropdown.style.right = '0';

        const exportOptions = [
            { format: 'csv', label: 'CSV', icon: 'fas fa-file-csv' },
            { format: 'xlsx', label: 'Excel', icon: 'fas fa-file-excel' },
            { format: 'pdf', label: 'PDF', icon: 'fas fa-file-pdf' },
            { format: 'print', label: 'Print', icon: 'fas fa-print' }
        ];

        exportOptions.forEach(option => {
            const optionBtn = document.createElement('button');
            optionBtn.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm flex items-center';
            optionBtn.innerHTML = `
                <i class="${option.icon} mr-2"></i>${option.label}
            `;

            optionBtn.addEventListener('click', function() {
                exportData(option.format);
                exportDropdown.classList.add('hidden');
            });

            exportDropdown.appendChild(optionBtn);
        });

        // Toggle dropdown
        exportBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            exportDropdown.classList.toggle('hidden');

            // Position dropdown
            const rect = exportBtn.getBoundingClientRect();
            exportDropdown.style.top = `${rect.bottom + window.scrollY}px`;
            exportDropdown.style.left = `${rect.left + window.scrollX}px`;
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!exportDropdown.contains(e.target) && e.target !== exportBtn) {
                exportDropdown.classList.add('hidden');
            }
        });

        document.body.appendChild(exportDropdown);
    }

    // Export data
    function exportData(format) {
        if (!tagsTable) {
            toastr.error('Table not initialized');
            return;
        }

        try {
            switch (format) {
                case 'csv':
                    tagsTable.download("csv", "tags.csv");
                    toastr.success('CSV exported successfully');
                    break;
                case 'xlsx':
                    tagsTable.download("xlsx", "tags.xlsx", { sheetName: "Tags" });
                    toastr.success('Excel exported successfully');
                    break;
                case 'pdf':
                    // For PDF export, you might need a PDF library
                    toastr.info('PDF export requires additional setup');
                    break;
                case 'print':
                    tagsTable.print(false, true);
                    break;
                default:
                    toastr.error('Unknown export format');
            }
        } catch (error) {
            console.error('Export error:', error);
            toastr.error('Failed to export data');
        }
    }

    // ==================== TAG OPERATIONS ====================

    // Delete tag
    async function deleteTag(id) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will delete the tag and remove it from all associated products.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.delete(`/tags/${id}`);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    await refreshAllData();
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || 'Failed to delete tag';
                toastr.error(errorMsg);
            }
        }
    }

    // Toggle tag status
    async function toggleTagStatus(id, isActive) {
        try {
            const response = await axiosInstance.post(`/tags/${id}/status`, {
                status: isActive
            });

            if (response.data.success) {
                toastr.success(`Tag ${isActive ? 'activated' : 'deactivated'} successfully!`);
                await refreshAllData();
            } else {
                toastr.error('Failed to update tag status');
                await loadTagsData();
            }
        } catch (error) {
            console.error('Error updating status:', error);
            const errorMsg = error.response?.data?.message || 'Failed to update tag status';
            toastr.error(errorMsg);
            await loadTagsData();
        }
    }

    // Toggle featured status
    async function toggleFeatured(id) {
        // Get current featured status
        const row = tagsTable.getRow(id);
        if (!row) return;

        const data = row.getData();
        const currentFeatured = data.featured === true || data.featured === 1;
        const newFeatured = !currentFeatured;

        const result = await Swal.fire({
            title: 'Update Featured Status',
            text: `Are you sure you want to ${newFeatured ? 'feature' : 'unfeature'} "${data.name}"?`,
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: `Yes, ${newFeatured ? 'feature' : 'unfeature'}`,
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post(`/tags/${id}/featured`, {
                    featured: newFeatured
                });

                if (response.data.success) {
                    toastr.success(`Tag ${newFeatured ? 'featured' : 'unfeatured'} successfully!`);
                    await refreshAllData();
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || 'Failed to update featured status';
                toastr.error(errorMsg);
            }
        }
    }

    // View tag details
    async function viewTagDetails(id) {
        try {
            const response = await axiosInstance.get(`/tags/${id}`);

            if (response.data.success) {
                const tag = response.data.data;

                Swal.fire({
                    title: tag.name,
                    html: `
                        <div class="text-left">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="w-12 h-12 rounded-lg flex items-center justify-center" style="background-color: ${tag.color || '#3b82f6'}22">
                                    ${tag.icon ? `<i class="${tag.icon} text-xl" style="color: ${tag.color || '#3b82f6'}"></i>` : ''}
                                </div>
                                <div>
                                    <p class="font-semibold">${tag.slug}</p>
                                    <p class="text-sm text-gray-500">${tag.product_count || 0} products</p>
                                </div>
                            </div>
                            <p class="mb-3"><strong>Description:</strong> ${tag.description || 'No description'}</p>
                            <p class="mb-1"><strong>Status:</strong> <span class="capitalize">${tag.status ? 'Active' : 'Inactive'}</span></p>
                            <p class="mb-3"><strong>Featured:</strong> ${tag.featured ? 'Yes' : 'No'}</p>
                            <p class="mb-3"><strong>Created:</strong> ${new Date(tag.created_at).toLocaleDateString()}</p>
                        </div>
                    `,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Edit',
                    cancelButtonText: 'Close',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        editTag(id);
                    }
                });
            }
        } catch (error) {
            toastr.error('Failed to load tag details');
        }
    }

    // ==================== BULK ACTIONS ====================

    // Setup bulk actions modal
    function setupBulkActionsModal() {
        const bulkActionsBtn = document.getElementById('tagsBulkActionsBtn');
        if (bulkActionsBtn) {
            bulkActionsBtn.addEventListener('click', function() {
                const selectedRows = tagsTable.getSelectedRows();

                if (selectedRows.length === 0) {
                    toastr.warning('Please select at least one tag');
                    return;
                }

                document.getElementById('bulkActionsModal').classList.remove('hidden');
            });
        }
    }

    // Apply bulk action
    async function applyBulkAction(action) {
        const selectedRows = tagsTable.getSelectedRows();
        const selectedIds = selectedRows.map(row => row.getData().id);

        if (selectedIds.length === 0) {
            toastr.warning('No tags selected');
            return;
        }

        let endpoint, payload, confirmMessage;

        switch (action) {
            case 'activate':
                endpoint = '/tags/bulk-status';
                payload = { ids: selectedIds, status: true };
                confirmMessage = 'activate';
                break;
            case 'deactivate':
                endpoint = '/tags/bulk-status';
                payload = { ids: selectedIds, status: false };
                confirmMessage = 'deactivate';
                break;
            case 'feature':
                endpoint = '/tags/bulk-featured';
                payload = { ids: selectedIds, featured: true };
                confirmMessage = 'mark as featured';
                break;
            case 'unfeature':
                endpoint = '/tags/bulk-featured';
                payload = { ids: selectedIds, featured: false };
                confirmMessage = 'remove from featured';
                break;
            case 'delete':
                endpoint = '/tags/bulk-delete';
                payload = { ids: selectedIds };
                confirmMessage = 'delete';
                break;
            default:
                return;
        }

        const result = await Swal.fire({
            title: 'Confirm Bulk Action',
            text: `Are you sure you want to ${confirmMessage} ${selectedIds.length} tag(s)?`,
            icon: action === 'delete' ? 'warning' : 'info',
            showCancelButton: true,
            confirmButtonText: `Yes, ${confirmMessage}`,
            cancelButtonText: 'Cancel',
            confirmButtonColor: action === 'delete' ? '#ef4444' : '#3b82f6'
        });

        if (result.isConfirmed) {
            try {
                const response = await axiosInstance.post(endpoint, payload);

                if (response.data.success) {
                    toastr.success(response.data.message);
                    closeBulkActions();
                    await refreshAllData();
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || `Failed to ${confirmMessage} tags`;
                toastr.error(errorMsg);
            }
        }
    }

    // Close bulk actions modal
    function closeBulkActions() {
        document.getElementById('bulkActionsModal').classList.add('hidden');
    }

    // ==================== HELPER FUNCTIONS ====================

    // Safe event listener
    function safeAddEventListener(elementId, event, handler) {
        const element = document.getElementById(elementId);
        if (element) {
            element.addEventListener(event, handler);
        }
    }

    // Get element value
    function getElementValue(elementId) {
        const element = document.getElementById(elementId);
        return element ? element.value : '';
    }

    // Set element value
    function setElementValue(elementId, value) {
        const element = document.getElementById(elementId);
        if (element) element.value = value;
    }

    // Get checkbox value
    function getCheckboxValue(elementId) {
        const element = document.getElementById(elementId);
        return element ? element.checked : false;
    }

    // Set checkbox value
    function setCheckboxValue(elementId, checked) {
        const element = document.getElementById(elementId);
        if (element) element.checked = checked;
    }

    // Update element text
    function updateElementText(elementId, text) {
        const element = document.getElementById(elementId);
        if (element) element.textContent = text;
    }

    // Show element
    function showElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) element.classList.remove('hidden');
    }

    // Hide element
    function hideElement(elementId) {
        const element = document.getElementById(elementId);
        if (element) element.classList.add('hidden');
    }

    // Clear errors
    function clearErrors(errorIds) {
        errorIds.forEach(errorId => {
            const errorElement = document.getElementById(errorId);
            if (errorElement) {
                errorElement.classList.add('hidden');
                errorElement.textContent = '';
            }
        });
    }

    // Show error
    function showError(elementId, message) {
        const errorElement = document.getElementById(elementId);
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.remove('hidden');
        }
    }

    // ==================== INITIALIZE CONTROLS AFTER TABLE LOAD ====================

    // Wait for table to load then setup controls
    setTimeout(() => {
        setupColumnVisibility();
        setupExportFunctionality();
    }, 1000);

    // Manual refresh function
    async function manualRefresh() {
        await refreshAllData();
        toastr.success('Data refreshed');
    }
</script>
@endpush
