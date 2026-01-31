@extends('admin.layouts.master')

@section('title', 'Notifications')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-3xl font-bold text-gray-900">Notifications</h2>
            <p class="text-gray-600 mt-2">Manage and view all system notifications</p>
        </div>
        <div class="flex space-x-3">
            <button id="markAllRead" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                <i class="fas fa-check-double mr-2"></i> Mark All as Read
            </button>
            <button id="clearAll" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors">
                <i class="fas fa-trash-alt mr-2"></i> Clear All
            </button>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <button class="notification-tab active py-3 px-1 border-b-2 border-indigo-500 text-sm font-medium text-indigo-600" data-filter="all">
                All Notifications
            </button>
            <button class="notification-tab py-3 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="unread">
                Unread <span id="unread-count" class="ml-2 bg-rose-500 text-white rounded-full px-2 py-1 text-xs">3</span>
            </button>
            <button class="notification-tab py-3 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="system">
                System
            </button>
            <button class="notification-tab py-3 px-1 border-b-2 border-transparent text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300" data-filter="orders">
                Orders
            </button>
        </nav>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="divide-y divide-gray-200">
            <!-- Notification Item - Unread -->
            <div class="notification-item p-6 hover:bg-gray-50 cursor-pointer transition-colors" data-type="orders" data-read="false">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-emerald-500 to-green-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">New Order Received</h4>
                                <p class="mt-1 text-sm text-gray-600">Order #ORD-2023-0012 has been placed by John Doe. Total: $245.99</p>
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>5 minutes ago</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    New
                                </span>
                                <button class="mark-read-btn text-gray-400 hover:text-indigo-600">
                                    <i class="far fa-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Item - Unread -->
            <div class="notification-item p-6 hover:bg-gray-50 cursor-pointer transition-colors" data-type="system" data-read="false">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-server text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">System Backup Completed</h4>
                                <p class="mt-1 text-sm text-gray-600">Daily system backup completed successfully. Backup size: 2.4 GB</p>
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>1 hour ago</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="mark-read-btn text-gray-400 hover:text-indigo-600">
                                    <i class="far fa-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Item - Read -->
            <div class="notification-item p-6 hover:bg-gray-50 cursor-pointer transition-colors bg-gray-50" data-type="orders" data-read="true">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-amber-500 to-orange-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Low Stock Alert</h4>
                                <p class="mt-1 text-sm text-gray-600">Product "Wireless Headphones" is running low on stock. Current stock: 5 units</p>
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>3 hours ago</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    Stock
                                </span>
                                <button class="mark-read-btn text-indigo-600">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Item - Read -->
            <div class="notification-item p-6 hover:bg-gray-50 cursor-pointer transition-colors" data-type="system" data-read="true">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">New User Registration</h4>
                                <p class="mt-1 text-sm text-gray-600">New user "sarah_johnson" has registered on the platform</p>
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>Yesterday, 2:30 PM</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="mark-read-btn text-indigo-600">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notification Item - Read -->
            <div class="notification-item p-6 hover:bg-gray-50 cursor-pointer transition-colors" data-type="orders" data-read="true">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-blue-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white"></i>
                        </div>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-sm font-semibold text-gray-900">Order Shipped</h4>
                                <p class="mt-1 text-sm text-gray-600">Order #ORD-2023-0011 has been shipped. Tracking number: TRK-789456123</p>
                                <div class="mt-2 flex items-center text-xs text-gray-500">
                                    <i class="far fa-clock mr-1"></i>
                                    <span>2 days ago</span>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    Shipped
                                </span>
                                <button class="mark-read-btn text-indigo-600">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-notifications" class="hidden p-12 text-center">
            <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-bell-slash text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No notifications</h3>
            <p class="text-gray-600 mb-6">All caught up! You don't have any notifications at the moment.</p>
        </div>
    </div>

    <!-- Load More -->
    <div class="mt-6 text-center">
        <button id="loadMore" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
            <i class="fas fa-redo mr-2"></i> Load More Notifications
        </button>
    </div>
</div>

