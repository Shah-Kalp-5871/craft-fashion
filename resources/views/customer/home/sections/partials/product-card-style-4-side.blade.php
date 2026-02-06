@php
    $pName = is_array($product) ? $product['name'] : $product->name;
    $pSlug = is_array($product) ? $product['slug'] : $product->slug;
    $pPrice = is_array($product) ? $product['price'] : $product->price;
    $pImage = is_array($product) ? ($product['main_image'] ?? '') : ($product->main_image ?? '');
@endphp
<div class="group flex items-center gap-4 bg-white p-4 rounded-xl shadow-sm hover:shadow-md transition-shadow">
    <div class="w-24 h-24 flex-shrink-0 rounded-lg overflow-hidden">
        <img src="{{ Str::startsWith($pImage, 'http') ? $pImage : asset('storage/' . $pImage) }}" 
             alt="{{ $pName }}" 
             class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
    </div>
    <div>
        <h4 class="font-bold text-dark mb-1 line-clamp-1 group-hover:text-primary transition-colors">
            <a href="{{ route('customer.products.details', $pSlug) }}">{{ $pName }}</a>
        </h4>
        <p class="text-primary font-bold">₹{{ number_format($pPrice) }}</p>
        <a href="{{ route('customer.products.details', $pSlug) }}" class="text-xs text-gray-500 underline hover:text-dark mt-1 block">View Details</a>
    </div>
</div>
