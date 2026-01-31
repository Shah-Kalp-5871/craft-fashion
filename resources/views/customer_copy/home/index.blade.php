@extends('customer.layouts.master')

@section('title', 'APIQO Jewellery - Premium Imitation Jewellery')
@section('meta_description', 'Discover exquisite imitation jewellery at APIQO. Elegant designs, premium quality, and affordable prices - bangles, necklaces, rings, earrings and more.')
@section('meta_keywords', 'imitation jewellery, fashion jewelry, premium imitation, bangles, necklaces, rings, earrings, pendants, bracelets')
@section('og_title', 'APIQO Jewellery - Premium Imitation Jewellery')
@section('og_description', 'Discover exquisite imitation jewellery at APIQO. Elegant designs, premium quality, and affordable prices.')
@section('og_image', asset('logo.jpeg'))
@section('twitter_title', 'APIQO Jewellery - Premium Imitation Jewellery')
@section('twitter_description', 'Discover exquisite imitation jewellery at APIQO. Elegant designs, premium quality, and affordable prices.')

@section('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
/* Hero Slider */
.promo-slider {
    width: 100%;
    /* Use the banner's native aspect ratio for desktop */
    aspect-ratio: 1920 / 800;
    /* Ensure it doesn't get too short on smaller laptops */
    min-height: 600px; 
    /* Cap the height so it doesn't take over huge screens */
    max-height: 800px;
    position: relative;
    overflow: hidden;
}

.swiper {
    width: 100%;
    height: 100%;
}

.swiper-slide {
    position: relative;
}

.swiper-slide img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(0.7);
}

.slide-content {
    position: absolute;
    top: 50%;
    left: 10%;
    transform: translateY(-50%);
    z-index: 10;
    color: var(--white);
    max-width: 600px;
}

.slide-content h2 {
    font-size: 3.5rem;
    font-weight: 800;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 8px rgba(0,0,0,0.5);
    line-height: 1.1;
}

.slide-content p {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    text-shadow: 1px 1px 4px rgba(0,0,0,0.5);
}

.shop-btn {
    display: inline-block;
    padding: 1rem 2.5rem;
    background-color: var(--primary-gold);
    color: var(--white);
    text-decoration: none;
    border-radius: 50px;
    font-weight: 700;
    font-size: 1.1rem;
    transition: var(--transition);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.shop-btn:hover {
    background-color: var(--white);
    color: var(--primary-blue);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
}

/* Swiper Customize */
.swiper-button-next, .swiper-button-prev {
    color: var(--white);
}

.swiper-pagination-bullet {
    background: var(--white);
    opacity: 0.5;
}

.swiper-pagination-bullet-active {
    background: var(--primary-gold);
    opacity: 1;
}

:root {
    --primary-gold: #d4af37;
    --primary-blue: #1a237e;
    --light-blue: #e8eaf6;
    --light-gold: #f9f5e8;
    --text-dark: #333;
    --text-light: #666;
    --white: #ffffff;
    --shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    --transition: all 0.3s ease;
}

/* Scoped to home page content only */
.home-page * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

.home-page {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #fefefe;
    color: var(--text-dark);
    line-height: 1.6;
}

/* Collection Section Styles */
.collection-section {
    padding: 4rem 5%;
}

.section-title {
    text-align: center;
    margin-bottom: 3rem;
    color: var(--primary-blue);
    position: relative;
    padding-bottom: 1rem;
}

.section-title h2 {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
}

.section-title p {
    color: var(--text-light);
    max-width: 700px;
    margin: 0 auto;
}

.section-title::after {
    content: '';
    position: absolute;
    width: 100px;
    height: 3px;
    background-color: var(--primary-gold);
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
}

/* Bangles - 1 Big Left + 4 Small Right Grid */
.bangles-grid {
    display: grid;
    grid-template-columns: 2fr 3fr;
    gap: 25px;
    min-height: 600px;
}

.bangles-main {
    border-radius: 25px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    transition: var(--transition);
}

.bangles-main img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.bangles-main::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.85) 100%);
    z-index: 1;
}

.bangles-main > *:not(img) {
    position: relative;
    z-index: 2;
}

.bangles-main .collection-title,
.bangles-main .collection-price {
    color: white;
}

.bangles-main:hover {
    transform: scale(1.02);
    box-shadow: 0 25px 70px rgba(0,0,0,0.4);
}