<!-- Notification Detail Modal -->
<div id="notificationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-lg w-full">
        <div class="p-6">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-gray-900" id="modalTitle">Notification Details</h3>
                    <p class="text-gray-600 mt-1" id="modalTime"></p>
                </div>
                <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div id="modalContent" class="prose max-w-none">
                    <!-- Content will be inserted here -->
                </div>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button id="markAsReadModal" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                    Mark as Read
                </button>
                <button id="deleteModal" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let currentFilter = 'all';
    
    // Tab switching
    $('.notification-tab').click(function() {
        currentFilter = $(this).data('filter');
        $('.notification-tab').removeClass('active border-indigo-500 text-indigo-600')
            .addClass('border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300');
        $(this).addClass('active border-indigo-500 text-indigo-600')
            .removeClass('border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300');
        
        filterNotifications();
    });
    
    // Filter notifications
    function filterNotifications() {
        $('.notification-item').each(function() {
            const type = $(this).data('type');
            const isRead = $(this).data('read');
            
            let show = true;
            
            if (currentFilter === 'unread') {
                show = !isRead;
            } else if (currentFilter === 'system' || currentFilter === 'orders') {
                show = type === currentFilter;
            }
            
            if (show) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        // Show empty state if no notifications visible
        const visibleCount = $('.notification-item:visible').length;
        if (visibleCount === 0) {
            $('#empty-notifications').show();
        } else {
            $('#empty-notifications').hide();
        }
        
        // Update unread count
        const unreadCount = $('.notification-item[data-read="false"]').length;
        $('#unread-count').text(unreadCount);
    }
    
    // Mark as read
    $('.mark-read-btn').click(function(e) {
        e.stopPropagation();
        const notification = $(this).closest('.notification-item');
        notification.data('read', true);
        notification.removeClass('bg-gray-50');
        $(this).html('<i class="fas fa-check-circle"></i>').addClass('text-indigo-600');
        
        // Update unread count
        const unreadCount = $('.notification-item[data-read="false"]').length;
        $('#unread-count').text(unreadCount);
    });
    
    // Mark all as read
    $('#markAllRead').click(function() {
        Swal.fire({
            title: 'Mark all as read?',
            text: 'This will mark all notifications as read.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4f46e5',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, mark all',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('.notification-item').each(function() {
                    $(this).data('read', true);
                    $(this).find('.mark-read-btn').html('<i class="fas fa-check-circle"></i>').addClass('text-indigo-600');
                });
                
                $('#unread-count').text('0');
                toastr.success('All notifications marked as read');
            }
        });
    });
    
    // Clear all notifications
    $('#clearAll').click(function() {
        Swal.fire({
            title: 'Clear all notifications?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, clear all',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                $('.notification-item').remove();
                $('#empty-notifications').show();
                $('#unread-count').text('0');
                toastr.success('All notifications cleared');
            }
        });
    });
    
    // Load more notifications
    $('#loadMore').click(function() {
        // Simulate loading more notifications
        toastr.info('Loading more notifications...');
        // In real implementation, you would fetch from API
    });
    
    // Notification click for details
    $('.notification-item').click(function() {
        const title = $(this).find('h4').text();
        const content = $(this).find('p').text();
        const time = $(this).find('.text-xs span').text();
        
        $('#modalTitle').text(title);
        $('#modalTime').text(time);
        $('#modalContent').html(`<p>${content}</p>`);
        
        // Show modal
        $('#notificationModal').removeClass('hidden').addClass('flex');
    });
    
    // Close modal
    $('#closeModal').click(function() {
        $('#notificationModal').removeClass('flex').addClass('hidden');
    });
    
    // Click outside to close
    $('#notificationModal').click(function(e) {
        if (e.target === this) {
            $(this).removeClass('flex').addClass('hidden');
        }
    });
    
    // Mark as read from modal
    $('#markAsReadModal').click(function() {
        // Find and mark the currently viewed notification as read
        toastr.success('Notification marked as read');
        $('#notificationModal').removeClass('flex').addClass('hidden');
    });
    
    // Delete from modal
    $('#deleteModal').click(function() {
        Swal.fire({
            title: 'Delete notification?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                toastr.success('Notification deleted');
                $('#notificationModal').removeClass('flex').addClass('hidden');
            }
        });
    });
    
    // Initialize filter
    filterNotifications();
});
</script>
@endpush