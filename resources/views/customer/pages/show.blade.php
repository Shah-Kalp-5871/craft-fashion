@extends('customer.layouts.master')

@section('content')
<div class="bg-gray-50 py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">
            <div class="p-8 md:p-12">
                <h1 class="text-4xl font-black text-gray-900 mb-8 border-b border-gray-100 pb-8 uppercase tracking-tight">{{ $page->title }}</h1>
                
                <div class="prose prose-lg max-w-none text-gray-600 leading-relaxed font-medium">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
