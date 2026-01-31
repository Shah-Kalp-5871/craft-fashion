@extends('admin.layouts.master')

@section('title', 'Inventory Management')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Inventory Management</h2>
            <p class="text-gray-600">Monitor and manage product stock levels</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">
                <i class="fas fa-exchange-alt mr-2"></i>Stock Adjustments
            </a>
            <a href="{{ route('admin.inventory.history') }}" class="btn-secondary">
                <i class="fas fa-history mr-2"></i>Stock History
            </a>
            <a href="{{ route('admin.products.create') }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Add Product
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Products</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="totalProducts">0</p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-xl">
                <i class="fas fa-boxes text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">In Stock</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="inStockCount">0</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <i class="fas fa-check-circle text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Low Stock</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="lowStockCount">0</p>
            </div>
            <div class="p-3 bg-amber-50 rounded-xl">
                <i class="fas fa-exclamation-triangle text-amber-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="outOfStockCount">0</p>
            </div>
            <div class="p-3 bg-rose-50 rounded-xl">
                <i class="fas fa-times-circle text-rose-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Quick Actions</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <button onclick="exportInventory()"
                class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <i class="fas fa-file-export text-gray-400 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700">Export Report</p>
                <p class="text-sm text-gray-500">CSV/Excel</p>
            </button>
            <button onclick="showLowStockAlert()"
                class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <i class="fas fa-bell text-amber-400 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700">Low Stock Alert</p>
                <p class="text-sm text-gray-500">Set threshold</p>
            </button>
            <button onclick="bulkStockUpdate()"
                class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <i class="fas fa-edit text-indigo-400 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700">Bulk Update</p>
                <p class="text-sm text-gray-500">Multiple products</p>
            </button>
            <button onclick="showStockAnalysis()"
                class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-200 rounded-xl hover:bg-gray-50 transition">
                <i class="fas fa-chart-bar text-blue-400 text-2xl mb-2"></i>
                <p class="font-medium text-gray-700">Stock Analysis</p>
                <p class="text-sm text-gray-500">View reports</p>
            </button>
        </div>
    </div>
</div>

