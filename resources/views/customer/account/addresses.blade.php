@extends('customer.layouts.master')

@section('title', 'My Addresses | ' . config('constants.SITE_NAME'))

@section('content')
<section class="py-12 bg-gray-50 min-h-[60vh]">
    <div class="container mx-auto px-4">
        <!-- Breadcrumb -->
        <div class="mb-8">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('customer.home.index') }}" class="inline-flex items-center text-sm font-medium text-secondary hover:text-primary">
                            <i class="fas fa-home mr-2"></i>
                            Home
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <a href="{{ route('customer.account.profile') }}" class="text-sm font-medium text-secondary hover:text-primary">My Account</a>
                        </div>
                    </li>
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-dark md:ml-2">My Addresses</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Page Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold font-playfair text-dark mb-2">My Addresses</h1>
                <p class="text-secondary">Manage your shipping and billing addresses.</p>
            </div>
            <button onclick="openAddressModal()" class="bg-primary text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-primary/90 transition shadow-sm flex items-center">
                <i class="fas fa-plus mr-2"></i> Add New Address
            </button>
        </div>

        @if(session('success'))
            <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6 flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-50 text-red-700 p-4 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($addresses->isEmpty())
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-map-marker-alt text-2xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-dark mb-2">No addresses saved yet</h3>
                <p class="text-secondary text-sm mb-6">Add an address for faster checkout.</p>
                <button onclick="openAddressModal()" class="text-primary font-medium hover:underline">
                    Add Address Now
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($addresses as $address)
                    <div class="bg-white rounded-2xl shadow-sm border {{ $address->is_default ? 'border-primary ring-1 ring-primary/20' : 'border-gray-100' }} p-6 relative group hover:shadow-md transition-shadow">
                        @if($address->is_default)
                            <div class="absolute top-4 right-4 bg-primary/10 text-primary text-xs font-semibold px-2 py-1 rounded-full">
                                Default
                            </div>
                        @else
                           <!-- Set Default Action -->
                           <div class="absolute top-4 right-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                <form action="{{ route('customer.account.addresses.set-default', $address->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-secondary hover:text-primary underline">Set as Default</button>
                                </form>
                           </div>
                        @endif

                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-500 mr-3">
                                @if($address->type == 'home')
                                    <i class="fas fa-home"></i>
                                @elseif($address->type == 'work')
                                    <i class="fas fa-building"></i>
                                @else
                                    <i class="fas fa-map-marker-alt"></i>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-dark text-lg">{{ ucfirst($address->type ?? 'Address') }}</h3>
                                <p class="text-xs text-secondary">{{ $address->name }}</p>
                            </div>
                        </div>

                        <div class="space-y-1 text-sm text-secondary mb-6 min-h-[80px]">
                            <p class="text-dark">{{ $address->address }}</p>
                            <p>{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</p>
                            <p>{{ $address->country }}</p>
                            <p class="pt-2"><i class="fas fa-phone-alt mr-2 text-xs"></i>{{ $address->mobile }}</p>
                        </div>

                        <div class="border-t border-gray-100 pt-4 flex justify-between items-center">
                            <button onclick='editAddress(@json($address))' class="text-primary text-sm font-medium hover:underline flex items-center">
                                <i class="far fa-edit mr-1.5"></i> Edit
                            </button>
                            
                            <form action="{{ route('customer.account.addresses.delete', $address->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 text-sm font-medium hover:underline flex items-center">
                                    <i class="far fa-trash-alt mr-1.5"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>

<!-- Address Modal -->
<div id="addressModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddressModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-lg leading-6 font-bold text-dark" id="modal-title">Add New Address</h3>
                    <button onclick="closeAddressModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <form id="addressForm" action="{{ route('customer.account.addresses.store') }}" method="POST">
                    @csrf
                    <div id="method_field"></div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="col-span-2 md:col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" name="name" id="name" required class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm" placeholder="John Doe">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="mobile" class="block text-sm font-medium text-gray-700 mb-1">Mobile Number</label>
                            <input type="text" name="mobile" id="mobile" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10)" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm" placeholder="9876543210">
                        </div>
                        <div class="col-span-2">
                             <label for="address" class="block text-sm font-medium text-gray-700 mb-1">Address Detail</label>
                             <textarea name="address" id="address" rows="2" required class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm" placeholder="Flat No, Building, Street"></textarea>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="pincode" class="block text-sm font-medium text-gray-700 mb-1">Pincode</label>
                            <input type="text" name="pincode" id="pincode" required maxlength="6" oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6)" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm" placeholder="123456">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" name="city" id="city" required class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm" placeholder="New York">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="state" class="block text-sm font-medium text-gray-700 mb-1">State</label>
                            <input type="text" name="state" id="state" required class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm" placeholder="NY">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                            <select name="country" id="country" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring focus:ring-primary/20 transition shadow-sm text-sm">
                                <option value="IN">India</option>
                                <option value="US">United States</option>
                                <option value="UK">United Kingdom</option>
                                <!-- Add more options as needed -->
                            </select>
                        </div>
                        
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Address Type</label>
                            <div class="flex gap-4">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="home" class="text-primary focus:ring-primary" checked>
                                    <span class="ml-2 text-sm text-gray-600">Home</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="work" class="text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-600">Work</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="shipping" class="text-primary focus:ring-primary">
                                    <span class="ml-2 text-sm text-gray-600">Other</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="col-span-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" id="is_default" class="rounded border-gray-300 text-primary focus:ring-primary" value="1">
                                <span class="ml-2 text-sm text-gray-600">Make this my default address</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end gap-3">
                         <button type="button" onclick="closeAddressModal()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-200 transition">
                            Cancel
                        </button>
                        <button type="submit" class="bg-primary text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-primary/90 transition shadow-sm">
                            Save Address
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openAddressModal() {
        // Reset form
        document.getElementById('addressForm').action = "{{ route('customer.account.addresses.store') }}";
        document.getElementById('method_field').innerHTML = '';
        document.getElementById('modal-title').innerText = 'Add New Address';
        
        document.getElementById('name').value = '';
        document.getElementById('mobile').value = '';
        document.getElementById('address').value = '';
        document.getElementById('pincode').value = '';
        document.getElementById('city').value = '';
        document.getElementById('state').value = '';
        document.getElementById('country').value = 'IN';
        document.getElementById('is_default').checked = false;
        
        // Show modal
        document.getElementById('addressModal').classList.remove('hidden');
    }

    function editAddress(address) {
        // Set form action
        let url = "{{ route('customer.account.addresses.update', ':id') }}";
        url = url.replace(':id', address.id);
        document.getElementById('addressForm').action = url;
        
        // Add PUT method field
        document.getElementById('method_field').innerHTML = '@method("PUT")';
        document.getElementById('modal-title').innerText = 'Edit Address';
        
        // Populate fields
        document.getElementById('name').value = address.name;
        document.getElementById('mobile').value = address.mobile;
        document.getElementById('address').value = address.address;
        document.getElementById('pincode').value = address.pincode;
        document.getElementById('city').value = address.city;
        document.getElementById('state').value = address.state;
        document.getElementById('country').value = address.country;
        
        // Radio buttons
        const radios = document.getElementsByName('type');
        for (let i = 0; i < radios.length; i++) {
            if (radios[i].value === address.type) {
                radios[i].checked = true;
            }
        }
        
        document.getElementById('is_default').checked = address.is_default;
        
        // Show modal
        document.getElementById('addressModal').classList.remove('hidden');
    }

    function closeAddressModal() {
        document.getElementById('addressModal').classList.add('hidden');
    }
</script>
@endsection
