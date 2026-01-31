@extends('admin.layouts.master')

@section('title', 'Banner Management')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Banner Management</h2>
        <p class="text-gray-600">Manage your homepage slider banners</p>
    </div>
    <a href="{{ route('admin.banners.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add New Banner
    </a>
</div>


@if(session('success'))
<div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @forelse($banners as $banner)
    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 group">
        <div class="relative h-48 overflow-hidden">
            <img src="{{ Str::startsWith($banner->image, 'http') ? $banner->image : asset('storage/' . $banner->image) }}" 
                 alt="{{ $banner->title }}" 
                 class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
            <div class="absolute top-2 right-2">
                <span class="px-2 py-1 rounded text-xs font-bold {{ $banner->status ? 'bg-emerald-500 text-white' : 'bg-gray-500 text-white' }}">
                    {{ $banner->status ? 'Active' : 'Inactive' }}
                </span>
            </div>
        </div>
        <div class="p-4">
            <h3 class="font-bold text-gray-800 truncate mb-1">{{ $banner->title ?? 'No Title' }}</h3>
            <p class="text-sm text-gray-500 truncate mb-4">{{ $banner->subtitle ?? 'No Subtitle' }}</p>
            
            <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-50">
                <div class="text-xs text-gray-400">
                    Order: {{ $banner->sort_order }}
                </div>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="text-indigo-600 hover:text-indigo-900 p-2 rounded-lg hover:bg-indigo-50 transition">
                        <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-rose-600 hover:text-rose-900 p-2 rounded-lg hover:bg-rose-50 transition">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-full py-20 text-center bg-white rounded-xl border border-dashed border-gray-300">
        <div class="mb-4">
            <i class="fas fa-images text-gray-300 text-5xl"></i>
        </div>
        <h3 class="text-lg font-medium text-gray-800 mb-1">No Banners Found</h3>
        <p class="text-gray-500 mb-6">Start by adding your first promotional banner.</p>
        <a href="{{ route('admin.banners.create') }}" class="text-indigo-600 font-bold hover:underline">
            Add New Banner
        </a>
    </div>
    @endforelse
</div>

@endsection
