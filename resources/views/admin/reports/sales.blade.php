@extends('admin.layouts.master')

@section('title', 'Sales Reports')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Sales Reports</h2>
            <p class="text-gray-600">Analyze your sales performance and trends</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select id="periodFilter" onchange="updateSalesData()"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="7">Last 7 Days</option>
                <option value="30" selected>Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">This Year</option>
            </select>

            <button onclick="exportSalesReport()" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>
</div>

<!-- Sales Overview Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Revenue -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="totalRevenue">$24,568</p>
                <p class="text-sm text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i><span id="revenueChange">12.5%</span> increase
                </p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-dollar-sign text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="totalOrders">1,248</p>
                <p class="text-sm text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i><span id="ordersChange">8.2%</span> increase
                </p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Average Order Value -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Average Order Value</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="avgOrderValue">$89.50</p>
                <p class="text-sm text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i><span id="avgOrderChange">5.3%</span> increase
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Conversion Rate -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Conversion Rate</p>
                <p class="text-2xl font-bold text-gray-900 mt-1" id="conversionRate">3.2%</p>
                <p class="text-sm text-rose-600 mt-1">
                    <i class="fas fa-arrow-down mr-1"></i><span id="conversionChange">0.8%</span> decrease
                </p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-percentage text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts + Top Products -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Revenue Overview</h3>
            <div class="flex items-center space-x-2">
                <button onclick="setChartType('line')" id="lineChartBtn" class="px-3 py-1 text-sm border border-gray-300 rounded-lg bg-indigo-50 text-indigo-700">
                    <i class="fas fa-chart-line mr-1"></i>Line
                </button>
                <button onclick="setChartType('bar')" id="barChartBtn" class="px-3 py-1 text-sm border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i class="fas fa-chart-bar mr-1"></i>Bar
                </button>
            </div>
        </div>
        <div class="h-80 bg-gray-50 rounded-xl flex items-center justify-center">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>

    <!-- Top Selling Products -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Top Selling Products</h3>
        <div class="space-y-4" id="topProductsList">
            <!-- Products will be loaded dynamically -->
        </div>
    </div>
</div>

<!-- Sales Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Detailed Sales Data</h3>
    </div>

    <div class="p-6">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchSalesInput" placeholder="Search sales..."
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
        <div id="salesTable"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Sales data
const salesData = [
    { id: 1, date: '2024-01-15', order_id: 'ORD-7891', customer: 'John Doe', products: 3, amount: 125.50, status: 'completed' },
    { id: 2, date: '2024-01-14', order_id: 'ORD-7892', customer: 'Jane Smith', products: 2, amount: 89.99, status: 'completed' },
    { id: 3, date: '2024-01-13', order_id: 'ORD-7893', customer: 'Mike Johnson', products: 1, amount: 45.00, status: 'completed' },
    { id: 4, date: '2024-01-12', order_id: 'ORD-7894', customer: 'Sarah Wilson', products: 4, amount: 189.75, status: 'completed' },
    { id: 5, date: '2024-01-11', order_id: 'ORD-7895', customer: 'David Brown', products: 2, amount: 67.50, status: 'completed' },
    { id: 6, date: '2024-01-10', order_id: 'ORD-7896', customer: 'Emily Davis', products: 3, amount: 134.99, status: 'processing' },
    { id: 7, date: '2024-01-09', order_id: 'ORD-7897', customer: 'Robert Miller', products: 1, amount: 29.99, status: 'completed' },
    { id: 8, date: '2024-01-08', order_id: 'ORD-7898', customer: 'Linda Martinez', products: 2, amount: 78.50, status: 'completed' },
    { id: 9, date: '2024-01-07', order_id: 'ORD-7899', customer: 'James Garcia', products: 5, amount: 245.25, status: 'shipped' },
    { id: 10, date: '2024-01-06', order_id: 'ORD-7900', customer: 'Patricia Rodriguez', products: 2, amount: 92.50, status: 'completed' },
    { id: 11, date: '2024-01-05', order_id: 'ORD-7901', customer: 'Michael Wilson', products: 3, amount: 156.80, status: 'completed' },
    { id: 12, date: '2024-01-04', order_id: 'ORD-7902', customer: 'Jennifer Brown', products: 1, amount: 49.99, status: 'cancelled' },
    { id: 13, date: '2024-01-03', order_id: 'ORD-7903', customer: 'William Taylor', products: 4, amount: 178.40, status: 'completed' },
    { id: 14, date: '2024-01-02', order_id: 'ORD-7904', customer: 'Elizabeth Lee', products: 2, amount: 85.75, status: 'completed' },
    { id: 15, date: '2024-01-01', order_id: 'ORD-7905', customer: 'Christopher Clark', products: 3, amount: 112.30, status: 'completed' }
];

