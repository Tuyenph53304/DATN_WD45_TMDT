<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use App\Models\Voucher;
use App\Models\ShippingAddress;
use App\Services\MoMoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $momoService;

    public function __construct(MoMoService $momoService)
    {
        $this->momoService = $momoService;
    }

    /**
     * Tạo đơn hàng và chuyển đến thanh toán
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để thanh toán');
        }

        // Lấy giỏ hàng
        $cartItems = CartItem::with(['productVariant.product', 'productVariant.attributeValues'])
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
        }

        // Tính tổng tiền
        $subtotal = 0;
        foreach ($cartItems as $item) {
            $subtotal += $item->productVariant->price * $item->quantity;
        }

        // Áp dụng voucher nếu có
        $voucher = null;
        $discount = 0;
        $finalAmount = $subtotal;

        if (session('voucher_code')) {
            $voucher = Voucher::where('code', session('voucher_code'))->first();
            if ($voucher && $voucher->isValid($subtotal)) {
                $discount = $voucher->calculateDiscount($subtotal);
                $finalAmount = $subtotal - $discount;
            }
        }

        // Validate địa chỉ giao hàng
        $shippingAddressId = $request->input('shipping_address_id');
        if (!$shippingAddressId) {
            return redirect()->route('cart.index')->with('error', 'Vui lòng chọn địa chỉ giao hàng');
        }

        // Kiểm tra địa chỉ thuộc về user
        $shippingAddress = ShippingAddress::where('id', $shippingAddressId)
            ->where('user_id', $user->id)
            ->first();

        if (!$shippingAddress) {
            return redirect()->route('cart.index')->with('error', 'Địa chỉ giao hàng không hợp lệ');
        }

        // Lấy phương thức thanh toán
        $paymentMethod = $request->input('payment_method', 'momo');

        // Tạo đơn hàng
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shippingAddressId,
                'total_price' => $subtotal,
                'status' => $paymentMethod === 'cod' ? 'processing' : 'pending',
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'paid' : 'pending',
                'voucher_code' => $voucher ? $voucher->code : null,
                'discount_amount' => $discount,
                'final_amount' => $finalAmount,
            ]);

            // Tạo order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_variant_id' => $cartItem->product_variant_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->productVariant->price,
                ]);
            }

            // Xử lý theo phương thức thanh toán
            if ($paymentMethod === 'cod') {
                // Thanh toán COD - commit transaction và xóa giỏ hàng
                DB::commit();

                // Xóa giỏ hàng
                CartItem::where('user_id', $user->id)->delete();

                // Xóa voucher khỏi session nếu đã sử dụng
                if ($voucher) {
                    session()->forget('voucher_code');
                    $voucher->incrementUsage();
                }

                Log::info('COD Order Created', [
                    'order_id' => $order->id,
                    'amount' => $finalAmount,
                ]);

                return redirect()->route('payment.success', ['order' => $order->id])
                    ->with('success', 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
            } else {
                // Thanh toán MoMo - tạo payment URL trước khi commit
                $paymentData = $this->momoService->createPaymentUrl(
                    $order->id,
                    $finalAmount,
                    "Thanh toan don hang #{$order->id}",
                    "Don hang #{$order->id}"
                );

                if (!$paymentData['success']) {
                    DB::rollBack();
                    return redirect()->route('cart.index')->with('error', $paymentData['message'] ?? 'Không thể tạo URL thanh toán');
                }

                // Lưu transaction ref vào order
                $order->transaction_id = $paymentData['order_id'];
                $order->save();

                // Commit transaction
                DB::commit();

                // Xóa giỏ hàng
                CartItem::where('user_id', $user->id)->delete();

                // Xóa voucher khỏi session nếu đã sử dụng
                if ($voucher) {
                    session()->forget('voucher_code');
                    $voucher->incrementUsage();
                }

                // Log để debug
                if (config('app.debug')) {
                    Log::info('MoMo Checkout', [
                        'order_id' => $order->id,
                        'amount' => $finalAmount,
                        'payment_url' => $paymentData['url'],
                    ]);
                }

                // Chuyển đến trang thanh toán MoMo
                return redirect($paymentData['url']);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Payment Checkout Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->route('cart.index')->with('error', 'Có lỗi xảy ra khi tạo đơn hàng: ' . $e->getMessage());
        }
    }

    /**
     * Xử lý callback từ MoMo
     */
    public function callback(Request $request)
    {
        $inputData = $request->all();

        // Log tất cả dữ liệu callback để debug
        if (config('app.debug')) {
            Log::info('MoMo Callback Received', [
                'all_data' => $inputData,
            ]);
        }

        // Xác thực callback
        $result = $this->momoService->verifyPayment($inputData);

        if (!$result['success']) {
            Log::warning('MoMo Callback Verification Failed', [
                'result' => $result,
                'input_data' => $inputData,
            ]);
            return redirect()->route('payment.fail')->with('error', 'Xác thực thanh toán thất bại: ' . ($result['message'] ?? 'Sai chữ ký'));
        }

        // Lấy order từ orderId
        $orderIdStr = $result['order_id'];
        $orderId = explode('_', $orderIdStr)[0];
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning('MoMo Order Not Found', [
                'order_id_str' => $orderIdStr,
                'order_id' => $orderId,
            ]);
            return redirect()->route('payment.fail')->with('error', 'Không tìm thấy đơn hàng');
        }

        // Kiểm tra result code
        // 0 = Thành công
        if ($result['result_code'] == '0') {
            // Cập nhật đơn hàng
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->transaction_id = $result['transaction_id'];
            $order->save();

            // Xóa giỏ hàng
            CartItem::where('user_id', $order->user_id)->delete();

            Log::info('MoMo Payment Success', [
                'order_id' => $order->id,
                'transaction_id' => $result['transaction_id'],
            ]);

            return redirect()->route('payment.success', ['order' => $order->id])
                ->with('success', 'Thanh toán thành công!');
        } else {
            // Thanh toán thất bại
            $order->payment_status = 'failed';
            $order->save();

            $errorMessages = [
                '1001' => 'Giao dịch bị từ chối',
                '1002' => 'Giao dịch đang được xử lý',
                '1003' => 'Giao dịch bị hủy',
                '1004' => 'Giao dịch không thành công',
                '1005' => 'Giao dịch hết hạn',
                '1006' => 'Giao dịch thất bại',
            ];

            $errorMessage = $errorMessages[$result['result_code']] ?? ($result['message'] ?? 'Thanh toán thất bại. Mã lỗi: ' . $result['result_code']);

            Log::warning('MoMo Payment Failed', [
                'order_id' => $order->id,
                'result_code' => $result['result_code'],
                'error_message' => $errorMessage,
            ]);

            return redirect()->route('payment.fail')
                ->with('error', $errorMessage);
        }
    }

    /**
     * Trang thanh toán thành công
     */
    public function success($order)
    {
        $order = Order::with(['orderItems.productVariant.product', 'shippingAddress'])
            ->where('id', $order)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('user.payment.success', compact('order'));
    }

    /**
     * Trang thanh toán thất bại
     */
    public function fail()
    {
        return view('user.payment.fail');
    }

    /**
     * Xử lý IPN (Instant Payment Notification) từ MoMo
     */
    public function ipn(Request $request)
    {
        $inputData = $request->all();

        // Log IPN
        Log::info('MoMo IPN Received', [
            'all_data' => $inputData,
        ]);

        // Xác thực IPN
        $result = $this->momoService->verifyPayment($inputData);

        if (!$result['success']) {
            Log::warning('MoMo IPN Verification Failed', [
                'result' => $result,
            ]);
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        // Lấy order
        $orderIdStr = $result['order_id'];
        $orderId = explode('_', $orderIdStr)[0];
        $order = Order::find($orderId);

        if (!$order) {
            Log::warning('MoMo IPN Order Not Found', [
                'order_id' => $orderId,
            ]);
            return response()->json(['error' => 'Order not found'], 404);
        }

        // Cập nhật đơn hàng nếu thành công
        if ($result['result_code'] == '0' && $order->payment_status != 'paid') {
            $order->payment_status = 'paid';
            $order->status = 'processing';
            $order->transaction_id = $result['transaction_id'];
            $order->save();

            // Xóa giỏ hàng
            CartItem::where('user_id', $order->user_id)->delete();

            Log::info('MoMo IPN Payment Success', [
                'order_id' => $order->id,
            ]);
        }

        return response()->json(['success' => true], 200);
    }
}
