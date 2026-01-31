@extends('admin.layouts.master')

@section('title', 'Stock Adjustments')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Stock Adjustments</h2>
            <p class="text-gray-600">Make bulk stock updates and adjustments</p>
        </div>
        <a href="{{ route('admin.inventory.index') }}" class="btn-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Back to Inventory
        </a>
    </div>
</div>

<!-- Bulk Stock Update Form -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Bulk Stock Update</h3>
    </div>
    <form id="bulkUpdateForm" class="p-6">
        <div class="space-y-6">
            <!-- Step 1: Select Products -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Step 1: Select Products</h4>
                <div class="flex space-x-4 mb-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select id="bulkCategoryFilter" onchange="filterBulkProducts()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Categories</option>
                        </select>
                    </div>
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Brand</label>
                        <select id="bulkBrandFilter" onchange="filterBulkProducts()"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">All Brands</option>
                        </select>
                    </div>
                </div>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 max-h-64 overflow-y-auto">
                    <div id="bulkProductsList" class="space-y-2">
                        <!-- Products will be loaded here -->
                        <div class="text-center py-4 text-gray-500">Loading products...</div>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <span id="selectedCount" class="text-sm text-gray-600">0 products selected</span>
                    <button type="button" onclick="selectAllBulkProducts()" class="text-sm text-indigo-600 hover:text-indigo-800">
                        Select Visible
                    </button>
                </div>
            </div>

            <!-- Step 2: Stock Update Details -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Step 2: Update Details</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Update Type</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="radio" name="bulkUpdateType" value="add" checked class="mr-2">
                                <span class="text-sm text-gray-700">Add Stock</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="bulkUpdateType" value="remove" class="mr-2">
                                <span class="text-sm text-gray-700">Remove Stock</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="bulkUpdateType" value="set" class="mr-2">
                                <span class="text-sm text-gray-700">Set Stock to Specific Value</span>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <span id="quantityLabelText">Quantity to Add</span>
                        </label>
                        <input type="number" id="bulkUpdateQuantity" name="quantity" min="0" step="1"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Enter quantity" required>
                    </div>
                </div>
            </div>

            <!-- Step 3: Additional Information -->
            <div>
                <h4 class="text-md font-semibold text-gray-700 mb-3">Step 3: Additional Information</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Update</label>
                        <select id="bulkUpdateReason" name="reason"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="restock">Restock/Inventory Replenishment</option>
                            <option value="sale">Sale/Order Fulfillment</option>
                            <option value="return">Customer Return</option>
                            <option value="damage">Damaged/Defective Items</option>
                            <option value="adjustment">Stock Adjustment</option>
                            <option value="transfer">Warehouse Transfer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reference/PO Number</label>
                        <input type="text" id="bulkReference" name="reference"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            placeholder="Optional">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea id="bulkUpdateNotes" name="notes" rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        placeholder="Add any additional notes about this stock adjustment..."></textarea>
                </div>
            </div>

            <!-- Preview -->
            <div id="bulkUpdatePreview" class="hidden p-4 bg-gray-50 rounded-lg border border-gray-200">
                <h4 class="text-md font-semibold text-gray-700 mb-3">Update Preview</h4>
                <div id="previewContent">
                    <!-- Preview will be loaded here -->
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 pt-6 border-t">
                <button type="button" onclick="resetBulkForm()" class="btn-secondary">
                    Reset
                </button>
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Apply Stock Update
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Recent Stock Adjustments -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Recent Stock Adjustments</h3>
    </div>
    <div class="p-6">
        <div id="recentAdjustments" class="space-y-4">
            <!-- Recent adjustments will be loaded here -->
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-exchange-alt text-4xl mb-3 opacity-50"></i>
                <p>No recent stock adjustments</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const axiosInstance = axios.create({
    baseURL: '{{ url('') }}/api/admin',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Authorization': `Bearer ${window.ADMIN_API_TOKEN || "{{ session('admin_api_token') }}"}`
    }
});

