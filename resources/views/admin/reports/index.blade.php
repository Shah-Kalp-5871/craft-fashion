@extends('admin.layouts.master')

@section('title', 'Reports Dashboard')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-1">Reports Dashboard</h2>
            <p class="text-gray-600">Comprehensive overview of your store performance and analytics</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <select class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-indigo-500">
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
                <option>This Year</option>
                <option>All Time</option>
            </select>

            <button class="btn-primary">
                <i class="fas fa-download mr-2"></i>Export All Reports
            </button>
        </div>
    </div>
</div>

<!-- KPIs -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
    <!-- KPI Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Revenue</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">$24,568</p>
                <p class="text-sm text-emerald-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>12.5% increase</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center">
                <i class="fas fa-dollar-sign text-white text-lg"></i>
            </div>
        </div>
    </div>

    <!-- KPI Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">1,248</p>
                <p class="text-sm text-emerald-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>8.2% increase</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center">
                <i class="fas fa-shopping-cart text-white text-lg"></i>
            </div>
        </div>
    </div>

    <!-- KPI Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Avg. Order Value</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">$89.50</p>
                <p class="text-sm text-emerald-600 mt-1"><i class="fas fa-arrow-up mr-1"></i>5.3% increase</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center">
                <i class="fas fa-chart-line text-white text-lg"></i>
            </div>
        </div>
    </div>

    <!-- KPI Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 card-hover">
        <div class="flex justify-between items-center">
            <div>
                <p class="text-sm text-gray-600">Conversion Rate</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">3.2%</p>
                <p class="text-sm text-rose-600 mt-1"><i class="fas fa-arrow-down mr-1"></i>0.8% decrease</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center">
                <i class="fas fa-percentage text-white text-lg"></i>
            </div>
        </div>
    </div>
</div>

<!-- Overview Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
    <!-- Sales Report Summary -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 card-hover">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Sales Report</h3>
            <a href="{{ route('admin.reports.sales') }}"
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>

        <div class="space-y-3">
            <div class="flex justify-between"><span>Total Sales</span><span class="font-semibold">$24,568</span></div>
            <div class="flex justify-between"><span>Orders</span><span class="font-semibold">1,248</span></div>
            <div class="flex justify-between"><span>Avg. Daily Sales</span><span class="font-semibold">$819</span></div>
            <div class="flex justify-between"><span>Refund Rate</span><span class="font-semibold text-rose-600">2.3%</span></div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500 flex items-center">
            <i class="fas fa-chart-line mr-2"></i>Last updated: Today, 9:42 AM
        </div>
    </div>

    <!-- Customers Report Summary -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 card-hover">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Customers Report</h3>
            <a href="{{ route('admin.reports.customers') }}"
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>

        <div class="space-y-3">
            <div class="flex justify-between"><span>Total Customers</span><span class="font-semibold">5,423</span></div>
            <div class="flex justify-between"><span>New This Month</span><span class="font-semibold">248</span></div>
            <div class="flex justify-between"><span>Repeat Rate</span><span class="font-semibold">42%</span></div>
            <div class="flex justify-between"><span>Avg. Lifetime Value</span><span class="font-semibold">$450</span></div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500 flex items-center">
            <i class="fas fa-users mr-2"></i>Customer growth: +15.3% this month
        </div>
    </div>

    <!-- Products Report Summary -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 card-hover">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Products Report</h3>
            <a href="{{ route('admin.reports.products') }}"
               class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">View Details</a>
        </div>

        <div class="space-y-3">
            <div class="flex justify-between"><span>Total Products</span><span class="font-semibold">856</span></div>
            <div class="flex justify-between"><span>Products Sold</span><span class="font-semibold">12,458</span></div>
            <div class="flex justify-between"><span>Out of Stock</span><span class="font-semibold text-rose-600">23</span></div>
            <div class="flex justify-between"><span>Low Stock</span><span class="font-semibold text-amber-600">45</span></div>
        </div>

        <div class="mt-4 pt-4 border-t border-gray-200 text-sm text-gray-500 flex items-center">
            <i class="fas fa-box mr-2"></i>Inventory value: $189,250
        </div>
    </div>
