@extends('admin.layouts.master')

@section('title', 'Create Banner')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Create New Banner</h2>
            <nav class="text-sm text-gray-500">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('admin.banners.index') }}" class="hover:text-indigo-600">Banners</a>
                <span class="mx-2">/</span>
                <span class="text-gray-700">Create</span>
            </nav>
        </div>
        <a href="{{ route('admin.banners.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg transition duration-200">
            Back to List
        </a>
    </div>
</div>

<div class="max-w-4xl">
    <form action="{{ route('admin.banners.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image Selection -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image <span class="text-rose-500">*</span></label>
                    <div class="flex items-start space-x-6">
                        <div id="image-preview" class="w-full h-48 md:w-64 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                            <i class="fas fa-image text-gray-300 text-4xl"></i>
                        </div>
                        <div class="flex-1">
                            <input type="hidden" name="image" id="image-url" value="{{ old('image') }}">
                            <button type="button" onclick="openMediaModal()" class="bg-indigo-50 text-indigo-600 px-4 py-2 rounded-lg border border-indigo-100 hover:bg-indigo-100 transition mb-2">
                                <i class="fas fa-images mr-2"></i>Select from Media
                            </button>
                            <p class="text-xs text-gray-500">Recommended size: 1920x800px or similar aspect ratio.</p>
                            @error('image') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="Enter banner title">
                    @error('title') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Subtitle -->
                <div>
                    <label for="subtitle" class="block text-sm font-medium text-gray-700 mb-1">Subtitle</label>
                    <input type="text" name="subtitle" id="subtitle" value="{{ old('subtitle') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="Enter banner subtitle">
                    @error('subtitle') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- CTA Text -->
                <div>
                    <label for="cta_text" class="block text-sm font-medium text-gray-700 mb-1">Button Text</label>
                    <input type="text" name="cta_text" id="cta_text" value="{{ old('cta_text') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="e.g. Shop Now">
                    @error('cta_text') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- CTA Link -->
                <div>
                    <label for="cta_link" class="block text-sm font-medium text-gray-700 mb-1">Button Link</label>
                    <input type="text" name="cta_link" id="cta_link" value="{{ old('cta_link') }}" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition"
                           placeholder="e.g. /category/necklaces">
                    @error('cta_link') <p class="text-rose-500 text-xs mt-1">{{ $message }}</p> @enderror
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
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-bold shadow-md shadow-indigo-100">Create Banner</button>
        </div>
    </form>
</div>

<!-- Modal Component will be included here via scripts or a partial -->
@include('admin.partials.media-modal')

@endsection

@push('scripts')
<script>
function openMediaModal() {
    window.mediaModal.open({
        onSelect: function(media) {
            document.getElementById('image-url').value = media.url;
            document.getElementById('image-preview').innerHTML = `<img src="${media.url}" class="w-full h-full object-cover">`;
        }
    });
}
</script>
@endpush
