<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading Screen Script
    setTimeout(() => {
        const loadingScreen = document.getElementById('loading-screen');
        if (loadingScreen) {
            loadingScreen.classList.add('hidden');
            setTimeout(() => {
                loadingScreen.remove();
            }, 500);
        }
    }, 400);
    
    // Search toggle
    const searchToggle = document.getElementById('searchToggle');
    const searchBar = document.getElementById('searchBar');
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    
    if (searchToggle) {
        searchToggle.addEventListener('click', function() {
            searchBar.classList.toggle('hidden');
            if (!searchBar.classList.contains('hidden')) {
                searchInput.focus();
            }
        });
    }
    
    // Mobile menu toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('hidden');
        });
    }
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm.length > 2) {
                simulateSearch(searchTerm);
            } else {
                if (searchResults) {
                    searchResults.classList.add('hidden');
                }
            }
        });
    }
    
    function simulateSearch(term) {
        // Mock search results
        const mockResults = [
            { name: 'Rose Gold Diamond Necklace', category: 'Necklaces', price: 2499, image: 'https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=200&h=200&fit=crop', url: '{{ route('customer.category.products', ['slug' => 'necklaces']) }}' },
            { name: 'Diamond Stud Earrings', category: 'Earrings', price: 3999, image: 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=200&h=200&fit=crop', url: '{{ route('customer.category.products', ['slug' => 'earrings']) }}' },
            { name: 'Pearl & Gold Bracelet', category: 'Bracelets', price: 1899, image: 'https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=200&h=200&fit=crop', url: '{{ route('customer.category.products', ['slug' => 'bracelets']) }}' },
            { name: 'Emerald Gold Ring Set', category: 'Rings', price: 4299, image: 'https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=200&h=200&fit=crop', url: '{{ route('customer.category.products', ['slug' => 'rings']) }}' }
        ];
        
        const filteredResults = mockResults.filter(item => 
            item.name.toLowerCase().includes(term) || 
            item.category.toLowerCase().includes(term)
        );
        
        if (searchResults) {
            if (filteredResults.length > 0) {
                let html = '<div class="grid grid-cols-1 gap-4">';
                filteredResults.forEach(result => {
                    html += `
                    <a href="${result.url}" class="flex items-center gap-4 p-3 hover:bg-amber-50 rounded-lg transition-colors">
                        <img src="${result.image}" alt="${result.name}" class="w-16 h-16 object-cover rounded">
                        <div>
                            <p class="font-medium text-gray-800">${result.name}</p>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-amber-700 font-bold">${formatPrice(result.price)}</span>
                                <span class="text-gray-500 text-sm">•</span>
                                <span class="text-gray-600 text-sm">${result.category}</span>
                            </div>
                        </div>
                    </a>
                    `;
                });
                html += '</div>';
                
                searchResults.innerHTML = html;
                searchResults.classList.remove('hidden');
            } else {
                searchResults.innerHTML = '<p class="text-center text-gray-600 py-4">No products found</p>';
                searchResults.classList.remove('hidden');
            }
        }
    }
    
    // Cart preview on hover
    const cartIcon = document.querySelector('a[href="{{ route('customer.cart') }}"]');
    const cartPreview = document.getElementById('cartPreview');
    
    if (cartIcon && cartPreview) {
        cartIcon.addEventListener('mouseenter', function() {
            cartPreview.classList.remove('hidden');
        });
        
        cartIcon.addEventListener('mouseleave', function(e) {
            setTimeout(() => {
                if (!cartPreview.matches(':hover') && !cartIcon.matches(':hover')) {
                    cartPreview.classList.add('hidden');
                }
            }, 100);
        });
        
        cartPreview.addEventListener('mouseleave', function() {
            cartPreview.classList.add('hidden');
        });
    }
    
    // Animation on scroll
    const animateOnScroll = function() {
        const elements = document.querySelectorAll('[data-animate]');
        
        elements.forEach(element => {
            const elementPosition = element.getBoundingClientRect().top;
            const screenPosition = window.innerHeight / 1.2;
            
            if (elementPosition < screenPosition) {
                element.classList.add('animated');
            }
        });
    };
    
    window.addEventListener('scroll', animateOnScroll);
    animateOnScroll(); // Initial check
    
    // Format price function
    window.formatPrice = function(price) {
        return '₹' + price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    };
    
    // Add to cart function (for quick add buttons)
    window.quickAddToCart = function(productId, productName, productPrice, productImage) {
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('product_id', productId);
        formData.append('product_name', productName);
        formData.append('product_price', productPrice);
        formData.append('product_image', productImage);
        formData.append('quantity', 1);
        
        fetch('{{ route("customer.cart") }}', {
            method: 'POST',
            body: formData
        }).then(response => response.text()).then(() => {
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-800 px-6 py-3 rounded-lg shadow-lg z-50 animate-slide-in-right';
            notification.innerHTML = `
                <div class="flex items-center gap-3">
                    <i class="fas fa-check-circle text-green-600"></i>
                    <div>
                        <p class="font-medium">Added to cart!</p>
                        <p class="text-sm">${productName}</p>
                    </div>
                </div>
            `;
            
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
                location.reload(); // Reload to update cart count
            }, 3000);
        }).catch(error => {
            console.error('Error:', error);
        });
    };
});

