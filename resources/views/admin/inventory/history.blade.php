@extends('admin.layouts.master')

@section('title', 'Stock History')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Stock History</h2>
            <p class="text-gray-600">Track all stock adjustments and changes</p>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
        </a>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Filter History</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                <select id="dateRangeFilter" onchange="filterHistory()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="all">All Time</option>
                    <option value="today">Today</option>
                    <option value="yesterday">Yesterday</option>
                    <option value="week">Last 7 Days</option>
                    <option value="month">Last 30 Days</option>
                    <option value="quarter">Last 3 Months</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Action Type</label>
                <select id="actionFilter" onchange="filterHistory()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Actions</option>
                    <option value="add">Stock Added</option>
                    <option value="remove">Stock Removed</option>
                    <option value="set">Stock Set</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                <select id="reasonFilter" onchange="filterHistory()"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">All Reasons</option>
                    <option value="restock">Restock</option>
                    <option value="sale">Sale</option>
                    <option value="return">Return</option>
                    <option value="damage">Damage</option>
                    <option value="adjustment">Adjustment</option>
                    <option value="transfer">Transfer</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Search</label>
                <div class="relative">
                    <input type="text" id="productSearch" placeholder="Search products..."
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        onkeyup="filterHistory()">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button onclick="exportHistory()" class="btn-secondary">
                <i class="fas fa-file-export mr-2"></i>Export History
            </button>
        </div>
    </div>
</div>

<!-- History Stats -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Total Adjustments</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="totalAdjustments">0</p>
            </div>
            <div class="p-3 bg-indigo-50 rounded-xl">
                <i class="fas fa-history text-indigo-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Stock Added</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="stockAdded">0</p>
            </div>
            <div class="p-3 bg-emerald-50 rounded-xl">
                <i class="fas fa-plus-circle text-emerald-600 text-xl"></i>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-600">Stock Removed</p>
                <p class="text-2xl font-bold text-gray-800 mt-1" id="stockRemoved">0</p>
            </div>
            <div class="p-3 bg-rose-50 rounded-xl">
                <i class="fas fa-minus-circle text-rose-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Stock History Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Stock Adjustment History</h3>
    </div>
    <div class="p-6">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchHistoryInput" placeholder="Search history..."
                        class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 order-1 sm:order-2">
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
                        <button data-export="csv" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                            <i class="fas fa-file-csv mr-2"></i>CSV
                        </button>
                        <button data-export="xlsx" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                            <i class="fas fa-file-excel mr-2"></i>Excel
                        </button>
                        <button data-export="print" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabulator Container -->
        <div id="historyTable"></div>
    </div>
</div>

<!-- History Detail Modal -->
<div id="historyDetailModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Stock Adjustment Details</h2>
            <button onclick="closeHistoryModal()" class="absolute top-4 right-6 text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        <div class="p-6 overflow-y-auto">
            <div id="historyDetailContent">
                <!-- Details will be loaded here -->
            </div>
        </div>
        <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex justify-end">
                <button onclick="closeHistoryModal()" class="btn-secondary">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const axiosInstance = axios.create({
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
    }
});

let historyTable;
let currentHistoryData = [];

// Load and filter history
async function loadHistory() {
    updateHistoryStats();
    
    if (historyTable) {
        historyTable.setData();
    } else {
        initializeHistoryTable();
    }
}

// Update history statistics
async function updateHistoryStats() {
    try {
        const filters = getFilterParams();
        const response = await axiosInstance.get('/api/admin/inventory/history/statistics', { params: filters });
        if (response.data.success) {
            const stats = response.data.data;
            document.getElementById('totalAdjustments').textContent = stats.total_adjustments;
            document.getElementById('stockAdded').textContent = stats.stock_added;
            document.getElementById('stockRemoved').textContent = stats.stock_removed;
        }
    } catch (error) {
        console.error('Error fetching history stats:', error);
    }
}

function getFilterParams() {
    const dateRange = document.getElementById('dateRangeFilter').value;
    const actionType = document.getElementById('actionFilter').value;
    const reason = document.getElementById('reasonFilter').value;
    const search = document.getElementById('productSearch').value;

    let params = {
        change_type: actionType,
        reason: reason,
        search: search
    };

    if (dateRange !== 'all') {
        const now = new Date();
        let startDate, endDate;

        switch(dateRange) {
            case 'today':
                startDate = now.toISOString().split('T')[0];
                break;
            case 'yesterday':
                const yesterday = new Date(now);
                yesterday.setDate(yesterday.getDate() - 1);
                startDate = yesterday.toISOString().split('T')[0];
                endDate = startDate;
                break;
            case 'week':
                const weekAgo = new Date(now);
                weekAgo.setDate(weekAgo.getDate() - 7);
                startDate = weekAgo.toISOString().split('T')[0];
                break;
            case 'month':
                const monthAgo = new Date(now);
                monthAgo.setDate(monthAgo.getDate() - 30);
                startDate = monthAgo.toISOString().split('T')[0];
                break;
            case 'quarter':
                const quarterAgo = new Date(now);
                quarterAgo.setDate(quarterAgo.getDate() - 90);
                startDate = quarterAgo.toISOString().split('T')[0];
                break;
        }

        if (startDate) params.start_date = startDate;
        if (endDate) params.end_date = endDate;
    }

    return params;
}

