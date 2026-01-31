@extends('admin.layouts.master')

@section('title', 'Customer Reports')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Customer Reports</h2>
            <p class="text-gray-600">Analyze customer behavior and demographics</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select id="periodFilter"
                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                <option value="30">Last 30 Days</option>
                <option value="90" selected>Last 90 Days</option>
                <option value="365">This Year</option>
                <option value="all">All Time</option>
            </select>

            <button id="exportReportBtn" class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export Report
            </button>
        </div>
    </div>
</div>

<!-- Customer Metrics -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- Total Customers -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Customers</p>
                <p id="totalCustomers" class="text-2xl font-bold text-gray-900 mt-1">5,423</p>
                <p class="text-sm text-emerald-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>15.3% increase
                </p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-users text-indigo-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- New Customers -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">New Customers</p>
                <p id="newCustomers" class="text-2xl font-bold text-gray-900 mt-1">248</p>
                <p class="text-sm text-emerald-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>8.7% increase</p>
            </div>
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-user-plus text-emerald-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- AOV -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Avg. Order Value</p>
                <p id="avgOrderValue" class="text-2xl font-bold text-gray-900 mt-1">$89.50</p>
                <p class="text-sm text-emerald-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>5.2% increase</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-shopping-cart text-blue-600 text-lg"></i>
            </div>
        </div>
    </div>

    <!-- Repeat Rate -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Repeat Rate</p>
                <p id="repeatRate" class="text-2xl font-bold text-gray-900 mt-1">42%</p>
                <p class="text-sm text-rose-600 mt-1">
                    <i class="fas fa-arrow-down mr-1"></i>2.1% decrease
                </p>
            </div>
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-redo text-amber-600 text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Customer Analytics -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Growth Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Customer Growth</h3>
        <div class="h-80 bg-gray-50 rounded-xl flex items-center justify-center">
            <canvas id="customerGrowthChart"></canvas>
        </div>
    </div>

    <!-- Demographics -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Customer Demographics</h3>

        <!-- Age Group -->
        <div class="mb-6">
            <div class="flex justify-between text-sm mb-3">
                <span class="text-gray-600">Age Group</span>
                <span class="font-medium">Distribution</span>
            </div>

            <div class="space-y-3">
                @php
                $ages = [
                    ['18-24', 15],
                    ['25-34', 35],
                    ['35-44', 28],
                    ['45+', 22],
                ];
                @endphp
                
                @foreach($ages as $a)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 w-16">{{ $a[0] }}</span>

                    <div class="w-40 bg-gray-200 h-2 rounded-full">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $a[1] }}%"></div>
                    </div>

                    <span class="text-sm font-medium w-10 text-right">{{ $a[1] }}%</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Locations -->
        <div>
            <div class="flex justify-between text-sm mb-3">
                <span class="text-gray-600">Location</span>
                <span class="font-medium">Customers</span>
            </div>

            <div class="space-y-2">
                @php
                $locations = [
                    ['United States', 3245],
                    ['Canada', 856],
                    ['United Kingdom', 723],
                    ['Australia', 456],
                    ['Other', 143],
                ];
                @endphp
                
                @foreach($locations as $l)
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">{{ $l[0] }}</span>
                    <span class="text-sm font-medium">{{ $l[1] }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Top Customers Table - Tabulator -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Top Customers by Lifetime Value</h3>
    </div>

    <div class="p-6">
        <!-- Tabulator Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchInput" placeholder="Search customers..."
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
        <div id="customersReportTable"></div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Static customer reports data
    window.customersReportData = [
        {
            id: 1,
            rank: 1,
            name: "John Smith",
            email: "john.smith@example.com",
            total_orders: 15,
            total_spent: 1875.50,
            avg_order_value: 125.03,
            last_order: new Date(Date.now() - 2 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jan 15, 2022",
            location: "New York, USA",
            status: "active"
        },
        {
            id: 2,
            rank: 2,
            name: "Sarah Johnson",
            email: "sarah.j@example.com",
            total_orders: 12,
            total_spent: 1420.00,
            avg_order_value: 118.33,
            last_order: new Date(Date.now() - 5 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Mar 22, 2022",
            location: "Los Angeles, USA",
            status: "active"
        },
        {
            id: 3,
            rank: 3,
            name: "Michael Chen",
            email: "michael.c@example.com",
            total_orders: 10,
            total_spent: 1250.75,
            avg_order_value: 125.08,
            last_order: new Date(Date.now() - 1 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Feb 10, 2022",
            location: "Toronto, Canada",
            status: "active"
        },
        {
            id: 4,
            rank: 4,
            name: "Emma Wilson",
            email: "emma.w@example.com",
            total_orders: 9,
            total_spent: 975.25,
            avg_order_value: 108.36,
            last_order: new Date(Date.now() - 7 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Apr 05, 2022",
            location: "London, UK",
            status: "active"
        },
        {
            id: 5,
            rank: 5,
            name: "Robert Brown",
            email: "robert.b@example.com",
            total_orders: 8,
            total_spent: 825.00,
            avg_order_value: 103.13,
            last_order: new Date(Date.now() - 14 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "May 18, 2022",
            location: "Sydney, Australia",
            status: "active"
        },
        {
            id: 6,
            rank: 6,
            name: "Lisa Anderson",
            email: "lisa.a@example.com",
            total_orders: 7,
            total_spent: 675.50,
            avg_order_value: 96.50,
            last_order: new Date(Date.now() - 3 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jun 30, 2022",
            location: "Chicago, USA",
            status: "active"
        },
        {
            id: 7,
            rank: 7,
            name: "David Miller",
            email: "david.m@example.com",
            total_orders: 7,
            total_spent: 625.25,
            avg_order_value: 89.32,
            last_order: new Date(Date.now() - 10 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jul 12, 2022",
            location: "Vancouver, Canada",
            status: "active"
        },
        {
            id: 8,
            rank: 8,
            name: "Jennifer Lee",
            email: "jennifer.l@example.com",
            total_orders: 6,
            total_spent: 550.00,
            avg_order_value: 91.67,
            last_order: new Date(Date.now() - 21 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Aug 08, 2022",
            location: "Manchester, UK",
            status: "inactive"
        },
        {
            id: 9,
            rank: 9,
            name: "Thomas White",
            email: "thomas.w@example.com",
            total_orders: 5,
            total_spent: 475.75,
            avg_order_value: 95.15,
            last_order: new Date(Date.now() - 28 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Sep 25, 2022",
            location: "Melbourne, Australia",
            status: "active"
        },
        {
            id: 10,
            rank: 10,
            name: "Maria Garcia",
            email: "maria.g@example.com",
            total_orders: 5,
            total_spent: 425.50,
            avg_order_value: 85.10,
            last_order: new Date(Date.now() - 35 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Oct 15, 2022",
            location: "Miami, USA",
            status: "active"
        },
        {
            id: 11,
            rank: 11,
            name: "James Wilson",
            email: "james.w@example.com",
            total_orders: 4,
            total_spent: 380.00,
            avg_order_value: 95.00,
            last_order: new Date(Date.now() - 42 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Nov 05, 2022",
            location: "Edinburgh, UK",
            status: "active"
        },
        {
            id: 12,
            rank: 12,
            name: "Patricia Taylor",
            email: "patricia.t@example.com",
            total_orders: 4,
            total_spent: 350.25,
            avg_order_value: 87.56,
            last_order: new Date(Date.now() - 49 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Dec 10, 2022",
            location: "Brisbane, Australia",
            status: "inactive"
        },
        {
            id: 13,
            rank: 13,
            name: "Christopher Martinez",
            email: "chris.m@example.com",
            total_orders: 3,
            total_spent: 300.00,
            avg_order_value: 100.00,
            last_order: new Date(Date.now() - 56 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Jan 05, 2023",
            location: "Calgary, Canada",
            status: "active"
        },
        {
            id: 14,
            rank: 14,
            name: "Linda Harris",
            email: "linda.h@example.com",
            total_orders: 3,
            total_spent: 275.50,
            avg_order_value: 91.83,
            last_order: new Date(Date.now() - 63 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Feb 14, 2023",
            location: "Dublin, Ireland",
            status: "active"
        },
        {
            id: 15,
            rank: 15,
            name: "William Clark",
            email: "william.c@example.com",
            total_orders: 2,
            total_spent: 225.00,
            avg_order_value: 112.50,
            last_order: new Date(Date.now() - 70 * 86400000).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }),
            customer_since: "Mar 22, 2023",
            location: "Auckland, New Zealand",
            status: "active"
        }
    ];

    // Initialize Tabulator
    let customersReportTable;

    document.addEventListener('DOMContentLoaded', function () {
        // Update metrics
        updateMetrics();
        initCustomerGrowthChart(); 
        
        // Create Tabulator
        customersReportTable = new Tabulator("#customersReportTable", {
            data: customersReportData,
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
                    title: "Customer",
                    field: "name",
                    sorter: "string",
                    responsive: 0,
                    width: 280,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Search Customer…",
                    formatter: function (cell, formatterParams, onRendered) {
                        const rowData = cell.getRow().getData();
                        const initials = rowData.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2);
                        return `
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                                ${initials}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="font-medium text-gray-900 truncate">${rowData.name}</p>
                                <p class="text-sm text-gray-500 truncate">${rowData.email}</p>
                                <p class="text-xs text-gray-400 truncate">${rowData.location}</p>
                            </div>
                        </div>
                    `;
                    }
                },
                {
                    title: "Total Orders",
                    field: "total_orders",
                    width: 120,
                    sorter: "number",
                    hozAlign: "center",
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Orders…",
                    formatter: function (cell) {
                        const orders = cell.getValue();
                        return `<div class="text-center">
                        <span class="font-semibold text-gray-900">${orders}</span>
                        <div class="text-xs text-gray-500">orders</div>
                    </div>`;
                    }
                },
                {
                    title: "Total Spent",
                    field: "total_spent",
                    width: 140,
                    sorter: "number",
                    hozAlign: "right",
                    responsive: 0,
                    formatter: function (cell) {
                        const spent = cell.getValue();
                        return `<div class="text-right">
                        <span class="font-bold text-gray-900">$${spent.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                        <div class="text-xs text-gray-500">lifetime value</div>
                    </div>`;
                    }
                },
                {
                    title: "Avg. Order Value",
                    field: "avg_order_value",
                    width: 150,
                    sorter: "number",
                    hozAlign: "right",
                    responsive: 0,
                    formatter: function (cell) {
                        const aov = cell.getValue();
                        return `<div class="text-right">
                        <span class="font-semibold text-gray-900">$${aov.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</span>
                        <div class="text-xs text-gray-500">per order</div>
                    </div>`;
                    }
                },
                {
                    title: "Last Order",
                    field: "last_order",
                    width: 130,
                    sorter: "string",
                    hozAlign: "center",
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Date…",
                    formatter: function (cell) {
                        const date = cell.getValue();
                        return `<div class="text-center">
                        <span class="font-medium text-gray-900">${date}</span>
                        <div class="text-xs text-gray-500">last purchase</div>
                    </div>`;
                    }
                },
                {
                    title: "Customer Since",
                    field: "customer_since",
                    width: 130,
                    sorter: "string",
                    hozAlign: "center",
                    responsive: 0,
                    headerFilter: "input",
                    headerFilterPlaceholder: "Date…"
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
                            <button onclick="viewCustomer(${id})" 
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

        window.customersReportTable = customersReportTable;

        customersReportTable.on("tableBuilt", () => {
            initSearch();
            initExport();
            initColumnVisibility();
            initPeriodFilter();
            fixTabulatorLayout();
        });

    });

    // ============================
    // SEARCH FUNCTIONALITY
    // ============================
    function initSearch() {
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('keyup', function () {
            customersReportTable.setFilter([
                [
                    { field: "name", type: "like", value: this.value },
                    { field: "email", type: "like", value: this.value },
                    { field: "location", type: "like", value: this.value },
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

        const columns = customersReportTable.getColumnDefinitions();

        columns.forEach((column, index) => {
            if (column.field === "id") return; // skip actions column

            const field = column.field;

            const columnBtn = document.createElement('button');
            columnBtn.className =
                'w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-50 text-sm flex items-center';
            columnBtn.innerHTML = `
            <input type="checkbox" class="mr-2" ${customersReportTable.getColumn(field).isVisible() ? 'checked' : ''}>
            ${column.title}
        `;

            columnBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                e.preventDefault();

                const col = customersReportTable.getColumn(field);
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
                        customersReportTable.download("csv", "customer_report.csv");
                        break;
                    case 'xlsx':
                        customersReportTable.download("xlsx", "customer_report.xlsx", { sheetName: "Top Customers" });
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
                        <button onclick="exportCustomerReport('full_excel')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-left">
                            <i class="fas fa-file-excel text-emerald-600 text-lg mb-1"></i>
                            <p class="font-medium">Full Excel Report</p>
                            <p class="text-xs text-gray-500">All data with charts</p>
                        </button>
                        <button onclick="exportCustomerReport('summary_pdf')" class="p-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-left">
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
                updateMetricsBasedOnPeriod(period);
                toastr.success('Customer data updated!');
            }, 500);
        });
    }

    // ============================
    // METRICS FUNCTIONS
    // ============================
    function updateMetrics() {
        const totalCustomers = window.customersReportData.length;
        const newCustomers = window.customersReportData.filter(c => {
            const customerSince = new Date(c.customer_since);
            const ninetyDaysAgo = new Date(Date.now() - 90 * 86400000);
            return customerSince > ninetyDaysAgo;
        }).length;

        const totalSpent = window.customersReportData.reduce((sum, customer) => sum + customer.total_spent, 0);
        const avgOrderValue = totalSpent / window.customersReportData.reduce((sum, customer) => sum + customer.total_orders, 0);
        const repeatCustomers = window.customersReportData.filter(c => c.total_orders > 1).length;
        const repeatRate = (repeatCustomers / totalCustomers * 100).toFixed(1);

        document.getElementById('totalCustomers').textContent = totalCustomers.toLocaleString();
        document.getElementById('newCustomers').textContent = newCustomers.toLocaleString();
        document.getElementById('avgOrderValue').textContent = `$${avgOrderValue.toFixed(2)}`;
        document.getElementById('repeatRate').textContent = `${repeatRate}%`;
    }

    function updateMetricsBasedOnPeriod(period) {
        // Simulate different metrics based on period
        const variations = {
            '30': { total: 5423, new: 248, aov: 89.50, repeat: 42 },
            '90': { total: 7321, new: 589, aov: 92.75, repeat: 45 },
            '365': { total: 12458, new: 2150, aov: 95.20, repeat: 48 },
            'all': { total: 18542, new: 5423, aov: 88.90, repeat: 52 }
        };

        const data = variations[period] || variations['90'];

        document.getElementById('totalCustomers').textContent = data.total.toLocaleString();
        document.getElementById('newCustomers').textContent = data.new.toLocaleString();
        document.getElementById('avgOrderValue').textContent = `$${data.aov.toFixed(2)}`;
        document.getElementById('repeatRate').textContent = `${data.repeat}%`;
    }

    // ============================
    // EXPORT CUSTOMER REPORT
    // ============================
    function exportCustomerReport(type) {
        Swal.fire({
            title: 'Generating Report...',
            text: 'Please wait while we prepare your customer report.',
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
                customersReportTable.download("xlsx", "customer_analytics_report.xlsx", { sheetName: "Customer Analytics" });
            } else {
                toastr.success('PDF report would be generated here (requires additional configuration)');
            }
        }, 1500);
    }

    // ============================
    // VIEW CUSTOMER DETAILS (Updated for Tabulator)
    // ============================
    function viewCustomer(id) {
        const customer = window.customersReportData.find(c => c.id === id);

        if (!customer) return;

        Swal.fire({
            title: "Customer Details",
            width: 500,
            html: `
            <div class="text-left space-y-4">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-r from-indigo-400 to-purple-500 flex items-center justify-center text-white text-xl font-bold">
                        ${customer.name.split(' ').map(n => n[0]).join('').toUpperCase().substring(0, 2)}
                    </div>

                    <div>
                        <p class="font-semibold text-gray-900">${customer.name}</p>
                        <p class="text-sm text-gray-500">${customer.email}</p>
                        <p class="text-xs text-gray-400">${customer.location}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div><strong>Rank:</strong></div>
                    <div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${customer.rank === 1 ? 'bg-amber-100 text-amber-800' :
                    customer.rank === 2 ? 'bg-blue-100 text-blue-800' :
                        customer.rank === 3 ? 'bg-emerald-100 text-emerald-800' :
                            'bg-gray-100 text-gray-800'
                }">
                            #${customer.rank}
                        </span>
                    </div>
                    
                    <div><strong>Total Orders:</strong></div><div>${customer.total_orders}</div>
                    <div><strong>Total Spent:</strong></div><div>$${customer.total_spent.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                    <div><strong>AOV:</strong></div><div>$${customer.avg_order_value.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                    <div><strong>Last Order:</strong></div><div>${customer.last_order}</div>
                    <div><strong>Member Since:</strong></div><div>${customer.customer_since}</div>

                    <div><strong>Status:</strong></div>
                    <div>
                        ${customer.status === 'active'
                    ? '<span class="status-badge status-active">Active</span>'
                    : '<span class="status-badge status-inactive">Inactive</span>'
                }
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h4 class="font-medium text-gray-800 mb-2">Purchase History (Last 6 Months)</h4>
                    <div class="h-32 bg-gray-50 rounded-lg flex items-center justify-center">
                        <p class="text-gray-400 text-sm">Purchase history chart placeholder</p>
                    </div>
                </div>
            </div>
        `,
            confirmButtonText: "Close",
        });
    }

    function initCustomerGrowthChart() {
    const ctx = document.getElementById('customerGrowthChart').getContext('2d');

    const labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun"];
    const newCustomers = [120, 150, 180, 220, 210, 248];

    new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: "New Customers",
                data: newCustomers,
                borderWidth: 2,
                borderColor: "rgba(99, 102, 241, 1)", // Indigo
                backgroundColor: "rgba(99, 102, 241, 0.2)",
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false }},
            scales: {
                y: { beginAtZero: true },
                x: { grid: { display: false }}
            }
        }
    });
}

    // Fix layout function
    function fixTabulatorLayout() {
        if (customersReportTable) {
            customersReportTable.redraw(true);
        }
    }
</script>
@endpush