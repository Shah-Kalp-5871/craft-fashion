@extends('admin.layouts.master')

@section('title', 'Create Home Section')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Home Section</h2>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('admin.home-sections.index') }}" class="hover:text-indigo-600">Home Sections</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Create</span>
            </nav>
        </div>
        <a href="{{ route('admin.home-sections.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
            Back to List
        </a>
    </div>
</div>

<div class="max-w-4xl">
    <form action="{{ route('admin.home-sections.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-6 pb-2 border-b">Section Details</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Section Title <span class="text-rose-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="e.g. Featured Collection">
                    @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Subtitle -->
                <div class="md:col-span-2">
                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="e.g. Handpicked designs for you">
                    @error('subtitle') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Selection Type <span class="text-rose-500">*</span></label>
                    <select name="type" id="type" onchange="toggleTypeFields()" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="category" {{ old('type') == 'category' ? 'selected' : '' }}>Category Based</option>
                        <option value="custom_products" {{ old('type') == 'custom_products' ? 'selected' : '' }}>Custom Product Selection</option>
                    </select>
                    @error('type') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Style -->
                <div>
                    <label for="style" class="block text-sm font-medium text-gray-700 mb-1">Display Style <span class="text-rose-500">*</span></label>
                    <select name="style" id="style" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        @foreach($styles as $value => $label)
                            <option value="{{ $value }}" {{ old('style') == $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('style') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Category Field -->
                <div id="category-field-container" class="{{ old('type', 'category') == 'custom_products' ? 'hidden' : '' }}">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Select Category <span class="text-rose-500">*</span></label>
                    <select name="category_id" id="category_id" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                        <option value="">-- Choose Category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Custom Products Field -->
                <div id="products-field-container" class="md:col-span-2 {{ old('type') == 'custom_products' ? '' : 'hidden' }}">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Products <span class="text-rose-500">*</span></label>
                    
                    <div class="relative mb-4">
                        <input type="text" id="product-search" placeholder="Search products by name or code..." 
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        
                        <!-- Search Results Dropdown -->
                        <div id="search-results" class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto">
                            <!-- Results will be injected here -->
                        </div>
                    </div>

                    <!-- Selected Products List -->
                    <div id="selected-products-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 p-4 border-2 border-dashed border-gray-200 rounded-xl min-h-[100px]">
                        <!-- Selected items will appear here -->
                        <p class="col-span-full text-center text-gray-400 py-4 no-products-msg">No products selected yet</p>
                    </div>
                    @error('product_ids') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-1">Sort Order</label>
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition">
                    @error('sort_order') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Status -->
                <div class="flex items-center pt-6">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="status" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Active Status</span>
                    </label>
                </div>
            </div>
        </div>

        <div class="flex justify-end space-x-4">
            <button type="reset" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">Reset</button>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold shadow-md shadow-indigo-100">Create Section</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function toggleTypeFields() {
    const type = document.getElementById('type').value;
    const catContainer = document.getElementById('category-field-container');
    const prodContainer = document.getElementById('products-field-container');

    if (type === 'category') {
        catContainer.classList.remove('hidden');
        prodContainer.classList.add('hidden');
    } else {
        catContainer.classList.add('hidden');
        prodContainer.classList.remove('hidden');
    }
}

// Product Search & Selection Logic
let selectedProducts = [];
const searchInput = document.getElementById('product-search');
const resultsDiv = document.getElementById('search-results');
const selectedList = document.getElementById('selected-products-list');

searchInput.addEventListener('input', _.debounce(async function() {
    const query = this.value;
    if (query.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }

    try {
        const response = await axios.get(`{{ route('admin.products.search') }}?q=${query}`);
        if (response.data.success) {
            renderSearchResults(response.data.data);
        }
    } catch (error) {
        console.error('Search error:', error);
    }
}, 300));

function renderSearchResults(products) {
    if (products.length === 0) {
        resultsDiv.innerHTML = '<div class="p-4 text-gray-500 text-sm">No products found</div>';
    } else {
        resultsDiv.innerHTML = products.map(p => `
            <div onclick="selectProduct(${p.id}, '${p.name}', '${p.image}')" 
                 class="flex items-center p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0 transition">
                <img src="${p.image}" class="w-10 h-10 object-cover rounded mr-3">
                <div>
                    <div class="font-bold text-sm text-gray-800">${p.name}</div>
                </div>
            </div>
        `).join('');
    }
    resultsDiv.classList.remove('hidden');
}

function selectProduct(id, name, image) {
    if (selectedProducts.some(p => p.id === id)) {
        alert('Product already selected');
        return;
    }

    selectedProducts.push({id, name, image});
    renderSelectedProducts();
    resultsDiv.classList.add('hidden');
    searchInput.value = '';
}

function removeProduct(id) {
    selectedProducts = selectedProducts.filter(p => p.id !== id);
    renderSelectedProducts();
}

function renderSelectedProducts() {
    if (selectedProducts.length === 0) {
        selectedList.innerHTML = '<p class="col-span-full text-center text-gray-400 py-4 no-products-msg">No products selected yet</p>';
    } else {
        selectedList.innerHTML = selectedProducts.map(p => `
            <div class="relative group bg-gray-50 rounded-lg p-2 border border-gray-200">
                <img src="${p.image}" class="w-full h-24 object-cover rounded mb-2">
                <div class="text-[10px] font-bold text-gray-800 truncate" title="${p.name}">${p.name}</div>
                <input type="hidden" name="product_ids[]" value="${p.id}">
                <button type="button" onclick="removeProduct(${p.id})" 
                        class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition shadow-md">
                    <i class="fas fa-times text-xs"></i>
                </button>
            </div>
        `).join('');
    }
}

// Close search results when clicking outside
document.addEventListener('click', function(e) {
    if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) {
        resultsDiv.classList.add('hidden');
    }
});
</script>
@endpush
