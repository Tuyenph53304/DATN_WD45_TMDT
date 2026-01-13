<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    /**
     * Hiển thị dashboard admin
     */
    public function index(Request $request)
    {
        $period = $request->get('period', 'month'); // day, week, month, year

        // Thống kê Users
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
        $newUsersToday = User::whereDate('created_at', today())->count();
        $newUsersThisWeek = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $newUsersThisMonth = User::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $newUsersThisYear = User::whereYear('created_at', now()->year)->count();
        $recentUsers = User::latest()->take(5)->get();

        // Thống kê Orders
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending_confirmation')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $shippingOrders = Order::where('status', 'shipping')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $failedOrders = Order::where('status', 'delivery_failed')->count();
        
        // Đơn hàng theo thời gian
        $ordersToday = Order::whereDate('created_at', today())->count();
        $ordersThisWeek = Order::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();
        $ordersThisYear = Order::whereYear('created_at', now()->year)->count();

        // Thống kê Revenue
        $totalRevenue = Order::where('payment_status', 'paid')->sum('final_amount');
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('final_amount');
        $weekRevenue = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('final_amount');
        $monthRevenue = Order::where('payment_status', 'paid')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('final_amount');
        $yearRevenue = Order::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->sum('final_amount');

        // Giá trị đơn hàng trung bình
        $avgOrderValue = Order::where('payment_status', 'paid')->avg('final_amount') ?? 0;
        $avgOrderValueToday = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->avg('final_amount') ?? 0;

        // Tỷ lệ chuyển đổi (đơn hàng đã thanh toán / tổng đơn hàng)
        $conversionRate = $totalOrders > 0 
            ? (Order::where('payment_status', 'paid')->count() / $totalOrders) * 100 
            : 0;

        $recentOrders = Order::with(['user', 'orderItems'])->latest()->take(10)->get();

        // Thống kê Products
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', true)->count();
        $inactiveProducts = Product::where('status', false)->count();
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', true)->count();

        // Thống kê Vouchers
        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('status', true)->count();
        $usedVouchers = Order::whereNotNull('voucher_code')->distinct('voucher_code')->count();

        // Thống kê doanh thu theo ngày (30 ngày gần nhất)
        $dailyRevenue = Order::where('payment_status', 'paid')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Thống kê doanh thu theo tuần (12 tuần gần nhất)
        $weeklyRevenue = Order::where('payment_status', 'paid')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('WEEK(created_at) as week'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', now()->subWeeks(12))
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get();

        // Thống kê doanh thu theo tháng (12 tháng gần nhất)
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Thống kê doanh thu theo năm (5 năm gần nhất)
        $yearlyRevenue = Order::where('payment_status', 'paid')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(final_amount) as revenue'),
                DB::raw('COUNT(*) as orders')
            )
            ->where('created_at', '>=', now()->subYears(5))
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // Top sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->select(
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(10)
            ->get();

        // Thống kê đơn hàng theo trạng thái (cho biểu đồ)
        $ordersByStatus = Order::select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('status')
            ->get();

        // Thống kê đơn hàng theo phương thức thanh toán
        $ordersByPaymentMethod = Order::select(
                'payment_method',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(final_amount) as revenue')
            )
            ->where('payment_status', 'paid')
            ->groupBy('payment_method')
            ->get();

        // Thống kê theo giờ trong ngày (24 giờ)
        $hourlyOrders = Order::select(
                DB::raw('HOUR(created_at) as hour'),
                DB::raw('COUNT(*) as count')
            )
            ->whereDate('created_at', today())
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        // Thống kê khách hàng mới theo tháng (12 tháng)
        $newUsersByMonth = User::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(12))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('admin.dashboard.index', compact(
            'period',
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'newUsersToday',
            'newUsersThisWeek',
            'newUsersThisMonth',
            'newUsersThisYear',
            'recentUsers',
            'totalOrders',
            'ordersToday',
            'ordersThisWeek',
            'ordersThisMonth',
            'ordersThisYear',
            'pendingOrders',
            'confirmedOrders',
            'shippingOrders',
            'deliveredOrders',
            'completedOrders',
            'cancelledOrders',
            'failedOrders',
            'totalRevenue',
            'todayRevenue',
            'weekRevenue',
            'monthRevenue',
            'yearRevenue',
            'avgOrderValue',
            'avgOrderValueToday',
            'conversionRate',
            'recentOrders',
            'totalProducts',
            'activeProducts',
            'inactiveProducts',
            'totalCategories',
            'activeCategories',
            'totalVouchers',
            'activeVouchers',
            'usedVouchers',
            'dailyRevenue',
            'weeklyRevenue',
            'monthlyRevenue',
            'yearlyRevenue',
            'topProducts',
            'ordersByStatus',
            'ordersByPaymentMethod',
            'hourlyOrders',
            'newUsersByMonth'
        ));
    }
}
