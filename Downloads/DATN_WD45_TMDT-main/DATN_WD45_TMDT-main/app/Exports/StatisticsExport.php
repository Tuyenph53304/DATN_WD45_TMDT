<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\Voucher;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Sheets\Sheet;

class StatisticsExport implements FromArray, WithHeadings
{
    /**
     * @return array
     */
    public function array(): array
    {
        // Thống kê Users
        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalRegularUsers = User::where('role', 'user')->count();

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

        $data = [
            ['Thống kê tổng quan', ''],
            ['Tổng số người dùng', $totalUsers],
            ['Số admin', $totalAdmins],
            ['Số người dùng thường', $totalRegularUsers],
            ['', ''],
            ['Thống kê đơn hàng', ''],
            ['Tổng số đơn hàng', $totalOrders],
            ['Đơn hàng chờ xác nhận', $pendingOrders],
            ['Đơn hàng đã xác nhận', $confirmedOrders],
            ['Đơn hàng đang giao', $shippingOrders],
            ['Đơn hàng đã giao', $deliveredOrders],
            ['Đơn hàng hoàn thành', $completedOrders],
            ['Đơn hàng đã hủy', $cancelledOrders],
            ['Đơn hàng giao thất bại', $failedOrders],
            ['Tổng doanh thu', $totalRevenue],
            ['Doanh thu hôm nay', $todayRevenue],
            ['', ''],
            ['Thống kê sản phẩm', ''],
            ['Tổng số sản phẩm', $totalProducts],
            ['Sản phẩm hoạt động', $activeProducts],
            ['Tổng số danh mục', $totalCategories],
            ['Danh mục hoạt động', $activeCategories],
            ['', ''],
            ['Thống kê voucher', ''],
            ['Tổng số voucher', $totalVouchers],
            ['Voucher hoạt động', $activeVouchers],
            ['', ''],
            ['Doanh thu theo tháng (7 tháng gần nhất)', ''],
        ];

        foreach ($monthlyRevenue as $revenue) {
            $data[] = [$revenue->month, $revenue->revenue];
        }

        $data[] = ['', ''];
        $data[] = ['Top 5 sản phẩm bán chạy', ''];
        $data[] = ['Tên sản phẩm', 'Số lượng bán', 'Doanh thu'];

        foreach ($topProducts as $product) {
            $data[] = [$product->name, $product->total_sold, $product->total_revenue];
        }

        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Chỉ số',
            'Giá trị'
        ];
    }
}