.bangles-side {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 25px;
}

.bangle-item {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    min-height: 280px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: var(--transition);
}

.bangle-item img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.bangle-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.85) 100%);
    z-index: 1;
}

.bangle-item > *:not(img) {
    position: relative;
    z-index: 2;
}

.bangle-item .collection-title,
.bangle-item .collection-price {
    color: white;
}

.bangle-item:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}

/* Ring - Creative Masonry Layout */
.ring-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-auto-rows: 180px;
    gap: 20px;
}

.ring-item {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: var(--transition);
}

.ring-item img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.ring-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.85) 100%);
    z-index: 1;
}

.ring-item > *:not(img) {
    position: relative;
    z-index: 2;
}

.ring-item .collection-title,
.ring-item .collection-price {
    color: white;
}

.ring-item:nth-child(1) {
    grid-column: span 2;
    grid-row: span 2;
}

.ring-item:nth-child(4) {
    grid-row: span 2;
}

.ring-item:nth-child(6) {
    grid-column: span 2;
}

.ring-item:hover {
    transform: scale(1.05);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}

/* Necklaces - Horizontal Scroll Cards */
.necklaces-grid {
    display: flex;
    gap: 25px;
    overflow-x: auto;
    padding: 20px 0;
    scroll-snap-type: x mandatory;
}

.necklaces-grid::-webkit-scrollbar {
    height: 8px;
}

.necklaces-grid::-webkit-scrollbar-track {
    background: var(--light-blue);
    border-radius: 10px;
}

.necklaces-grid::-webkit-scrollbar-thumb {
    background: var(--primary-gold);
    border-radius: 10px;
}

.necklace-item {
    min-width: 320px;
    min-height: 400px;
    border-radius: 25px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: var(--transition);
    scroll-snap-align: start;
}

.necklace-item img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.necklace-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.85) 100%);
    z-index: 1;
}

.necklace-item > *:not(img) {
    position: relative;
    z-index: 2;
}

.necklace-item .collection-title,
.necklace-item .collection-price {
    color: white;
}

.necklace-item:hover {
    transform: translateY(-15px) scale(1.05);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

/* Bracelets - Center Featured + Side Items */
.bracelets-grid {
    display: grid;
    grid-template-columns: 1fr 2fr 1fr;
    gap: 25px;
    align-items: start;
}

.bracelet-side {
    display: flex;
    flex-direction: column;
    gap: 25px;
}

.bracelet-main {
    border-radius: 30px;
    position: relative;
    overflow: hidden;
    min-height: 500px;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    transition: var(--transition);
}

.bracelet-main img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.bracelet-main::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.85) 100%);
    z-index: 1;
}

.bracelet-main > *:not(img) {
    position: relative;
    z-index: 2;
}

.bracelet-main .collection-title,
.bracelet-main .collection-price,
.bracelet-main p {
    color: white;
}

.bracelet-main:hover {
    transform: scale(1.03);
    box-shadow: 0 25px 70px rgba(0,0,0,0.4);
}

.bracelet-item {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    min-height: 230px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: var(--transition);
}

.bracelet-item img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.bracelet-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 40%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.85) 100%);
    z-index: 1;
}

.bracelet-item > *:not(img) {
    position: relative;
    z-index: 2;
}

.bracelet-item .collection-title,
.bracelet-item .collection-price {
    color: white;
}

.bracelet-item:hover {
    transform: rotate(-5deg) scale(1.05);
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
}

/* Earrings - Diagonal Staggered Grid */
.earrings-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.earring-item {
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    min-height: 280px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    transition: var(--transition);
}

.earring-item img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
    transition: var(--transition);
}

.earring-item::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 50%;
    background: linear-gradient(to bottom, rgba(0,0,0,0) 0%, rgba(0,0,0,0.9) 100%);
    z-index: 1;
}

.earring-item > *:not(img) {
    position: relative;
    z-index: 2;
}

.earring-item .collection-title,
.earring-item .collection-price {
    color: white;
}

.earring-item:nth-child(1) {
    transform: translateY(-20px);
}

.earring-item:nth-child(3) {
    transform: translateY(-20px);
}

.earring-item:nth-child(5) {
    transform: translateY(20px);
}