<!-- Inventory Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Filter Inventory</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                <select id="filterStatus" onchange="filterInventory()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Status</option>
                    <option value="in_stock">In Stock</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="out_of_stock">Out of Stock</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select id="filterCategory" onchange="filterInventory()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Categories</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                <select id="filterBrand" onchange="filterInventory()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Brands</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <div class="relative">
                    <input type="text" id="searchInventory" placeholder="Search products..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Inventory Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Inventory Overview</h3>
    </div>
    <div class="p-6">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchInventoryInput" placeholder="Search inventory..."
                        class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                <!-- Bulk Update Button -->
                <button id="bulkUpdateBtn" class="btn-secondary">
                    <i class="fas fa-edit mr-2"></i>Bulk Update
                </button>
                <!-- Column Visibility Button -->
                <button id="columnVisibilityBtn" class="btn-secondary">
                    <i class="fas fa-columns mr-2"></i>Columns
                </button>
                <!-- Export Dropdown -->
                <div class="relative group">
                    <button id="exportBtn" class="btn-primary">
                        <i class="fas fa-file-export mr-2"></i>Export
                    </button>
                    <div class="absolute mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden group-hover:block 
                            right-0 md:right-0 md:left-auto left-0 md:left-auto">
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
        <div id="inventoryTable"></div>
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockUpdateModal"
    class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-md w-full">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Update Stock</h2>
            <button onclick="closeStockModal()"
                class="absolute top-4 right-6 text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <form id="stockUpdateForm" class="p-6">
            <input type="hidden" id="updateProductId">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                    <p id="updateProductName" class="font-medium text-gray-900"></p>
                    <p id="updateProductSku" class="text-sm text-gray-500"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Stock</label>
                    <p id="currentStockValue" class="text-lg font-bold text-gray-900"></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Type</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" onclick="setUpdateType('add')" id="btnAdd"
                            class="py-2 px-4 border border-gray-300 rounded-lg bg-emerald-50 text-emerald-700 font-medium hover:bg-emerald-100">
                            Add Stock
                        </button>
                        <button type="button" onclick="setUpdateType('remove')" id="btnRemove"
                            class="py-2 px-4 border border-gray-300 rounded-lg bg-rose-50 text-rose-700 font-medium hover:bg-rose-100">
                            Remove Stock
                        </button>
                        <button type="button" onclick="setUpdateType('set')" id="btnSet"
                            class="py-2 px-4 border border-gray-300 rounded-lg bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">
                            Set Stock
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2" id="quantityLabel">Quantity to
                        Add</label>
                    <input type="number" id="updateQuantity" min="0" step="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="0" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select id="updateReason"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="restock">Restock/Inventory Replenishment</option>
                        <option value="sale">Sale/Order Fulfillment</option>
                        <option value="return">Customer Return</option>
                        <option value="damage">Damaged/Defective Items</option>
                        <option value="adjustment">Stock Adjustment</option>
                        <option value="transfer">Warehouse Transfer</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="updateNotes" rows="2"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Add any additional notes..."></textarea>
                </div>
                <div id="newStockPreview" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="text-sm font-medium text-gray-700 mb-2">Stock Preview</div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Current Stock:</span>
                        <span id="previewCurrent" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Change:</span>
                        <span id="previewChange" class="font-medium"></span>
                    </div>
                    <div class="flex justify-between items-center pt-2 border-t border-gray-300">
                        <span class="text-gray-800 font-medium">New Stock:</span>
                        <span id="previewNew" class="font-bold text-lg"></span>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3 mt-6">
                <button type="button" onclick="closeStockModal()" class="btn-secondary flex-1">
                    Cancel
                </button>
                <button type="submit" class="btn-primary flex-1">
                    <i class="fas fa-save mr-2"></i>Update Stock
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const axiosInstance = axios.create({
        baseURL: '{{ url('') }}/api/admin',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
        }
    });

    // Debounce helper
    function debounce(func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    }

    let currentUpdateType = 'add';
    let stockUpdateProduct = null;
    let inventoryTable;

    // Initialize stats
    async function updateStats() {
        try {
            const response = await axiosInstance.get('inventory/statistics');
            if (response.data.success) {
                const stats = response.data.data;
                document.getElementById('totalProducts').textContent = stats.total_items;
                document.getElementById('inStockCount').textContent = stats.in_stock;
                document.getElementById('lowStockCount').textContent = stats.low_stock;
                document.getElementById('outOfStockCount').textContent = stats.out_of_stock;
            }
        } catch (error) {
            console.error('Error fetching stats:', error);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        updateStats();

        // Initialize Tabulator
        inventoryTable = new Tabulator("#inventoryTable", {
            ajaxURL: "{{ url('') }}/api/admin/inventory",
            ajaxConfig: {
                headers: {
                    'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            },
            ajaxResponse: function(url, params, response) {
                if (!response.success || !response.data) {
                    return { last_page: 1, data: [] };
                }
                return {
                    last_page: response.data.meta?.last_page || 1,
                    data: Array.isArray(response.data.data) ? response.data.data : []
                };
            },
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "remote",
            paginationSize: 10,
            movableColumns: true,
            paginationSizeSelector: [10, 20, 50, 100],
            selectable: true,
            selectableRangeMode: "click",
            columns: [
                {
                    title: "<input type='checkbox' id='selectAllInventory'>",
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
                    title: "Product",
                    field: "name",
                    sorter: "string",
                    responsive: 0,
                    width: 250,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Product...",
                    formatter: function (cell, formatterParams, onRendered) {
                        const rowData = cell.getRow().getData();
                        return `
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center overflow-hidden">
                                ${rowData.image ?
                                `<img src="${rowData.image}" class="w-full h-full object-cover">` :
                                `<i class="fas fa-box text-gray-500"></i>`
                            }
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${rowData.name}</p>
                                ${rowData.variants > 0 ?
                                `<p class="text-xs text-indigo-600">${rowData.variants} variants</p>` :
                                ''
                            }
                            </div>
                        </div>
                    `;
                    }
                },
                {
                    title: "SKU",
                    field: "sku",
                    sorter: "string",
                    width: 150,
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search SKU..."
                },
                {
                    title: "Category",
                    field: "category_name",
                    sorter: "string",
                    width: 150,
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Category..."
                },
                {
                    title: "Brand",
                    field: "brand_name",
                    sorter: "string",
                    width: 120,
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Brand..."
                },
                {
                    title: "Current Stock",
                    field: "current_stock",
                    sorter: "number",
                    width: 150,
                    responsive: 0,
                    headerFilter: "number",
                    headerFilterPlaceholder: "Search Stock...",
                    formatter: function (cell) {
                        const rowData = cell.getRow().getData();
                        const stockClass = rowData.status === 'in_stock' ? 'text-emerald-600' :
                            rowData.status === 'low_stock' ? 'text-amber-600' : 'text-rose-600';
                        return `<span class="font-bold text-lg ${stockClass}">${cell.getValue()}</span>`;
                    }
                },
                {
                    title: "Min Stock",
                    field: "min_stock",
                    sorter: "number",
                    width: 120,
                    responsive: 0,
                    headerFilter: "number",
                    headerFilterPlaceholder: "Search Min..."
                },
                {
                    title: "Status",
                    field: "status",
                    width: 140,
                    responsive: 0,
                    hozAlign: "center",
                    headerFilter: "list",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "in_stock": "In Stock",
                            "low_stock": "Low Stock",
                            "out_of_stock": "Out of Stock"
                        }
                    },
                    formatter: function (cell) {
                        const value = cell.getValue();
                        let badgeClass, badgeText;
                        switch (value) {
                            case 'in_stock':
                                badgeClass = 'bg-emerald-100 text-emerald-800';
                                badgeText = 'In Stock';
                                break;
                            case 'low_stock':
                                badgeClass = 'bg-amber-100 text-amber-800';
                                badgeText = 'Low Stock';
                                break;
                            case 'out_of_stock':
                                badgeClass = 'bg-rose-100 text-rose-800';
                                badgeText = 'Out of Stock';
                                break;
                            default:
                                badgeClass = 'bg-gray-100 text-gray-800';
                                badgeText = value;
                        }
                        return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
                        ${badgeText}
                    </span>`;
                    }
                },
                {
                    title: "Last Updated",
                    field: "last_updated",
                    width: 180,
                    responsive: 0,
                    sorter: "string",
                    formatter: function (cell) {
                        const date = new Date(cell.getValue());
                        return date.toLocaleDateString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                },
                {
                    title: "Actions",
                    field: "id",
                    width: 150,
                    hozAlign: "center",
                    responsive: 0,
                    headerSort: false,
                    formatter: function (cell) {
                        const id = cell.getValue();
                        return `
                        <div class="flex space-x-2 justify-center">
                            <button onclick="updateStock(${id})" class="text-indigo-600 hover:text-indigo-900">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="viewStockHistory(${id})" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-history"></i>
                            </button>
                            <button onclick="viewProductDetails(${id})" class="text-gray-600 hover:text-gray-900">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    `;
                    }
                }
            ],
            rowFormatter: function (row) {
                const rowEl = row.getElement();
                rowEl.classList.add('hover:bg-gray-50');
            }
        });

        // Initialize controls
        initInventoryControls();
        fetchDropdowns();
    });

        async function fetchDropdowns() {
            try {
                const [catRes, brandRes] = await Promise.all([
                    axiosInstance.get('categories/dropdown'),
                    axiosInstance.get('brands/dropdown')
                ]);

            const catSelect = document.getElementById('filterCategory');
            catRes.data.data.forEach(cat => {
                const opt = document.createElement('option');
                opt.value = cat.id;
                opt.textContent = cat.name;
                catSelect.appendChild(opt);
            });

            const brandSelect = document.getElementById('filterBrand');
            brandRes.data.data.forEach(brand => {
                const opt = document.createElement('option');
                opt.value = brand.id;
                opt.textContent = brand.name;
                brandSelect.appendChild(opt);
            });
        } catch (error) {
            console.error('Error fetching dropdowns:', error);
        }
    }

    function initInventoryControls() {
        // Search functionality
        const searchInput = document.getElementById('searchInventoryInput');
        searchInput.addEventListener('keyup', function () {
            inventoryTable.setFilter([
                [
                    { field: "name", type: "like", value: this.value },
                    { field: "sku", type: "like", value: this.value },
                    { field: "category", type: "like", value: this.value },
                    { field: "brand", type: "like", value: this.value }
                ]
            ]);
        });

        // Filter functionality
        window.filterInventory = function () {
            const status = document.getElementById('filterStatus').value;
            const categoryId = document.getElementById('filterCategory').value;
            const brandId = document.getElementById('filterBrand').value;
            const search = document.getElementById('searchInventory').value;

            const filters = [];

            if (status) {
                filters.push({ field: "stock_status", type: "=", value: status });
            }

            if (categoryId) {
                filters.push({ field: "category_id", type: "=", value: categoryId });
            }

            if (brandId) {
                filters.push({ field: "brand_id", type: "=", value: brandId });
            }

            if (search) {
                filters.push({ field: "search", type: "like", value: search });
            }

            inventoryTable.setFilter(filters);
        };

        document.getElementById('searchInventory').addEventListener('keyup', debounce(window.filterInventory, 300));

        // Column visibility
        const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
        const columnMenu = document.createElement('div');
        columnMenu.className = 'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

        const columns = inventoryTable.getColumnDefinitions();
        columns.forEach((column, index) => {
            if (index === 0) return; // skip checkbox column

            const field = column.field;
            const columnBtn = document.createElement('button');
            columnBtn.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
            columnBtn.innerHTML = `
            <input type="checkbox" class="mr-2" ${inventoryTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;

            columnBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                e.preventDefault();
                const col = inventoryTable.getColumn(field);
                const checkbox = this.querySelector('input');
                col.toggle();
                setTimeout(() => {
                    checkbox.checked = col.isVisible();
                }, 10);
            });

            columnMenu.appendChild(columnBtn);
        });

        columnVisibilityBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            columnMenu.classList.toggle('hidden');
        });

        document.addEventListener('click', function (e) {
            if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
                columnMenu.classList.add('hidden');
            }
        });

        columnVisibilityBtn.parentElement.appendChild(columnMenu);

        // Export functionality
        const exportBtns = document.querySelectorAll('[data-export]');
        exportBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const format = this.getAttribute('data-export');
                switch (format) {
                    case 'csv':
                        inventoryTable.download("csv", "inventory.csv");
                        break;
                    case 'xlsx':
                        inventoryTable.download("xlsx", "inventory.xlsx", { sheetName: "Inventory" });
                        break;
                    case 'print':
                        window.print();
                        break;
                }
            });
        });

        // Bulk update button
        document.getElementById('bulkUpdateBtn').addEventListener('click', showBulkActions);
    }

    // Update stock function
    function updateStock(variantId) {
        const row = inventoryTable.getRow(variantId);
        if (!row) return;
        const variant = row.getData();

        stockUpdateProduct = variant;
        currentUpdateType = 'add';

        document.getElementById('updateProductId').value = variant.id;
        document.getElementById('updateProductName').textContent = variant.name;
        document.getElementById('updateProductSku').textContent = `SKU: ${variant.sku}`;
        document.getElementById('currentStockValue').textContent = variant.current_stock;
        document.getElementById('updateQuantity').value = '';
        document.getElementById('updateNotes').value = '';

        setUpdateType('add');
        document.getElementById('stockUpdateModal').classList.remove('hidden');
    }

    function setUpdateType(type) {
        currentUpdateType = type;

        // Reset all buttons
        document.getElementById('btnAdd').classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
        document.getElementById('btnRemove').classList.remove('bg-rose-50', 'text-rose-700', 'border-rose-300');
        document.getElementById('btnSet').classList.remove('bg-indigo-50', 'text-indigo-700', 'border-indigo-300');

        document.getElementById('btnAdd').classList.add('border-gray-300');
        document.getElementById('btnRemove').classList.add('border-gray-300');
        document.getElementById('btnSet').classList.add('border-gray-300');

        // Set active button
        switch (type) {
            case 'add':
                document.getElementById('btnAdd').classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                document.getElementById('quantityLabel').textContent = 'Quantity to Add';
                break;
            case 'remove':
                document.getElementById('btnRemove').classList.add('bg-rose-50', 'text-rose-700', 'border-rose-300');
                document.getElementById('quantityLabel').textContent = 'Quantity to Remove';
                break;
            case 'set':
                document.getElementById('btnSet').classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-300');
                document.getElementById('quantityLabel').textContent = 'Set Stock To';
                break;
        }

        updateStockPreview();
    }

    function updateStockPreview() {
        if (!stockUpdateProduct) return;

        const quantity = parseInt(document.getElementById('updateQuantity').value) || 0;
        const currentStock = stockUpdateProduct.current_stock;
        let newStock = currentStock;
        let changeText = '';

        switch (currentUpdateType) {
            case 'add':
                newStock = currentStock + quantity;
                changeText = `+${quantity}`;
                break;
            case 'remove':
                newStock = currentStock - quantity;
                changeText = `-${quantity}`;
                break;
            case 'set':
                newStock = quantity;
                changeText = `Set to ${quantity}`;
                break;
        }

        const preview = document.getElementById('newStockPreview');
        if (quantity > 0) {
            preview.classList.remove('hidden');
            document.getElementById('previewCurrent').textContent = currentStock;
            document.getElementById('previewChange').textContent = changeText;
            document.getElementById('previewNew').textContent = newStock;
        } else {
            preview.classList.add('hidden');
        }
    }

    // Listen to quantity changes
    document.getElementById('updateQuantity').addEventListener('input', updateStockPreview);

    function closeStockModal() {
        document.getElementById('stockUpdateModal').classList.add('hidden');
        stockUpdateProduct = null;
    }

    // Handle form submission
    document.getElementById('stockUpdateForm').addEventListener('submit', async function (e) {
        e.preventDefault();

        const variantId = document.getElementById('updateProductId').value;
        const quantity = parseInt(document.getElementById('updateQuantity').value);
        const reason = document.getElementById('updateReason').value;
        const notes = document.getElementById('updateNotes').value;

        if (isNaN(quantity) || quantity < 0) {
            toastr.error('Please enter a valid quantity');
            return;
        }

        const typeLabel = currentUpdateType === 'add' ? 'addition' : currentUpdateType === 'remove' ? 'removal' : 'set';
        
        const { isConfirmed } = await Swal.fire({
            title: 'Confirm Stock Update',
            text: `Are you sure you want to perform this stock ${typeLabel}?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update stock',
            cancelButtonText: 'Cancel'
        });

        if (isConfirmed) {
            try {
                const response = await axiosInstance.post('inventory/update', {
                    variant_id: variantId,
                    quantity: quantity,
                    type: currentUpdateType,
                    reason: reason,
                    notes: notes
                });

                if (response.data.success) {
                    toastr.success('Stock updated successfully');
                    closeStockModal();
                    inventoryTable.setData(); // Refresh table
                    updateStats(); // Refresh stats
                }
            } catch (error) {
                console.error('Error updating stock:', error);
                toastr.error(error.response?.data?.message || 'Failed to update stock');
            }
        }
    });

    // Stock history tracking
    function addToStockHistory(productId, productName, oldStock, newStock, action, quantity, reason, notes) {
        const historyEntry = {
            id: Date.now(),
            product_id: productId,
            product_name: productName,
            old_stock: oldStock,
            new_stock: newStock,
            action: action,
            quantity: quantity,
            reason: reason,
            notes: notes,
            updated_by: 'Admin',
            updated_at: new Date().toISOString()
        };

        // Save to localStorage for demo (in real app, this would be API call)
        let history = JSON.parse(localStorage.getItem('stockHistory') || '[]');
        history.unshift(historyEntry);
        localStorage.setItem('stockHistory', JSON.stringify(history.slice(0, 100))); // Keep last 100 entries
    }

    // View stock history
    async function viewStockHistory(variantId) {
        const row = inventoryTable.getRow(variantId);
        if (!row) return;
        const variant = row.getData();

        try {
            const response = await axiosInstance.get('inventory/history', {
                params: { variant_id: variantId }
            });

            if (response.data.success) {
                const history = response.data.data.data;

                if (history.length === 0) {
                    Swal.fire({
                        title: 'No Stock History',
                        text: `No stock history found for ${variant.name}`,
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                    return;
                }

                let historyHTML = `<div class="text-left space-y-3 max-h-96 overflow-y-auto">`;

                history.forEach(entry => {
                    const actionIcon = entry.action === 'add' ? '⬆️' : entry.action === 'remove' ? '⬇️' : '⚡';
                    const actionColor = entry.action === 'add' ? 'text-emerald-600' : entry.action === 'remove' ? 'text-rose-600' : 'text-indigo-600';

                    historyHTML += `
                    <div class="p-3 border border-gray-200 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="font-medium ${actionColor}">${actionIcon} ${entry.action.toUpperCase()}</span>
                            <span class="text-sm text-gray-500">${entry.updated_at}</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-500">From:</span>
                                <span class="font-medium ml-1">${entry.old_stock}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">To:</span>
                                <span class="font-medium ml-1">${entry.new_stock}</span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 mt-2">
                            <span class="text-gray-500">Reason:</span> ${entry.reason}
                        </div>
                        ${entry.notes ? `<div class="text-sm text-gray-500 mt-1">${entry.notes}</div>` : ''}
                        <div class="text-xs text-gray-400 mt-1">Updated by: ${entry.updated_by}</div>
                    </div>
                `;
                });

                historyHTML += `</div>`;

                Swal.fire({
                    title: `Stock History: ${variant.name}`,
                    html: historyHTML,
                    width: '600px',
                    showConfirmButton: false,
                    showCloseButton: true
                });
            }
        } catch (error) {
            console.error('Error fetching stock history:', error);
            toastr.error('Failed to fetch stock history');
        }
    }

    // View product details
    function viewProductDetails(productId) {
        const product = inventoryData.find(p => p.id === productId);
        if (!product) return;

        Swal.fire({
            title: product.name,
            html: `
            <div class="text-left space-y-4">
                <div class="flex space-x-4">
                    <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden">
                        ${product.image ?
                    `<img src="${product.image}" class="w-full h-full object-cover">` :
                    `<i class="fas fa-box text-gray-400 text-2xl"></i>`
                }
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">${product.name}</p>
                        <p class="text-sm text-gray-500">${product.sku}</p>
                        <p class="text-sm text-gray-500">${product.category} • ${product.brand}</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">Current Stock</p>
                        <p class="text-xl font-bold ${product.status === 'in_stock' ? 'text-emerald-600' : product.status === 'low_stock' ? 'text-amber-600' : 'text-rose-600'}">
                            ${product.current_stock}
                        </p>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-500">Minimum Stock</p>
                        <p class="text-xl font-bold text-gray-800">${product.min_stock}</p>
                    </div>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Price:</span>
                        <span class="font-medium">$${product.price}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Variants:</span>
                        <span class="font-medium">${product.variants}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Location:</span>
                        <span class="font-medium">${product.location}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Last Updated:</span>
                        <span class="font-medium">${new Date(product.last_updated).toLocaleString()}</span>
                    </div>
                </div>
            </div>
        `,
            confirmButtonText: 'Update Stock',
            cancelButtonText: 'Close',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#9ca3af'
        }).then((result) => {
            if (result.isConfirmed) {
                updateStock(productId);
            }
        });
    }

    // Bulk Actions
    function showBulkActions() {
        const selectedRows = inventoryTable.getSelectedRows();
        const selectedData = selectedRows.map(row => row.getData());

        if (selectedData.length === 0) {
            Swal.fire({
                title: 'No Products Selected',
                text: 'Please select at least one product to perform bulk actions.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Bulk Stock Update',
            html: `
            <div class="text-left space-y-4">
                <p class="text-gray-600">${selectedData.length} product(s) selected</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Update Type</label>
                    <div class="grid grid-cols-3 gap-2">
                        <button onclick="setBulkType('add')" id="bulkBtnAdd" class="py-2 px-4 border border-gray-300 rounded-lg bg-emerald-50 text-emerald-700 font-medium hover:bg-emerald-100">
                            Add Stock
                        </button>
                        <button onclick="setBulkType('remove')" id="bulkBtnRemove" class="py-2 px-4 border border-gray-300 rounded-lg bg-rose-50 text-rose-700 font-medium hover:bg-rose-100">
                            Remove Stock
                        </button>
                        <button onclick="setBulkType('set')" id="bulkBtnSet" class="py-2 px-4 border border-gray-300 rounded-lg bg-indigo-50 text-indigo-700 font-medium hover:bg-indigo-100">
                            Set Stock
                        </button>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="bulkQuantity" min="1" step="1"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Enter quantity">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select id="bulkReason" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="restock">Restock/Inventory Replenishment</option>
                        <option value="sale">Sale/Order Fulfillment</option>
                        <option value="adjustment">Stock Adjustment</option>
                    </select>
                </div>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Apply to Selected',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const quantity = parseInt(document.getElementById('bulkQuantity').value);
                if (!quantity || quantity <= 0) {
                    Swal.showValidationMessage('Please enter a valid quantity');
                    return false;
                }
                return {
                    type: window.bulkType || 'add',
                    quantity: quantity,
                    reason: document.getElementById('bulkReason').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const selectedRows = inventoryTable.getSelectedRows();
                applyBulkUpdate(selectedRows, result.value);
            }
        });
    }

    let bulkType = 'add';
    function setBulkType(type) {
        bulkType = type;

        // Reset all buttons
        document.getElementById('bulkBtnAdd').classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
        document.getElementById('bulkBtnRemove').classList.remove('bg-rose-50', 'text-rose-700', 'border-rose-300');
        document.getElementById('bulkBtnSet').classList.remove('bg-indigo-50', 'text-indigo-700', 'border-indigo-300');

        document.getElementById('bulkBtnAdd').classList.add('border-gray-300');
        document.getElementById('bulkBtnRemove').classList.add('border-gray-300');
        document.getElementById('bulkBtnSet').classList.add('border-gray-300');

        // Set active button
        switch (type) {
            case 'add':
                document.getElementById('bulkBtnAdd').classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                break;
            case 'remove':
                document.getElementById('bulkBtnRemove').classList.add('bg-rose-50', 'text-rose-700', 'border-rose-300');
                break;
            case 'set':
                document.getElementById('bulkBtnSet').classList.add('bg-indigo-50', 'text-indigo-700', 'border-indigo-300');
                break;
        }
    }

    async function applyBulkUpdate(selectedRows, options) {
        const selectedIds = selectedRows.map(row => row.getData().id);

        const { isConfirmed } = await Swal.fire({
            title: 'Confirm Bulk Update',
            text: `Are you sure you want to ${options.type} ${options.quantity} items to ${selectedIds.length} product(s)?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, update all',
            cancelButtonText: 'Cancel'
        });

        if (isConfirmed) {
            try {
                const response = await axiosInstance.post('inventory/bulk-update', {
                    variant_ids: selectedIds,
                    quantity: options.quantity,
                    type: options.type,
                    reason: options.reason,
                    notes: 'Bulk update via dashboard'
                });

                if (response.data.success) {
                    toastr.success(`Successfully processed bulk update for ${selectedIds.length} products`);
                    inventoryTable.deselectRow();
                    inventoryTable.setData();
                    updateStats();
                }
            } catch (error) {
                console.error('Error in bulk update:', error);
                toastr.error('Failed to process bulk update');
            }
        }
    }

    // Quick Actions Functions
    function exportInventory() {
        Swal.fire({
            title: 'Export Inventory Report',
            html: `
            <div class="text-left space-y-4">
                <p class="text-gray-600">Select export options:</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                    <select id="exportFormat" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" id="exportAll" checked class="mr-2">
                        <span class="text-sm text-gray-700">Include all products</span>
                    </label>
                </div>
            </div>
        `,
            showCancelButton: true,
            confirmButtonText: 'Export',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                return {
                    format: document.getElementById('exportFormat').value,
                    all: document.getElementById('exportAll').checked
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Exporting...',
                    text: 'Please wait while we generate your report',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                setTimeout(() => {
                    Swal.close();
                    toastr.success(`Inventory report exported as ${result.value.format.toUpperCase()}`);

                    // Trigger download
                    if (result.value.format === 'csv') {
                        inventoryTable.download("csv", "inventory_report.csv");
                    } else if (result.value.format === 'excel') {
                        inventoryTable.download("xlsx", "inventory_report.xlsx", { sheetName: "Inventory" });
                    }
                }, 1500);
            }
        });
    }

    function showLowStockAlert() {
        toastr.info('Low stock alerts can be configured in settings.');
    }

    function showStockAnalysis() {
        toastr.info('Detailed stock analysis is available in the Reports section.');
    }
</script>
@endpush