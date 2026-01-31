@extends('admin.layouts.master')

@section('title', 'Popup Settings')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Popup Settings</h2>
            <p class="text-gray-600">Configure a simple popup image that appears on page load</p>
        </div>
    </div>
</div>

<!-- Popup Configuration -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-800">Popup Configuration</h3>
        <p class="text-sm text-gray-600 mt-1">Set up your single popup image</p>
    </div>
    
    <div class="p-6">
        <form id="popupForm" class="space-y-6">
            <!-- Enable/Disable Popup -->
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="enabled" checked
                        class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                    <span class="text-sm font-medium text-gray-700">Enable Popup</span>
                </label>
                <p class="text-xs text-gray-500 mt-1 ml-8">Show popup when users visit your site</p>
            </div>
            
            <!-- Image Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Popup Image *</label>
                
                <div id="imageUploadArea" class="mt-2">
                    <!-- Upload Area -->
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 transition-colors cursor-pointer"
                         onclick="document.getElementById('imageInput').click()">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                        <p class="text-gray-600 font-medium">Click to upload image</p>
                        <p class="text-sm text-gray-500 mt-1">Recommended size: 600x400px (JPG, PNG, GIF)</p>
                        <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(event)">
                    </div>
                    
                    <!-- Hidden fields to store image data -->
                    <input type="hidden" id="imageUrl" name="image_url" value="">
                    <input type="hidden" id="imageAlt" name="image_alt" value="Popup Image">
                </div>
            </div>
            
            <!-- Link URL -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Link URL (Optional)</label>
                <input type="url" name="link_url" value="#"
                    class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    placeholder="https://example.com/page">
                <p class="text-xs text-gray-500 mt-1">Where users should go when they click the popup image</p>
            </div>
            
            <!-- Display Settings -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Display Delay (ms)</label>
                    <input type="number" name="delay" value="2000" min="0" max="10000"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-gray-500 mt-1">Time before popup appears (0 = immediately)</p>
                </div>
                
                <div>
                    <label class="flex items-center space-x-3">
                        <input type="checkbox" name="show_once_per_session" checked
                            class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 h-5 w-5">
                        <span class="text-sm font-medium text-gray-700">Show once per session</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-1 ml-8">Popup appears only once per browser session</p>
                </div>
            </div>
            
            <div class="pt-4 border-t">
                <div class="flex justify-end">
                    <button type="button" onclick="previewPopup()" class="btn-secondary mr-3">
                        <i class="fas fa-eye mr-2"></i>Preview Popup
                    </button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save mr-2"></i>Save Settings
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Popup Preview Modal -->
<div id="previewModal" class="fixed inset-0 z-[10000] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Popup Preview</h2>
            <button onclick="closePreviewModal()"
                class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        
        <div class="p-6">
            <div id="popupPreviewContent" class="text-center">
                <!-- Preview will be loaded here -->
            </div>
            
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-medium text-gray-700 mb-2">How it works:</h4>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>• Popup appears after 2000ms delay</li>
                    <li>• Shows once per browser session</li>
                    <li>• Users can close it with the X button</li>
                </ul>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
            <button onclick="closePreviewModal()" class="btn-secondary">Close Preview</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Preview uploaded image
function previewImage(event) {
    const file = event.target.files[0];
    if (!file) return;
    
    // In a real application, you would upload to server and get URL
    // For demo, create a local preview
    const reader = new FileReader();
    reader.onload = function(e) {
        const imageUrl = e.target.result;
        const imageAlt = file.name;
        
        document.getElementById('imageUrl').value = imageUrl;
        document.getElementById('imageAlt').value = imageAlt;
        
        const uploadArea = document.getElementById('imageUploadArea');
        uploadArea.innerHTML = `
            <div class="relative group">
                <img id="imagePreview" src="${imageUrl}" 
                     alt="Popup Preview" class="max-w-md rounded-lg shadow-sm border border-gray-300">
                <button type="button" onclick="removeImage()"
                    class="absolute top-2 right-2 bg-red-500 text-white p-2 rounded-full opacity-0 group-hover:opacity-100 transition-opacity">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
    };
    reader.readAsDataURL(file);
}

// Remove selected image
function removeImage() {
    document.getElementById('imageUrl').value = '';
    document.getElementById('imageAlt').value = '';
    
    const uploadArea = document.getElementById('imageUploadArea');
    uploadArea.innerHTML = `
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-indigo-400 transition-colors cursor-pointer"
             onclick="document.getElementById('imageInput').click()">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
            <p class="text-gray-600 font-medium">Click to upload image</p>
            <p class="text-sm text-gray-500 mt-1">Recommended size: 600x400px (JPG, PNG, GIF)</p>
            <input type="file" id="imageInput" accept="image/*" class="hidden" onchange="previewImage(event)">
        </div>
    `;
}

// Form submission
document.getElementById('popupForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);
    
    // Validate
    if (data.enabled && !data.image_url) {
        toastr.error('Please upload an image for the popup');
        return;
    }
    
    // Show loading
    toastr.info('Saving popup settings...');
    
    // Simulate API call
    setTimeout(() => {
        toastr.success('Popup settings saved successfully!');
    }, 1500);
});

// Preview popup
function previewPopup() {
    const formData = new FormData(document.getElementById('popupForm'));
    const data = Object.fromEntries(formData);
    
    let previewHtml = '';
    
    previewHtml = `
        <div class="max-w-md mx-auto">
            <div class="relative inline-block">
                <img src="https://picsum.photos/600/400?random=1" 
                     alt="Popup Preview" 
                     class="rounded-lg shadow-lg border border-gray-300">
                
                <!-- Close button (would appear on actual popup) -->
                <div class="absolute -top-3 -right-3">
                    <button class="bg-gray-800 text-white w-8 h-8 rounded-full flex items-center justify-center hover:bg-gray-900">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <!-- Link overlay (if link is set) -->
                <div class="absolute inset-0 bg-black bg-opacity-20 rounded-lg flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                    <span class="bg-white px-4 py-2 rounded-lg text-sm font-medium">
                        <i class="fas fa-external-link-alt mr-2"></i>Click to visit link
                    </span>
                </div>
            </div>
            <p class="text-sm text-gray-500 mt-4">This image will pop up when users visit your site</p>
        </div>
    `;
    
    document.getElementById('popupPreviewContent').innerHTML = previewHtml;
    document.getElementById('previewModal').classList.remove('hidden');
}

// Close preview modal
function closePreviewModal() {
    document.getElementById('previewModal').classList.add('hidden');
}
</script>
@endpush