.earring-item:hover {
    transform: translateY(-15px) scale(1.05);
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

.earring-item:nth-child(1):hover,
.earring-item:nth-child(3):hover {
    transform: translateY(-35px) scale(1.05);
}

.earring-item:nth-child(5):hover {
    transform: translateY(5px) scale(1.05);
}

/* Pendants - Circular/Hexagon Layout */
.pendants-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 35px;
    padding: 20px;
}

.pendant-item {
    width: 220px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 15px;
}

.pendant-item-image {
    width: 220px;
    height: 220px;
    border-radius: 50%;
    position: relative;
    overflow: hidden;
    box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    transition: var(--transition);
}

.pendant-item img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    z-index: 0;
}

.pendant-item-image::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: radial-gradient(circle, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 100%);
    z-index: 1;
}

.pendant-item-info {
    text-align: center;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.pendant-item .collection-title {
    color: var(--primary-blue);
    font-size: 1.1rem;
    font-weight: 700;
    margin: 0;
}

.pendant-item .collection-price {
    color: var(--primary-gold);
    font-size: 1.4rem;
    font-weight: 700;
    font-family: 'Georgia', serif;
    margin: 0;
    text-shadow: none;
}

.pendant-item .view-btn {
    padding: 0.5rem 1.2rem;
    font-size: 0.85rem;
}

.pendant-item:nth-child(2) {
    transform: translateY(30px);
}

.pendant-item:nth-child(4) {
    transform: translateY(30px);
}

.pendant-item:hover .pendant-item-image {
    transform: scale(1.15) rotate(10deg);
    box-shadow: 0 25px 60px rgba(0,0,0,0.3);
}

/* Collection item common styles */
.bangles-main,
.bangle-item,
.ring-item,
.necklace-item,
.bracelet-main,
.bracelet-item,
.earring-item,
.pendant-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-end;
    text-align: center;
    padding: 2rem;
}

.collection-title {
    font-size: 1.3rem;
    margin-bottom: 0.8rem;
    font-weight: 700;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.collection-price {
    font-weight: 800;
    font-size: 1.4rem;
    margin-bottom: 1.2rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.view-btn {
    background: rgba(255, 255, 255, 0.95);
    color: var(--primary-blue);
    border: none;
    padding: 0.7rem 2rem;
    border-radius: 25px;
    cursor: pointer;
    font-weight: 700;
    font-size: 1rem;
    transition: var(--transition);
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    text-transform: uppercase;
    letter-spacing: 1px;
}

.view-btn:hover {
    background: var(--primary-gold);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.4);
}

/* Testimonials */
.testimonials {
    background-color: var(--light-blue);
    padding: 4rem 5%;
    margin: 3rem 0;
}

.testimonials-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 30px;
    margin-top: 3rem;
}

.testimonial-card {
    background-color: var(--white);
    border-radius: 10px;
    padding: 2rem;
    box-shadow: var(--shadow);
    position: relative;
}

.testimonial-card::before {
    content: '"';
    font-size: 5rem;
    color: var(--primary-gold);
    opacity: 0.2;
    position: absolute;
    top: -10px;
    left: 10px;
}

.testimonial-text {
    margin-bottom: 1.5rem;
    font-style: italic;
}

.testimonial-author {
    display: flex;
    align-items: center;
    gap: 15px;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: var(--primary-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white);
    font-weight: bold;
}

.author-info h4 {
    color: var(--primary-blue);
    margin-bottom: 0.2rem;
}

.author-info p {
    font-size: 0.9rem;
    color: var(--text-light);
}

.verified {
    color: var(--primary-gold);
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-top: 0.3rem;
}

/* Awards Section */
.awards-section {
    padding: 4rem 5%;
    background-color: var(--light-gold);
}

.awards-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    margin-top: 3rem;
}

.award-card {
    text-align: center;
    padding: 2rem 1rem;
    background-color: var(--white);
    border-radius: 10px;
    box-shadow: var(--shadow);
    transition: var(--transition);
}

.award-card:hover {
    transform: translateY(-10px);
}

.award-icon {
    font-size: 3rem;
    color: var(--primary-gold);
    margin-bottom: 1rem;
}

.award-card h3 {
    color: var(--primary-blue);
    margin-bottom: 0.5rem;
}

/* Why Choose Us */
.features-section {
    padding: 4rem 5%;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 30px;
    margin-top: 3rem;
}

