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
     * Mua ngay từ trang sản phẩm
     */
    public function buyNow(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Vui lòng đăng nhập để mua hàng');
        }

        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = \App\Models\ProductVariant::findOrFail($request->variant_id);

        // Kiểm tra tồn kho
        if ($variant->stock < $request->quantity) {
            return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ. Còn lại: ' . $variant->stock);
        }

        // Lưu thông tin vào session để checkout
        session([
            'buy_now' => [
                'variant_id' => $variant->id,
                'quantity' => $request->quantity,
            ]
        ]);

        return redirect()->route('checkout');
    }

    /**
     * Hiển thị trang xác nhận đặt hàng
     */
    public function checkoutForm(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để thanh toán');
        }

        $cartItems = collect();
        $subtotal = 0;

        // Kiểm tra nếu là "Mua ngay"
        if (session('buy_now')) {
            $buyNowData = session('buy_now');
            $variant = \App\Models\ProductVariant::with(['product', 'attributeValues'])
                ->findOrFail($buyNowData['variant_id']);

            // Tạo cart item tạm thời để hiển thị
            $tempCartItem = new CartItem();
            $tempCartItem->id = 'temp_' . $variant->id;
            $tempCartItem->product_variant_id = $variant->id;
            $tempCartItem->quantity = $buyNowData['quantity'];
            $tempCartItem->setRelation('productVariant', $variant);
            
            $cartItems = collect([$tempCartItem]);
            $subtotal = $variant->price * $buyNowData['quantity'];
        } else {
            // Lấy giỏ hàng - chỉ lấy các item đã chọn
            $selectedItems = $request->input('selected_items', '');
            
            // Debug logging
            Log::info('CheckoutForm - selected_items from request', [
                'selected_items' => $selectedItems,
                'all_input' => $request->all(),
                'query_params' => $request->query(),
            ]);
            
            // Xử lý selected_items: loại bỏ khoảng trắng và giá trị rỗng
            $selectedItemIds = [];
            if (!empty($selectedItems)) {
                $items = explode(',', $selectedItems);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (!empty($item) && is_numeric($item)) {
                        $selectedItemIds[] = (int)$item;
                    }
                }
            }
            
            // Debug logging
            Log::info('CheckoutForm - processed selected_item_ids', [
                'selected_item_ids' => $selectedItemIds,
                'count' => count($selectedItemIds),
            ]);
            
            // Nếu không có selected_items hợp lệ, redirect về giỏ hàng
            if (empty($selectedItemIds)) {
                Log::warning('CheckoutForm - No selected items', [
                    'selected_items_raw' => $selectedItems,
                    'user_id' => $user->id,
                ]);
                return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán');
            }

            // Lưu selected_items vào session để giữ lại khi redirect
            session(['checkout_selected_items' => implode(',', $selectedItemIds)]);

            $cartItems = CartItem::with(['productVariant.product', 'productVariant.attributeValues'])
                ->where('user_id', $user->id)
                ->whereIn('id', $selectedItemIds)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Không tìm thấy sản phẩm đã chọn. Vui lòng chọn lại.');
            }

            // Tính tổng tiền
            foreach ($cartItems as $item) {
                $subtotal += $item->productVariant->price * $item->quantity;
            }
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
            } else {
                session()->forget('voucher_code');
                $voucher = null;
            }
        }

        // Lấy địa chỉ giao hàng
        $shippingAddresses = ShippingAddress::where('user_id', $user->id)->get();

        $isBuyNow = session()->has('buy_now');

        return view('user.checkout.index', compact('cartItems', 'subtotal', 'voucher', 'discount', 'finalAmount', 'shippingAddresses', 'isBuyNow'));
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

        $cartItems = collect();
        $subtotal = 0;

        // Kiểm tra nếu là "Mua ngay"
        if (session('buy_now')) {
            $buyNowData = session('buy_now');
            $variant = \App\Models\ProductVariant::with(['product', 'attributeValues'])
                ->findOrFail($buyNowData['variant_id']);

            // Kiểm tra tồn kho
            if ($variant->stock < $buyNowData['quantity']) {
                session()->forget('buy_now');
                return redirect()->back()->with('error', 'Số lượng sản phẩm không đủ. Còn lại: ' . $variant->stock);
            }

            // Tạo cart item tạm thời
            $tempCartItem = new CartItem();
            $tempCartItem->id = 'temp_' . $variant->id;
            $tempCartItem->product_variant_id = $variant->id;
            $tempCartItem->quantity = $buyNowData['quantity'];
            $tempCartItem->setRelation('productVariant', $variant);
            
            $cartItems = collect([$tempCartItem]);
            $subtotal = $variant->price * $buyNowData['quantity'];
        } else {
            // Lấy giỏ hàng - chỉ lấy các item đã chọn
            // Ưu tiên lấy từ request, nếu không có thì lấy từ session
            $selectedItems = $request->input('selected_items', session('checkout_selected_items', ''));
            
            // Xử lý selected_items: loại bỏ khoảng trắng và giá trị rỗng
            $selectedItemIds = [];
            if (!empty($selectedItems)) {
                $items = explode(',', $selectedItems);
                foreach ($items as $item) {
                    $item = trim($item);
                    if (!empty($item) && is_numeric($item)) {
                        $selectedItemIds[] = (int)$item;
                    }
                }
            }
            
            if (empty($selectedItemIds)) {
                return redirect()->route('cart.index')->with('error', 'Vui lòng chọn ít nhất một sản phẩm để thanh toán');
            }

            $cartItems = CartItem::with(['productVariant.product', 'productVariant.attributeValues'])
                ->where('user_id', $user->id)
                ->whereIn('id', $selectedItemIds)
                ->get();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Giỏ hàng của bạn đang trống');
            }

            // Tính tổng tiền
            foreach ($cartItems as $item) {
                $subtotal += $item->productVariant->price * $item->quantity;
            }
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

        // Xử lý địa chỉ giao hàng
        $shippingAddressId = $request->input('shipping_address_id');
        $addressOption = $request->input('address_option', 'default');
        
        // Nếu chọn chỉnh sửa, tạo địa chỉ mới hoặc cập nhật
        if ($addressOption === 'custom') {
            $request->validate([
                'receiver_name' => 'required|string|max:255',
                'receiver_phone' => 'required|string|max:20',
                'receiver_address' => 'required|string|max:500',
                'receiver_city' => 'required|string|max:255',
            ]);

            // Tạo địa chỉ mới tạm thời (không lưu vào DB, chỉ dùng cho đơn hàng này)
            // Hoặc có thể tạo ShippingAddress mới
            $customShippingAddress = ShippingAddress::create([
                'user_id' => $user->id,
                'full_name' => $request->receiver_name,
                'phone' => $request->receiver_phone,
                'address' => $request->receiver_address,
                'city' => $request->receiver_city,
                'default' => false,
            ]);
            $shippingAddressId = $customShippingAddress->id;
        } else {
            // Sử dụng địa chỉ mặc định
            if (!$shippingAddressId) {
                // selected_items đã được lưu trong session từ checkoutForm()
                return redirect()->route('checkout')->with('error', 'Vui lòng chọn địa chỉ giao hàng');
            }

            // Kiểm tra địa chỉ thuộc về user
            $shippingAddress = ShippingAddress::where('id', $shippingAddressId)
                ->where('user_id', $user->id)
                ->first();

            if (!$shippingAddress) {
                // selected_items đã được lưu trong session từ checkoutForm()
                return redirect()->route('checkout')->with('error', 'Địa chỉ giao hàng không hợp lệ');
            }
        }

        // Lấy phương thức thanh toán
        $paymentMethod = $request->input('payment_method', 'momo');
        $momoPaymentType = $request->input('momo_payment_type', 'wallet'); // wallet, card, atm

        // Kiểm tra có phải buy_now không (trước khi tạo đơn hàng)
        $isBuyNow = session()->has('buy_now');
        $buyNowData = null;
        if ($isBuyNow) {
            $buyNowData = session('buy_now');
        }

        // Tạo đơn hàng
        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $user->id,
                'shipping_address_id' => $shippingAddressId,
                'total_price' => $subtotal,
                'status' => 'pending_confirmation', // Trạng thái ban đầu: chờ xác nhận
                'payment_method' => $paymentMethod,
                'payment_status' => $paymentMethod === 'cod' ? 'unpaid' : 'pending',
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

                // KHÔNG trừ stock ở đây vì đã trừ khi thêm vào giỏ hàng
                // Stock sẽ được cộng lại nếu đơn hàng bị hủy
            }

            // Nếu là buy_now, cần trừ stock vì không qua giỏ hàng
            if ($isBuyNow && $buyNowData) {
                $buyNowVariant = \App\Models\ProductVariant::find($buyNowData['variant_id']);
                if ($buyNowVariant) {
                    // Kiểm tra stock trước khi trừ
                    if ($buyNowVariant->stock < $buyNowData['quantity']) {
                        DB::rollBack();
                        return redirect()->route('checkout')->with('error', 'Số lượng sản phẩm không đủ. Còn lại: ' . $buyNowVariant->stock);
                    }
                    $buyNowVariant->decrement('stock', $buyNowData['quantity']);
                }
            }

            // Xử lý theo phương thức thanh toán
            if ($paymentMethod === 'cod') {
                // Thanh toán COD - commit transaction và xóa giỏ hàng
                DB::commit();

                // Xóa giỏ hàng (chỉ các item đã chọn, nếu không phải buy_now)
                if (!$isBuyNow) {
                    // Lấy từ request hoặc session
                    $selectedItems = $request->input('selected_items', session('checkout_selected_items', ''));
                    if ($selectedItems) {
                        // Xử lý selected_items: loại bỏ khoảng trắng và giá trị rỗng
                        $selectedItemIds = [];
                        $items = explode(',', $selectedItems);
                        foreach ($items as $item) {
                            $item = trim($item);
                            if (!empty($item) && is_numeric($item)) {
                                $selectedItemIds[] = (int)$item;
                            }
                        }
                        if (!empty($selectedItemIds)) {
                            CartItem::where('user_id', $user->id)
                                ->whereIn('id', $selectedItemIds)
                                ->delete();
                        }
                    }
                }

                // Xóa session buy_now nếu có
                if ($isBuyNow) {
                    session()->forget('buy_now');
                }

                // Xóa voucher khỏi session nếu đã sử dụng
                if ($voucher) {
                    session()->forget('voucher_code');
                    $voucher->incrementUsage();
                }

                // Xóa session checkout_selected_items
                session()->forget('checkout_selected_items');

                Log::info('COD Order Created', [
                    'order_id' => $order->id,
                    'amount' => $finalAmount,
                ]);

                return redirect()->route('payment.success', ['order' => $order->id])
                    ->with('success', 'Đặt hàng thành công! Bạn sẽ thanh toán khi nhận hàng.');
            } else {
                // Thanh toán MoMo - tạo payment URL trước khi commit
                // Xác định loại thanh toán MoMo
                $momoPaymentType = $request->input('momo_payment_type', 'wallet');
                
                $paymentData = $this->momoService->createPaymentUrl(
                    $order->id,
                    $finalAmount,
                    "Thanh toan don hang #{$order->id}",
                    "Don hang #{$order->id}",
                    $momoPaymentType
                );

                if (!$paymentData['success']) {
                    DB::rollBack();
                    
                    // Thông báo lỗi chi tiết hơn
                    $errorMessage = $paymentData['message'] ?? 'Không thể tạo URL thanh toán';
                    $errorCode = $paymentData['error_code'] ?? null;
                    
                    // Xử lý đặc biệt cho mã lỗi 1005 (Giao dịch hết hạn)
                    if ($errorCode == '1005' || strpos($errorMessage, 'hết hạn') !== false) {
                        $errorMessage = 'Giao dịch MoMo đã hết hạn (Mã lỗi: 1005). ' . 
                                       'Nguyên nhân có thể do: ' .
                                       '1) Thông tin demo MoMo đã hết hạn, ' .
                                       '2) QR code đã hết thời gian hiệu lực. ' .
                                       'Giải pháp: Vui lòng đăng ký tài khoản MoMo Merchant tại https://developers.momo.vn/ ' .
                                       'và cấu hình thông tin trong file .env, hoặc thử lại sau vài phút.';
                    }
                    // Kiểm tra các lỗi khác liên quan đến QR
                    elseif (strpos($errorMessage, 'QR') !== false || 
                            strpos($errorMessage, 'không tồn tại') !== false) {
                        $errorMessage = 'QR code không tồn tại hoặc đã hết hạn. ' . 
                                       'Vui lòng đăng ký tài khoản MoMo Merchant tại https://developers.momo.vn/ ' .
                                       'hoặc chọn phương thức thanh toán khác.';
                    }
                    
                    // Log chi tiết để debug
                    Log::warning('MoMo Payment Failed', [
                        'order_id' => $order->id,
                        'error_code' => $errorCode,
                        'error_message' => $errorMessage,
                        'amount' => $finalAmount,
                    ]);
                    
                    return redirect()->route('cart.index')->with('error', $errorMessage);
                }

                // Lưu transaction ref vào order
                $order->transaction_id = $paymentData['order_id'];
                $order->save();

                // Commit transaction
                DB::commit();

                // Xóa giỏ hàng (chỉ các item đã chọn, nếu không phải buy_now)
                if (!$isBuyNow) {
                    // Lấy từ request hoặc session
                    $selectedItems = $request->input('selected_items', session('checkout_selected_items', ''));
                    if ($selectedItems) {
                        // Xử lý selected_items: loại bỏ khoảng trắng và giá trị rỗng
                        $selectedItemIds = [];
                        $items = explode(',', $selectedItems);
                        foreach ($items as $item) {
                            $item = trim($item);
                            if (!empty($item) && is_numeric($item)) {
                                $selectedItemIds[] = (int)$item;
                            }
                        }
                        if (!empty($selectedItemIds)) {
                            CartItem::where('user_id', $user->id)
                                ->whereIn('id', $selectedItemIds)
                                ->delete();
                        }
                    }
                }

                // Xóa session buy_now nếu có
                if ($isBuyNow) {
                    session()->forget('buy_now');
                }

                // Xóa voucher khỏi session nếu đã sử dụng
                if ($voucher) {
                    session()->forget('voucher_code');
                    $voucher->incrementUsage();
                }

                // Xóa session checkout_selected_items
                session()->forget('checkout_selected_items');

                // Log để debug
                if (config('app.debug')) {
                    Log::info('MoMo Checkout', [
                        'order_id' => $order->id,
                        'amount' => $finalAmount,
                        'payment_url' => $paymentData['url'],
                        'payment_type' => $momoPaymentType,
                        'request_type' => $paymentData['request_type'] ?? 'unknown',
                    ]);
                }

                // Chuyển đến trang thanh toán MoMo
                // Lưu ý: Với payWithCC/payWithATM, MoMo sẽ hiển thị form nhập thẻ
                // Với captureWallet, MoMo sẽ hiển thị QR code
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
            // Cập nhật đơn hàng - thanh toán thành công
            $order->payment_status = 'paid';
            $order->status = 'pending_confirmation'; // Chuyển sang chờ xác nhận sau khi thanh toán thành công
            $order->transaction_id = $result['transaction_id'];
            
            // Lưu thông tin thanh toán chi tiết
            $paymentInfo = [
                'transaction_id' => $result['transaction_id'],
                'amount' => $result['amount'],
                'payment_method' => $order->payment_method,
                'pay_type' => $inputData['payType'] ?? null,
                'order_type' => $inputData['orderType'] ?? null,
                'response_time' => $inputData['responseTime'] ?? now()->toDateTimeString(),
                'message' => $result['message'] ?? 'Thanh toán thành công',
            ];
            
            // Lưu vào customer_note hoặc có thể tạo bảng payment_details riêng
            // Tạm thời lưu JSON vào một field hoặc log
            Log::info('MoMo Payment Success - Details', [
                'order_id' => $order->id,
                'payment_info' => $paymentInfo,
            ]);
            
            $order->save();

            // Xóa giỏ hàng
            CartItem::where('user_id', $order->user_id)->delete();

            // Xóa session checkout_selected_items
            session()->forget('checkout_selected_items');

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
            $order->status = 'pending_confirmation'; // Chuyển sang chờ xác nhận sau khi thanh toán thành công
            $order->transaction_id = $result['transaction_id'];
            
            // Lưu thông tin thanh toán chi tiết
            $paymentInfo = [
                'transaction_id' => $result['transaction_id'],
                'amount' => $result['amount'],
                'payment_method' => $order->payment_method,
                'pay_type' => $inputData['payType'] ?? null,
                'order_type' => $inputData['orderType'] ?? null,
                'response_time' => $inputData['responseTime'] ?? now()->toDateTimeString(),
                'message' => $result['message'] ?? 'Thanh toán thành công',
            ];
            
            Log::info('MoMo IPN Payment Success - Details', [
                'order_id' => $order->id,
                'payment_info' => $paymentInfo,
            ]);
            
            $order->save();

            // Xóa giỏ hàng
            CartItem::where('user_id', $order->user_id)->delete();

            // Xóa session checkout_selected_items
            session()->forget('checkout_selected_items');

            Log::info('MoMo IPN Payment Success', [
                'order_id' => $order->id,
            ]);
        }

        return response()->json(['success' => true], 200);
    }
}