</div>

<!-- Performance Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
    <!-- Revenue Trend -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Revenue Trend</h3>
            <select class="border border-gray-300 rounded-lg px-3 py-1 text-sm focus:ring-2 focus:ring-indigo-500">
                <option>Last 7 Days</option>
                <option>Last 30 Days</option>
                <option>Last 90 Days</option>
            </select>
        </div>

        <div class="h-64 bg-gray-50 rounded-xl flex items-center justify-center">
            <div class="text-center">
                <i class="fas fa-chart-bar text-4xl text-gray-300 mb-3"></i>
                <p class="text-gray-500">Revenue trend chart</p>
                <p class="text-sm text-gray-400">Showing daily revenue for the last 30 days</p>
            </div>
        </div>
    </div>

    <!-- Top Categories -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-semibold text-gray-800">Top Categories</h3>
            <span class="text-sm text-gray-500">By Revenue</span>
        </div>

        <div class="space-y-4">
            @php
            $categories = [
                ['name'=>'Electronics','revenue'=>12500,'percentage'=>35,'growth'=>12],
                ['name'=>'Clothing','revenue'=>8900,'percentage'=>25,'growth'=>8],
                ['name'=>'Home & Kitchen','revenue'=>7200,'percentage'=>20,'growth'=>15],
                ['name'=>'Accessories','revenue'=>5400,'percentage'=>15,'growth'=>5],
                ['name'=>'Books','revenue'=>1800,'percentage'=>5,'growth'=>-2]
            ];
            @endphp
            
            @foreach($categories as $c)
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3 flex-1">
                    <div class="w-3 h-3 bg-indigo-500 rounded-full"></div>
                    <span class="text-sm font-medium text-gray-700 flex-1">{{ $c['name'] }}</span>
                </div>

                <div class="flex items-center gap-4 w-48">
                    <div class="w-24 bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $c['percentage'] }}%"></div>
                    </div>
                    <span class="text-sm font-medium w-16 text-right">${{ number_format($c['revenue']) }}</span>
                    <span class="text-sm {{ $c['growth']>=0?'text-emerald-600':'text-rose-600' }} w-12">
                        {{ ($c['growth']>=0?'+':'').$c['growth'] }}%
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Quick Metrics -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Key Performance Metrics</h3>

        <div class="grid grid-cols-2 gap-4">
            @php
            $metrics = [
                ['$89.50','Average Order Value','emerald'],
                ['2.4','Items per Order','blue'],
                ['42%','Repeat Customer Rate','purple'],
                ['18.3%','Cart Abandonment','amber'],
                ['2.3%','Refund Rate','rose']
            ];
            @endphp
            
            @foreach($metrics as $m)
            <div class="text-center p-4 bg-gray-50 rounded-xl">
                <p class="text-2xl font-bold text-{{ $m[2] }}-600">{{ $m[0] }}</p>
                <p class="text-sm text-gray-600">{{ $m[1] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-6">Recent Report Activity</h3>
        <div class="space-y-4">
            @php
            $activity = [
                ['icon'=>'file-export','color'=>'emerald','title'=>'Sales Report Exported','time'=>'Today, 9:30 AM','badge'=>'Completed'],
                ['icon'=>'chart-bar','color'=>'blue','title'=>'Customer Analytics Generated','time'=>'Yesterday, 3:15 PM','badge'=>'Processed'],
                ['icon'=>'exclamation-triangle','color'=>'amber','title'=>'Low Stock Alert Report','time'=>'2 days ago','badge'=>'Warning'],
                ['icon'=>'file-pdf','color'=>'purple','title'=>'Monthly Report PDF','time'=>'3 days ago','badge'=>'Generated']
            ];
            @endphp
            
            @foreach($activity as $a)
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-{{ $a['color'] }}-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-{{ $a['icon'] }} text-{{ $a['color'] }}-600"></i>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-900">{{ $a['title'] }}</p>
                        <p class="text-xs text-gray-500">{{ $a['time'] }}</p>
                    </div>
                </div>

                <span class="text-xs text-{{ $a['color'] }}-600 bg-{{ $a['color'] }}-50 px-2 py-1 rounded-full">
                    {{ $a['badge'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="bg-white mt-10 rounded-2xl border border-gray-100 shadow-sm p-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Report Actions</h3>

    <div class="flex flex-wrap gap-3">
        <button onclick="generateCustomReport()" class="btn-primary">
            <i class="fas fa-plus mr-2"></i>Create Custom Report
        </button>

        <button onclick="scheduleReport()" class="btn-secondary">
            <i class="fas fa-clock mr-2"></i>Schedule Report
        </button>

        <button onclick="exportAllReports()" class="btn-success">
            <i class="fas fa-file-export mr-2"></i>Export All
        </button>

        <button onclick="viewReportHistory()" class="btn-secondary">
            <i class="fas fa-history mr-2"></i>History
        </button>
    </div>
</div>
@endsection

@push('scripts')
<script>
/* ----- Custom Report Modal ----- */
function generateCustomReport() {
    Swal.fire({
        title: "Generate Custom Report",
        width: 600,
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Report Type</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option>Sales Report</option>
                        <option>Customer Report</option>
                        <option>Product Report</option>
                        <option>Inventory Report</option>
                    </select>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm mb-1 font-medium">Start Date</label>
                        <input type="date" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-sm mb-1 font-medium">End Date</label>
                        <input type="date" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Format</label>
                    <div class="flex gap-4">
                        <label class="flex items-center"><input type="radio" name="format" checked> <span class="ml-2">PDF</span></label>
                        <label class="flex items-center"><input type="radio" name="format"> <span class="ml-2">Excel</span></label>
                        <label class="flex items-center"><input type="radio" name="format"> <span class="ml-2">CSV</span></label>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Generate Report",
        customClass: { confirmButton: 'btn-primary', cancelButton: 'btn-secondary' }
    }).then(res => {
        if (res.isConfirmed) toastr.success("Custom report generation started!");
    });
}

/* ----- Schedule Modal ----- */
function scheduleReport() {
    Swal.fire({
        title: "Schedule Report",
        width: 550,
        html: `
            <div class="text-left space-y-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Report Type</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option>Daily Sales</option>
                        <option>Weekly Summary</option>
                        <option>Monthly Overview</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Frequency</label>
                    <select class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                        <option>Daily</option>
                        <option>Weekly</option>
                        <option>Monthly</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Email Recipient</label>
                    <input type="email" placeholder="Enter email" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: "Schedule",
        customClass: { confirmButton: 'btn-primary', cancelButton: 'btn-secondary' }
    }).then(res => {
        if (res.isConfirmed) toastr.success("Report scheduled!");
    });
}

/* ----- Export All ----- */
function exportAllReports() {
    Swal.fire({
        title: "Export All Reports",
        text: "Exports Sales, Customers, Products & Inventory reports in ZIP.",
        icon: "info",
        showCancelButton: true,
        confirmButtonText: "Export All",
        customClass: { confirmButton: 'btn-primary', cancelButton: 'btn-secondary' }
    }).then(res => {
        if (res.isConfirmed) toastr.success("Export started!");
    });
}

/* ----- History ----- */ 
function viewReportHistory() {
    Swal.fire({
        title: "Report History",
        width: 500,
        html: `
            <div class="max-h-80 overflow-y-auto space-y-3">
                ${[1,2,3,4,5].map(i => `
                    <div class="flex justify-between items-center p-3 border rounded-lg">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-pdf text-rose-500 text-lg"></i>
                            <div>
                                <p class="font-medium text-sm">Sales Report ${i}</p>
                                <p class="text-xs text-gray-500">${i} days ago</p>
                            </div>
                        </div>
                        <button class="text-indigo-600 hover:text-indigo-900">Download</button>
                    </div>
                `).join('')}
            </div>
        `,
        confirmButtonText: "Close",
    });
}
</script>
@endpush