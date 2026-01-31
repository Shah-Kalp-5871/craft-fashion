@extends('admin.layouts.master')

@section('title', 'Home Page Sections')

@section('content')
<div class="mb-8 flex justify-between items-center">
    <div>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Home Page Sections</h2>
        <p class="text-gray-600">Manage products collections and layouts on your homepage</p>
    </div>
    <a href="{{ route('admin.home-sections.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition duration-200">
        <i class="fas fa-plus mr-2"></i>Add New Section
    </a>
</div>

@if(session('success'))
<div class="bg-emerald-100 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-6">
    {{ session('success') }}
</div>
@endif

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Sort</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Title</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Type</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Style</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($sections as $section)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="px-2 py-1 bg-gray-100 rounded text-xs font-bold">{{ $section->sort_order }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="font-bold text-gray-800">{{ $section->title }}</div>
                        <div class="text-xs text-gray-500">{{ $section->subtitle }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider 
                            {{ $section->type === 'category' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            {{ str_replace('_', ' ', $section->type) }}
                        </span>
                        @if($section->category)
                        <div class="text-xs text-gray-400 mt-1">{{ $section->category->name }}</div>
                        @elseif($section->product_ids)
                        <div class="text-xs text-gray-400 mt-1">{{ count($section->product_ids) }} Products</div>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">
                        <span class="capitalize">{{ str_replace('_', ' ', $section->style) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="toggleStatus({{ $section->id }})" id="status-btn-{{ $section->id }}"
                                class="px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition
                                {{ $section->status ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ $section->status ? 'Active' : 'Inactive' }}
                        </button>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center justify-center space-x-2">
                            <a href="{{ route('admin.home-sections.edit', $section->id) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.home-sections.destroy', $section->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this section?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-20 text-center text-gray-500">
                        <i class="fas fa-layer-group text-3xl mb-2 text-gray-300"></i>
                        <p>No home page sections found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function toggleStatus(id) {
    try {
        const response = await axios.post(`{{ url('admin/crm/home-sections') }}/${id}/toggle-status`);
        if (response.data.success) {
            const btn = document.getElementById(`status-btn-${id}`);
            const isActive = response.data.status;
            btn.textContent = isActive ? 'Active' : 'Inactive';
            btn.className = `px-2 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider transition ${isActive ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'}`;
        }
    } catch (error) {
        alert('Failed to update status');
    }
}
</script>
@endpush
