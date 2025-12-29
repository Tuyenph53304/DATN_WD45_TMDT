<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    /**
     * Hiển thị danh sách orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'shippingAddress', 'orderItems']);

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id', 'like', '%' . $request->search . '%')
                  ->orWhere('transaction_id', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', function ($userQuery) use ($request) {
                      $userQuery->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('email', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Lọc theo status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo payment_status
        if ($request->has('payment_status') && $request->payment_status) {
            $query->where('payment_status', $request->payment_status);
        }

        // Lọc theo payment_method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->latest()->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Xem chi tiết order
     */
    public function show(Order $order)
    {
        $order->load(['user', 'shippingAddress', 'orderItems.productVariant.product', 'orderItems.productVariant.attributeValues']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Xác nhận đơn hàng (chuyển từ "chờ xác nhận" sang "đã xác nhận")
     */
    public function confirmOrder(Order $order)
    {
        if ($order->status !== 'pending_confirmation') {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Chỉ có thể xác nhận đơn hàng đang ở trạng thái "Chờ xác nhận"!');
        }

        $order->update(['status' => 'confirmed']);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đã xác nhận đơn hàng thành công!');
    }

    /**
     * Xác nhận hủy đơn hàng từ khách hàng
     */
    public function confirmCancel(Order $order)
    {
        if ($order->status !== 'pending_confirmation') {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Chỉ có thể xác nhận hủy đơn hàng đang ở trạng thái "Chờ xác nhận"!');
        }

        $order->update(['status' => 'cancelled']);

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Đã xác nhận hủy đơn hàng!');
    }

    /**
     * Cập nhật status của order
     *
     * Quy tắc chuyển trạng thái:
     * pending_confirmation → confirmed → shipping → delivered → completed (THÀNH CÔNG)
     * pending_confirmation → cancelled (ĐÃ HỦY)
     * delivered → delivery_failed (GIAO HÀNG KHÔNG THÀNH CÔNG)
     *
     * Trạng thái completed, cancelled, delivery_failed KHÔNG THỂ THAY ĐỔI
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Kiểm tra nếu đơn hàng đã ở trạng thái cuối cùng
        if ($order->isFinalStatus()) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Không thể cập nhật trạng thái đơn hàng đã ở trạng thái cuối cùng!');
        }

        $request->validate([
            'status' => 'required|in:pending_confirmation,confirmed,shipping,delivered,completed,cancelled,delivery_failed',
        ]);

        $newStatus = $request->status;
        $currentStatus = $order->status;

        // Kiểm tra quá trình chuyển trạng thái hợp lệ sử dụng method từ model
        if (!$order->canTransitionTo($newStatus)) {
            $statusLabels = config('constants.order_status');
            $currentLabel = $statusLabels[$currentStatus]['label'] ?? $currentStatus;
            $newLabel = $statusLabels[$newStatus]['label'] ?? $newStatus;
            
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', "Không thể chuyển từ trạng thái '{$currentLabel}' sang '{$newLabel}'!");
        }

        $order->update(['status' => $newStatus]);

        $statusLabels = config('constants.order_status');
        $newLabel = $statusLabels[$newStatus]['label'] ?? $newStatus;

        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', "Đã cập nhật trạng thái đơn hàng sang '{$newLabel}' thành công!");
    }

    /**
     * Xóa order
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Xóa đơn hàng thành công!');
    }
}
