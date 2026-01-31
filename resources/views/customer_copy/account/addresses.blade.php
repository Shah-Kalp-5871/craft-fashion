@extends('customer.layouts.master')

@section('title', 'My Addresses - ' . config('app.name'))

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2">
            <li><a href="{{ route('customer.home.index') }}" class="text-amber-600 hover:text-amber-800">Home</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li><a href="{{ route('customer.account.profile') }}" class="text-amber-600 hover:text-amber-800">My Account</a></li>
            <li><i class="fas fa-chevron-right text-gray-400 text-xs"></i></li>
            <li class="text-gray-600">My Addresses</li>
        </ol>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <!-- User Info -->
                <div class="flex items-center gap-4 mb-6 pb-6 border-b">
                    <div class="w-16 h-16 bg-gradient-to-br from-amber-100 to-amber-200 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-2xl text-amber-700"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800">{{ $customer->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $customer->email ?? $customer->mobile }}</p>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="space-y-2">
                    <a href="{{ route('customer.account.profile') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('customer.wishlist.index') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-heart"></i>
                        <span>My Wishlist</span>
                        @php
                            $wishlistCount = \App\Models\Wishlist::where('customer_id', $customer->id)->count();
                        @endphp
                        @if($wishlistCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $wishlistCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.orders') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-shopping-bag"></i>
                        <span>My Orders</span>
                        @php
                            $ordersCount = \App\Models\Order::where('customer_id', $customer->id)->count();
                        @endphp
                        @if($ordersCount > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $ordersCount }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.addresses') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg bg-amber-50 text-amber-700">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Addresses</span>
                        @if($addresses->count() > 0)
                        <span class="ml-auto bg-amber-600 text-white text-xs px-2 py-1 rounded-full">
                            {{ $addresses->count() }}
                        </span>
                        @endif
                    </a>

                    <a href="{{ route('customer.account.change-password') }}"
                       class="flex items-center gap-3 px-4 py-3 rounded-lg text-gray-700 hover:bg-amber-50">
                        <i class="fas fa-lock"></i>
                        <span>Change Password</span>
                    </a>

                    <form method="POST" action="{{ route('customer.logout') }}" class="mt-6">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-3 px-4 py-3 rounded-lg text-red-600 hover:bg-red-50 w-full">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </nav>
            </div>
        </div>

        <!-- Main Content -->
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">My Addresses ({{ $addresses->count() }})</h2>
                    <button onclick="openAddAddressModal()"
                            class="px-6 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700">
                        <i class="fas fa-plus mr-2"></i>Add New Address
                    </button>
                </div>

                @if($addresses->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($addresses as $address)
                    <div class="relative border {{ $address->is_default ? 'border-2 border-amber-500' : 'border-gray-200' }} rounded-2xl p-6 {{ $address->is_default ? 'bg-amber-50' : '' }} hover:shadow-lg transition-shadow">
                        @if($address->is_default)
                        <div class="absolute top-4 right-4">
                            <span class="bg-amber-600 text-white text-xs px-3 py-1 rounded-full">Default</span>
                        </div>
                        @endif

                        <div class="mb-4">
                            <h3 class="font-bold text-gray-800 text-lg">{{ $address->name }}</h3>
                            <p class="text-gray-600">
                                @switch($address->type)
                                    @case('shipping')
                                        <i class="fas fa-truck mr-1"></i> Shipping Address
                                        @break
                                    @case('billing')
                                        <i class="fas fa-file-invoice mr-1"></i> Billing Address
                                        @break
                                    @case('both')
                                        <i class="fas fa-address-card mr-1"></i> Shipping & Billing
                                        @break
                                @endswitch
                            </p>
                        </div>

                        <div class="space-y-2 text-gray-600">
                            <p>{{ $address->address }}</p>
                            <p>{{ $address->city }}, {{ $address->state }} {{ $address->pincode }}</p>
                            <p>{{ $address->country }}</p>
                            <div class="pt-2">
                                <p><i class="fas fa-phone mr-2"></i> {{ $address->mobile }}</p>
                            </div>
                        </div>

                        <div class="flex gap-3 mt-6 pt-6 border-t {{ $address->is_default ? 'border-amber-200' : 'border-gray-200' }}">
                            <button onclick="editAddress({{ $address->id }})"
                                    class="px-4 py-2 border border-amber-600 text-amber-600 rounded-lg hover:bg-amber-50">
                                <i class="fas fa-edit mr-2"></i>Edit
                            </button>

                            @if(!$address->is_default)
                            <form method="POST" action="{{ route('customer.account.addresses.set-default', $address->id) }}" class="inline">
                                @csrf
                                <button type="submit"
                                        class="px-4 py-2 border border-gray-600 text-gray-600 rounded-lg hover:bg-gray-50">
                                    <i class="fas fa-star mr-2"></i>Set as Default
                                </button>
                            </form>
                            @else
                            <button class="px-4 py-2 border border-gray-300 text-gray-400 rounded-lg cursor-not-allowed" disabled>
                                <i class="fas fa-star mr-2"></i>Default
                            </button>
                            @endif

                            <form method="POST" action="{{ route('customer.account.addresses.delete', $address->id) }}" class="inline ml-auto" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-4 py-2 border border-red-600 text-red-600 rounded-lg hover:bg-red-50">
                                    <i class="fas fa-trash mr-2"></i>Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <!-- Empty State -->
                <div class="text-center py-12" id="emptyAddresses">
                    <i class="fas fa-map-marker-alt text-5xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">No Addresses Saved</h3>
                    <p class="text-gray-600 mb-6">You haven't saved any addresses yet. Add your first address to get started.</p>
                    <button onclick="openAddAddressModal()"
                            class="inline-flex items-center gap-3 bg-gradient-to-r from-amber-600 to-amber-800 text-white px-8 py-3 rounded-full font-bold shadow-lg hover:shadow-xl">
                        <i class="fas fa-plus mr-2"></i>
                        Add New Address
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Address Modal -->
<div id="addressModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-8">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-2xl font-bold text-gray-800" id="modalTitle">Add New Address</h3>
                <button onclick="closeAddressModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="addressForm" method="POST" action="{{ route('customer.account.addresses.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" id="addressId" name="id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Full Name *</label>
                        <input type="text" id="fullName" name="name" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Address Type</label>
                        <select id="addressType" name="type"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                            <option value="shipping">Shipping</option>
                            <option value="billing">Billing</option>
                            <option value="both">Shipping & Billing</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Mobile Number *</label>
                    <input type="text" id="mobile" name="mobile" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Address Line 1 *</label>
                    <input type="text" id="addressLine1" name="address" required
                           class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">City *</label>
                        <input type="text" id="city" name="city" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">State *</label>
                        <input type="text" id="state" name="state" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Pincode *</label>
                        <input type="text" id="pincode" name="pincode" required
                               class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Country *</label>
                        <select id="country" name="country" required
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-amber-500 focus:ring-2 focus:ring-amber-200 focus:outline-none">
                            <option value="IN" selected>India</option>
                            <option value="US">United States</option>
                            <option value="GB">United Kingdom</option>
                            <option value="CA">Canada</option>
                            <option value="AU">Australia</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Set as Default</label>
                        <div class="flex items-center mt-2">
                            <input type="checkbox" id="setAsDefault" name="is_default" value="1"
                                   class="w-5 h-5 text-amber-600 rounded focus:ring-amber-500">
                            <label for="setAsDefault" class="ml-2 text-gray-600">
                                Set this as my default address
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex gap-4 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddressModal()"
                            class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-8 py-3 bg-amber-600 text-white rounded-lg hover:bg-amber-700 flex-1">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openAddAddressModal() {
    document.getElementById('modalTitle').textContent = 'Add New Address';
    document.getElementById('addressForm').action = "{{ route('customer.account.addresses.store') }}";
    document.getElementById('addressForm').reset();
    document.getElementById('addressId').value = '';
    document.getElementById('addressModal').classList.remove('hidden');
    document.getElementById('addressModal').classList.add('flex');
}

function editAddress(addressId) {
    // Fetch address data via AJAX
    fetch(`/api/addresses/${addressId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('modalTitle').textContent = 'Edit Address';
            document.getElementById('addressForm').action = `/account/addresses/${addressId}`;

            // Add method spoofing for PUT request
            let methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            document.getElementById('addressForm').appendChild(methodInput);

            // Fill form with address data
            document.getElementById('addressId').value = data.id;
            document.getElementById('fullName').value = data.name;
            document.getElementById('mobile').value = data.mobile;
            document.getElementById('addressLine1').value = data.address;
            document.getElementById('city').value = data.city;
            document.getElementById('state').value = data.state;
            document.getElementById('pincode').value = data.pincode;
            document.getElementById('country').value = data.country;
            document.getElementById('addressType').value = data.type;
            document.getElementById('setAsDefault').checked = data.is_default == 1;

            document.getElementById('addressModal').classList.remove('hidden');
            document.getElementById('addressModal').classList.add('flex');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to load address data');
        });
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.add('hidden');
    document.getElementById('addressModal').classList.remove('flex');
    // Remove method input if exists
    const methodInput = document.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
}
</script>
@endpush