// Top products data
const topProductsData = [
    { id: 1, name: "Wireless Bluetooth Headphones", sold: 175, revenue: 1250 },
    { id: 2, name: "Smart Fitness Watch", sold: 150, revenue: 1400 },
    { id: 3, name: "Organic Cotton T-Shirt", sold: 200, revenue: 900 },
    { id: 4, name: "Stainless Steel Water Bottle", sold: 125, revenue: 600 },
    { id: 5, name: "Wireless Phone Charger", sold: 180, revenue: 1100 }
];

let salesTable;
let revenueChart;
let chartType = 'line';

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Tabulator
    initializeSalesTable();
    
    // Initialize charts and top products
    initializeCharts();
    loadTopProducts();
    
    // Initialize controls
    initSalesControls();
});

function initializeSalesTable() {
    salesTable = new Tabulator("#salesTable", {
        data: salesData,
        layout: "fitColumns",
        responsiveLayout: "hide",
        pagination: "local",
        paginationSize: 10,
        movableColumns: true,
        paginationSizeSelector: [10, 20, 50, 100],
        columns: [
            {
                title: "Date",
                field: "date",
                width: 120,
                sorter: "date",
                headerFilter: "input",
                headerFilterPlaceholder: "Search date...",
                formatter: function(cell) {
                    const date = new Date(cell.getValue());
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                }
            },
            {
                title: "Order ID",
                field: "order_id",
                width: 140,
                sorter: "string",
                headerFilter: "input",
                headerFilterPlaceholder: "Search order..."
            },
            {
                title: "Customer",
                field: "customer",
                width: 180,
                sorter: "string",
                headerFilter: "input",
                headerFilterPlaceholder: "Search customer..."
            },
            {
                title: "Products",
                field: "products",
                width: 120,
                sorter: "number",
                headerFilter: "number",
                headerFilterPlaceholder: "Search count...",
                formatter: function(cell) {
                    const count = cell.getValue();
                    return `${count} item${count !== 1 ? 's' : ''}`;
                },
                hozAlign: "center"
            },
            {
                title: "Amount",
                field: "amount",
                width: 120,
                sorter: "number",
                headerFilter: "number",
                headerFilterPlaceholder: "Search amount...",
                formatter: function(cell) {
                    return `<span class="font-semibold text-gray-900">$${cell.getValue().toFixed(2)}</span>`;
                },
                hozAlign: "right"
            },
            {
                title: "Status",
                field: "status",
                width: 140,
                responsive: 0,
                headerFilter: "select",
                headerFilterParams: {
                    values: {
                        "": "All Status",
                        "completed": "Completed",
                        "processing": "Processing",
                        "shipped": "Shipped",
                        "cancelled": "Cancelled"
                    }
                },
                formatter: function(cell) {
                    const status = cell.getValue();
                    let badgeClass, badgeText;
                    
                    switch(status) {
                        case 'completed':
                            badgeClass = 'bg-emerald-100 text-emerald-800';
                            badgeText = 'Completed';
                            break;
                        case 'processing':
                            badgeClass = 'bg-blue-100 text-blue-800';
                            badgeText = 'Processing';
                            break;
                        case 'shipped':
                            badgeClass = 'bg-amber-100 text-amber-800';
                            badgeText = 'Shipped';
                            break;
                        case 'cancelled':
                            badgeClass = 'bg-rose-100 text-rose-800';
                            badgeText = 'Cancelled';
                            break;
                        default:
                            badgeClass = 'bg-gray-100 text-gray-800';
                            badgeText = status;
                    }
                    
                    return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${badgeClass}">
                        ${badgeText}
                    </span>`;
                }
            }
        ],
        rowFormatter: function(row) {
            const rowEl = row.getElement();
            rowEl.classList.add('hover:bg-gray-50');
        }
    });
}

function initSalesControls() {
    // Search functionality
    const searchInput = document.getElementById('searchSalesInput');
    searchInput.addEventListener('keyup', function() {
        salesTable.setFilter([
            [
                { field: "order_id", type: "like", value: this.value },
                { field: "customer", type: "like", value: this.value },
                { field: "status", type: "like", value: this.value }
            ]
        ]);
    });

    // Column visibility
    const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
    const columnMenu = document.createElement('div');
    columnMenu.className = 'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden right-12 md:right-24 md:left-auto left-0';

    const columns = salesTable.getColumnDefinitions();
    columns.forEach((column, index) => {
        const field = column.field;
        const columnBtn = document.createElement('button');
        columnBtn.className = 'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
        columnBtn.innerHTML = `
            <input type="checkbox" class="mr-2" ${salesTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;
        
        columnBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            e.preventDefault();
            const col = salesTable.getColumn(field);
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
                    salesTable.download("csv", "sales_report.csv");
                    break;
                case 'xlsx':
                    salesTable.download("xlsx", "sales_report.xlsx", { sheetName: "Sales Report" });
                    break;
                case 'print':
                    window.print();
                    break;
            }
        });
    });

    // Period filter
    document.getElementById('periodFilter').addEventListener('change', updateSalesData);
}

function initializeCharts() {
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    // Sample data for the chart
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
    const data = [12000, 19000, 15000, 22000, 18000, 24568];
    
    revenueChart = new Chart(ctx, {
        type: chartType,
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue',
                data: data,
                backgroundColor: chartType === 'bar' ? 'rgba(99, 102, 241, 0.2)' : 'rgba(99, 102, 241, 0.1)',
                borderColor: 'rgba(99, 102, 241, 1)',
                borderWidth: 2,
                fill: chartType === 'line',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
}

function setChartType(type) {
    chartType = type;
    
    // Update button styles
    document.getElementById('lineChartBtn').classList.remove('bg-indigo-50', 'text-indigo-700');
    document.getElementById('lineChartBtn').classList.add('hover:bg-gray-50');
    document.getElementById('barChartBtn').classList.remove('bg-indigo-50', 'text-indigo-700');
    document.getElementById('barChartBtn').classList.add('hover:bg-gray-50');
    
    if (type === 'line') {
        document.getElementById('lineChartBtn').classList.add('bg-indigo-50', 'text-indigo-700');
    } else {
        document.getElementById('barChartBtn').classList.add('bg-indigo-50', 'text-indigo-700');
    }
    
    // Update chart
    revenueChart.destroy();
    initializeCharts();
}

function loadTopProducts() {
    const container = document.getElementById('topProductsList');
    container.innerHTML = '';
    
    topProductsData.forEach((product, index) => {
        const productElement = document.createElement('div');
        productElement.className = 'flex justify-between items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition';
        productElement.innerHTML = `
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-lg flex items-center justify-center text-white font-bold">
                    ${index + 1}
                </div>
                <div>
                    <p class="font-medium text-gray-900">${product.name}</p>
                    <p class="text-sm text-gray-500">${product.sold} sold</p>
                </div>
            </div>
            <span class="font-medium text-emerald-600">$${product.revenue}</span>
        `;
        container.appendChild(productElement);
    });
}

function updateSalesData() {
    const period = document.getElementById('periodFilter').value;
    
    // Simulate loading
    Swal.fire({
        title: 'Updating Report...',
        text: 'Loading data for the selected period',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate API call delay
    setTimeout(() => {
        // Update stats based on period
        let revenue, orders, avgOrder, conversion;
        
        switch(period) {
            case '7':
                revenue = 5680;
                orders = 64;
                avgOrder = 88.75;
                conversion = 2.8;
                break;
            case '30':
                revenue = 24568;
                orders = 1248;
                avgOrder = 89.50;
                conversion = 3.2;
                break;
            case '90':
                revenue = 68900;
                orders = 3125;
                avgOrder = 91.20;
                conversion = 3.5;
                break;
            case '365':
                revenue = 285000;
                orders = 15000;
                avgOrder = 95.00;
                conversion = 4.1;
                break;
        }
        
        // Update cards
        document.getElementById('totalRevenue').textContent = '$' + revenue.toLocaleString();
        document.getElementById('totalOrders').textContent = orders.toLocaleString();
        document.getElementById('avgOrderValue').textContent = '$' + avgOrder.toFixed(2);
        document.getElementById('conversionRate').textContent = conversion + '%';
        
        // Update changes
        const changes = {
            7: { revenue: '2.1%', orders: '1.5%', avgOrder: '1.8%', conversion: '0.3%' },
            30: { revenue: '12.5%', orders: '8.2%', avgOrder: '5.3%', conversion: '0.8%' },
            90: { revenue: '18.2%', orders: '12.5%', avgOrder: '7.8%', conversion: '1.2%' },
            365: { revenue: '25.4%', orders: '18.7%', avgOrder: '10.2%', conversion: '1.8%' }
        };
        
        document.getElementById('revenueChange').textContent = changes[period].revenue;
        document.getElementById('ordersChange').textContent = changes[period].orders;
        document.getElementById('avgOrderChange').textContent = changes[period].avgOrder;
        document.getElementById('conversionChange').textContent = changes[period].conversion;
        
        Swal.close();
        toastr.success('Sales report updated for the selected period');
    }, 1000);
}

function exportSalesReport() {
    Swal.fire({
        title: 'Export Sales Report',
        html: `
            <div class="text-left space-y-4">
                <p class="text-gray-600">Select export options:</p>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Report Type</label>
                    <select id="reportType" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                        <option value="summary">Summary Report</option>
                        <option value="detailed">Detailed Report</option>
                        <option value="customers">Customer Report</option>
                        <option value="products">Products Report</option>
                    </select>
                </div>
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
                        <input type="checkbox" id="includeCharts" class="mr-2">
                        <span class="text-sm text-gray-700">Include charts and graphs</span>
                    </label>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Export Report',
        cancelButtonText: 'Cancel',
        customClass: {
            confirmButton: 'btn-primary',
            cancelButton: 'btn-secondary'
        },
        preConfirm: () => {
            return {
                type: document.getElementById('reportType').value,
                format: document.getElementById('exportFormat').value,
                includeCharts: document.getElementById('includeCharts').checked
            };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Exporting...',
                text: 'Please wait while we generate your sales report',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            setTimeout(() => {
                Swal.close();
                toastr.success(`Sales report exported as ${result.value.format.toUpperCase()}`);
                
                // Trigger download
                if (result.value.format === 'csv') {
                    salesTable.download("csv", "sales_report.csv");
                } else if (result.value.format === 'excel') {
                    salesTable.download("xlsx", "sales_report.xlsx", { sheetName: "Sales Report" });
                }
            }, 1500);
        }
    });
}
</script>
@endpush