.feature-card {
    text-align: center;
    padding: 2rem 1rem;
    background-color: var(--light-blue);
    border-radius: 10px;
    transition: var(--transition);
}

.feature-card:hover {
    background-color: var(--primary-blue);
    color: var(--white);
}

.feature-card:hover h3,
.feature-card:hover p,
.feature-card:hover .feature-icon {
    color: var(--white);
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--primary-gold);
    margin-bottom: 1rem;
}

.feature-card h3 {
    color: var(--primary-blue);
    margin-bottom: 0.8rem;
}

/* Additional Features */
.additional-features {
    padding: 3rem 5%;
    background-color: var(--primary-blue);
    color: var(--white);
}

.features-list {
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 20px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
}

.feature-item i {
    color: var(--primary-gold);
}

/* Floating Cart Button */
.floating-cart {
    position: fixed;
    bottom: 2rem;
    right: 2rem;
    width: 3.5rem;
    height: 3.5rem;
    background: linear-gradient(135deg, var(--primary-blue) 0%, var(--primary-gold) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
    z-index: 100;
    cursor: pointer;
    text-decoration: none;
}

.cart-count {
    position: absolute;
    top: -0.25rem;
    right: -0.25rem;
    background: #ef4444;
    color: white;
    width: 1.25rem;
    height: 1.25rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    font-weight: bold;
}

/* Notification Toast */
.notification-toast {
    position: fixed;
    top: 1.5rem;
    right: 1.5rem;
    padding: 1rem 1.5rem;
    background: white;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 0.75rem;
    z-index: 1000;
    transform: translateX(150%);
    transition: transform 0.3s ease;
}

.notification-toast.show {
    transform: translateX(0);
}

.notification-toast.success {
    border-left: 3px solid #10b981;
}

.notification-toast.error {
    border-left: 3px solid #ef4444;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .bangles-grid {
        grid-template-columns: 1fr;
    }

    .bangles-side {
        grid-template-columns: repeat(2, 1fr);
    }

    .ring-grid {
        grid-template-columns: repeat(2, 1fr);
        grid-auto-rows: 200px;
    }

    .ring-item:nth-child(1) {
        grid-column: span 2;
        grid-row: span 1;
    }

    .ring-item:nth-child(4) {
        grid-row: span 1;
    }

    .ring-item:nth-child(6) {
        grid-column: span 2;
    }

    .bracelets-grid {
        grid-template-columns: 1fr;
    }

    .earrings-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .testimonials-grid, .awards-grid, .features-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .collection-section {
        padding: 2rem 5%;
    }
    
    /* Hero Banner Responsive */
    .promo-slider {
        /* On mobile, prioritize effective use of vertical space */
        aspect-ratio: unset;
        height: 65vh; /* Taller on mobile to show more image and fit content */
        min-height: 500px; /* Absolute minimum for content */
        max-height: none;
    }

    .slide-content {
        left: 5%;
        width: 90%;
        max-width: none;
        text-align: center;
    }

    .slide-content h2 {
        font-size: 2rem;
    }

    .slide-content p {
        font-size: 1rem;
        margin-bottom: 1.5rem;
    }

    .shop-btn {
        padding: 0.8rem 2rem;
        font-size: 1rem;
    }


    .section-title h2 {
        font-size: 1.8rem;
    }

    /* Bangles - 2 Column Grid */
    .bangles-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .bangles-main {
        min-height: 350px;
    }

    .bangles-side {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .bangle-item {
        min-height: 220px;
    }

    /* Ring - Single Column Stacked */
    .ring-grid {
        grid-template-columns: 1fr;
        grid-auto-rows: auto;
        gap: 20px;
    }

    .ring-item {
        min-height: 280px;
        grid-column: span 1 !important;
        grid-row: span 1 !important;
    }

    /* Necklaces - Keep Horizontal Scroll */
    .necklaces-grid {
        flex-direction: row;
    }

    .necklace-item {
        min-width: 85%;
        min-height: 350px;
    }

    /* Bracelets - Single Column */
    .bracelets-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .bracelet-main {
        min-height: 350px;
    }

    .bracelet-side {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .bracelet-item {
        min-height: 250px;
        margin: 0 !important;
    }

    /* Earrings - 2 Column Grid */
    .earrings-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .earring-item {
        min-height: 250px;
        transform: none !important;
    }

    /* Pendants - Single Column with proper spacing */
    .pendants-grid {
        flex-direction: column;
        gap: 30px;
        padding: 10px;
    }

    .pendant-item {
        width: 100%;
        transform: none !important;
    }

    .pendant-item-image {
        width: 100%;
        max-width: 280px;
        height: 280px;
        margin: 0 auto;
    }

    .pendant-item-info {
        width: 100%;
    }

    .testimonials-grid, .awards-grid, .features-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endsection

@section('content')
<div class="home-page">

<!-- Hero Section -->
<section class="promo-slider">
    <div class="swiper">
        <div class="swiper-wrapper">
            @forelse($banners as $banner)
            <div class="swiper-slide">
                <img src="{{ $banner->image }}" alt="{{ $banner->title }}">
                <div class="slide-content">
                    <h2>{{ $banner->title }}</h2>
                    <p>{{ $banner->subtitle }}</p>
                    @if($banner->cta_text)
                    <a href="{{ $banner->cta_link ?? '#' }}" class="shop-btn">{{ $banner->cta_text }}</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="swiper-slide">
                <img src="https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=1200" alt="APIQO Jewellery">
                <div class="slide-content">
                    <h2>Exquisite Jewellery</h2>
                    <p>Discover our unique collection</p>
                    <a href="{{ route('customer.products.list') }}" class="shop-btn">Shop Now</a>
                </div>
            </div>
            @endforelse
        </div>
        <div class="swiper-pagination"></div>
    </div>
</section>

<!-- Dynamic Sections -->
@foreach($dynamicSections as $section)
    @php
        $products = $section['products'];
    @endphp

    @if($section['style'] === 'style_1')
    <!-- Style 1: 1 Big Left + 4 Small Right Grid (Bangles Style) -->
    <section class="collection-section">
        <div class="section-title">
            <h2>{{ $section['title'] }}</h2>
            @if($section['subtitle']) <p>{{ $section['subtitle'] }}</p> @endif
        </div>

        <div class="bangles-grid">
            @php
                $mainProduct = $products[0] ?? null;
                $subProducts = array_slice($products, 1, 4);
            @endphp

            @if($mainProduct)
            <div class="bangles-main">
                <img src="{{ asset('storage/' . $mainProduct['main_image']) }}" alt="{{ $mainProduct['name'] }}">
                <h3 class="collection-title">{{ $mainProduct['name'] }}</h3>
                <p class="collection-price">₹{{ number_format($mainProduct['price'], 2) }}</p>
                <a href="{{ route('customer.products.details', $mainProduct['slug']) }}" class="view-btn">View Details</a>
            </div>
            @endif

            <div class="bangles-side">
                @foreach($subProducts as $product)
                <div class="bangle-item">
                    <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                    <h3 class="collection-title">{{ $product['name'] }}</h3>
                    <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                    <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @elseif($section['style'] === 'style_2')
    <!-- Style 2: Creative Masonry Layout (Rings Style) -->
    <section class="collection-section">
        <div class="section-title">
            <h2>{{ $section['title'] }}</h2>
            @if($section['subtitle']) <p>{{ $section['subtitle'] }}</p> @endif
        </div>

        <div class="ring-grid">
            @foreach($products as $index => $product)
            @if($loop->index < 6)
            <div class="ring-item">
                <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                <h3 class="collection-title">{{ $product['name'] }}</h3>
                <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
            </div>
            @endif
            @endforeach
        </div>
    </section>

    @elseif($section['style'] === 'style_3')
    <!-- Style 3: Horizontal Scroll Cards (Necklaces Style) -->
    <section class="collection-section">
        <div class="section-title">
            <h2>{{ $section['title'] }}</h2>
            @if($section['subtitle']) <p>{{ $section['subtitle'] }}</p> @endif
        </div>

        <div class="necklaces-grid">
            @foreach($products as $product)
            @if($loop->index < 8)
            <div class="necklace-item">
                <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                <h3 class="collection-title">{{ $product['name'] }}</h3>
                <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
            </div>
            @endif
            @endforeach
        </div>
    </section>

    @elseif($section['style'] === 'style_4')
    <!-- Style 4: Center Featured + Side Items (Bracelets Style) -->
    <section class="collection-section">
        <div class="section-title">
            <h2>{{ $section['title'] }}</h2>
            @if($section['subtitle']) <p>{{ $section['subtitle'] }}</p> @endif
        </div>

        <div class="bracelets-grid">
            @php
                $mainProduct = $products[0] ?? null;
                $sideProducts = array_slice($products, 1, 4);
                $leftProducts = array_slice($sideProducts, 0, 2);
                $rightProducts = array_slice($sideProducts, 2, 2);
            @endphp

            <div class="bracelet-side">
                @foreach($leftProducts as $product)
                <div class="bracelet-item">
                    <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                    <h3 class="collection-title">{{ $product['name'] }}</h3>
                    <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                    <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
                </div>
                @endforeach
            </div>

            @if($mainProduct)
            <div class="bracelet-main">
                <img src="{{ asset('storage/' . $mainProduct['main_image']) }}" alt="{{ $mainProduct['name'] }}">
                <h3 class="collection-title">{{ $mainProduct['name'] }}</h3>
                <p class="collection-price">₹{{ number_format($mainProduct['price'], 2) }}</p>
                @if(isset($mainProduct['short_description']))
                <p>{{ $mainProduct['short_description'] }}</p>
                @endif
                <a href="{{ route('customer.products.details', $mainProduct['slug']) }}" class="view-btn">View Details</a>
            </div>
            @endif

            <div class="bracelet-side">
                @foreach($rightProducts as $product)
                <div class="bracelet-item">
                    <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                    <h3 class="collection-title">{{ $product['name'] }}</h3>
                    <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                    <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @elseif($section['style'] === 'style_5')
    <!-- Style 5: Diagonal Staggered Grid (Earrings Style) -->
    <section class="collection-section">
        <div class="section-title">
            <h2>{{ $section['title'] }}</h2>
            @if($section['subtitle']) <p>{{ $section['subtitle'] }}</p> @endif
        </div>

        <div class="earrings-grid">
            @foreach($products as $index => $product)
            @if($loop->index < 6)
            <div class="earring-item">
                <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                <h3 class="collection-title">{{ $product['name'] }}</h3>
                <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
            </div>
            @endif
            @endforeach
        </div>
    </section>

    @elseif($section['style'] === 'style_6')
    <!-- Style 6: Circular/Hexagon Layout (Pendants Style) -->
    <section class="collection-section">
        <div class="section-title">
            <h2>{{ $section['title'] }}</h2>
            @if($section['subtitle']) <p>{{ $section['subtitle'] }}</p> @endif
        </div>

        <div class="pendants-grid">
            @foreach($products as $index => $product)
            @if($loop->index < 5)
            <div class="pendant-item">
                <div class="pendant-item-image">
                    <img src="{{ asset('storage/' . $product['main_image']) }}" alt="{{ $product['name'] }}">
                </div>
                <div class="pendant-item-info">
                    <h3 class="collection-title">{{ $product['name'] }}</h3>
                    <p class="collection-price">₹{{ number_format($product['price'], 2) }}</p>
                    <a href="{{ route('customer.products.details', $product['slug']) }}" class="view-btn">View Details</a>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </section>
    @endif
@endforeach

<!-- Testimonials -->
<section class="testimonials">
    <div class="section-title">
        <h2>TESTIMONIALS</h2>
        <p>What Our Customers Say</p>
        <p>Join thousands of satisfied customers who love our imitation jewellery</p>
    </div>

    <div class="testimonials-grid">
        @forelse($testimonials as $testimonial)
        <div class="testimonial-card">
            <div class="testimonial-text">
                "{{ $testimonial->message }}"
            </div>
            <div class="testimonial-author">
                <div class="author-avatar">
                    @if($testimonial->image)
                        <img src="{{ asset('storage/' . $testimonial->image) }}" alt="{{ $testimonial->name }}" class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr($testimonial->name, 0, 2)) }}
                    @endif
                </div>
                <div class="author-info">
                    <h4>{{ $testimonial->name }}</h4>
                    <p>{{ $testimonial->designation }}</p>
                    <div class="verified">
                        <i class="fas fa-check-circle"></i> Verified
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-8 text-gray-500">
            No testimonials yet.
        </div>
        @endforelse
    </div>
