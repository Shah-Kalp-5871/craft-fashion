{{-- resources/views/admin/customers/index.blade.php --}}
@extends('admin.layouts.master')

@section('title', 'Customer Management - Admin Panel')

@section('content')
<div class="mb-8">
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Customer Management</h2>
            <p class="text-gray-600">Manage your customer accounts and profiles</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <select id="statusFilter" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-sm">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="blocked">Blocked</option>
            </select>
            <button onclick="openAddUserModal()" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Add New Customer
            </button>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">All Customers</h3>
    </div>
    <div class="p-6">
        <!-- Toolbar -->
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 mb-4">
            <div class="order-2 sm:order-1">
                <div class="relative" style="width: 260px;">
                    <input type="text" id="searchUsersInput" placeholder="Search customers..."
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
            <table id="customersTable" class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" onclick="toggleSelectAll(this)">
                        </th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Orders</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="customersTableBody" class="bg-white divide-y divide-gray-200">
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

    .status-active {
        background-color: #d1fae5;
        color: #065f46;
    }

    .status-inactive {
        background-color: #fef3c7;
        color: #92400e;
    }

    .status-blocked {
        background-color: #fee2e2;
        color: #991b1b;
    }
</style>
@endpush

@push('scripts')
<script>
    // Route templates (these will be replaced with actual URLs)
    const routeTemplates = {
        edit: '{{ route("admin.users.edit", ["user" => ":id"]) }}',
        toggleBlock: '{{ route("admin.users.toggle-block", ["user" => ":id"]) }}',
        toggleStatus: '{{ route("admin.users.toggle-status", ["user" => ":id"]) }}',
        destroy: '{{ route("admin.users.destroy", ["user" => ":id"]) }}',
        view: '{{ route("admin.users.show", ["user" => ":id"]) }}'
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
    let currentFilter = '';
    let currentSearch = '';
    let selectedCustomers = new Set();

    document.addEventListener('DOMContentLoaded', function() {
        loadCustomers();

        // Search functionality
        const searchInput = document.getElementById('searchUsersInput');
        let searchTimeout;

        searchInput.addEventListener('keyup', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                currentSearch = this.value;
                currentPage = 1;
                loadCustomers();
            }, 500);
        });

        // Status filter
        const statusFilter = document.getElementById('statusFilter');
        statusFilter.addEventListener('change', function() {
            currentFilter = this.value;
            currentPage = 1;
            loadCustomers();
        });

        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', function(e) {
            const checkboxes = document.querySelectorAll('.customer-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
                if (e.target.checked) {
                    selectedCustomers.add(checkbox.value);
                } else {
                    selectedCustomers.delete(checkbox.value);
                }
            });
        });
    });

    function loadCustomers() {
        showLoading();

        const params = new URLSearchParams({
            page: currentPage,
            status: currentFilter,
            search: currentSearch,
            per_page: 10
        });

        fetch(`{{ route('admin.users.data') }}?${params}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderCustomers(data.data);
                renderPagination(data.pagination);
            } else {
                toastr.error('Error loading customers');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error loading customers');
            hideLoading();
        });
    }

    function renderCustomers(customers) {
        const tbody = document.getElementById('customersTableBody');
        tbody.innerHTML = '';

        if (customers.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                        No customers found
                    </td>
                </tr>
            `;
            return;
        }

        customers.forEach(customer => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50';

            const statusClass = customer.is_block ? 'status-blocked' :
                               customer.status ? 'status-active' : 'status-inactive';
            const statusText = customer.is_block ? 'Blocked' :
                              customer.status ? 'Active' : 'Inactive';

            const createdAt = new Date(customer.created_at);
            const formattedDate = createdAt.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });

            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="checkbox" class="customer-checkbox" value="${customer.id}"
                           onchange="toggleCustomerSelection(${customer.id}, this.checked)">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    #${customer.id}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="h-10 w-10 flex-shrink-0">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center text-white font-semibold">
                                ${customer.name.charAt(0).toUpperCase()}
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">
                                ${customer.name}
                            </div>
                            <div class="text-sm text-gray-500">
                                Customer
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <a href="mailto:${customer.email}" class="text-indigo-600 hover:text-indigo-900">
                        ${customer.email}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${customer.mobile || 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${customer.orders_count > 10 ? 'bg-emerald-100 text-emerald-800' : 'bg-blue-100 text-blue-800'}">
                        <i class="fas fa-shopping-bag mr-1"></i>${customer.orders_count}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ₹${(Number(customer.total_spent || 0).toFixed(2))}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="status-badge ${statusClass}">
                        ${statusText}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${formattedDate}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewCustomer(${customer.id})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editCustomer(${customer.id})" class="text-indigo-600 hover:text-indigo-900">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="toggleBlock(${customer.id})" class="${customer.is_block ? 'text-green-600 hover:text-green-900' : 'text-yellow-600 hover:text-yellow-900'}">
                            <i class="fas ${customer.is_block ? 'fa-unlock' : 'fa-ban'}"></i>
                        </button>
                        <button onclick="deleteCustomer(${customer.id})" class="text-red-600 hover:text-red-900">
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
        loadCustomers();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function toggleSelectAll(checkbox) {
        const checkboxes = document.querySelectorAll('.customer-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = checkbox.checked;
            if (checkbox.checked) {
                selectedCustomers.add(cb.value);
            } else {
                selectedCustomers.delete(cb.value);
            }
        });
    }

    function toggleCustomerSelection(id, isChecked) {
        if (isChecked) {
            selectedCustomers.add(id);
        } else {
            selectedCustomers.delete(id);
            document.getElementById('selectAll').checked = false;
        }
    }

    function openAddUserModal() {
        Swal.fire({
            title: 'Add New Customer',
            html: `
                <form id="addCustomerForm" class="text-left space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                            <input type="text" name="name" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                        <input type="tel" name="mobile" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                            <input type="password" name="password" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="status" checked class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Active Account</span>
                            </label>
                        </div>
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_block" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                <span class="ml-2 text-sm text-gray-700">Block Account</span>
                            </label>
                        </div>
                    </div>
                </form>
            `,
            showCancelButton: true,
            confirmButtonText: 'Add Customer',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn-primary',
                cancelButton: 'btn-secondary'
            },
            preConfirm: () => {
                const form = document.getElementById('addCustomerForm');
                const formData = new FormData(form);
                const data = Object.fromEntries(formData.entries());

                // Convert checkbox values to boolean
                data.status = form.querySelector('[name="status"]').checked;
                data.is_block = form.querySelector('[name="is_block"]').checked;

                // Validate
                if (!data.name || !data.email || !data.password || !data.password_confirmation) {
                    Swal.showValidationMessage('Please fill in all required fields');
                    return false;
                }

                if (data.password !== data.password_confirmation) {
                    Swal.showValidationMessage('Passwords do not match');
                    return false;
                }

                return data;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                addCustomer(result.value);
            }
        });
    }

    function addCustomer(data) {
        showLoading();

        fetch('{{ route("admin.users.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message);
                loadCustomers();
            } else {
                toastr.error(data.message || 'Error adding customer');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error adding customer');
            hideLoading();
        });
    }

    function viewCustomer(id) {
        showLoading();

        // Since we don't have a show route, we'll fetch from the data endpoint
        fetch(`{{ route('admin.users.data') }}?search=${id}&per_page=1`, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                const customer = data.data[0];
                Swal.fire({
                    title: 'Customer Details',
                    html: `
                        <div class="text-left space-y-4">
                            <div class="flex items-center space-x-4 pb-4 border-b">
                                <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-purple-500 rounded-full flex items-center justify-center text-white font-bold text-2xl">
                                    ${customer.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <p class="text-xl font-semibold text-gray-900">${customer.name}</p>
                                    <p class="text-sm text-gray-500">Customer ID: #${customer.id}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-500 font-medium mb-1">Email</p>
                                    <p class="text-gray-900">${customer.email}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium mb-1">Mobile</p>
                                    <p class="text-gray-900">${customer.mobile || 'N/A'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium mb-1">Status</p>
                                    <p class="text-gray-900">${customer.status ? 'Active' : 'Inactive'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium mb-1">Blocked</p>
                                    <p class="text-gray-900">${customer.is_block ? 'Yes' : 'No'}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium mb-1">Total Orders</p>
                                    <p class="text-gray-900">${customer.orders_count || 0}</p>
                                </div>
                                <div>
                                    <p class="text-gray-500 font-medium mb-1">Joined Date</p>
                                    <p class="text-gray-900">${new Date(customer.created_at).toLocaleDateString()}</p>
                                </div>
                                ${customer.is_block ? `
                                    <div class="col-span-2">
                                        <p class="text-gray-500 font-medium mb-1">Block Reason</p>
                                        <p class="text-gray-900">${customer.block_reason || 'No reason provided'}</p>
                                    </div>
                                ` : ''}
                            </div>
                        </div>
                    `,
                    width: '600px',
                    showCancelButton: true,
                    confirmButtonText: 'Close',
                    cancelButtonText: 'Edit Customer',
                    customClass: {
                        confirmButton: 'btn-primary',
                        cancelButton: 'btn-secondary'
                    }
                }).then((result) => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        editCustomer(id);
                    }
                });
            } else {
                toastr.error('Error loading customer details');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('Error loading customer details');
            hideLoading();
        });
    }

    function editCustomer(id) {
        // Using route template with parameter replacement
        const editUrl = routeTemplates.edit.replace(':id', id);
        window.location.href = editUrl;
    }

    function toggleBlock(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: 'This will block/unblock the customer account',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, proceed!',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn-primary',
                cancelButton: 'btn-secondary'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                const url = routeTemplates.toggleBlock.replace(':id', id);
                fetch(url, {
                    method: 'POST',
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
                        loadCustomers();
                    } else {
                        toastr.error(data.message || 'Error updating block status');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error updating block status');
                    hideLoading();
                });
            }
        });
    }

    function deleteCustomer(id) {
        Swal.fire({
            title: 'Are you sure?',
            html: `
                <p>You are about to delete this customer.</p>
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
                        loadCustomers();
                    } else {
                        toastr.error(data.message || 'Error deleting customer');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error deleting customer');
                    hideLoading();
                });
            }
        });
    }

    function exportToCSV() {
        showLoading();

        const params = new URLSearchParams({
            status: currentFilter,
            search: currentSearch,
            export: 'csv'
        });

        window.location.href = `{{ route('admin.users.export') }}?${params}`;
        setTimeout(hideLoading, 1000);
    }

    function exportToExcel() {
        showLoading();

        const params = new URLSearchParams({
            status: currentFilter,
            search: currentSearch,
            export: 'excel'
        });

        window.location.href = `{{ route('admin.users.export') }}?${params}`;
        setTimeout(hideLoading, 1000);
    }

    function printTable() {
        const printContent = document.getElementById('customersTable').outerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = `
            <html>
                <head>
                    <title>Customers Report</title>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                    <style>
                        body { font-family: Arial, sans-serif; }
                        table { width: 100%; border-collapse: collapse; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f8f9fa; }
                        .status-badge { padding: 2px 8px; border-radius: 12px; font-size: 12px; }
                        .status-active { background-color: #d1fae5; color: #065f46; }
                        .status-inactive { background-color: #fef3c7; color: #92400e; }
                        .status-blocked { background-color: #fee2e2; color: #991b1b; }
                    </style>
                </head>
                <body>
                    <h2>Customers Report</h2>
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
        if (selectedCustomers.size === 0) {
            toastr.warning('Please select at least one customer');
            return;
        }

        Swal.fire({
            title: 'Bulk Actions',
            html: `
                <div class="space-y-3">
                    <button onclick="bulkDelete()" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-red-600 border border-gray-200 rounded-lg">
                        <i class="fas fa-trash mr-2"></i>Delete Selected (${selectedCustomers.size})
                    </button>
                    <button onclick="bulkBlock()" class="w-full text-left px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-yellow-600 border border-gray-200 rounded-lg">
                        <i class="fas fa-ban mr-2"></i>Block Selected (${selectedCustomers.size})
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
        if (selectedCustomers.size === 0) {
            toastr.warning('Please select at least one customer');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            html: `
                <p>You are about to delete ${selectedCustomers.size} customer(s).</p>
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

                fetch('{{ route("admin.users.bulk-delete") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: Array.from(selectedCustomers) })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        selectedCustomers.clear();
                        document.getElementById('selectAll').checked = false;
                        loadCustomers();
                    } else {
                        toastr.error(data.message || 'Error deleting customers');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error deleting customers');
                    hideLoading();
                });
            }
        });
    }

    function bulkBlock() {
        if (selectedCustomers.size === 0) {
            toastr.warning('Please select at least one customer');
            return;
        }

        Swal.fire({
            title: 'Block Customers',
            input: 'textarea',
            inputLabel: 'Reason for blocking (optional)',
            inputPlaceholder: 'Enter reason...',
            showCancelButton: true,
            confirmButtonText: 'Block Customers',
            cancelButtonText: 'Cancel',
            customClass: {
                confirmButton: 'btn-primary',
                cancelButton: 'btn-secondary'
            },
            preConfirm: (reason) => {
                return { reason: reason || null };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                showLoading();

                fetch('{{ route("admin.users.bulk-block") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        ids: Array.from(selectedCustomers),
                        reason: result.value.reason
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        toastr.success(data.message);
                        selectedCustomers.clear();
                        document.getElementById('selectAll').checked = false;
                        loadCustomers();
                    } else {
                        toastr.error(data.message || 'Error blocking customers');
                    }
                    hideLoading();
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('Error blocking customers');
                    hideLoading();
                });
            }
        });
    }
</script>
@endpush
