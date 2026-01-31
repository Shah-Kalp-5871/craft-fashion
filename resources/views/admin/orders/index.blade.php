{{-- resources/views/admin/orders/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Order Management - Admin Panel')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Order Management</h2>
            <p class="text-gray-600">Manage customer orders and track order status</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="confirmed">Confirmed</option>
                <option value="processing">Processing</option>
                <option value="shipped">Shipped</option>
                <option value="delivered">Delivered</option>
                <option value="cancelled">Cancelled</option>
                <option value="refunded">Refunded</option>
            </select>
            <select id="paymentFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">All Payment</option>
                <option value="pending">Pending</option>
                <option value="paid">Paid</option>
                <option value="partially_paid">Partially Paid</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
        </div>
    </div>
</div>

<!-- Orders Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">All Orders</h3>
    </div>
    <div class="p-6">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchInput" placeholder="Search orders..."
                        class="pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent w-full">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 order-1 sm:order-2">
                <!-- Bulk Actions -->
                <button onclick="showBulkActions()" class="btn-secondary">
                    <i class="fas fa-tasks mr-2"></i>Bulk Actions
                </button>
                <!-- Export Buttons -->
                <button onclick="exportToCSV()" class="btn-secondary">
                    <i class="fas fa-file-csv mr-2"></i>CSV
                </button>
                <button onclick="exportToExcel()" class="btn-secondary">
                    <i class="fas fa-file-excel mr-2"></i>Excel
                </button>
                <button onclick="printTable()" class="btn-secondary">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>

        <!-- Table Container -->
        <div class="overflow-x-auto">
            <table id="ordersTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Dynamic content will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="paginationContainer" class="mt-4">
            <!-- Pagination will be loaded here -->
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
    <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-indigo-600"></div>
</div>
@endsection

@push('styles')
<style>
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
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
        height: 26px;
        width: 26px;
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

    .status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .status-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-confirmed {
        background-color: #dbeafe;
        color: #1e40af;
    }

    .status-processing {
        background-color: #e0e7ff;
        color: #3730a3;
    }

    .status-shipped {
        background-color: #f0f9ff;
        color: #0369a1;
    }

    .status-delivered {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .status-refunded {
        background-color: #f5f5f5;
        color: #525252;
    }

    .payment-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
        font-weight: 600;
    }

    .payment-pending {
        background-color: #fef3c7;
        color: #92400e;
    }

    .payment-paid {
        background-color: #d1fae5;
        color: #065f46;
    }

    .payment-partially_paid {
        background-color: #f0f9ff;
        color: #0369a1;
    }

    .payment-failed {
        background-color: #fee2e2;
        color: #991b1b;
    }

    .payment-refunded {
        background-color: #f5f5f5;
        color: #525252;
    }
</style>
@endpush

@push('scripts')
<script>
    // Route templates
    const routeTemplates = {
        view: '{{ route("admin.orders.view", ["order" => ":id"]) }}',
        updateStatus: '{{ route("admin.orders.update-status", ["order" => ":id"]) }}',
        updatePaymentStatus: '{{ route("admin.orders.update-payment-status", ["order" => ":id"]) }}',
        updateTracking: '{{ route("admin.orders.update-tracking", ["order" => ":id"]) }}',
        destroy: '{{ route("admin.orders.destroy", ["order" => ":id"]) }}',
        invoice: '{{ route("admin.orders.invoice", ["order" => ":id"]) }}'
    };

    // Helper function to replace route parameters
    function getRoute(routeName, params) {
        let url = routeTemplates[routeName];
        for (const key in params) {
            url = url.replace(`:${key}`, params[key]);
        }
        return url;
    }

    let currentPage = 1;
    let currentStatusFilter = '';
    let currentPaymentFilter = '';
    let currentSearch = '';
    let selectedOrders = new Set();

    document.addEventListener('DOMContentLoaded', function() {
        loadOrders();

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        let searchTimeout;

        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = this.value;
                currentPage = 1;
                loadOrders();
            }, 500);
        });

        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function() {
            currentStatusFilter = this.value;
            currentPage = 1;
            loadOrders();
        });

        // Payment filter
        const paymentFilter = document.getElementById('paymentFilter');
        paymentFilter.addEventListener('change', function() {
            currentPaymentFilter = this.value;
            currentPage = 1;
            loadOrders();
        });

        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
                if (e.target.checked) {
                    selectedOrders.add(checkbox.value);
                } else {
                    selectedOrders.delete(checkbox.value);
                }
            });
        });
    });

    function loadOrders() {
        showLoading();

        const params = new URLSearchParams({
            page: currentPage,
            status: currentStatusFilter,
            payment_status: currentPaymentFilter,
            search: currentSearch,
            per_page: 10
        });

        fetch(`{{ route('admin.orders.data') }}?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderOrders(data.data);
                renderPagination(data.pagination);
            } else {
                toastr.error('Error loading orders');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error loading orders');
            hideLoading();
        });
    }

    function renderOrders(orders) {
        const tbody = document.getElementById('ordersTableBody');
        tbody.innerHTML = '';

        if (orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                        No orders found
                    </td>
                </tr>
            `;
            return;
        }

        orders.forEach(order => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';

            const statusClass = `status-${order.status}`;
            const statusText = order.status.charAt(0).toUpperCase() + order.status.slice(1).replace('_', ' ');

            const paymentClass = `payment-${order.payment_status}`;
            const paymentText = order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1).replace('_', ' ');

            const date = new Date(order.created_at);
            const formattedDate = date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" class="order-checkbox" value="${order.id}"
                           onchange="toggleOrderSelection(${order.id}, this.checked)">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                    ${order.order_number}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                ${order.customer_name.charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                ${order.customer_name}
                            </div>
                            <div class="text-sm text-gray-500">
                                ${order.customer_email}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formattedDate}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                        <i class="fas fa-shopping-bag mr-1"></i>${order.items_count}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                    ${parseFloat(order.grand_total).toFixed(2)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="status-badge ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="payment-badge ${paymentClass}">
                        ${paymentText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewOrder(${order.id})" class="text-blue-600 hover:text-blue-900" title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="updateOrderStatus(${order.id})" class="text-indigo-600 hover:text-indigo-900" title="Update Status">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="updatePaymentStatus(${order.id})" class="text-green-600 hover:text-green-900" title="Update Payment">
                            <i class="fas fa-credit-card"></i>
                        </button>
                        <button onclick="updateTracking(${order.id})" class="text-yellow-600 hover:text-yellow-900" title="Add Tracking">
                            <i class="fas fa-truck"></i>
                        </button>
                        <button onclick="printInvoice(${order.id})" class="text-purple-600 hover:text-purple-900" title="Print Invoice">
                            <i class="fas fa-print"></i>
                        </button>
                        <button onclick="deleteOrder(${order.id})" class="text-red-600 hover:text-red-900" title="Delete Order">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            tbody.appendChild(row);
        });
    }

    function renderPagination(pagination) {
        const container = document.getElementById('paginationContainer');

        let html = `
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">${pagination.from}</span> to
                    <span class="font-medium">${pagination.to}</span> of
                    <span class="font-medium">${pagination.total}</span> results
                </div>
                <div class="flex space-x-2">
        `;

        // Previous button
        html += `
            <button onclick="changePage(${currentPage - 1})"
                    ${currentPage === 1 ? 'disabled' : ''}
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                <span class="sr-only">Previous</span>
                <i class="fas fa-chevron-left"></i>
            </button>
        `;

        // Page numbers
        for (let i = 1; i <= pagination.last_page; i++) {
            if (i === 1 || i === pagination.last_page || (i >= currentPage - 2 && i <= currentPage + 2)) {
                html += `
                    <button onclick="changePage(${i})"
                            class="relative inline-flex items-center px-4 py-2 border ${currentPage === i ? 'border-indigo-500 bg-indigo-50 text-indigo-600' : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'} text-sm font-medium">
                        ${i}
                    </button>
                `;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                html += `
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                        ...
                    </span>
                `;
            }
        }

        // Next button
        html += `
            <button onclick="changePage(${currentPage + 1})"
                    ${currentPage === pagination.last_page ? 'disabled' : ''}
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${currentPage === pagination.last_page ? 'opacity-50 cursor-not-allowed' : ''}">
                <span class="sr-only">Next</span>
                <i class="fas fa-chevron-right"></i>
            </button>
        `;

        html += `
                </div>
            </div>
        `;

        container.innerHTML = html;
    }

    function changePage(page) {
        currentPage = page;
        loadOrders();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.order-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
            if (checkbox.checked) {
                selectedOrders.add(cb.value);
            } else {
                selectedOrders.delete(cb.value);
            }
        });
    }

    function toggleOrderSelection(id, isChecked) {
        if (isChecked) {
            selectedOrders.add(id);
        } else {
            selectedOrders.delete(id);
            document.getElementById('selectAll').checked = false;
        }
    }

    function viewOrder(id) {
        const url = routeTemplates.view.replace(':id', id);
        window.location.href = url;
    }

    function updateOrderStatus(id) {
        Swal.fire({
            title: 'Update Order Status',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                        <select id="orderStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="processing">Processing</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="cancelled">Cancelled</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="orderNotes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add notes about status change"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Status',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn-primary',
                cancelButton: 'btn-secondary'
            },
            preConfirm: () => {
                return {
                    status: document.getElementById('orderStatus').value,
                    notes: document.getElementById('orderNotes').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                const url = routeTemplates.updateStatus.replace(':id', id);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        loadOrders();
                    } else {
                        toastr.error(data.message || 'Error updating order status');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error updating order status');
                    hideLoading();
                });
            }
        });
    }

    function updatePaymentStatus(id) {
        Swal.fire({
            title: 'Update Payment Status',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                        <select id="paymentStatus" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="partially_paid">Partially Paid</option>
                            <option value="failed">Failed</option>
                            <option value="refunded">Refunded</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="paymentNotes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add notes about payment status"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Payment',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn-primary',
                cancelButton: 'btn-secondary'
            },
            preConfirm: () => {
                return {
                    payment_status: document.getElementById('paymentStatus').value,
                    notes: document.getElementById('paymentNotes').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                const url = routeTemplates.updatePaymentStatus.replace(':id', id);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        loadOrders();
                    } else {
                        toastr.error(data.message || 'Error updating payment status');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error updating payment status');
                    hideLoading();
                });
            }
        });
    }

    function updateTracking(id) {
        Swal.fire({
            title: 'Update Tracking Information',
            html: `
                <div class="text-left space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number *</label>
                        <input type="text" id="trackingNumber" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Carrier</label>
                        <input type="text" id="carrier" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="e.g., FedEx, UPS, DHL">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                        <textarea id="trackingNotes" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" placeholder="Add notes about shipment"></textarea>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update Tracking',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn-primary',
                cancelButton: 'btn-secondary'
            },
            preConfirm: () => {
                const trackingNumber = document.getElementById('trackingNumber').value;
                if (!trackingNumber) {
                    Swal.showValidationMessage('Please enter tracking number');
                    return false;
                }
                return {
                    tracking_number: trackingNumber,
                    carrier: document.getElementById('carrier').value,
                    notes: document.getElementById('trackingNotes').value
                };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                const url = routeTemplates.updateTracking.replace(':id', id);
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(result.value)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        loadOrders();
                    } else {
                        toastr.error(data.message || 'Error updating tracking information');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error updating tracking information');
                    hideLoading();
                });
            }
        });
    }

    function printInvoice(id) {
        const url = routeTemplates.invoice.replace(':id', id);
        window.open(url, '_blank');
    }

    function deleteOrder(id) {
        Swal.fire({
            title: 'Are you sure?',
            html: `
                <p>You are about to delete this order.</p>
                <p class="text-red-600 font-semibold mt-2">This action cannot be undone!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
            customClass: {
                cancelButton: 'btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                const url = routeTemplates.destroy.replace(':id', id);
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        loadOrders();
                    } else {
                        toastr.error(data.message || 'Error deleting order');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error deleting order');
                    hideLoading();
                });
            }
        });
    }

    function exportToCSV() {
        showLoading();

        const params = new URLSearchParams({
            status: currentStatusFilter,
            payment_status: currentPaymentFilter,
            search: currentSearch,
            export: 'csv'
        });

        window.location.href = `{{ route('admin.orders.export') }}?${params}`;
        setTimeout(hideLoading, 1000);
    }

    function exportToExcel() {
        showLoading();

        const params = new URLSearchParams({
            status: currentStatusFilter,
            payment_status: currentPaymentFilter,
            search: currentSearch,
            export: 'excel'
        });

        window.location.href = `{{ route('admin.orders.export') }}?${params}`;
        setTimeout(hideLoading, 1000);
    }

    function printTable() {
        const printContent = document.getElementById('ordersTable').outerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = `
            <html>
                <head>
                    <title>Orders Report</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                        .status-badge { padding: 2px 8px; border-radius: 12px; font-size: 12px; }
                    </style>
                </head>
                <body>
                    <h2>Orders Report</h2>
                    <p>Generated on: ${new Date().toLocaleDateString()}</p>
                    ${printContent}
                </body>
            </html>
        `;

        window.print();
        document.body.innerHTML = originalContent;
        location.reload();
    }

    function showLoading() {
        document.getElementById('loadingSpinner').classList.remove('hidden');
    }

    function hideLoading() {
        document.getElementById('loadingSpinner').classList.add('hidden');
    }

    function showBulkActions() {
        if (selectedOrders.size === 0) {
            toastr.warning('Please select at least one order');
            return;
        }

        Swal.fire({
            title: 'Bulk Actions',
            html: `
                <div class="space-y-3">
                    <button onclick="bulkDelete()" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-red-600 border border-gray-200 rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Delete Selected (${selectedOrders.size})
                    </button>
                </div>
            `,
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            showConfirmButton: false,
            customClass: {
                container: 'bulk-actions-modal'
            }
        });
    }

    function bulkDelete() {
        if (selectedOrders.size === 0) {
            toastr.warning('Please select at least one order');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            html: `
                <p>You are about to delete ${selectedOrders.size} order(s).</p>
                <p class="text-red-600 font-semibold mt-2">This action cannot be undone!</p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
            customClass: {
                cancelButton: 'btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                fetch('{{ route("admin.orders.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: Array.from(selectedOrders) })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        selectedOrders.clear();
                        document.getElementById('selectAll').checked = false;
                        loadOrders();
                    } else {
                        toastr.error(data.message || 'Error deleting orders');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error deleting orders');
                    hideLoading();
                });
            }
        });
    }
</script>
@endpush
