<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Hiển thị giỏ hàng
     */
    public function index()
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('home')->with('error', 'Vui lòng đăng nhập để xem giỏ hàng');
        }

        $cartItems = CartItem::with(['productVariant.product.images', 'productVariant.product', 'productVariant.attributeValues.attribute'])
            ->where('user_id', $user->id)
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->productVariant->price * $item->quantity;
        }

        // Lấy voucher từ session nếu có
        $voucher = null;
        $discount = 0;
        $finalTotal = $total;

        if (session('voucher_code')) {
            $voucher = \App\Models\Voucher::where('code', session('voucher_code'))->first();
            if ($voucher && $voucher->isValid($total)) {
                $discount = $voucher->calculateDiscount($total);
                $finalTotal = $total - $discount;
            } else {
                session()->forget('voucher_code');
                $voucher = null;
            }
        }

        return view('user.cart.index', compact('cartItems', 'total', 'voucher', 'discount', 'finalTotal'));
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     */
    public function add(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm vào giỏ hàng'
            ], 401);
        }

        $request->validate([
            'product_variant_id' => 'required|exists:product_variants,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ProductVariant::findOrFail($request->product_variant_id);

        // Kiểm tra tồn kho
        if ($variant->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ. Còn lại: ' . $variant->stock
            ], 400);
        }

        // Tìm cart item đã tồn tại
        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_variant_id', $request->product_variant_id)
            ->first();

        if ($cartItem) {
            // Cập nhật số lượng
            $newQuantity = $cartItem->quantity + $request->quantity;
            if ($variant->stock < $newQuantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Số lượng sản phẩm không đủ. Còn lại: ' . $variant->stock
                ], 400);
            }
            $cartItem->quantity = $newQuantity;
            $cartItem->save();

            // Trừ stock
            $variant->stock -= $request->quantity;
            $variant->save();
        } else {
            // Tạo mới
            $cartItem = CartItem::create([
                'user_id' => $user->id,
                'product_variant_id' => $request->product_variant_id,
                'quantity' => $request->quantity,
            ]);

            // Trừ stock
            $variant->stock -= $request->quantity;
            $variant->save();
        }

        // Refresh variant để lấy stock mới nhất
        $variant->refresh();

        // Đếm số lượng sản phẩm trong giỏ
        $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Đã thêm sản phẩm vào giỏ hàng',
            'cart_count' => $cartCount,
            'updated_stock' => $variant->stock
        ]);
    }

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $variant = $cartItem->productVariant;
        $oldQuantity = $cartItem->quantity;
        $newQuantity = $request->quantity;

        // Tính số lượng thay đổi
        $quantityDiff = $newQuantity - $oldQuantity;

        // Kiểm tra tồn kho (cần kiểm tra stock hiện tại + số lượng đã có trong giỏ)
        // Stock hiện tại = stock thực tế + số lượng đã có trong giỏ (vì đã bị trừ khi thêm vào giỏ)
        $availableStock = $variant->stock + $oldQuantity;

        if ($availableStock < $newQuantity) {
            return response()->json([
                'success' => false,
                'message' => 'Số lượng sản phẩm không đủ. Còn lại: ' . $availableStock
            ], 400);
        }

        // Cập nhật số lượng
        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        // Cập nhật stock (trừ đi số lượng thay đổi)
        $variant->stock -= $quantityDiff;
        $variant->save();

        // Refresh variant để lấy stock mới nhất
        $variant->refresh();

        // Tính lại tổng tiền
        $total = $cartItem->productVariant->price * $cartItem->quantity;
        $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');
        $cartTotal = CartItem::with('productVariant')
            ->where('user_id', $user->id)
            ->get()
            ->sum(function($item) {
                return $item->productVariant->price * $item->quantity;
            });

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật số lượng',
            'total' => number_format($total),
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal),
            'updated_stock' => $variant->stock,
            'variant_id' => $variant->id
        ]);
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập'
            ], 401);
        }

        $cartItem = CartItem::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Lưu thông tin variant và quantity trước khi xóa
        $variant = $cartItem->productVariant;
        $quantity = $cartItem->quantity;
        $productName = $variant->product->name ?? 'Unknown Product';

        // Xóa cart item
        $cartItem->delete();

        // Cộng lại stock
        $variant->stock += $quantity;
        $variant->save();

        // Log thông tin xóa sản phẩm
        \Illuminate\Support\Facades\Log::info('Cart Item Removed', [
            'user_id' => $user->id,
            'cart_item_id' => $id,
            'product_name' => $productName,
            'variant_id' => $variant->id,
            'quantity' => $quantity,
            'stock_restored' => $variant->stock,
        ]);

        // Tính lại tổng tiền
        $cartCount = CartItem::where('user_id', $user->id)->sum('quantity');
        $cartTotal = CartItem::with('productVariant')
            ->where('user_id', $user->id)
            ->get()
            ->sum(function($item) {
                return $item->productVariant->price * $item->quantity;
            });

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ hàng',
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal),
            'updated_stock' => $variant->stock,
            'variant_id' => $variant->id
        ]);
    }
}
