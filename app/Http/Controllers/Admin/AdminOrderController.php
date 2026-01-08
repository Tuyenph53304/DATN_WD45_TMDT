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
        if (!$order->cancelled_request) {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Đơn hàng này không có yêu cầu hủy!');
        }

        // Chỉ cho phép xác nhận hủy khi đơn hàng từ confirmed trở đi (không phải pending_confirmation)
        if ($order->status === 'pending_confirmation') {
            return redirect()->route('admin.orders.show', $order->id)
                ->with('error', 'Đơn hàng đang chờ xác nhận sẽ được hủy trực tiếp bởi khách hàng, không cần admin xác nhận!');
        }

        // Đóng luồng: chuyển sang trạng thái cancelled và reset cancelled_request
        $order->update([
            'status' => 'cancelled',
            'cancelled_request' => false, // Đã xử lý yêu cầu hủy
        ]);

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

        // Chuẩn bị dữ liệu cập nhật
        // Khi admin chọn "delivered" thì tự động chuyển sang "completed"
        $finalStatus = $newStatus;
        if ($newStatus === 'delivered') {
            $finalStatus = 'completed';
        }
        
        $updateData = ['status' => $finalStatus];

        /**
         * Đồng bộ trạng thái thanh toán với trạng thái đơn hàng:
         * - Khi admin chuyển đơn sang "delivered" (đã giao hàng) hoặc "completed" (thành công),
         *   nếu payment_status chưa là "paid" thì tự động cập nhật thành "paid".
         *
         * Lý do:
         * - Đối với thanh toán online (MoMo), payment_status thường đã là "paid" từ callback.
         * - Đối với COD, khi admin xác nhận đã giao hàng thì thực tế tiền đã được thu,
         *   nên cần chuyển payment_status sang "paid" để thống kê doanh thu chính xác.
         */
        if (in_array($finalStatus, ['delivered', 'completed']) && $order->payment_status !== 'paid') {
            $updateData['payment_status'] = 'paid';
        }

        $order->update($updateData);
        
        // Cập nhật lại newStatus để hiển thị đúng thông báo
        $newStatus = $finalStatus;

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