let bulkSelectedProducts = new Set();
let loadedProducts = [];

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    fetchDropdowns();
    loadBulkProducts();
    loadRecentAdjustments();
    
    // Listen for changes
    document.querySelectorAll('input[name="bulkUpdateType"]').forEach(radio => {
        radio.addEventListener('change', updateQuantityLabel);
    });
    
    document.getElementById('bulkUpdateQuantity').addEventListener('input', updateBulkPreview);
});

async function fetchDropdowns() {
    try {
        const [catRes, brandRes] = await Promise.all([
            axiosInstance.get('categories/dropdown'),
            axiosInstance.get('brands/dropdown')
        ]);

        const catSelect = document.getElementById('bulkCategoryFilter');
        catRes.data.data.forEach(cat => {
            const opt = document.createElement('option');
            opt.value = cat.id;
            opt.textContent = cat.name;
            catSelect.appendChild(opt);
        });

        const brandSelect = document.getElementById('bulkBrandFilter');
        brandRes.data.data.forEach(brand => {
            const opt = document.createElement('option');
            opt.value = brand.id;
            opt.textContent = brand.name;
            brandSelect.appendChild(opt);
        });
    } catch (error) {
        console.error('Error fetching dropdowns:', error);
    }
}

// Load products for bulk update
async function loadBulkProducts() {
    const productList = document.getElementById('bulkProductsList');
    
    const categoryId = document.getElementById('bulkCategoryFilter').value;
    const brandId = document.getElementById('bulkBrandFilter').value;
    
    try {
        const response = await axiosInstance.get('inventory', {
            params: {
                category_id: categoryId,
                brand_id: brandId,
                size: 100 // Load up to 100 for selection
            }
        });

        if (response.data.success) {
            loadedProducts = response.data.data.data;
            productList.innerHTML = '';
            
            if (loadedProducts.length === 0) {
                productList.innerHTML = '<div class="text-center py-4 text-gray-500">No products found</div>';
                return;
            }

            loadedProducts.forEach(product => {
                const isSelected = bulkSelectedProducts.has(product.id);
                const productDiv = document.createElement('div');
                productDiv.className = 'flex items-center justify-between p-3 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer';
                productDiv.onclick = (e) => {
                    if (e.target.tagName !== 'INPUT') {
                        const checkbox = productDiv.querySelector('input');
                        checkbox.checked = !checkbox.checked;
                        toggleBulkProduct(product.id);
                    }
                };
                
                productDiv.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <input type="checkbox" value="${product.id}" 
                            ${isSelected ? 'checked' : ''}
                            onchange="toggleBulkProduct(${product.id})"
                            onclick="event.stopPropagation()"
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                        <div class="w-8 h-8 bg-gray-100 rounded flex items-center justify-center overflow-hidden">
                            ${product.image ? 
                                `<img src="${product.image}" class="w-full h-full object-cover">` : 
                                `<i class="fas fa-box text-gray-400 text-xs"></i>`
                            }
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">${product.name}</p>
                            <p class="text-xs text-gray-500">${product.sku}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-medium ${product.stock_status === 'in_stock' ? 'text-emerald-600' : product.stock_status === 'low_stock' ? 'text-amber-600' : 'text-rose-600'}">
                            ${product.current_stock} in stock
                        </p>
                    </div>
                `;
                productList.appendChild(productDiv);
            });
        }
    } catch (error) {
        console.error('Error loading products:', error);
        productList.innerHTML = '<div class="text-center py-4 text-rose-500">Failed to load products</div>';
    }
    
    updateSelectedCount();
}

// Toggle product selection
function toggleBulkProduct(productId) {
    if (bulkSelectedProducts.has(productId)) {
        bulkSelectedProducts.delete(productId);
    } else {
        bulkSelectedProducts.add(productId);
    }
    updateSelectedCount();
    updateBulkPreview();
}

// Select all products
function selectAllBulkProducts() {
    const checkboxes = document.querySelectorAll('#bulkProductsList input[type="checkbox"]');
    const allSelected = Array.from(checkboxes).every(cb => cb.checked);
    
    checkboxes.forEach(cb => {
        const productId = parseInt(cb.value);
        if (allSelected) {
            bulkSelectedProducts.delete(productId);
            cb.checked = false;
        } else {
            bulkSelectedProducts.add(productId);
            cb.checked = true;
        }
    });
    
    updateSelectedCount();
    updateBulkPreview();
}

// Update selected count
function updateSelectedCount() {
    document.getElementById('selectedCount').textContent = 
        `${bulkSelectedProducts.size} products selected`;
}

// Filter products when category/brand changes
function filterBulkProducts() {
    loadBulkProducts();
}

// Update bulk preview
function updateBulkPreview() {
    const quantity = parseInt(document.getElementById('bulkUpdateQuantity').value) || 0;
    const updateTypeRadio = document.querySelector('input[name="bulkUpdateType"]:checked');
    const updateType = updateTypeRadio ? updateTypeRadio.value : 'add';
    const previewDiv = document.getElementById('bulkUpdatePreview');
    
    if (bulkSelectedProducts.size === 0 || quantity <= 0) {
        previewDiv.classList.add('hidden');
        return;
    }
    
    previewDiv.classList.remove('hidden');
    
    let previewHTML = '<div class="space-y-3">';
    
    // Get selected products
    const selectedProductsArr = loadedProducts.filter(p => bulkSelectedProducts.has(p.id));
    
    selectedProductsArr.forEach(product => {
        let newStock = product.current_stock;
        let changeText = '';
        
        switch(updateType) {
            case 'add':
                newStock = product.current_stock + quantity;
                changeText = `+${quantity}`;
                break;
            case 'remove':
                if (quantity > product.current_stock) {
                    newStock = 0;
                    changeText = `-${product.current_stock} (max available)`;
                } else {
                    newStock = product.current_stock - quantity;
                    changeText = `-${quantity}`;
                }
                break;
            case 'set':
                newStock = quantity;
                changeText = `Set to ${quantity}`;
                break;
        }
        
        const stockClass = newStock <= 0 ? 'text-rose-600' : 
                          newStock <= 10 ? 'text-amber-600' : 'text-emerald-600';
        
        previewHTML += `
            <div class="flex justify-between items-center p-2 bg-white border border-gray-200 rounded">
                <div>
                    <p class="text-sm font-medium">${product.name}</p>
                    <p class="text-xs text-gray-500">Current: ${product.current_stock}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm ${stockClass} font-medium">${changeText}</p>
                    <p class="text-xs text-gray-500">New: ${newStock}</p>
                </div>
            </div>
        `;
    });
    
    previewHTML += '</div>';
    document.getElementById('previewContent').innerHTML = previewHTML;
}

// Update quantity label based on update type
function updateQuantityLabel() {
    const updateType = document.querySelector('input[name="bulkUpdateType"]:checked').value;
    const label = document.getElementById('quantityLabelText');
    
    switch(updateType) {
        case 'add':
            label.textContent = 'Quantity to Add';
            break;
        case 'remove':
            label.textContent = 'Quantity to Remove';
            break;
        case 'set':
            label.textContent = 'Set Stock To';
            break;
    }
    
    updateBulkPreview();
}

// Reset bulk form
function resetBulkForm() {
    bulkSelectedProducts.clear();
    document.getElementById('bulkUpdateQuantity').value = '';
    document.getElementById('bulkReference').value = '';
    document.getElementById('bulkUpdateNotes').value = '';
    document.getElementById('bulkCategoryFilter').value = '';
    document.getElementById('bulkBrandFilter').value = '';
    document.querySelector('input[name="bulkUpdateType"][value="add"]').checked = true;
    
    loadBulkProducts();
    updateQuantityLabel();
    updateBulkPreview();
    
    toastr.info('Form has been reset');
}

// Handle form submission
document.getElementById('bulkUpdateForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    if (bulkSelectedProducts.size === 0) {
        toastr.error('Please select at least one product');
        return;
    }
    
    const quantity = parseInt(document.getElementById('bulkUpdateQuantity').value);
    const updateType = document.querySelector('input[name="bulkUpdateType"]:checked').value;
    const reason = document.getElementById('bulkUpdateReason').value;
    const reference = document.getElementById('bulkReference').value;
    const notes = document.getElementById('bulkUpdateNotes').value;
    
    if (isNaN(quantity) || quantity < 0) {
        toastr.error('Please enter a valid quantity');
        return;
    }
    
    const { isConfirmed } = await Swal.fire({
        title: 'Confirm Bulk Update',
        text: `Are you sure you want to update stock for ${bulkSelectedProducts.size} products?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, apply update',
        cancelButtonText: 'Cancel'
    });
    
    if (isConfirmed) {
        try {
            const response = await axiosInstance.post('inventory/bulk-update', {
                variant_ids: Array.from(bulkSelectedProducts),
                quantity: quantity,
                type: updateType,
                reason: reason,
                notes: notes + (reference ? ` | Ref: ${reference}` : '')
            });
            
            if (response.data.success) {
                toastr.success('Bulk stock update successful');
                resetBulkForm();
                loadRecentAdjustments();
            }
        } catch (error) {
            console.error('Error applying bulk update:', error);
            toastr.error(error.response?.data?.message || 'Failed to apply bulk update');
        }
    }
});

// Load recent adjustments
async function loadRecentAdjustments() {
    const container = document.getElementById('recentAdjustments');
    
    try {
        const response = await axiosInstance.get('inventory/history', {
            params: { per_page: 5 }
        });
        
        if (response.data.success && response.data.data.data.length > 0) {
            const history = response.data.data.data;
            container.innerHTML = '';
            
            history.forEach(entry => {
                const actionColor = entry.action === 'add' ? 'text-emerald-600' : 
                                  entry.action === 'remove' ? 'text-rose-600' : 'text-indigo-600';
                const actionIcon = entry.action === 'add' ? 'fa-plus-circle' : 
                                 entry.action === 'remove' ? 'fa-minus-circle' : 'fa-exchange-alt';
                
                const entryDiv = document.createElement('div');
                entryDiv.className = 'flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-indigo-200 transition-colors cursor-pointer';
                entryDiv.onclick = () => window.location.href = "{{ route('admin.inventory.history') }}";
                
                entryDiv.innerHTML = `
                    <div class="flex items-center space-x-4">
                        <div class="p-2 bg-white rounded-lg shadow-sm">
                            <i class="fas ${actionIcon} ${actionColor} text-lg"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">${entry.product_name}</p>
                            <p class="text-xs text-gray-500">${entry.updated_at} • ${entry.updated_by}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold ${actionColor}">${entry.action === 'add' ? '+' : entry.action === 'remove' ? '-' : ''}${entry.quantity}</p>
                        <p class="text-xs text-gray-400">Reason: ${entry.reason}</p>
                    </div>
                `;
                container.appendChild(entryDiv);
            });
        }
    } catch (error) {
        console.error('Error loading recent adjustments:', error);
    }
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Load products
    loadBulkProducts();
    
    // Load recent adjustments
    loadRecentAdjustments();
    
    // Update quantity label when radio changes
    document.querySelectorAll('input[name="bulkUpdateType"]').forEach(radio => {
        radio.addEventListener('change', function() {
            updateQuantityLabel();
        });
    });
    
    // Update preview when quantity changes
    document.getElementById('bulkUpdateQuantity').addEventListener('input', updateBulkPreview);
    
    // Initial label update
    updateQuantityLabel();
});
</script>
@endpush