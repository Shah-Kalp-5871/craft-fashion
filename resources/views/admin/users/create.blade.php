@extends('admin.layouts.master')

@section('title', 'Add New Customer - Admin Panel')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Add New Customer</h2>
                <p class="text-gray-600">Create a new customer account</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Customers
            </a>
        </div>
    </div>

    <!-- Premium Card -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-2xl p-8">
        <form id="addCustomerForm" class="space-y-8" action="{{ route('admin.users.store') }}" method="POST">
            @csrf
            
            <!-- Section: Basic Info -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                        <input type="text" name="first_name" id="firstName" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                        <input type="text" name="last_name" id="lastName" class="form-input" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                        <input type="email" name="email" id="email" class="form-input" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Section: Address -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Address</h3>
                <textarea name="address" id="address" rows="3" class="form-input" placeholder="Full address"></textarea>
            </div>

            <!-- Section: Login & Status -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Security & Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password *</label>
                        <input type="password" name="password" id="password" class="form-input" required>
                    </div>
                    <div class="flex items-center space-x-3 mt-7">
                        <input type="checkbox" name="active" id="activeStatus" checked class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <label for="activeStatus" class="text-sm text-gray-700">Active Account</label>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Create Customer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('addCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Validate form
    const firstName = document.getElementById('firstName').value;
    const lastName = document.getElementById('lastName').value;
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    
    if (!firstName || !lastName || !email || !password) {
        toastr.error("Please fill all required fields");
        return;
    }
    
    // Show loading
    Swal.fire({
        title: 'Creating Customer...',
        text: 'Please wait while we create customer account',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Simulate API call (in real app, this would be actual form submission)
    setTimeout(() => {
        Swal.close();
        
        // Generate new user ID for local data
        const newId = Math.max(...window.usersData.map(u => u.id)) + 1;
        
        // Add to local data (in real app, this would be backend)
        const newUser = {
            id: newId,
            name: `${firstName} ${lastName}`,
            email: email,
            phone: document.getElementById('phone').value || '+1 (555) 000-0000',
            orders_count: 0,
            status: document.getElementById('activeStatus').checked ? 'active' : 'inactive',
            joined_date: new Date().toISOString().split('T')[0],
            total_spent: 0.00,
            last_login: new Date().toISOString().split('T')[0],
            address: document.getElementById('address').value || 'Address not provided'
        };
        
        // Add to global data array
        if (typeof window.usersData !== 'undefined') {
            window.usersData.push(newUser);
        }
        
        toastr.success("Customer created successfully!");
        
        // Redirect back
        setTimeout(() => {
            window.location.href = "{{ route('admin.users.index') }}";
        }, 800);
    }, 1500);
});
</script>

<style>
.form-input {
    @apply w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}
</style>
@endsection