<?php

return [
    // ============================================
    // WEBSITE INFORMATION
    // ============================================
    'site' => [
        'name' => 'BeeFast Laptop',
        'slogan' => 'Cửa hàng laptop uy tín',
        'description' => 'Chuyên cung cấp laptop chính hãng, giá tốt nhất thị trường',
        'logo' => '/assets/img/logo.png',
        'favicon' => '/assets/img/favicon.ico',
    ],

    // ============================================
    // CONTACT INFORMATION
    // ============================================
    'contact' => [
        'hotline' => '1900 1234',
        'phone' => '0123456789',
        'email' => 'support@beefast.vn',
        'address' => '123 Đường ABC, Quận XYZ, TP.HCM',
        'working_hours' => '8:00 - 22:00 (Tất cả các ngày)',
        'zalo' => 'https://zalo.me/0123456789',
        'facebook' => 'https://facebook.com/laptopstore',
        'messenger' => 'https://m.me/laptopstore',
    ],

    // ============================================
    // COLORS & THEME - Harmonious Color Palette
    // ============================================
    'theme' => [
        'primary' => '#4F46E5', // Indigo - chính
        'primary_dark' => '#4338CA',
        'primary_light' => '#6366F1',
        'primary_lighter' => '#818CF8',
        'secondary' => '#10B981', // Emerald
        'danger' => '#F43F5E', // Rose
        'warning' => '#F59E0B', // Amber
        'success' => '#10B981',
        'info' => '#06B6D4', // Cyan
        'dark' => '#1E293B', // Slate
        'light' => '#F8FAFC',
        'white' => '#FFFFFF',
        'text_primary' => '#0F172A',
        'text_secondary' => '#64748B',
        'text_muted' => '#94A3B8',
        'border' => '#E2E8F0',
        'bg_light' => '#F1F5F9',
        'bg_card' => '#FFFFFF',
    ],

    // ============================================
    // GRADIENTS - Harmonious Gradients
    // ============================================
    'gradients' => [
        'primary' => 'linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%)',
        'primary_soft' => 'linear-gradient(135deg, #6366F1 0%, #8B5CF6 100%)',
        'danger' => 'linear-gradient(135deg, #F43F5E 0%, #EC4899 100%)',
        'success' => 'linear-gradient(135deg, #10B981 0%, #059669 100%)',
        'warning' => 'linear-gradient(135deg, #F59E0B 0%, #F97316 100%)',
        'info' => 'linear-gradient(135deg, #06B6D4 0%, #3B82F6 100%)',
        'top_bar' => 'linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%)',
        'flash_sale' => 'linear-gradient(135deg, #FEF2F2 0%, #FEE2E2 100%)',
        'card_hover' => 'linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%)',
    ],

    // ============================================
    // SHADOWS
    // ============================================
    'shadows' => [
        'sm' => '0 1px 2px 0 rgba(0, 0, 0, 0.05)',
        'md' => '0 4px 6px -1px rgba(0, 0, 0, 0.1)',
        'lg' => '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
        'xl' => '0 20px 25px -5px rgba(0, 0, 0, 0.1)',
        '2xl' => '0 25px 50px -12px rgba(0, 0, 0, 0.25)',
    ],

    // ============================================
    // BORDER RADIUS
    // ============================================
    'radius' => [
        'sm' => '12px',
        'md' => '16px',
        'lg' => '24px',
        'xl' => '32px',
        '2xl' => '40px',
        'full' => '50%',
    ],

    // ============================================
    // SPACING
    // ============================================
    'spacing' => [
        'section_padding' => '3rem 0',
        'card_padding' => '1.25rem',
        'button_padding' => '10px 20px',
    ],

    // ============================================
    // TYPOGRAPHY
    // ============================================
    'typography' => [
        'font_family' => "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif",
        'font_size_base' => '16px',
        'font_size_sm' => '14px',
        'font_size_lg' => '18px',
        'font_size_xl' => '20px',
        'font_size_2xl' => '24px',
        'font_size_3xl' => '30px',
        'font_weight_normal' => '400',
        'font_weight_medium' => '500',
        'font_weight_semibold' => '600',
        'font_weight_bold' => '700',
        'line_height' => '1.6',
    ],

    // ============================================
    // ANIMATIONS
    // ============================================
    'animations' => [
        'duration_fast' => '0.2s',
        'duration_normal' => '0.3s',
        'duration_slow' => '0.5s',
        'easing' => 'cubic-bezier(0.4, 0, 0.2, 1)',
    ],

    // ============================================
    // BREAKPOINTS
    // ============================================
    'breakpoints' => [
        'sm' => '576px',
        'md' => '768px',
        'lg' => '992px',
        'xl' => '1200px',
        'xxl' => '1400px',
    ],

    // ============================================
    // PRODUCT SETTINGS
    // ============================================
    'product' => [
        'items_per_page' => 20,
        'items_per_row_desktop' => 4,
        'items_per_row_tablet' => 3,
        'items_per_row_mobile' => 2,
        'image_placeholder' => 'https://via.placeholder.com/400x300?text=No+Image',
    ],

    // ============================================
    // FLASH SALE SETTINGS
    // ============================================
    'flash_sale' => [
        'enabled' => true,
        'title' => 'FLASH SALE - Giảm sốc đến 50%',
        'countdown_end_time' => '23:59:59',
        'badge_color' => '#ef4444',
    ],

    // ============================================
    // BANNER SETTINGS
    // ============================================
    'banner' => [
        'auto_play' => true,
        'interval' => 4000, // milliseconds
        'height_desktop' => '500px',
        'height_mobile' => '300px',
    ],

    // ============================================
    // SOCIAL MEDIA
    // ============================================
    'social' => [
        'facebook' => 'https://facebook.com/laptopstore',
        'instagram' => 'https://instagram.com/laptopstore',
        'youtube' => 'https://youtube.com/laptopstore',
        'tiktok' => 'https://tiktok.com/@laptopstore',
        'zalo' => 'https://zalo.me/0123456789',
    ],

    // ============================================
    // ORDER STATUS - 7 trạng thái đơn hàng
    // ============================================
    'order_status' => [
        'pending_confirmation' => [
            'value' => 'pending_confirmation',
            'label' => 'Chờ xác nhận',
            'description' => 'Đơn hàng mới được tạo, chờ admin xác nhận hoặc khách hàng có thể hủy',
            'color' => '#F59E0B', // Amber
            'can_transition_to' => ['confirmed', 'cancelled'],
            'is_final' => false,
            'can_cancel_by_customer' => true, // Khách hàng có thể hủy
        ],
        'confirmed' => [
            'value' => 'confirmed',
            'label' => 'Đã xác nhận',
            'description' => 'Admin đã xác nhận đơn hàng, chờ vận chuyển đến',
            'color' => '#3B82F6', // Blue
            'can_transition_to' => ['shipping'],
            'is_final' => false,
            'can_cancel_by_customer' => false,
            'can_request_cancel' => true, // Khách hàng có thể yêu cầu hủy (cần admin xác nhận)
        ],
        'shipping' => [
            'value' => 'shipping',
            'label' => 'Đang giao hàng',
            'description' => 'Đơn hàng đang được vận chuyển đến khách hàng',
            'color' => '#8B5CF6', // Purple
            'can_transition_to' => ['delivered'],
            'is_final' => false,
            'can_cancel_by_customer' => false,
            'can_request_cancel' => false, // Không thể yêu cầu hủy khi đang giao hàng
        ],
        'delivered' => [
            'value' => 'delivered',
            'label' => 'Đã giao hàng',
            'description' => 'Đơn hàng đã đến tay khách hàng, chờ khách xác nhận nhận hàng hoặc hoàn hàng',
            'color' => '#06B6D4', // Cyan
            'can_transition_to' => ['completed', 'delivery_failed'],
            'is_final' => false,
            'can_cancel_by_customer' => false,
            'can_request_cancel' => false, // Không thể yêu cầu hủy khi đã giao hàng
            'can_confirm_by_customer' => true, // Khách hàng có thể xác nhận nhận hàng
            'can_return_by_customer' => true, // Khách hàng có thể hoàn hàng
        ],
        'completed' => [
            'value' => 'completed',
            'label' => 'Thành công',
            'description' => 'Đơn hàng đã hoàn thành, khách hàng đã xác nhận nhận hàng',
            'color' => '#10B981', // Emerald
            'can_transition_to' => [], // Không thể chuyển sang trạng thái khác
            'is_final' => true,
            'can_cancel_by_customer' => false,
        ],
        'cancelled' => [
            'value' => 'cancelled',
            'label' => 'Đã hủy',
            'description' => 'Đơn hàng đã bị hủy',
            'color' => '#F43F5E', // Rose
            'can_transition_to' => [], // Không thể chuyển sang trạng thái khác
            'is_final' => true,
            'can_cancel_by_customer' => false,
        ],
        'delivery_failed' => [
            'value' => 'delivery_failed',
            'label' => 'Giao hàng không thành công',
            'description' => 'Khách hàng đã hoàn hàng, giao hàng không thành công',
            'color' => '#EF4444', // Red
            'can_transition_to' => [], // Không thể chuyển sang trạng thái khác
            'is_final' => true,
            'can_cancel_by_customer' => false,
        ],
    ],

    // ============================================
    // PAYMENT METHODS
    // ============================================
    'payment' => [
        'methods' => [
            'cash' => 'Tiền mặt',
            'bank_transfer' => 'Chuyển khoản',
            'credit_card' => 'Thẻ tín dụng',
            'momo' => 'MoMo',
            'zalo_pay' => 'ZaloPay',
            'vnpay' => 'VNPay',
        ],
    ],

    // ============================================
    // SHIPPING
    // ============================================
    'shipping' => [
        'free_threshold' => 5000000, // VND
        'free_message' => 'Miễn phí vận chuyển cho đơn hàng trên 5 triệu',
        'standard_fee' => 30000, // VND
    ],

    // ============================================
    // CATEGORIES
    // ============================================
    'categories' => [
        'gaming' => [
            'name' => 'Laptop Gaming',
            'icon' => 'bi-controller',
            'color' => '#ef4444',
            'subcategories' => ['ASUS ROG', 'MSI Gaming', 'Acer Predator', 'Lenovo Legion'],
        ],
        'office' => [
            'name' => 'Laptop Văn phòng',
            'icon' => 'bi-briefcase',
            'color' => '#06b6d4',
            'subcategories' => ['Dell', 'HP', 'Lenovo ThinkPad', 'MacBook'],
        ],
        'graphic' => [
            'name' => 'Laptop Đồ họa',
            'icon' => 'bi-palette',
            'color' => '#8b5cf6',
            'subcategories' => ['MacBook Pro', 'Dell XPS', 'HP ZBook'],
        ],
    ],

    // ============================================
    // SECTION TITLES
    // ============================================
    'sections' => [
        'featured' => [
            'title' => 'Sản phẩm nổi bật',
            'icon' => 'bi-star-fill',
            'color' => '#f59e0b',
        ],
        'flash_sale' => [
            'title' => 'FLASH SALE',
            'icon' => 'bi-lightning-fill',
            'color' => '#ef4444',
        ],
        'gaming' => [
            'title' => 'Laptop Gaming',
            'icon' => 'bi-controller',
            'color' => '#ef4444',
        ],
        'office' => [
            'title' => 'Laptop Văn phòng',
            'icon' => 'bi-briefcase',
            'color' => '#06b6d4',
        ],
        'featured_display' => [
            'title' => 'Sản phẩm trưng bày',
            'icon' => 'bi-trophy-fill',
            'color' => '#f59e0b',
        ],
    ],

    // ============================================
    // BUTTON TEXTS
    // ============================================
    'buttons' => [
        'add_to_cart' => 'Thêm vào giỏ',
        'buy_now' => 'Mua ngay',
        'view_all' => 'Xem tất cả',
        'view_detail' => 'Xem chi tiết',
        'add_to_wishlist' => 'Yêu thích',
        'search' => 'Tìm kiếm',
        'login' => 'Đăng nhập',
        'register' => 'Đăng ký',
        'logout' => 'Đăng xuất',
    ],

    // ============================================
    // MESSAGES
    // ============================================
    'messages' => [
        'add_to_cart_success' => 'Đã thêm sản phẩm vào giỏ hàng',
        'add_to_wishlist_success' => 'Đã thêm vào danh sách yêu thích',
        'login_required' => 'Vui lòng đăng nhập để tiếp tục',
        'out_of_stock' => 'Sản phẩm đã hết hàng',
    ],

    // ============================================
    // META TAGS
    // ============================================
    'meta' => [
        'keywords' => 'laptop, máy tính xách tay, laptop gaming, laptop văn phòng, laptop đồ họa, mua laptop online',
        'author' => 'LaptopStore',
        'og_type' => 'website',
        'og_image' => '/assets/img/og-image.jpg',
    ],
];

