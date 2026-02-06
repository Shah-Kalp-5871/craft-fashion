@extends('customer.layouts.master')

@section('title', 'My Orders | ' . config('constants.SITE_NAME'))

@section('content')
<section class="py-12 bg-white">
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
                    <li aria-current="page">
                        <div class="flex items-center">
                            <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                            <span class="ml-1 text-sm font-medium text-dark md:ml-2">My Orders</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Page Header -->
        <div class="mb-10 text-center">
            <div class="inline-flex items-center bg-primary/10 text-dark px-4 py-2 rounded-full text-sm font-medium mb-4">
                <i class="fas fa-shopping-bag mr-2"></i>
                My Shopping History
            </div>
            
            <h1 class="text-4xl md:text-5xl font-bold font-playfair text-dark mb-4">
                My <span class="text-primary">Orders</span>
            </h1>
            
            <p class="text-xl text-secondary max-w-2xl mx-auto">
                Track your orders, view order details, and manage your purchases
            </p>
        </div>

        <!-- Orders Content -->
        @if($orders->isEmpty())
            <!-- Empty Orders State -->
            <div class="text-center py-20">
                <div class="w-32 h-32 bg-primary/10 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-bag text-5xl text-primary"></i>
                </div>
                <h3 class="text-3xl font-bold font-playfair text-dark mb-4">No Orders Yet</h3>
                <p class="text-xl text-secondary mb-8 max-w-md mx-auto">You haven't placed any orders yet. Start shopping to discover our exquisite collection!</p>
                <a href="{{ route('customer.products.list') }}" class="bg-primary text-white px-8 py-4 rounded-full font-semibold transition-all duration-300 hover:scale-105 hover:shadow-xl hover:bg-primary/90 inline-flex items-center">
                    <i class="fas fa-bag-shopping mr-3"></i>
                    <span>Start Shopping</span>
                </a>
            </div>
        @else
            <!-- Orders List -->
            <div class="space-y-8">
                @foreach($orders as $order)
                    @php
                        $statusClass = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'confirmed' => 'bg-blue-50 text-blue-800 border-blue-200',
                            'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'shipped' => 'bg-purple-100 text-purple-800 border-purple-200',
                            'delivered' => 'bg-green-100 text-green-800 border-green-200',
                            'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                            'refunded' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'returned' => 'bg-orange-100 text-orange-800 border-orange-200',
                        ];
                        
                        $statusText = [
                            'pending' => 'Pending',
                            'confirmed' => 'Confirmed',
                            'processing' => 'Processing',
                            'shipped' => 'Shipped',
                            'delivered' => 'Delivered',
                            'cancelled' => 'Cancelled',
                            'refunded' => 'Refunded',
                            'returned' => 'Returned',
                        ];
                    @endphp
                    
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <!-- Order Header -->
                        <div class="bg-gradient-to-r from-primary/5 to-primary/10 px-6 py-4 border-b border-primary/20">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h3 class="text-xl font-bold font-playfair text-dark">Order #{{ $order->order_number }}</h3>
                                    <p class="text-secondary text-sm">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                                </div>
                                <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                                    <span class="px-4 py-2 rounded-full text-sm font-medium border {{ $statusClass[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    <span class="text-xl font-bold text-primary">
                                        ₹{{ number_format($order->grand_total, 2) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Items Preview -->
                        <div class="p-6">
                            <div class="flex flex-col lg:flex-row gap-6">
                                <!-- Items -->
                                <div class="flex-1">
                                    <h4 class="font-semibold text-dark mb-4">Items</h4>
                                    <div class="space-y-4">
                                        @foreach($order->items->take(2) as $item)
                                            <div class="flex items-center gap-4">
                                                <div class="flex-shrink-0">
                                                    @php
                                                        $orderImageUrl = null;
                                                        if($item->variant && $item->variant->display_image) {
                                                            $orderImageUrl = $item->variant->display_image;
                                                        } elseif($item->product && $item->product->main_image) {
                                                            $orderImageUrl = asset('storage/' . $item->product->main_image);
                                                        }
                                                    @endphp

                                                    @if($orderImageUrl)
                                                        <img src="{{ $orderImageUrl }}" alt="{{ $item->product_name }}" 
                                                             class="w-16 h-16 object-cover rounded-lg shadow-sm">
                                                    @else
                                                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400">
                                                            <i class="fas fa-image"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h5 class="font-medium text-dark">{{ $item->product_name }}</h5>
                                                    <div class="flex items-center gap-3 mt-1 text-sm text-secondary">
                                                        @if(!empty($item->attributes))
                                                            @foreach($item->attributes as $key => $value)
                                                                <span class="bg-gray-100 px-2 py-1 rounded">{{ ucfirst($key) }}: {{ $value }}</span>
                                                            @endforeach
                                                        @endif
                                                        <span>Qty: {{ $item->quantity }}</span>
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-primary font-medium">₹{{ number_format($item->unit_price, 2) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        @if($order->items->count() > 2)
                                            <div class="text-center pt-4 border-t border-gray-100">
                                                <p class="text-primary text-sm font-medium">
                                                    + {{ $order->items->count() - 2 }} more item(s)
                                                </p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Order Progress -->
                                <div class="lg:w-1/3">
                                    <h4 class="font-semibold text-dark mb-4">Order Status</h4>
                                    @if($order->status == 'cancelled' || $order->status == 'refunded' || $order->status == 'returned')
                                        <div class="p-4 bg-red-50 rounded-lg text-red-700 text-center">
                                            <i class="fas fa-times-circle text-3xl mb-2"></i>
                                            <p class="font-medium">Order {{ ucfirst($order->status) }}</p>
                                            @if($order->cancellation_reason)
                                                <p class="text-sm mt-1 opacity-80">Reason: {{ $order->cancellation_reason }}</p>
                                            @endif
                                        </div>
                                    @else
                                        <div class="relative">
                                            <!-- Progress line -->
                                            <div class="absolute top-1/2 left-0 right-0 h-1 bg-gray-200 -translate-y-1/2 z-0"></div>
                                            @php
                                                $progressWidth = 'w-0';
                                                if(in_array($order->status, ['pending', 'failed'])) $progressWidth = 'w-1/4';
                                                if(in_array($order->status, ['confirmed', 'processing'])) $progressWidth = 'w-2/4';
                                                if($order->status == 'shipped') $progressWidth = 'w-3/4';
                                                if($order->status == 'delivered') $progressWidth = 'w-full';
                                            @endphp
                                            <div class="absolute top-1/2 left-0 h-1 bg-primary -translate-y-1/2 z-0 {{ $progressWidth }}"></div>
                                            
                                            <!-- Steps -->
                                            <div class="flex justify-between relative z-10">
                                                <div class="text-center">
                                                    <div class="w-8 h-8 mx-auto rounded-full bg-primary text-white flex items-center justify-center mb-2">
                                                        <i class="fas fa-shopping-cart text-xs"></i>
                                                    </div>
                                                    <p class="text-xs">Ordered</p>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <div class="w-8 h-8 mx-auto rounded-full 
                                                        {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 
                                                             'bg-primary text-white' : 'bg-gray-200 text-gray-600' }} 
                                                        flex items-center justify-center mb-2">
                                                        <i class="fas fa-box text-xs"></i>
                                                    </div>
                                                    <p class="text-xs">Confirmed</p>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <div class="w-8 h-8 mx-auto rounded-full 
                                                        {{ in_array($order->status, ['shipped', 'delivered']) ? 
                                                             'bg-primary text-white' : 'bg-gray-200 text-gray-600' }} 
                                                        flex items-center justify-center mb-2">
                                                        <i class="fas fa-truck text-xs"></i>
                                                    </div>
                                                    <p class="text-xs">Shipped</p>
                                                </div>
                                                
                                                <div class="text-center">
                                                    <div class="w-8 h-8 mx-auto rounded-full 
                                                        {{ $order->status == 'delivered' ? 'bg-primary text-white' : 'bg-gray-200 text-gray-600' }} 
                                                        flex items-center justify-center mb-2">
                                                        <i class="fas fa-check text-xs"></i>
                                                    </div>
                                                    <p class="text-xs">Delivered</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    
                                    <!-- Estimated Delivery -->
                                    @if(!in_array($order->status, ['cancelled', 'refunded', 'returned', 'delivered']))
                                        <div class="mt-6 p-4 bg-primary/5 rounded-lg">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-primary/10 p-2 rounded-full">
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium text-dark">Estimated Delivery</p>
                                                    <p class="text-sm text-secondary">
                                                        {{ $order->created_at->addDays(5)->format('F j, Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <!-- Order Actions -->
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                                <div class="flex items-center text-sm text-secondary">
                                    <i class="fas fa-info-circle mr-2 text-primary"></i>
                                    @if($order->status == 'delivered')
                                        Order was delivered on {{ $order->delivered_at ? $order->delivered_at->format('F j, Y') : $order->updated_at->format('F j, Y') }}
                                    @elseif($order->status == 'shipped')
                                        Order is on the way
                                    @elseif($order->status == 'cancelled')
                                        Order was cancelled on {{ $order->cancelled_at ? $order->cancelled_at->format('F j, Y') : $order->updated_at->format('F j, Y') }}
                                    @else
                                        Your order is being processed
                                    @endif
                                </div>
                                
                                <div class="flex gap-3">
                                    <a href="{{ route('customer.account.orders.details', ['id' => $order->id]) }}" 
                                       class="bg-white text-dark border border-primary px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary hover:text-white flex items-center">
                                        <i class="fas fa-eye mr-2"></i>
                                        View Details
                                    </a>
                                    
                                    @if($order->status == 'delivered')
                                    <!-- Reorder functionality could be implemented here -->
                                    <button class="hidden bg-primary text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-primary/90 flex items-center">
                                        <i class="fas fa-redo mr-2"></i>
                                        Reorder
                                    </button>
                                    @endif
                                    
                                    <a href="{{ config('constants.WHATSAPP_LINK') }}?text=I have a query about order #{{ $order->order_number }}" 
                                       class="bg-green-500 text-white px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 hover:scale-105 hover:shadow-lg hover:bg-green-600 flex items-center">
                                        <i class="fab fa-whatsapp mr-2"></i>
                                        Get Help
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12 flex justify-center">
                {{ $orders->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</section>

<!-- Features Banner -->
<section class="py-16 bg-gradient-to-r from-primary to-primary/90 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 text-center">
            <div data-aos="fade-up" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-truck text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Free Home Try</h3>
                <p class="text-white/80">Try before you purchase</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="100" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Easy Returns</h3>
                <p class="text-white/80">7-day return policy</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="200" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-ruler-combined text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Custom Tailoring</h3>
                <p class="text-white/80">Perfect fit guaranteed</p>
            </div>
            
            <div data-aos="fade-up" data-aos-delay="300" class="p-6">
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-award text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold mb-2">Premium Quality</h3>
                <p class="text-white/80">Finest fabrics used</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* Custom styles for orders page */
    .order-card {
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        transform: translateY(-2px);
    }
    
    /* Progress bar animation */
    .progress-bar {
        transition: width 0.5s ease;
    }
    
    /* Status badge animation */
    .status-badge {
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add hover effects to order cards
        document.querySelectorAll('.order-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.boxShadow = '0 20px 40px rgba(201, 143, 131, 0.1)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.boxShadow = '';
            });
        });
        
        // Reorder functionality
        document.querySelectorAll('.reorder-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const orderId = this.closest('.order-card').querySelector('.order-id').textContent;
                
                // Show confirmation modal or toast
                if(confirm('Add all items from order ' + orderId + ' to cart?')) {
                    // In a real application, you would:
                    // 1. Fetch order details
                    // 2. Add all items to cart
                    // 3. Redirect to cart page
                    
                    // For now, show a success message
                    alert('All items from order ' + orderId + ' have been added to your cart!');
                    
                    // You would typically redirect to cart page
                    // window.location.href = "{{ route('customer.cart') }}";
                }
            });
        });
        
        // Track package functionality (for shipped orders)
        document.querySelectorAll('.track-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                
                const orderId = this.closest('.order-card').querySelector('.order-id').textContent;
                
                // In a real application, you would:
                // 1. Fetch tracking information from API
                // 2. Show tracking modal with details
                
                alert('Tracking information for order ' + orderId + ' will be available once the package is shipped.');
            });
        });
    });
</script>
@endpush