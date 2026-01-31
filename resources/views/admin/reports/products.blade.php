@extends('admin.layouts.master')

@section('title', 'Product Reports')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Product Reports</h2>
            <p class="text-gray-600">Analyze product performance and inventory trends</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select id="periodFilter"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="30">Last 30 Days</option>
                <option value="90">Last 90 Days</option>
                <option value="365">This Year</option>
                <option value="all">All Time</option>
            </select>

            <button id="exportReportBtn" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>
</div>

<!-- Product Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Products -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Products</p>
                <p id="totalProducts" class="text-2xl font-bold text-gray-900 mt-1">856</p>
                <p class="text-sm text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>12.5% increase
                </p>
            </div>

            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-box text-purple-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Products Sold -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Products Sold</p>
                <p id="totalSold" class="text-2xl font-bold text-gray-900 mt-1">12,458</p>
                <p class="text-sm text-emerald-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>18.3% increase</p>
            </div>

            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Out of Stock</p>
                <p id="outOfStock" class="text-2xl font-bold text-gray-900 mt-1">23</p>
                <p class="text-sm text-rose-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>5.2% increase</p>
            </div>

            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-exclamation-triangle text-rose-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Low Stock -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Low Stock</p>
                <p id="lowStock" class="text-2xl font-bold text-gray-900 mt-1">45</p>
                <p class="text-sm text-amber-600 mt-1"><i class="fas fa-arrow-down mr-1"></i>3.1% decrease</p>
            </div>

            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-box-open text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Product Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Sales Performance -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Product Sales Performance</h3>

        <div class="h-80 bg-gray-50 rounded-xl flex items-center justify-center">
            <canvas id="productSalesChart"></canvas>
        </div>
    </div>

    <!-- Category Distribution -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Sales by Category</h3>

        <div id="categoryDistribution" class="space-y-4">
            <!-- Will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Top Products Table - Tabulator -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Top Selling Products</h3>
    </div>

    <div class="p-6">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchInput" placeholder="Search products..."
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
                        <button data-export="pdf"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </button>
                        <button data-export="print"
                            class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 text-sm">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabulator Table -->
        <div id="productsReportTable"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Static product reports data
    window.productsReportData = [
        {
            id: 1,
            rank: 1,
            name: "Wireless Bluetooth Headphones",
            sku: "ELEC-WBH-001",
            category: "Electronics",
            units_sold: 1250,
            revenue: 74500.00,
            stock: 45,
            stock_status: "in_stock",
            status: "active",
            image: "headphones.jpg",
            growth: 25
        },
        {
            id: 2,
            rank: 2,
            name: "Smart Fitness Watch",
            sku: "WRB-SFW-002",
            category: "Wearables",
            units_sold: 980,
            revenue: 147000.00,
            stock: 23,
            stock_status: "low_stock",
            status: "active",
            image: "watch.jpg",
            growth: 18
        },
        {
            id: 3,
            rank: 3,
            name: "Organic Cotton T-Shirt",
            sku: "CLO-OCT-003",
            category: "Clothing",
            units_sold: 845,
            revenue: 21125.00,
            stock: 0,
            stock_status: "out_of_stock",
            status: "inactive",
            image: "tshirt.jpg",
            growth: 12
        },
        {
            id: 4,
            rank: 4,
            name: "Stainless Steel Water Bottle",
            sku: "ACC-SSW-004",
            category: "Accessories",
            units_sold: 720,
            revenue: 17928.00,
            stock: 78,
            stock_status: "in_stock",
            status: "active",
            image: "bottle.jpg",
            growth: 32
        },
        {
            id: 5,
            rank: 5,
            name: "Wireless Phone Charger",
            sku: "ELEC-WPC-005",
            category: "Electronics",
            units_sold: 650,
            revenue: 19487.50,
            stock: 34,
            stock_status: "in_stock",
            status: "active",
            image: "charger.jpg",
            growth: 15
        },
        {
            id: 6,
            rank: 6,
            name: "Yoga Mat Premium",
            sku: "FIT-YMP-006",
            category: "Fitness",
            units_sold: 520,
            revenue: 20748.00,
            stock: 12,
            stock_status: "low_stock",
            status: "active",
            image: "yogamat.jpg",
            growth: 28
        },
        {
            id: 7,
            rank: 7,
            name: "Ceramic Coffee Mug Set",
            sku: "HOM-CCM-007",
            category: "Home & Kitchen",
            units_sold: 485,
            revenue: 16975.00,
            stock: 56,
            stock_status: "in_stock",
            status: "active",
            image: "mugs.jpg",
            growth: 8
        },
        {
            id: 8,
            rank: 8,
            name: "LED Desk Lamp",
            sku: "HOM-LDL-008",
            category: "Home & Office",
            units_sold: 420,
            revenue: 16758.00,
            stock: 0,
            stock_status: "out_of_stock",
            status: "inactive",
            image: "lamp.jpg",
            growth: 5
        },
        {
            id: 9,
            rank: 9,
            name: "Premium Backpack",
            sku: "ACC-PBP-009",
            category: "Accessories",
            units_sold: 380,
            revenue: 19000.00,
            stock: 15,
            stock_status: "low_stock",
            status: "active",
            image: "backpack.jpg",
            growth: 22
        },
        {
            id: 10,
            rank: 10,
            name: "Gaming Keyboard",
            sku: "ELEC-GKB-010",
            category: "Electronics",
            units_sold: 320,
            revenue: 19200.00,
            stock: 42,
            stock_status: "in_stock",
            status: "active",
            image: "keyboard.jpg",
            growth: 35
        },
        {
            id: 11,
            rank: 11,
            name: "Bluetooth Speaker",
            sku: "ELEC-BTS-011",
            category: "Electronics",
            units_sold: 290,
            revenue: 21750.00,
            stock: 8,
            stock_status: "low_stock",
            status: "active",
            image: "speaker.jpg",
            growth: 19
        },
        {
            id: 12,
            rank: 12,
            name: "Smartphone Case",
            sku: "ACC-SPC-012",
            category: "Accessories",
            units_sold: 560,
            revenue: 13440.00,
            stock: 67,
            stock_status: "in_stock",
            status: "active",
            image: "case.jpg",
            growth: 42
        },
        {
            id: 13,
            rank: 13,
            name: "Running Shoes",
            sku: "FIT-RSH-013",
            category: "Fitness",
            units_sold: 420,
            revenue: 33600.00,
            stock: 0,
            stock_status: "out_of_stock",
            status: "inactive",
            image: "shoes.jpg",
            growth: 7
        },
        {
            id: 14,
            rank: 14,
            name: "Desk Organizer",
            sku: "HOM-DOR-014",
            category: "Home & Office",
            units_sold: 380,
            revenue: 12160.00,
            stock: 23,
            stock_status: "low_stock",
            status: "active",
            image: "organizer.jpg",
            growth: 31
        },
        {
            id: 15,
            rank: 15,
            name: "Smart Thermostat",
            sku: "HOM-STH-015",
            category: "Home & Kitchen",
            units_sold: 210,
            revenue: 63000.00,
            stock: 5,
            stock_status: "low_stock",
            status: "active",
            image: "thermostat.jpg",
            growth: 45
        }
    ];

    // Category distribution data
    window.categoryDistributionData = [
        { name: "Electronics", sales: 12500, percentage: 35 },
        { name: "Clothing", sales: 8900, percentage: 25 },
        { name: "Home & Kitchen", sales: 7200, percentage: 20 },
        { name: "Accessories", sales: 5400, percentage: 15 },
        { name: "Others", sales: 1800, percentage: 5 }
    ];

    // Initialize Tabulator
    let productsReportTable;

    document.addEventListener('DOMContentLoaded', function () {
        // Initialize category distribution
        initCategoryDistribution();

        // Update metrics
        updateMetrics();

        // Create Tabulator
        productsReportTable = new Tabulator("#productsReportTable", {
            data: productsReportData,
            layout: "fitColumns",
            responsiveLayout: "hide",
            pagination: "local",
            paginationSize: 10,
            movableColumns: true,
            paginationSizeSelector: [10, 20, 50, 100],
            initialSort: [{ column: "rank", dir: "asc" }],
            columns: [
                {
                    title: "Rank",
                    field: "rank",
                    width: 80,
                    sorter: "number",
                    hozAlign: "center",
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Rank…",
                    formatter: function (cell) {
                        const rank = cell.getValue();
                        let rankClass = "bg-gray-100";
                        if (rank === 1) rankClass = "bg-amber-100 text-amber-800";
                        else if (rank === 2) rankClass = "bg-blue-100 text-blue-800";
                        else if (rank === 3) rankClass = "bg-emerald-100 text-emerald-800";

                        return `<span class="inline-flex items-center justify-center w-8 h-8 rounded-full font-semibold text-sm ${rankClass}">
                            #${rank}
                        </span>`;
                    }
                },
                {
                    title: "Product",
                    field: "name",
                    sorter: "string",
                    responsive: 0,
                    width: 280,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Product…",
                    formatter: function (cell, formatterParams, onRendered) {
                        const rowData = cell.getRow().getData();
                        return `
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-box text-gray-600"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="font-medium text-gray-900 truncate">${rowData.name}</p>
                                    <p class="text-sm text-gray-500 truncate">${rowData.sku}</p>
                                    ${rowData.growth ? `
                                        <div class="flex items-center mt-1">
                                            <i class="fas fa-chart-line text-emerald-500 text-xs mr-1"></i>
                                            <span class="text-xs font-medium text-emerald-600">${rowData.growth}% growth</span>
                                        </div>
                                    ` : ''}
                                </div>
                            </div>
                        `;
                    }
                },
                {
                    title: "Category",
                    field: "category",
                    width: 140,
                    sorter: "string",
                    hozAlign: "center",
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Category…",
                    formatter: function (cell) {
                        const category = cell.getValue();
                        const categoryColors = {
                            "Electronics": "bg-blue-100 text-blue-800",
                            "Clothing": "bg-purple-100 text-purple-800",
                            "Home & Kitchen": "bg-emerald-100 text-emerald-800",
                            "Accessories": "bg-amber-100 text-amber-800",
                            "Fitness": "bg-rose-100 text-rose-800",
                            "Wearables": "bg-indigo-100 text-indigo-800",
                            "Home & Office": "bg-gray-100 text-gray-800"
                        };
                        const colorClass = categoryColors[category] || "bg-gray-100 text-gray-800";

                        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${colorClass}">
                            ${category}
                        </span>`;
                    }
                },
                {
                    title: "Units Sold",
                    field: "units_sold",
                    width: 120,
                    sorter: "number",
                    hozAlign: "center",
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Sales…",
                    formatter: function (cell) {
                        const sold = cell.getValue();
                        return `<div class="text-center">
                            <span class="font-semibold text-gray-900">${sold.toLocaleString()}</span>
                            <div class="text-xs text-gray-500">units</div>
                        </div>`;
                    }
                },
                {
                    title: "Revenue",
                    field: "revenue",
                    width: 140,
                    sorter: "number",
                    hozAlign: "right",
                    responsive: 0,
                    formatter: function (cell) {
                        const revenue = cell.getValue();
                        return `<div class="text-right">
                            <span class="font-bold text-gray-900">$${revenue.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                            <div class="text-xs text-gray-500">total revenue</div>
                        </div>`;
                    }
                },
                {
                    title: "Stock",
                    field: "stock_status",
                    width: 120,
                    responsive: 0,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "in_stock": "In Stock",
                            "low_stock": "Low Stock",
                            "out_of_stock": "Out of Stock"
                        }
                    },
                    mutatorData: function (value) {
                        return value;
                    },
                    formatter: function (cell) {
                        const status = cell.getValue();
                        const rowData = cell.getRow().getData();
                        const stock = rowData.stock;

                        const statusConfig = {
                            'in_stock': { class: 'text-emerald-600', text: 'In Stock' },
                            'low_stock': { class: 'text-amber-600', text: 'Low Stock' },
                            'out_of_stock': { class: 'text-rose-600', text: 'Out of Stock' }
                        };
                        const config = statusConfig[status] || statusConfig['in_stock'];

                        return `<div class="text-center">
                            <span class="font-semibold ${config.class}">${stock}</span>
                            <div class="text-xs ${config.class}">${config.text}</div>
                        </div>`;
                    }
                },
                {
                    title: "Status",
                    field: "status",
                    width: 100,
                    responsive: 0,
                    hozAlign: "center",
                    headerFilter: "select",
                    headerFilterParams: {
                        values: {
                            "": "All",
                            "active": "Active",
                            "inactive": "Inactive"
                        }
                    },
                    mutatorData: function (value) {
                        return value;
                    },
                    formatter: function (cell) {
                        const status = cell.getValue();
                        const statusConfig = {
                            'active': { class: 'bg-emerald-100 text-emerald-800', icon: 'fa-check-circle' },
                            'inactive': { class: 'bg-rose-100 text-rose-800', icon: 'fa-times-circle' }
                        };
                        const config = statusConfig[status] || statusConfig['active'];

                        return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium ${config.class}">
                            <i class="fas ${config.icon} mr-1"></i>
                            <span class="capitalize">${status}</span>
                        </span>`;
                    }
                },
                {
                    title: "Actions",
                    field: "id",
                    width: 120,
                    hozAlign: "center",
                    responsive: 0,
                    headerSort: false,
                    formatter: function (cell, formatterParams, onRendered) {
                        const id = cell.getValue();
                        return `
                            <div class="flex justify-center">
                                <button onclick="viewProductReport(${id})" 
                                    class="text-indigo-600 hover:text-indigo-900 text-sm font-medium px-3 py-1 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                                    View Details
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

        window.productsReportTable = productsReportTable;

        productsReportTable.on("tableBuilt", () => {
            initSearch();
            initExport();
            initColumnVisibility();
            initPeriodFilter();
            fixTabulatorLayout();
        });
        initProductSalesChart();
    });

    // ============================
    // SEARCH FUNCTIONALITY
    // ============================
    function initSearch() {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keyup', function () {
            productsReportTable.setFilter([
                [
                    { field: "name", type: "like", value: this.value },
                    { field: "sku", type: "like", value: this.value },
                    { field: "category", type: "like", value: this.value },
                    { field: "id", type: "like", value: this.value }
                ]
            ]);
        });
    }

    // ============================
    // COLUMN VISIBILITY
    // ============================
    function initColumnVisibility() {
        const columnVisibilityBtn = document.getElementById('columnVisibilityBtn');
        const columnMenu = document.createElement('div');
        columnMenu.className =
            'absolute mt-12 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 hidden ' +
            'right-12 md:right-24 md:left-auto left-0';

        const columns = productsReportTable.getColumnDefinitions();

        columns.forEach((column, index) => {
            if (column.field === "id") return; // skip checkbox column

            const field = column.field;

            const columnBtn = document.createElement('button');
            columnBtn.className =
                'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
            columnBtn.innerHTML = `
                <input type="checkbox" class="mr-2" ${productsReportTable.getColumn(field).isVisible() ? 'checked' : ''}>
                ${column.title}
            `;

            columnBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                e.preventDefault();

                const col = productsReportTable.getColumn(field);
                const checkbox = this.querySelector('input');

                col.toggle();

                setTimeout(() => {
                    checkbox.checked = col.isVisible();
                }, 10);
            });

            columnMenu.appendChild(columnBtn);
        });

        // Toggle menu open/close
        columnVisibilityBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            columnMenu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!columnMenu.contains(e.target) && e.target !== columnVisibilityBtn) {
                columnMenu.classList.add('hidden');
            }
        });

        columnVisibilityBtn.parentElement.appendChild(columnMenu);
    }

    function initProductSalesChart() {
        const ctx = document.getElementById('productSalesChart').getContext('2d');

        const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul"];
        const salesData = [1200, 1800, 1500, 2000, 1750, 2300, 2500];

        new Chart(ctx, {
            type: "line",
            data: {
                labels: labels,
                datasets: [{
                    label: "Units Sold",
                    data: salesData,
                    borderWidth: 2,
                    tension: 0.4,
                    borderColor: "rgb(99,102,241)",
                    backgroundColor: "rgba(99,102,241,0.2)",
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true },
                    x: { grid: { display: false } }
                }
            }
        });
    }

    // ============================
    // EXPORT FUNCTIONALITY
    // ============================
    function initExport() {
        const exportBtns = document.querySelectorAll('[data-export]');

        exportBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                const format = this.getAttribute('data-export');

                switch (format) {
                    case 'csv':
                        productsReportTable.download("csv", "product_report.csv");
                        break;
                    case 'xlsx':
                        productsReportTable.download("xlsx", "product_report.xlsx", { sheetName: "Top Products" });
                        break;
                    case 'pdf':
                        toastr.info("PDF export would require additional configuration");
                        break;
                    case 'print':
                        window.print();
                        break;
                }
            });
        });

        // Main export button
        document.getElementById('exportReportBtn').addEventListener('click', function () {
            Swal.fire({
                title: 'Export Report',
                html: `
                    <div class="text-left space-y-3">
                        <p class="text-gray-600">Select report format:</p>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="exportReport('full_excel')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-left">
                                <i class="fas fa-file-excel text-emerald-600 text-lg mb-1"></i>
                                <p class="font-medium">Full Excel Report</p>
                                <p class="text-xs text-gray-500">All data with charts</p>
                            </button>
                            <button onclick="exportReport('summary_pdf')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-left">
                                <i class="fas fa-file-pdf text-rose-600 text-lg mb-1"></i>
                                <p class="font-medium">Summary PDF</p>
                                <p class="text-xs text-gray-500">Key metrics & insights</p>
                            </button>
                        </div>
                    </div>
                `,
                showConfirmButton: false,
                showCancelButton: true,
                cancelButtonText: 'Cancel'
            });
        });
    }

    // ============================
    // PERIOD FILTER
    // ============================
    function initPeriodFilter() {
        const periodFilter = document.getElementById('periodFilter');

        periodFilter.addEventListener('change', function () {
            const period = this.value;

            // Show loading
            toastr.info(`Loading data for ${period === '30' ? 'Last 30 Days' : period === '90' ? 'Last 90 Days' : period === '365' ? 'This Year' : 'All Time'}...`);

            // Simulate API call
            setTimeout(() => {
                // In a real app, you would fetch new data based on the period
                // For now, we'll just update the metrics
                updateMetricsBasedOnPeriod(period);
                toastr.success('Report data updated!');
            }, 500);
        });
    }

    // ============================
    // CATEGORY DISTRIBUTION
    // ============================
    function initCategoryDistribution() {
        const container = document.getElementById('categoryDistribution');

        window.categoryDistributionData.forEach((category, index) => {
            const colors = [
                'bg-indigo-500',
                'bg-blue-500',
                'bg-emerald-500',
                'bg-amber-500',
                'bg-rose-500'
            ];

            const item = document.createElement('div');
            item.className = 'flex items-center justify-between';
            item.innerHTML = `
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 ${colors[index]} rounded-full"></div>
                    <span class="text-sm font-medium text-gray-700">
                        ${category.name}
                    </span>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-32 bg-gray-200 h-2 rounded-full">
                        <div class="${colors[index]} h-2 rounded-full" style="width: ${category.percentage}%"></div>
                    </div>

                    <span class="text-sm font-medium w-16 text-right">
                        $${category.sales.toLocaleString()}
                    </span>

                    <span class="text-sm text-gray-500 w-10">
                        ${category.percentage}%
                    </span>
                </div>
            `;

            container.appendChild(item);
        });
    }

    // ============================
    // METRICS FUNCTIONS
    // ============================
    function updateMetrics() {
        const totalProducts = window.productsReportData.length;
        const totalSold = window.productsReportData.reduce((sum, product) => sum + product.units_sold, 0);
        const outOfStock = window.productsReportData.filter(p => p.stock_status === 'out_of_stock').length;
        const lowStock = window.productsReportData.filter(p => p.stock_status === 'low_stock').length;

        document.getElementById('totalProducts').textContent = totalProducts.toLocaleString();
        document.getElementById('totalSold').textContent = totalSold.toLocaleString();
        document.getElementById('outOfStock').textContent = outOfStock;
        document.getElementById('lowStock').textContent = lowStock;
    }

    function updateMetricsBasedOnPeriod(period) {
        // In a real app, this would fetch new metrics based on the selected period
        // For demonstration, we'll just update with some dummy variations
        const variations = {
            '30': { total: 856, sold: 12458, out: 23, low: 45 },
            '90': { total: 1240, sold: 35800, out: 45, low: 89 },
            '365': { total: 1856, sold: 145800, out: 67, low: 156 },
            'all': { total: 2856, sold: 458000, out: 89, low: 234 }
        };

        const data = variations[period] || variations['30'];

        document.getElementById('totalProducts').textContent = data.total.toLocaleString();
        document.getElementById('totalSold').textContent = data.sold.toLocaleString();
        document.getElementById('outOfStock').textContent = data.out;
        document.getElementById('lowStock').textContent = data.low;
    }

    // ============================
    // EXPORT REPORT
    // ============================
    function exportReport(type) {
        Swal.fire({
            title: 'Generating Report...',
            text: 'Please wait while we prepare your report.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        setTimeout(() => {
            Swal.close();
            if (type === 'full_excel') {
                productsReportTable.download("xlsx", "product_analytics_report.xlsx", { sheetName: "Product Analytics" });
            } else {
                toastr.success('PDF report would be generated here (requires additional configuration)');
            }
        }, 1500);
    }

    // ============================
    // VIEW PRODUCT REPORT
    // ============================
    function viewProductReport(id) {
        const product = window.productsReportData.find(p => p.id === id);

        if (!product) return;

        Swal.fire({
            title: "Product Performance",
            width: 520,
            html: `
                <div class="text-left space-y-4">

                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-gray-600 text-2xl"></i>
                        </div>

                        <div>
                            <p class="font-semibold text-gray-900">${product.name}</p>
                            <p class="text-sm text-gray-500">${product.sku}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>Rank:</strong></div>
                        <div>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${product.rank === 1 ? 'bg-amber-100 text-amber-800' :
                    product.rank === 2 ? 'bg-blue-100 text-blue-800' :
                        product.rank === 3 ? 'bg-emerald-100 text-emerald-800' :
                            'bg-gray-100 text-gray-800'
                }">
                                #${product.rank}
                            </span>
                        </div>
                        
                        <div><strong>Category:</strong></div><div>${product.category}</div>
                        <div><strong>Units Sold:</strong></div><div>${product.units_sold.toLocaleString()}</div>
                        <div><strong>Total Revenue:</strong></div><div>$${product.revenue.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                        <div><strong>Avg. Price:</strong></div><div>$${(product.revenue / product.units_sold).toFixed(2)}</div>
                        <div><strong>Growth Rate:</strong></div><div><span class="text-emerald-600 font-medium">${product.growth}%</span></div>

                        <div><strong>Stock Status:</strong></div>
                        <div>
                            ${product.stock_status === 'in_stock'
                    ? '<span class="status-badge status-active">In Stock</span>'
                    : product.stock_status === 'low_stock'
                        ? '<span class="status-badge status-pending">Low Stock</span>'
                        : '<span class="status-badge status-inactive">Out of Stock</span>'
                }
                            <span class="ml-2 text-sm text-gray-600">(${product.stock} units)</span>
                        </div>

                        <div><strong>Product Status:</strong></div>
                        <div>
                            ${product.status === 'active'
                    ? '<span class="status-badge status-active">Active</span>'
                    : '<span class="status-badge status-inactive">Inactive</span>'
                }
                        </div>
                    </div>

                    <div class="border-t pt-4">
                        <h4 class="font-medium text-gray-800 mb-2">Sales Trend (Last 30 Days)</h4>
                        <div class="h-32 bg-gray-50 rounded-lg flex items-center justify-center">
                            <p class="text-gray-400 text-sm">Trend chart placeholder</p>
                        </div>
                    </div>

                </div>
            `,
            confirmButtonText: "Close",
            customClass: {
                popup: 'mobile-swal'
            }
        });
    }

    // Fix layout function
    function fixTabulatorLayout() {
        if (productsReportTable) {
            productsReportTable.redraw(true);
        }
    }
</script>
@endpush