// Initialize Tabulator
function initializeHistoryTable() {
    historyTable = new Tabulator("#historyTable", {
        ajaxURL: "/api/admin/inventory/history",
        ajaxConfig: {
            headers: {
                'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`,
                'X-Requested-With': 'XMLHttpRequest',
            }
        },
        ajaxParams: getFilterParams(),
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
        columns: [
            {
                title: "Date & Time",
                field: "updated_at",
                width: 160,
                sorter: "date",
                headerFilter: "input",
                headerFilterPlaceholder: "Search date...",
                formatter: function(cell) {
                    const date = new Date(cell.getValue());
                    return `
                        <div class="text-sm">
                            <div class="font-medium">${date.toLocaleDateString()}</div>
                            <div class="text-gray-500">${date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</div>
                        </div>
                    `;
                }
            },
            {
                title: "Product",
                field: "product_name",
                sorter: "string",
                width: 200,
                responsive: 0,
                headerFilter: "input",
                headerFilterPlaceholder: "Search product...",
                formatter: function(cell) {
                    const rowData = cell.getRow().getData();
                    return `
                        <div class="font-medium text-gray-900">${rowData.product_name}</div>
                        <div class="text-xs text-gray-500">ID: ${rowData.product_id}</div>
                    `;
                }
            },
            {
                title: "Action",
                field: "action",
                width: 140,
                responsive: 0,
                headerFilter: "list",
                headerFilterParams: {
                    values: {
                        "": "All Actions",
                        "add": "Stock Added",
                        "remove": "Stock Removed",
                        "set": "Stock Set"
                    }
                },
                formatter: function(cell) {
                    const value = cell.getValue();
                    let badgeClass, badgeText;
                    switch(value) {
                        case 'add':
                            badgeClass = 'bg-emerald-100 text-emerald-800';
                            badgeText = 'Stock Added';
                            break;
                        case 'remove':
                            badgeClass = 'bg-rose-100 text-rose-800';
                            badgeText = 'Stock Removed';
                            break;
                        case 'set':
                            badgeClass = 'bg-indigo-100 text-indigo-800';
                            badgeText = 'Stock Set';
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
                title: "Change",
                field: "quantity",
                width: 120,
                responsive: 0,
                headerFilter: "number",
                headerFilterPlaceholder: "Search change...",
                formatter: function(cell) {
                    const rowData = cell.getRow().getData();
                    let changeText, changeClass;
                    switch(rowData.action) {
                        case 'add':
                            changeText = `+${rowData.quantity}`;
                            changeClass = 'text-emerald-600';
                            break;
                        case 'remove':
                            changeText = `-${rowData.quantity}`;
                            changeClass = 'text-rose-600';
                            break;
                        case 'set':
                            changeText = `Set ${rowData.quantity}`;
                            changeClass = 'text-indigo-600';
                            break;
                    }
                    return `<span class="font-bold ${changeClass}">${changeText}</span>`;
                }
            },
            {
                title: "From/To",
                field: "old_stock",
                width: 140,
                responsive: 0,
                headerFilter: "number",
                headerFilterPlaceholder: "Search stock...",
                formatter: function(cell) {
                    const rowData = cell.getRow().getData();
                    return `
                        <div class="text-sm">
                            <div>From: <span class="font-medium">${rowData.old_stock}</span></div>
                            <div>To: <span class="font-bold ${rowData.new_stock <= 0 ? 'text-rose-600' : rowData.new_stock <= 10 ? 'text-amber-600' : 'text-emerald-600'}">${rowData.new_stock}</span></div>
                        </div>
                    `;
                }
            },
            {
                title: "Reason",
                field: "reason",
                width: 140,
                responsive: 0,
                headerFilter: "list",
                headerFilterParams: {
                    values: {
                        "": "All Reasons",
                        "restock": "Restock",
                        "sale": "Sale",
                        "return": "Return",
                        "damage": "Damage",
                        "adjustment": "Adjustment",
                        "transfer": "Transfer"
                    }
                },
                formatter: function(cell) {
                    const reasonText = cell.getValue().replace(/_/g, ' ');
                    return `<span class="text-sm">${reasonText.charAt(0).toUpperCase() + reasonText.slice(1)}</span>`;
                }
            },
            {
                title: "Updated By",
                field: "updated_by",
                width: 120,
                responsive: 0,
                headerFilter: "input",
                headerFilterPlaceholder: "Search user..."
            },
            {
                title: "Reference",
                field: "reference",
                width: 120,
                responsive: 0,
                headerFilter: "input",
                headerFilterPlaceholder: "Search reference...",
                formatter: function(cell) {
                    const value = cell.getValue();
                    return value ? `<span class="text-sm text-gray-600">${value}</span>` : '-';
                }
            },
            {
                title: "Notes",
                field: "notes",
                width: 200,
                responsive: 0,
                headerFilter: "input",
                headerFilterPlaceholder: "Search notes...",
                formatter: function(cell) {
                    const rowData = cell.getRow().getData();
                    return `
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 truncate max-w-xs">${rowData.notes || '-'}</span>
                            <button onclick="viewHistoryDetail(${rowData.id})" class="ml-2 text-indigo-600 hover:text-indigo-800">
                                <i class="fas fa-eye"></i>
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

    // Initialize controls
    initHistoryControls();
}

function initHistoryControls() {
    // Search functionality
    const searchInput = document.getElementById('searchHistoryInput');
    searchInput.addEventListener('keyup', function() {
        historyTable.setFilter([
            [
                { field: "product_name", type: "like", value: this.value },
                { field: "updated_by", type: "like", value: this.value },
                { field: "reference", type: "like", value: this.value },
                { field: "notes", type: "like", value: this.value }
            ]
        ]);
    });

    // Column visibility
    const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
    const columnMenu = document.createElement('div');
    columnMenu.className = 'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

    const columns = historyTable.getColumnDefinitions();
    columns.forEach((column, index) => {
        const field = column.field;
        const columnBtn = document.createElement('button');
        columnBtn.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
        columnBtn.innerHTML = `
            <input type="checkbox" class="mr-2" ${historyTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;
        
        columnBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const col = historyTable.getColumn(field);
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

    // Export functionality
    const exportBtns = document.querySelectorAll('[data-export]');
    exportBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const format = this.getAttribute('data-export');
            switch(format) {
                case 'csv':
                    historyTable.download("csv", "stock_history.csv");
                    break;
                case 'xlsx':
                    historyTable.download("xlsx", "stock_history.xlsx", { sheetName: "Stock History" });
                    break;
                case 'print':
                    window.print();
                    break;
            }
        });
    });
}

// Filter history when filters change
function filterHistory() {
    if (historyTable) {
        historyTable.setData("/api/admin/inventory/history", getFilterParams());
        updateHistoryStats();
    }
}

// Export history
function exportHistory() {
    Swal.fire({
        title: 'Export History',
        html: `
            <div class="text-left space-y-4">
                <p class="text-gray-600">Select export options:</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Format</label>
                    <select id="exportHistoryFormat" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="csv">CSV</option>
                        <option value="excel">Excel</option>
                        <option value="pdf">PDF</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Include Data</label>
                    <div class="space-y-2">
                        <label class="flex items-center">
                            <input type="checkbox" checked class="mr-2">
                            <span class="text-sm text-gray-700">All fields</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" checked class="mr-2">
                            <span class="text-sm text-gray-700">Current filters</span>
                        </label>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Export',
        cancelButtonText: 'Cancel',
        preConfirm: () => {
            return document.getElementById('exportHistoryFormat').value;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Exporting...',
                text: 'Please wait while we generate your history report',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            setTimeout(() => {
                Swal.close();
                toastr.success(`History exported as ${result.value.toUpperCase()}`);
                
                // Trigger download
                if (result.value === 'csv') {
                    historyTable.download("csv", "stock_history_report.csv");
                } else if (result.value === 'excel') {
                    historyTable.download("xlsx", "stock_history_report.xlsx", { sheetName: "Stock History" });
                }
            }, 1500);
        }
    });
}

// View history detail
function viewHistoryDetail(historyId) {
    const entry = currentHistoryData.find(h => h.id === historyId);
    
    if (!entry) {
        toastr.error('History entry not found');
        return;
    }
    
    const date = new Date(entry.updated_at);
    const actionIcon = entry.action === 'add' ? 
        '<i class="fas fa-plus-circle text-emerald-500 text-2xl"></i>' : 
        entry.action === 'remove' ? 
        '<i class="fas fa-minus-circle text-rose-500 text-2xl"></i>' : 
        '<i class="fas fa-equals-circle text-indigo-500 text-2xl"></i>';
    
    const actionText = entry.action === 'add' ? 'Stock Added' : 
                      entry.action === 'remove' ? 'Stock Removed' : 'Stock Set';
    
    const changeText = entry.action === 'add' ? `+${entry.quantity}` : 
                      entry.action === 'remove' ? `-${entry.quantity}` : 
                      `Set to ${entry.quantity}`;
    
    const changeClass = entry.action === 'add' ? 'text-emerald-600' : 
                       entry.action === 'remove' ? 'text-rose-600' : 
                       'text-indigo-600';
    
    document.getElementById('historyDetailContent').innerHTML = `
        <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-3">
                    ${actionIcon}
                    <div>
                        <h3 class="font-semibold text-gray-800">${actionText}</h3>
                        <p class="text-sm text-gray-500">${date.toLocaleString()}</p>
                    </div>
                </div>
                <span class="text-2xl font-bold ${changeClass}">${changeText}</span>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Product Information</h4>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Product Name:</span>
                            <p class="font-medium">${entry.product_name}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Product ID:</span>
                            <p class="font-medium">${entry.product_id}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Stock Details</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Previous Stock:</span>
                            <span class="font-medium">${entry.old_stock}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">New Stock:</span>
                            <span class="font-bold ${entry.new_stock <= 0 ? 'text-rose-600' : entry.new_stock <= 10 ? 'text-amber-600' : 'text-emerald-600'}">
                                ${entry.new_stock}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Net Change:</span>
                            <span class="font-bold ${changeClass}">${changeText}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Transaction Information</h4>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Reason:</span>
                            <p class="font-medium capitalize">${entry.reason.replace(/_/g, ' ')}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Reference:</span>
                            <p class="font-medium">${entry.reference || 'N/A'}</p>
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">User Information</h4>
                    <div class="space-y-2">
                        <div>
                            <span class="text-sm text-gray-500">Updated By:</span>
                            <p class="font-medium">${entry.updated_by}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Date & Time:</span>
                            <p class="font-medium">${date.toLocaleString()}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            ${entry.notes ? `
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Notes</h4>
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-gray-700">${entry.notes}</p>
                    </div>
                </div>
            ` : ''}
        </div>
    `;
    
    document.getElementById('historyDetailModal').classList.remove('hidden');
}

function closeHistoryModal() {
    document.getElementById('historyDetailModal').classList.add('hidden');
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Add demo data if empty
    let history = JSON.parse(localStorage.getItem('stockHistory') || '[]');
    if (history.length === 0) {
        // Add some sample history entries
        const demoHistory = [
            {
                id: 1,
                product_id: 1,
                product_name: "Wireless Bluetooth Headphones",
                old_stock: 40,
                new_stock: 45,
                action: "add",
                quantity: 5,
                reason: "restock",
                reference: "PO-2024-001",
                notes: "Regular monthly restock",
                updated_by: "Admin",
                updated_at: "2024-01-15T14:30:00"
            },
            {
                id: 2,
                product_id: 2,
                product_name: "Smart Fitness Watch",
                old_stock: 25,
                new_stock: 23,
                action: "remove",
                quantity: 2,
                reason: "sale",
                reference: "SO-2024-012",
                notes: "Two units sold to customer",
                updated_by: "Admin",
                updated_at: "2024-01-14T11:20:00"
            },
            {
                id: 3,
                product_id: 3,
                product_name: "Organic Cotton T-Shirt",
                old_stock: 5,
                new_stock: 0,
                action: "remove",
                quantity: 5,
                reason: "sale",
                reference: "SO-2024-015",
                notes: "Final units sold, need to reorder",
                updated_by: "Admin",
                updated_at: "2024-01-13T09:15:00"
            },
            {
                id: 4,
                product_id: 4,
                product_name: "Stainless Steel Water Bottle",
                old_stock: 70,
                new_stock: 78,
                action: "add",
                quantity: 8,
                reason: "restock",
                reference: "PO-2024-002",
                notes: "Warehouse restock",
                updated_by: "Admin",
                updated_at: "2024-01-12T16:45:00"
            },
            {
                id: 5,
                product_id: 5,
                product_name: "Wireless Phone Charger",
                old_stock: 30,
                new_stock: 34,
                action: "add",
                quantity: 4,
                reason: "return",
                reference: "RT-2024-008",
                notes: "Customer returns",
                updated_by: "Admin",
                updated_at: "2024-01-11T10:30:00"
            }
        ];
        
        localStorage.setItem('stockHistory', JSON.stringify(demoHistory));
    }
    
    // Load initial history
    loadHistory();
});
</script>
@endpush