</section>

<!-- Awards Section -->
{{-- <section class="awards-section">
    <div class="section-title">
        <h2>Our Credentials</h2>
        <p>Quality you can trust, excellence you can see</p>
    </div>

    <div class="awards-grid">
        <div class="award-card">
            <div class="award-icon">
                <i class="fas fa-award"></i>
            </div>
            <h3>Award Winning Quality</h3>
            <p>Recognized for excellence in imitation jewellery design</p>
        </div>

        <div class="award-card">
            <div class="award-icon">
                <i class="fas fa-certificate"></i>
            </div>
            <h3>Certified Materials</h3>
            <p>All materials are certified safe and hypoallergenic</p>
        </div>

        <div class="award-card">
            <div class="award-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>Trusted Since 2010</h3>
            <p>Over a decade of serving satisfied customers</p>
        </div>

        <div class="award-card">
            <div class="award-icon">
                <i class="fas fa-star"></i>
            </div>
            <h3>5-Star Rated</h3>
            <p>Consistently high ratings from our customers</p>
        </div>
    </div>
</section> --}}

<!-- Why Choose Us -->
<section class="features-section">
    <div class="section-title">
        <h2>Why Choose Us</h2>
        <p>Experience the difference with our premium imitation jewellery services</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-undo-alt"></i>
            </div>
            <h3>Easy Returns</h3>
            <p>30-day hassle-free return policy</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
            <h3>COD Available</h3>
            <p>Pay when your order arrives</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shipping-fast"></i>
            </div>
            <h3>Free Shipping</h3>
            <p>On all orders over ₹999</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-lock"></i>
            </div>
            <h3>Secure Payment</h3>
            <p>100% protected transactions</p>
        </div>
    </div>
