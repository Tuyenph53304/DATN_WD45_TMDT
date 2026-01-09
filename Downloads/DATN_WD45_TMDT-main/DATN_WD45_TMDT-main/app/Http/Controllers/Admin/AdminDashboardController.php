<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Voucher;
use App\Exports\StatisticsExport;
use Maatwebsite\Excel\Facades\Excel;

class AdminDashboardController extends Controller
{
    /**
     * Hiển thị dashboard admin
     */
    public function index()
    {
        // Thống kê Users
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();
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
        $totalRevenue = Order::where('payment_status', 'paid')->sum('final_amount');
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', today())
            ->sum('final_amount');
        $recentOrders = Order::with(['user', 'orderItems'])->latest()->take(10)->get();

        // Thống kê Products
        $totalProducts = Product::count();
        $activeProducts = Product::where('status', true)->count();
        $totalCategories = Category::count();
        $activeCategories = Category::where('status', true)->count();

        // Thống kê Vouchers
        $totalVouchers = Voucher::count();
        $activeVouchers = Voucher::where('status', true)->count();

        // Thống kê doanh thu theo tháng (7 tháng gần nhất)
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(final_amount) as revenue')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top sản phẩm bán chạy
        $topProducts = DB::table('order_items')
            ->join('product_variants', 'order_items.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalUsers',
            'totalAdmins',
            'totalRegularUsers',
            'recentUsers',
            'totalOrders',
            'pendingOrders',
            'confirmedOrders',
            'shippingOrders',
            'deliveredOrders',
            'completedOrders',
            'cancelledOrders',
            'failedOrders',
            'totalRevenue',
            'todayRevenue',
            'recentOrders',
            'totalProducts',
            'activeProducts',
            'totalCategories',
            'activeCategories',
            'totalVouchers',
            'activeVouchers',
            'monthlyRevenue',
            'topProducts'
        ));
    }

    /**
     * Xuất báo cáo thống kê
     */
    public function exportStatistics()
    {
        return Excel::download(new StatisticsExport, 'bao-cao-thong-ke-' . now()->format('Y-m-d') . '.xlsx');
    }
}