// Animation for notification
const style = document.createElement('style');
style.textContent = `
@keyframes slide-in-right {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.animate-slide-in-right {
    animation: slide-in-right 0.3s ease-out;
}
`;
document.head.appendChild(style);

// Footer scripts
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced Animation on Scroll with Intersection Observer
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -100px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, observerOptions);

    // Observe all footer sections
    document.querySelectorAll('.footer-section').forEach((el) => {
        observer.observe(el);
    });

    // Scroll to Top Button
    const scrollTopBtn = document.getElementById('scrollTop');
    
    if (scrollTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 500) {
                scrollTopBtn.classList.add('visible');
            } else {
                scrollTopBtn.classList.remove('visible');
            }
        });
        
        scrollTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

    // Add sparkle effect on mouse move over footer
    const footer = document.querySelector('.footer-gradient');
    if (footer) {
        footer.addEventListener('mousemove', (e) => {
            if (Math.random() > 0.95) {
                const sparkle = document.createElement('div');
                sparkle.className = 'footer-sparkle';
                sparkle.style.left = e.clientX - footer.offsetLeft + 'px';
                sparkle.style.top = e.clientY - footer.offsetTop + 'px';
                sparkle.style.animationDelay = '0s';
                footer.appendChild(sparkle);
                
                setTimeout(() => {
                    sparkle.remove();
                }, 3000);
            }
        });
    }

    // Newsletter form animation
    const newsletterInput = document.querySelector('.newsletter-input');
    const newsletterBtn = document.querySelector('.newsletter-btn');
    
    if (newsletterBtn) {
        newsletterBtn.addEventListener('click', (e) => {
            e.preventDefault();
            if (newsletterInput && newsletterInput.value.trim() !== '') {
                // Add success animation
                newsletterBtn.innerHTML = '<i class="fas fa-check mr-2"></i> SUBSCRIBED!';
                newsletterBtn.style.background = 'linear-gradient(135deg, #10b981, #059669)';
                
                setTimeout(() => {
                    newsletterBtn.innerHTML = 'SUBSCRIBE NOW';
                    newsletterBtn.style.background = 'linear-gradient(135deg, #8B4513, #654321)';
                    newsletterInput.value = '';
                }, 3000);
            } else {
                // Shake animation
                if (newsletterInput) {
                    newsletterInput.style.animation = 'none';
                    setTimeout(() => {
                        newsletterInput.style.animation = '';
                    }, 10);
                    newsletterInput.classList.add('border-red-500');
                    setTimeout(() => {
                        newsletterInput.classList.remove('border-red-500');
                    }, 1000);
                }
            }
        });
    }

    // Social icons hover effect with particles
    document.querySelectorAll('.social-icon').forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) rotate(' + (Math.random() * 20 - 10) + 'deg) scale(1.15)';
        });
        
        icon.addEventListener('mouseleave', function() {
            this.style.transform = '';
        });
    });

    // Animate payment icons on hover
    document.querySelectorAll('.payment-icon').forEach(icon => {
        icon.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) rotate(' + (Math.random() * 10 - 5) + 'deg)';
        });
    });

    // Add parallax effect to footer particles
    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            window.requestAnimationFrame(() => {
                const scrolled = window.pageYOffset;
                document.querySelectorAll('.footer-particle').forEach((particle, index) => {
                    const speed = 0.5 + (index * 0.1);
                    particle.style.transform = `translateY(${scrolled * speed * -0.05}px)`;
                });
                ticking = false;
            });
            ticking = true;
        }
    });
});
</script>