</section>

<!-- Additional Features -->
<section class="additional-features">
    <div class="features-list">
        <div class="feature-item">
            <i class="fas fa-rocket"></i>
            <span>Same Day Dispatch</span>
        </div>
        <div class="feature-item">
            <i class="fas fa-gift"></i>
            <span>Gift Packaging</span>
        </div>
        <div class="feature-item">
            <i class="fas fa-headset"></i>
            <span>24/7 Support</span>
        </div>
        <div class="feature-item">
            <i class="fas fa-shield-alt"></i>
            <span>Quality Guarantee</span>
        </div>
    </div>
</section>



<!-- Notification Toast -->
<div id="notificationToast" class="notification-toast">
    <i class="fas fa-check-circle text-green-500"></i>
    <span id="toastMessage">Product added to cart!</span>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
// Configure Axios
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Swiper
const swiper = new Swiper('.swiper', {
    direction: 'horizontal',
    loop: true,
    autoplay: {
        delay: 5000,
        disableOnInteraction: false,
    },
    pagination: {
        el: '.swiper-pagination',
        clickable: true,
    },
    navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
    },
});

// Smooth scrolling for navigation links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        if(targetId === '#') return;

        const targetElement = document.querySelector(targetId);
        if(targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 100,
                behavior: 'smooth'
            });
        }
    });
});

