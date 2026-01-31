@extends('customer.layouts.master')

@section('content')
<div class="bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-6 border-b pb-4">{{ $page->title }}</h1>
                
                <div class="prose max-w-none text-gray-700 leading-relaxed">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
