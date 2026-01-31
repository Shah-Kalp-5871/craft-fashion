@extends('admin.layouts.master')

@section('title', 'CRM Home')

@section('content')
<div class="mb-8">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Home Page Manager</h2>
            <p class="text-gray-600">Manage content and layout of your home page</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mb-8">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Hero Carousel (Fixed Position)</h3>
                <p class="text-sm text-gray-600 mt-1">This section is always at the top of your home page. You can add multiple buttons per slide.</p>
            </div>
            <button onclick="addNewSlide()" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>Add New Slide
            </button>
        </div>
    </div>
    
    <div class="p-6">
        <form id="heroCarouselForm" class="space-y-6">
            <!-- Slides Container -->
            <div id="heroSlidesContainer" class="space-y-6">
                @php
                // Static slides data
                $slides = [
                    [
                        'image' => 'hero1.jpg',
                        'heading' => 'Welcome to Our Store',
                        'subheading' => 'Discover Amazing Products',
                        'buttons' => [
                            [
                                'text' => 'Shop Now',
                                'link' => '/shop'
                            ]
                        ]
                    ],
                    [
                        'image' => 'hero2.jpg',
                        'heading' => 'Summer Sale',
                        'subheading' => 'Up to 50% off',
                        'buttons' => [
                            [
                                'text' => 'View Deals',
                                'link' => '/sale'
                            ]
                        ]
                    ],
                    [
                        'image' => 'hero3.jpg',
                        'heading' => 'New Arrivals',
                        'subheading' => 'Check out latest products',
                        'buttons' => [
                            [
                                'text' => 'Explore',
                                'link' => '/new'
                            ]
                        ]
                    ]
                ];
                @endphp
                
                @foreach($slides as $index => $slide)
                <div class="border border-gray-200 rounded-xl p-5 slide-container" data-index="{{ $index }}">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-semibold text-gray-800">Slide {{ $index + 1 }}</h4>
                        <div class="flex space-x-2">
                            <button type="button" onclick="addButtonToSlide({{ $index }})" 
                                    class="btn-secondary text-sm">
                                <i class="fas fa-plus mr-1"></i> Add Button
                            </button>
                            @if(count($slides) > 1)
                            <button type="button" onclick="removeSlide({{ $index }})" 
                                    class="btn-secondary text-sm bg-rose-50 text-rose-600 hover:bg-rose-100">
                                <i class="fas fa-trash mr-1"></i> Remove
                            </button>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                            <div class="space-y-3">
                                <div class="w-full h-48 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center cursor-pointer overflow-hidden"
                                     onclick="openImagePicker('hero_carousel', {{ $index }})">
                                    @if(!empty($slide['image']))
                                    <img src="https://picsum.photos/600/400?random={{ $index + 1 }}" 
                                         class="w-full h-full object-cover rounded-lg" 
                                         id="heroImagePreview_{{ $index }}">
                                    @else
                                    <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                                    <span class="text-gray-500">Click to select image</span>
                                    @endif
                                </div>
                                <input type="hidden" 
                                       name="slides[{{ $index }}][image]" 
                                       value="{{ $slide['image'] ?? '' }}"
                                       id="heroImageInput_{{ $index }}">
                            </div>
                        </div>
                        
                        <!-- Content -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
                                <input type="text" 
                                       name="slides[{{ $index }}][heading]" 
                                       value="{{ $slide['heading'] ?? '' }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Subheading</label>
                                <input type="text" 
                                       name="slides[{{ $index }}][subheading]" 
                                       value="{{ $slide['subheading'] ?? '' }}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3">
                            </div>
                            
                            <!-- Buttons Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">Buttons</label>
                                <div id="buttonsContainer_{{ $index }}" class="space-y-4">
                                    @php
                                    $buttons = $slide['buttons'] ?? [];
                                    @endphp
                                    
                                    @foreach($buttons as $btnIndex => $button)
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-sm font-medium text-gray-700">Button {{ $btnIndex + 1 }}</span>
                                            @if(count($buttons) > 1)
                                            <button type="button" 
                                                    onclick="removeButtonFromSlide({{ $index }}, {{ $btnIndex }})" 
                                                    class="text-rose-600 hover:text-rose-800 text-sm">
                                                <i class="fas fa-trash mr-1"></i> Remove
                                            </button>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Button Text</label>
                                                <input type="text" 
                                                       name="slides[{{ $index }}][buttons][{{ $btnIndex }}][text]" 
                                                       value="{{ $button['text'] ?? '' }}"
                                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                                       placeholder="Button text">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Button Link</label>
                                                <input type="text" 
                                                       name="slides[{{ $index }}][buttons][{{ $btnIndex }}][link]" 
                                                       value="{{ $button['link'] ?? '' }}"
                                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                                       placeholder="/path">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    You can add up to 3 buttons per slide.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Save Button -->
            <div class="flex justify-end pt-4 border-t">
                <button type="submit" class="btn-primary">
                    <i class="fas fa-save mr-2"></i>Save Hero Carousel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Home Page Sections -->

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Home Page Sections</h3>
                <p class="text-sm text-gray-600 mt-1">Drag to reorder sections and edit content</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="addNewBanner()" class="btn-secondary">
                    <i class="fas fa-image mr-2"></i>Add Banner
                </button>
                <button onclick="addNewProductSection()" class="btn-primary">
                    <i class="fas fa-cube mr-2"></i>Add Product Section
                </button>
                <button onclick="updateSectionOrder()" class="btn-secondary">
                    <i class="fas fa-save mr-2"></i>Save Order
                </button>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Instructions -->
        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 mr-3 text-xl"></i>
                <div>
                    <p class="text-sm text-blue-800">
                        <strong>Instructions:</strong> Drag sections to reorder. Click "Edit" to change content. 
                        Click "Save Order" when you're done rearranging. Use "Add Banner" or "Add Product Section" to create new sections.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Sections List -->
        <div id="sectionsContainer" class="space-y-4">
            @php
            // Static sections data
            $homeSections = [
                [
                    'id' => 'featured_products_1',
                    'title' => 'Featured Products Section 1',
                    'type' => 'product_grid',
                    'order' => 2,
                    'data' => [
                        'heading' => 'Featured Products',
                        'product_ids' => [1, 2, 3, 4, 5, 6],
                        'card_style' => 'style1'
                    ]
                ],
                [
                    'id' => 'banner_1',
                    'title' => 'Promotional Banner',
                    'type' => 'banner',
                    'order' => 3,
                    'data' => [
                        'image' => 'promo-banner.jpg',
                        'heading' => 'Limited Time Offer',
                        'subheading' => 'Get 20% off on all items',
                        'button_text' => 'Grab Now',
                        'button_link' => '/offer'
                    ]
                ],
                [
                    'id' => 'featured_products_2',
                    'title' => 'Best Sellers',
                    'type' => 'product_grid',
                    'order' => 4,
                    'data' => [
                        'heading' => 'Best Sellers',
                        'product_ids' => [7, 8, 9, 10, 11, 12],
                        'card_style' => 'style1'
                    ]
                ]
            ];
            
            $productCardStyles = [
                'style1' => [
                    'name' => 'Modern Grid',
                    'description' => 'Clean design with product image, name, price and quick add to cart',
                    'icon' => 'fas fa-th-large',
                    'features' => ['Image focus', 'Quick actions', 'Clean typography']
                ],
                'style2' => [
                    'name' => 'Card with Badges',
                    'description' => 'Product cards with status badges (Sale, New, Out of stock)',
                    'icon' => 'fas fa-tag',
                    'features' => ['Status badges', 'Sale indicators', 'Inventory status']
                ],
                'style3' => [
                    'name' => 'Minimal List',
                    'description' => 'Simple list layout with smaller images and detailed information',
                    'icon' => 'fas fa-list',
                    'features' => ['Compact layout', 'More info visible', 'Space efficient']
                ],
                'style4' => [
                    'name' => 'Hover Effects',
                    'description' => 'Interactive cards with hover effects and quick view',
                    'icon' => 'fas fa-magic',
                    'features' => ['Hover animations', 'Quick view', 'Interactive elements']
                ],
                'style5' => [
                    'name' => 'Creative Showcase',
                    'description' => 'Unique layout with creative product presentation',
                    'icon' => 'fas fa-palette',
                    'features' => ['Creative layout', 'Visual focus', 'Unique design']
                ]
            ];
            @endphp
            
            @foreach($homeSections as $section)
            <div class="section-item border border-gray-200 rounded-xl p-5 bg-white hover:bg-gray-50" data-id="{{ $section['id'] }}">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Drag Handle -->
                        <div class="drag-handle cursor-move text-gray-400 hover:text-gray-600">
                            <i class="fas fa-grip-vertical text-xl"></i>
                        </div>
                        
                        <!-- Section Info -->
                        <div class="flex items-center space-x-3">
                            <!-- Icon -->
                            <div class="w-10 h-10 rounded-lg flex items-center justify-center {{ $section['type'] === 'product_grid' ? 'bg-blue-100' : 'bg-indigo-100' }}">
                                @if($section['type'] === 'product_grid')
                                    <i class="fas fa-cube text-blue-600"></i>
                                @else
                                    <i class="fas fa-image text-indigo-600"></i>
                                @endif
                            </div>
                            
                            <!-- Details -->
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $section['title'] }}</h4>
                                <div class="flex items-center space-x-3 text-sm text-gray-600 mt-1">
                                    <span class="flex items-center">
                                        <i class="fas fa-sort-numeric-up mr-1.5 text-xs"></i>
                                        Order: <span class="order-display ml-1">{{ $section['order'] }}</span>
                                    </span>
                                    @if($section['type'] === 'product_grid')
                                    <span class="flex items-center">
                                        <i class="fas fa-box mr-1.5 text-xs"></i>
                                        Products: {{ count($section['data']['product_ids']) }}
                                    </span>
                                    @if(isset($section['data']['card_style']))
                                    <span class="flex items-center">
                                        <i class="fas fa-palette mr-1.5 text-xs"></i>
                                        Style: {{ $productCardStyles[$section['data']['card_style']]['name'] ?? 'Default' }}
                                    </span>
                                    @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="flex items-center space-x-2">
                        <button onclick="editSection('{{ $section['id'] }}')" 
                                class="btn-secondary text-sm">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </button>
                        <button onclick="deleteSection('{{ $section['id'] }}')" 
                                class="btn-secondary text-sm bg-rose-50 text-rose-600 hover:bg-rose-100">
                            <i class="fas fa-trash mr-2"></i>Delete
                        </button>
                    </div>
                </div>
                
                <!-- Current Content Preview -->
                <div class="mt-4 pl-14 border-t border-gray-100 pt-4">
                    <div class="text-sm">
                        @if($section['type'] === 'product_grid')
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="text-gray-500">Heading:</span>
                                    <span class="font-medium text-gray-800 ml-2">
                                        {{ $section['data']['heading'] }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    @if(isset($section['data']['card_style']))
                                    <span class="text-gray-500">Style:</span>
                                    <span class="font-medium text-gray-800">
                                        {{ $productCardStyles[$section['data']['card_style']]['name'] ?? 'Default' }}
                                    </span>
                                    @endif
                                    <button onclick="editSection('{{ $section['id'] }}')" 
                                            class="text-sm text-indigo-600 hover:text-indigo-800">
                                        Change Products
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div>
                                    <span class="text-gray-500">Heading:</span>
                                    <span class="font-medium text-gray-800 ml-2">
                                        {{ $section['data']['heading'] }}
                                    </span>
                                </div>
                                @if(isset($section['data']['subheading']))
                                <div>
                                    <span class="text-gray-500">Subheading:</span>
                                    <span class="font-medium text-gray-800 ml-2">
                                        {{ $section['data']['subheading'] }}
                                    </span>
                                </div>
                                @endif
                                @if(isset($section['data']['button_text']))
                                <div>
                                    <span class="text-gray-500">Button:</span>
                                    <span class="font-medium text-gray-800 ml-2">
                                        {{ $section['data']['button_text'] }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div id="sectionModal" class="fixed inset-0 z-[9999] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-6xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800" id="modalTitle">Edit Section</h2>
            <button onclick="closeSectionModal()"
                class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        
        <div class="p-6 overflow-y-auto" id="modalContent">
             <!-- Content will be loaded here  -->
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
            <button onclick="closeSectionModal()" class="btn-secondary">Cancel</button>
            <button onclick="saveSection()" class="btn-primary">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </div>
</div>

<!-- Add New Section Modal -->
<div id="addSectionModal" class="fixed inset-0 z-[10000] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800" id="addModalTitle">Add New Section</h2>
            <button onclick="closeAddSectionModal()"
                class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        
        <div class="p-6 overflow-y-auto" id="addModalContent">
            <!-- Content will be loaded here -->
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
            <button onclick="closeAddSectionModal()" class="btn-secondary">Cancel</button>
            <button onclick="createNewSection()" class="btn-primary" id="createSectionBtn">
                <i class="fas fa-plus mr-2"></i>Create Section
            </button>
        </div>
    </div>
</div>

<!-- Product Search Modal -->
<div id="productSearchModal" class="fixed inset-0 z-[10000] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Search Products</h2>
            <button onclick="closeProductSearchModal()"
                class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <!-- Search Bar -->
            <div class="mb-6">
                <div class="flex space-x-3">
                    <div class="flex-1">
                        <input type="text" 
                               id="productSearchInput" 
                               placeholder="Search by product name or SKU..."
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                               onkeyup="searchProducts(event)">
                    </div>
                    <button onclick="searchProducts()" class="btn-primary">
                        <i class="fas fa-search mr-2"></i>Search
                    </button>
                </div>
                <p class="text-sm text-gray-500 mt-2">
                    Tip: Search by product name or SKU. Press Enter to search.
                </p>
            </div>
            
            <!-- Search Results -->
            <div id="searchResults" class="space-y-3 max-h-96 overflow-y-auto">
                <!-- Results will appear here -->
                <p class="text-gray-500 text-center py-8">Enter search terms to find products</p>
            </div>
            
            <!-- Selected Products Preview -->
            <div id="selectedProductsPreview" class="mt-6 pt-6 border-t border-gray-200 hidden">
                <h4 class="text-md font-semibold text-gray-700 mb-3">Selected Products</h4>
                <div id="selectedProductsList" class="space-y-2">
                    <!-- Selected products will appear here -->
                </div>
            </div>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-200 flex justify-between items-center">
            <div>
                <span id="selectedCount" class="text-sm text-gray-700">0 products selected</span>
            </div>
            <div class="flex space-x-3">
                <button onclick="closeProductSearchModal()" class="btn-secondary">Cancel</button>
                <button onclick="addSelectedProducts()" class="btn-primary">
                    <i class="fas fa-check mr-2"></i>Add to Section
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Image Picker Modal -->
<div id="imagePickerModal" class="fixed inset-0 z-[10000] hidden bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800">Select Image</h2>
            <button onclick="closeImagePicker()"
                class="text-gray-500 hover:text-gray-700 text-2xl leading-none">&times;</button>
        </div>
        
        <div class="p-6 overflow-y-auto">
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4" id="imageGrid">
                @php
                $sampleImages = [
                    'hero1.jpg', 'hero2.jpg', 'hero3.jpg',
                    'promo-banner.jpg', 'newsletter-banner.jpg',
                    'banner1.jpg', 'banner2.jpg', 'banner3.jpg'
                ];
                @endphp
                
                @foreach($sampleImages as $image)
                <div class="cursor-pointer" onclick="selectImage('{{ $image }}')">
                    <img src="https://picsum.photos/400/300?random={{ rand(1, 20) }}" 
                         class="w-full h-32 object-cover rounded-lg border border-gray-300 hover:opacity-80">
                    <p class="text-sm text-gray-700 mt-2 text-center">{{ $image }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Global variables
let currentSectionId = null;
let currentImageTarget = null;
let selectedProducts = [];
let newSectionType = null;
let newSectionStyle = 'style1';

// Static product data
const allProductsData = [
    { id: 1, name: 'Wireless Headphones Pro', sku: 'HP-001', price: 79.99, image: 'headphone1.jpg' },
    { id: 2, name: 'Smart Watch Series 5', sku: 'SW-005', price: 199.99, image: 'watch1.jpg' },
    { id: 3, name: 'Bluetooth Speaker', sku: 'BS-202', price: 49.99, image: 'speaker1.jpg' },
    { id: 4, name: 'Gaming Mouse RGB', sku: 'GM-450', price: 39.99, image: 'mouse1.jpg' },
    { id: 5, name: 'Mechanical Keyboard', sku: 'MK-789', price: 89.99, image: 'keyboard1.jpg' },
    { id: 6, name: 'Laptop Stand Aluminum', sku: 'LS-123', price: 29.99, image: 'stand1.jpg' },
    { id: 7, name: 'USB-C Hub 7-in-1', sku: 'UH-777', price: 59.99, image: 'hub1.jpg' },
    { id: 8, name: 'Wireless Charger Fast', sku: 'WC-150', price: 34.99, image: 'charger1.jpg' },
    { id: 9, name: 'Power Bank 20000mAh', sku: 'PB-200', price: 44.99, image: 'powerbank1.jpg' },
    { id: 10, name: 'Phone Case Premium', sku: 'PC-888', price: 19.99, image: 'case1.jpg' },
    { id: 11, name: 'Screen Protector Glass', sku: 'SP-999', price: 9.99, image: 'protector1.jpg' },
    { id: 12, name: 'Camera Lens 50mm', sku: 'CL-050', price: 149.99, image: 'lens1.jpg' }
];

const productCardStyles = {
    'style1': {
        'name': 'Modern Grid',
        'description': 'Clean design with product image, name, price and quick add to cart',
        'icon': 'fas fa-th-large',
        'features': ['Image focus', 'Quick actions', 'Clean typography']
    },
    'style2': {
        'name': 'Card with Badges',
        'description': 'Product cards with status badges (Sale, New, Out of stock)',
        'icon': 'fas fa-tag',
        'features': ['Status badges', 'Sale indicators', 'Inventory status']
    },
    'style3': {
        'name': 'Minimal List',
        'description': 'Simple list layout with smaller images and detailed information',
        'icon': 'fas fa-list',
        'features': ['Compact layout', 'More info visible', 'Space efficient']
    },
    'style4': {
        'name': 'Hover Effects',
        'description': 'Interactive cards with hover effects and quick view',
        'icon': 'fas fa-magic',
        'features': ['Hover animations', 'Quick view', 'Interactive elements']
    },
    'style5': {
        'name': 'Creative Showcase',
        'description': 'Unique layout with creative product presentation',
        'icon': 'fas fa-palette',
        'features': ['Creative layout', 'Visual focus', 'Unique design']
    }
};

// Initialize Sortable
document.addEventListener('DOMContentLoaded', function() {
    const sectionsContainer = document.getElementById('sectionsContainer');
    if (sectionsContainer) {
        new Sortable(sectionsContainer, {
            animation: 150,
            handle: '.drag-handle',
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            onEnd: function() {
                updateOrderDisplays();
            }
        });
    }
    
    // Hero carousel form submission
    const heroForm = document.getElementById('heroCarouselForm');
    if (heroForm) {
        heroForm.addEventListener('submit', function(e) {
            e.preventDefault();
            saveHeroCarousel();
        });
    }
});

// Update order displays after drag
function updateOrderDisplays() {
    const sections = document.querySelectorAll('.section-item');
    sections.forEach((section, index) => {
        const orderDisplay = section.querySelector('.order-display');
        if (orderDisplay) {
            orderDisplay.textContent = index + 2; // +2 because hero carousel is fixed at position 1
        }
    });
}

// Save section order
function updateSectionOrder() {
    const order = [];
    document.querySelectorAll('.section-item').forEach((section, index) => {
        const id = section.getAttribute('data-id');
        order.push({
            id: id,
            order: index + 2 // +2 because hero carousel is fixed at position 1
        });
    });
    
    toastr.info('Saving section order...');
    
    setTimeout(() => {
        toastr.success('Section order saved successfully!');
    }, 1000);
}

// Add new slide to hero carousel
function addNewSlide() {
    const container = document.getElementById('heroSlidesContainer');
    if (!container) return;
    
    // Count current slides
    const currentSlides = container.querySelectorAll('.slide-container').length;
    if (currentSlides >= 10) {
        toastr.error('Maximum 10 slides allowed');
        return;
    }
    
    const slideIndex = currentSlides;
    const slideId = `slide_${Date.now()}`;
    
    const slideHtml = `
        <div class="border border-gray-200 rounded-xl p-5 slide-container" data-index="${slideIndex}">
            <div class="flex justify-between items-center mb-4">
                <h4 class="font-semibold text-gray-800">New Slide ${slideIndex + 1}</h4>
                <div class="flex space-x-2">
                    <button type="button" onclick="addButtonToSlide(${slideIndex})" 
                            class="btn-secondary text-sm">
                        <i class="fas fa-plus mr-1"></i> Add Button
                    </button>
                    <button type="button" onclick="removeSlide(${slideIndex})" 
                            class="btn-secondary text-sm bg-rose-50 text-rose-600 hover:bg-rose-100">
                        <i class="fas fa-trash mr-1"></i> Remove
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Image -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Image *</label>
                    <div class="space-y-3">
                        <div class="w-full h-48 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center cursor-pointer overflow-hidden"
                             onclick="openImagePicker('hero_carousel', ${slideIndex})">
                            <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                            <span class="text-gray-500">Click to select image</span>
                        </div>
                        <input type="hidden" 
                               name="slides[${slideIndex}][image]" 
                               value=""
                               id="heroImageInput_${slideIndex}">
                    </div>
                </div>
                
                <!-- Content -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
                        <input type="text" 
                               name="slides[${slideIndex}][heading]" 
                               value=""
                               class="w-full border border-gray-300 rounded-lg px-4 py-3"
                               placeholder="Enter heading">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Subheading</label>
                        <input type="text" 
                               name="slides[${slideIndex}][subheading]" 
                               value=""
                               class="w-full border border-gray-300 rounded-lg px-4 py-3"
                               placeholder="Enter subheading">
                    </div>
                    
                    <!-- Buttons Section -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">Buttons</label>
                        <div id="buttonsContainer_${slideIndex}" class="space-y-4">
                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-sm font-medium text-gray-700">Button 1</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Button Text</label>
                                        <input type="text" 
                                               name="slides[${slideIndex}][buttons][0][text]" 
                                               value=""
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                               placeholder="Button text">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Button Link</label>
                                        <input type="text" 
                                               name="slides[${slideIndex}][buttons][0][link]" 
                                               value=""
                                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                               placeholder="/path">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            You can add up to 3 buttons per slide.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', slideHtml);
    toastr.success('New slide added');
    
    // Update all slide indices
    updateSlideIndices();
}

// Remove slide function
function removeSlide(slideIndex) {
    const container = document.getElementById('heroSlidesContainer');
    if (!container) return;
    
    const slides = container.querySelectorAll('.slide-container');
    if (slides.length <= 1) {
        toastr.error('At least one slide is required');
        return;
    }
    
    // Find slide with matching data-index
    const slideToRemove = Array.from(slides).find(slide => 
        slide.getAttribute('data-index') == slideIndex
    );
    
    if (!slideToRemove) return;
    
    Swal.fire({
        title: 'Remove this slide?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, remove it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ef4444'
    }).then((result) => {
        if (result.isConfirmed) {
            slideToRemove.remove();
            toastr.info('Slide removed');
            updateSlideIndices();
        }
    });
}

// Update slide indices after adding/removing slides
function updateSlideIndices() {
    const container = document.getElementById('heroSlidesContainer');
    if (!container) return;
    
    const slides = container.querySelectorAll('.slide-container');
    slides.forEach((slide, index) => {
        slide.setAttribute('data-index', index);
        
        // Update heading
        const heading = slide.querySelector('h4.font-semibold');
        if (heading) {
            heading.textContent = `Slide ${index + 1}`;
        }
        
        // Update all inputs with new indices
        const inputs = slide.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                // Replace slide index in name
                const newName = name.replace(/slides\[\d+\]/, `slides[${index}]`);
                input.setAttribute('name', newName);
            }
        });
        
        // Update button containers
        const buttonsContainer = slide.querySelector('[id^="buttonsContainer_"]');
        if (buttonsContainer) {
            buttonsContainer.id = `buttonsContainer_${index}`;
        }
        
        // Update image preview and input IDs
        const imagePreview = slide.querySelector('[id^="heroImagePreview_"]');
        if (imagePreview) {
            imagePreview.id = `heroImagePreview_${index}`;
        }
        
        const imageInput = slide.querySelector('[id^="heroImageInput_"]');
        if (imageInput) {
            imageInput.id = `heroImageInput_${index}`;
        }
        
        // Update onclick attributes
        const addButtonBtn = slide.querySelector('button[onclick*="addButtonToSlide"]');
        if (addButtonBtn) {
            addButtonBtn.setAttribute('onclick', `addButtonToSlide(${index})`);
        }
        
        const removeSlideBtn = slide.querySelector('button[onclick*="removeSlide"]');
        if (removeSlideBtn) {
            removeSlideBtn.setAttribute('onclick', `removeSlide(${index})`);
        }
    });
}

// Add new banner section
function addNewBanner() {
    newSectionType = 'banner';
    document.getElementById('addModalTitle').textContent = 'Add New Banner';
    
    const content = `
        <form id="newSectionForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title *</label>
                <input type="text" id="newSectionTitle" 
                       value="New Banner"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3"
                       placeholder="Enter section title">
                <p class="text-xs text-gray-500 mt-1">This title is only visible in the admin panel.</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Banner Image *</label>
                <div class="space-y-3">
                    <div class="w-full h-48 bg-gray-100 border-2 border-dashed border-gray-300 rounded-xl flex flex-col items-center justify-center cursor-pointer"
                         onclick="openImagePicker('new_banner')">
                        <i class="fas fa-image text-gray-400 text-3xl mb-2"></i>
                        <span class="text-gray-500">Click to select image</span>
                    </div>
                    <input type="hidden" id="newBannerImage" value="">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Heading</label>
                    <input type="text" id="newBannerHeading" 
                           value=""
                           class="w-full border border-gray-300 rounded-lg px-4 py-3"
                           placeholder="Enter heading">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Subheading</label>
                    <input type="text" id="newBannerSubheading" 
                           value=""
                           class="w-full border border-gray-300 rounded-lg px-4 py-3"
                           placeholder="Enter subheading">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Text</label>
                    <input type="text" id="newBannerButtonText" 
                           value=""
                           class="w-full border border-gray-300 rounded-lg px-4 py-3"
                           placeholder="Button text">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Button Link</label>
                    <input type="text" id="newBannerButtonLink" 
                           value=""
                           class="w-full border border-gray-300 rounded-lg px-4 py-3" 
                           placeholder="/path">
                </div>
            </div>
        </form>
    `;
    
    document.getElementById('addModalContent').innerHTML = content;
    document.getElementById('addSectionModal').classList.remove('hidden');
}

// Add new product section
function addNewProductSection() {
    newSectionType = 'product_grid';
    document.getElementById('addModalTitle').textContent = 'Add New Product Section';
    
    const content = `
        <form id="newSectionForm" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Title *</label>
                <input type="text" id="newSectionTitle" 
                       value="New Product Section"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3"
                       placeholder="Enter section title">
                <p class="text-xs text-gray-500 mt-1">This title is only visible in the admin panel.</p>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Section Heading *</label>
                <input type="text" id="newProductHeading" 
                       value="Featured Products"
                       class="w-full border border-gray-300 rounded-lg px-4 py-3"
                       placeholder="Enter heading for this section">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Product Card Style *</label>
                <select id="selectedCardStyle" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    ${Object.entries(productCardStyles).map(([key, style]) => `
                        <option value="${key}">${style.name}</option>
                    `).join('')}
                </select>
                <p class="text-xs text-gray-500 mt-1">Choose the style for product cards in this section</p>
            </div>
        </form>
    `;
    
    document.getElementById('addModalContent').innerHTML = content;
    document.getElementById('addSectionModal').classList.remove('hidden');
}

// Create new section
function createNewSection() {
    const sectionTitle = document.getElementById('newSectionTitle').value;
    
    if (!sectionTitle.trim()) {
        toastr.error('Please enter a section title');
        return;
    }
    
    toastr.info('Creating new section...');
    
    setTimeout(() => {
        toastr.success('New section created successfully!');
        closeAddSectionModal();
        
        // Reload page to show new section
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }, 1500);
}

// Close add section modal
function closeAddSectionModal() {
    document.getElementById('addSectionModal').classList.add('hidden');
    newSectionType = null;
    newSectionStyle = 'style1';
}

// Delete section
function deleteSection(sectionId) {
    Swal.fire({
        title: 'Delete this section?',
        text: 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#ef4444'
    }).then((result) => {
        if (result.isConfirmed) {
            toastr.info('Deleting section...');
            
            setTimeout(() => {
                toastr.success('Section deleted successfully!');
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }, 1500);
        }
    });
}

// Edit a section
function editSection(sectionId) {
    currentSectionId = sectionId;
    document.getElementById('modalTitle').textContent = `Edit Section`;
    
    const content = `
        <div class="text-center py-8">
            <i class="fas fa-edit text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">Section editing would be implemented here</p>
            <p class="text-sm text-gray-400 mt-1">In a real application, this would load section data</p>
        </div>
    `;
    
    document.getElementById('modalContent').innerHTML = content;
    document.getElementById('sectionModal').classList.remove('hidden');
}

// Close section modal
function closeSectionModal() {
    document.getElementById('sectionModal').classList.add('hidden');
    currentSectionId = null;
}

// Save section changes
function saveSection() {
    toastr.info('Saving section...');
    
    setTimeout(() => {
        toastr.success('Section updated successfully!');
        closeSectionModal();
    }, 1500);
}

// Open product search modal
function openProductSearchModal() {
    document.getElementById('productSearchModal').classList.remove('hidden');
    document.getElementById('productSearchInput').value = '';
    document.getElementById('productSearchInput').focus();
    searchProducts();
}

// Close product search modal
function closeProductSearchModal() {
    document.getElementById('productSearchModal').classList.add('hidden');
}

// Search products
function searchProducts(event = null) {
    if (event && event.key !== 'Enter' && event.type === 'keyup') return;
    
    const searchTerm = document.getElementById('productSearchInput').value.toLowerCase();
    const resultsContainer = document.getElementById('searchResults');
    
    if (!searchTerm) {
        resultsContainer.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500">Enter search terms to find products</p>
                <p class="text-sm text-gray-400 mt-1">Search by product name or SKU</p>
            </div>
        `;
        return;
    }
    
    // Filter products
    const filteredProducts = allProductsData.filter(product => 
        product.name.toLowerCase().includes(searchTerm) ||
        product.sku.toLowerCase().includes(searchTerm)
    ).slice(0, 20);
    
    if (filteredProducts.length === 0) {
        resultsContainer.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-search text-gray-300 text-3xl mb-3"></i>
                <p class="text-gray-500">No products found for "${searchTerm}"</p>
                <p class="text-sm text-gray-400 mt-1">Try different search terms</p>
            </div>
        `;
        return;
    }
    
    resultsContainer.innerHTML = filteredProducts.map(product => {
        const isSelected = selectedProducts.some(p => p.id === product.id);
        return `
            <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-gray-400"></i>
                    </div>
                    <div>
                        <h5 class="font-medium text-gray-800">${product.name}</h5>
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>SKU: ${product.sku}</span>
                            <span>ID: ${product.id}</span>
                            <span class="font-semibold text-indigo-600">$${product.price.toFixed(2)}</span>
                        </div>
                    </div>
                </div>
                <button onclick="toggleProductSelection(${product.id})" 
                        class="${isSelected ? 'btn-primary' : 'btn-secondary'} text-sm">
                    ${isSelected ? '<i class="fas fa-check mr-2"></i>Selected' : '<i class="fas fa-plus mr-2"></i>Select'}
                </button>
            </div>
        `;
    }).join('');
}

// Toggle product selection in search
function toggleProductSelection(productId) {
    const product = allProductsData.find(p => p.id === productId);
    if (!product) return;
    
    const index = selectedProducts.findIndex(p => p.id === productId);
    if (index === -1) {
        selectedProducts.push(product);
    } else {
        selectedProducts.splice(index, 1);
    }
    
    searchProducts();
}

// Add selected products from search modal to section
function addSelectedProducts() {
    closeProductSearchModal();
    toastr.success(`${selectedProducts.length} products added to section`);
}

// Add button to slide
function addButtonToSlide(slideIndex) {
    const container = document.getElementById(`buttonsContainer_${slideIndex}`);
    if (!container) return;
    
    // Count current buttons
    const currentButtons = container.querySelectorAll('.border.border-gray-200').length;
    if (currentButtons >= 3) {
        toastr.error('Maximum 3 buttons per slide allowed');
        return;
    }
    
    // Create new button element
    const buttonId = Date.now();
    const buttonHtml = `
        <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
            <div class="flex justify-between items-center mb-3">
                <span class="text-sm font-medium text-gray-700">Button ${currentButtons + 1}</span>
                <button type="button" 
                        onclick="removeButtonFromSlide(${slideIndex}, ${currentButtons})" 
                        class="text-rose-600 hover:text-rose-800 text-sm">
                    <i class="fas fa-trash mr-1"></i> Remove
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Button Text</label>
                    <input type="text" 
                           name="slides[${slideIndex}][buttons][${currentButtons}][text]" 
                           value=""
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="Button text">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Button Link</label>
                    <input type="text" 
                           name="slides[${slideIndex}][buttons][${currentButtons}][link]" 
                           value=""
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm"
                           placeholder="/path">
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', buttonHtml);
    toastr.success('Button added to slide');
}

// Remove button from slide
function removeButtonFromSlide(slideIndex, buttonIndex) {
    const container = document.getElementById(`buttonsContainer_${slideIndex}`);
    if (!container) return;
    
    const buttons = container.querySelectorAll('.border.border-gray-200');
    if (buttons.length <= 1) {
        toastr.error('At least one button is required per slide');
        return;
    }
    
    if (buttons[buttonIndex]) {
        buttons[buttonIndex].remove();
        toastr.info('Button removed');
        
        // Update button numbers
        updateButtonNumbers(slideIndex);
    }
}

// Update button numbers after removal
function updateButtonNumbers(slideIndex) {
    const container = document.getElementById(`buttonsContainer_${slideIndex}`);
    if (!container) return;
    
    const buttons = container.querySelectorAll('.border.border-gray-200');
    buttons.forEach((button, index) => {
        const label = button.querySelector('.text-sm.font-medium.text-gray-700');
        if (label) {
            label.textContent = `Button ${index + 1}`;
        }
        
        // Update input names
        const inputs = button.querySelectorAll('input, select');
        inputs.forEach(input => {
            const name = input.getAttribute('name');
            if (name) {
                const newName = name.replace(/\[\d+\]/, `[${index}]`);
                input.setAttribute('name', newName);
            }
        });
    });
}

// Open image picker
function openImagePicker(context, index = null) {
    currentImageTarget = { context, index };
    document.getElementById('imagePickerModal').classList.remove('hidden');
}

// Select image from picker
function selectImage(imageUrl) {
    if (currentImageTarget.context === 'hero_carousel' && currentImageTarget.index !== null) {
        const preview = document.getElementById(`heroImagePreview_${currentImageTarget.index}`);
        const input = document.getElementById(`heroImageInput_${currentImageTarget.index}`);
        
        if (preview) preview.src = imageUrl;
        if (input) input.value = imageUrl;
    } else if (currentImageTarget.context === 'new_banner') {
        const preview = document.getElementById('newBannerImagePreview');
        const input = document.getElementById('newBannerImage');
        if (preview) {
             preview.innerHTML = `<img src="${imageUrl}" class="w-full h-full object-cover rounded-xl">`;
        }
        if (input) input.value = imageUrl;
    }
    
    closeImagePicker();
}

// Close image picker
function closeImagePicker() {
    document.getElementById('imagePickerModal').classList.add('hidden');
    currentImageTarget = null;
}

// Save hero carousel
function saveHeroCarousel() {
    toastr.info('Saving hero carousel...');
    
    setTimeout(() => {
        toastr.success('Hero carousel saved successfully!');
    }, 1500);
}
</script>

<style>
.sortable-ghost {
    opacity: 0.4;
    background-color: #e5e7eb;
}

.sortable-chosen {
    background-color: #f3f4f6;
}
</style>
@endpush