<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    /**
     * Hiển thị danh sách khuyến mãi
     */
    public function index()
    {
        $vouchers = Voucher::where('status', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.vouchers.index', compact('vouchers'));
    }

    /**
     * Validate và áp dụng voucher
     */
    public function validate(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Mã voucher không tồn tại'
            ], 404);
        }

        // Lấy tổng tiền giỏ hàng
        $cartTotal = 0;
        if (Auth::check()) {
            $cartItems = \App\Models\CartItem::with('productVariant')
                ->where('user_id', Auth::id())
                ->get();

            foreach ($cartItems as $item) {
                $cartTotal += $item->productVariant->price * $item->quantity;
            }
        }

        // Kiểm tra voucher có hợp lệ không
        if (!$voucher->isValid($cartTotal)) {
            $message = 'Mã voucher không hợp lệ';

            if (!$voucher->status) {
                $message = 'Mã voucher đã bị vô hiệu hóa';
            } elseif (now()->lt($voucher->start_date)) {
                $message = 'Mã voucher chưa có hiệu lực';
            } elseif (now()->gt($voucher->end_date)) {
                $message = 'Mã voucher đã hết hạn';
            } elseif ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
                $message = 'Mã voucher đã hết lượt sử dụng';
            } elseif ($cartTotal < $voucher->min_order) {
                $message = 'Đơn hàng tối thiểu là ' . number_format((float)$voucher->min_order) . '₫';
            }

            return response()->json([
                'success' => false,
                'message' => $message
            ], 400);
        }

        // Tính số tiền giảm
        $discount = $voucher->calculateDiscount($cartTotal);
        $finalTotal = $cartTotal - $discount;

        return response()->json([
            'success' => true,
            'message' => 'Áp dụng mã voucher thành công',
            'voucher' => [
                'id' => $voucher->id,
                'code' => $voucher->code,
                'name' => $voucher->name,
                'type' => $voucher->type,
                'value' => $voucher->value,
            ],
            'discount' => $discount,
            'discount_formatted' => number_format($discount),
            'cart_total' => $cartTotal,
            'cart_total_formatted' => number_format($cartTotal),
            'final_total' => $finalTotal,
            'final_total_formatted' => number_format($finalTotal),
        ]);
    }

    /**
     * Áp dụng voucher vào session
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $voucher = Voucher::where('code', $request->code)->first();

        if (!$voucher) {
            return back()->with('error', 'Mã voucher không tồn tại');
        }

        // Lấy tổng tiền giỏ hàng
        $cartTotal = 0;
        if (Auth::check()) {
            $cartItems = \App\Models\CartItem::with('productVariant')
                ->where('user_id', Auth::id())
                ->get();

            foreach ($cartItems as $item) {
                $cartTotal += $item->productVariant->price * $item->quantity;
            }
        }

        // Kiểm tra voucher có hợp lệ không
        if (!$voucher->isValid($cartTotal)) {
            $message = 'Mã voucher không hợp lệ';

            if (!$voucher->status) {
                $message = 'Mã voucher đã bị vô hiệu hóa';
            } elseif (now()->lt($voucher->start_date)) {
                $message = 'Mã voucher chưa có hiệu lực';
            } elseif (now()->gt($voucher->end_date)) {
                $message = 'Mã voucher đã hết hạn';
            } elseif ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
                $message = 'Mã voucher đã hết lượt sử dụng';
            } elseif ($cartTotal < $voucher->min_order) {
                $message = 'Đơn hàng tối thiểu là ' . number_format((float)$voucher->min_order) . '₫';
            }

            return back()->with('error', $message);
        }

        // Lưu vào session
        session(['voucher_code' => $voucher->code]);

        return back()->with('success', 'Áp dụng mã voucher thành công!');
    }

    /**
     * Xóa voucher khỏi session
     */
    public function remove()
    {
        session()->forget('voucher_code');
        return back()->with('success', 'Đã xóa mã voucher');
    }
}