// Add to cart functionality
document.querySelectorAll('.add-to-cart-btn').forEach(button => {
    button.addEventListener('click', async function(e) {
        e.preventDefault();
        e.stopPropagation();

        const productId = this.getAttribute('data-product-id');
        const variantId = this.getAttribute('data-variant-id');
        const button = this;
        const originalText = button.innerHTML;

        button.innerHTML = 'Adding...';
        button.disabled = true;

        try {
            const response = await axios.post('{{ route("customer.cart.add") }}', {
                variant_id: variantId || productId,
                quantity: 1
            });

            if (response.data.success) {
                showNotification('Product added to cart!', 'success');
                updateCartCount(response.data.cart_count || 0);
            } else {
                showNotification(response.data.message || 'Failed to add to cart', 'error');
            }
        } catch (error) {
            console.error('Add to cart error:', error);
            showNotification('Failed to add to cart. Please try again.', 'error');
        } finally {
            button.innerHTML = originalText;
            button.disabled = false;
        }
    });
});

// Update cart count
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count');
    cartCountElements.forEach(element => {
        element.textContent = count;
    });
}

// Notification system
function showNotification(message, type = 'success') {
    const toast = document.getElementById('notificationToast');
    const toastMessage = document.getElementById('toastMessage');

    if (!toast) return;

    toastMessage.textContent = message;
    toast.className = `notification-toast ${type} show`;

    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>
@endpush
