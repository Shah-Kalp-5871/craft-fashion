@extends('admin.layouts.master')

@section('title', 'Edit Customer - Admin Panel')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-800">Edit Customer</h2>
                <p class="text-gray-600">Update customer information</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                <i class="fas fa-arrow-left mr-2"></i>Back to Customers
            </a>
        </div>
    </div>

    <!-- Premium Card -->
    <div class="bg-white shadow-sm border border-gray-200 rounded-2xl p-8">
        <form id="editCustomerForm" class="space-y-8" action="{{ route('admin.users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" id="userId" value="{{ $user->id }}">

            <!-- Section: Basic Info -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label">Full Name *</label>
                        <input type="text" name="name" class="form-input" value="{{ $user->name }}" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" value="{{ $user->email }}" required>
                    </div>
                    <div>
                        <label class="form-label">Mobile</label>
                        <input type="text" name="mobile" class="form-input" value="{{ $user->mobile }}">
                    </div>
                </div>
            </div>

            <!-- Section: Address -->
            <!-- Note: Address field is not in the model based on controller analysis, removing or keeping as UI placeholder if needed?
                 Controller only validates name, email, mobile, status, is_block.
                 I will hide it for now to avoid confusion as it won't be saved, or I can leave it but it won't persist.
                 Given the strict instructions, I will align with the controller.
            -->

            <!-- Section: Login & Status -->
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Security & Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Leave blank to keep current">
                    </div>
                    <div class="space-y-4 mt-2">
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="status" name="status" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ $user->status ? 'checked' : '' }}>
                            <label for="status" class="text-sm text-gray-700">Active Account</label>
                        </div>
                        <div class="flex items-center space-x-3">
                            <input type="checkbox" id="is_block" name="is_block" class="w-5 h-5 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" {{ $user->is_block ? 'checked' : '' }}>
                            <label for="is_block" class="text-sm text-gray-700">Block Account</label>
                        </div>
                    </div>
                </div>
                 <!-- Block Reason (Visible only if blocked) -->
                 <div id="blockReasonContainer" class="mt-4 {{ $user->is_block ? '' : 'hidden' }}">
                    <label class="form-label">Block Reason</label>
                    <textarea name="block_reason" rows="2" class="form-input" placeholder="Reason for blocking account...">{{ $user->block_reason }}</textarea>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('admin.users.index') }}" class="btn-secondary">
                    <i class="fas fa-arrow-left mr-2"></i>Back
                </a>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle block reason visibility
    const blockCheckbox = document.getElementById('is_block');
    const blockReasonContainer = document.getElementById('blockReasonContainer');

    blockCheckbox.addEventListener('change', function() {
        if (this.checked) {
            blockReasonContainer.classList.remove('hidden');
        } else {
            blockReasonContainer.classList.add('hidden');
        }
    });

    // Form Submission
    document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;

        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

        // Prepare data
        const formData = new FormData(form);
        // Add checkboxes explicitly if unchecked (FormData doesn't include unchecked checkboxes)
        if (!form.querySelector('[name="status"]').checked) formData.append('status', '0');
        else formData.append('status', '1');

        if (!form.querySelector('[name="is_block"]').checked) formData.append('is_block', '0');
        else formData.append('is_block', '1');

        // Initial check for password confirmation if password field was present
        // (Controller doesn't validate password on update unless complex logic, but here we just send what we have)
        // Converting FormData to JSON object for fetch
        const data = {};
        formData.forEach((value, key) => {
            // handle booleans for checkboxes if needed, but backend validation 'boolean' works with 0/1 or true/false strings usually
            // Laravel validation 'boolean' accepts 0, 1, '0', '1', true, false.
            if (key === 'status' || key === 'is_block') {
                 data[key] = value === '1' || value === 'on' ? 1 : 0;
            } else {
                data[key] = value;
            }
        });


        fetch(form.action, {
            method: 'POST', // Using POST with _method PUT
            headers: {
                'Content-Type': 'application/json', // Using JSON as Controller expects
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({
                ...data,
                _method: 'PUT' // Spoof PUT
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                toastr.success(data.message || "Customer updated successfully!");
                setTimeout(() => {
                    window.location.href = "{{ route('admin.users.index') }}";
                }, 1000);
            } else {
                toastr.error(data.message || "Error updating customer");
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error("An unexpected error occurred");
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText;
        });
    });
});
</script>

<style>
.form-label {
    @apply block text-sm font-medium text-gray-700 mb-1;
}
.form-input {
    @apply w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500;
}
</style>
@endsection