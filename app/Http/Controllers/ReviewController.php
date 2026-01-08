<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Hiển thị form tạo đánh giá từ đơn hàng (tất cả sản phẩm)
     */
    public function create($orderId)
    {
        $user = Auth::user();
        $order = Order::with(['orderItems.productVariant.product'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'completed') // Chỉ đơn hàng đã hoàn thành mới được đánh giá
            ->firstOrFail();

        // Lấy danh sách sản phẩm trong đơn hàng chưa được đánh giá
        $productsToReview = [];
        foreach ($order->orderItems as $item) {
            $product = $item->productVariant->product;
            $hasReviewed = Review::where('user_id', $user->id)
                ->where('product_id', $product->id)
                ->where('order_id', $order->id)
                ->exists();
            
            if (!$hasReviewed) {
                $productsToReview[] = [
                    'product' => $product,
                    'order_item' => $item,
                ];
            }
        }

        return view('user.reviews.create', compact('order', 'productsToReview'));
    }

    /**
     * Hiển thị form đánh giá cho từng sản phẩm
     */
    public function createSingle($orderId, $productId)
    {
        $user = Auth::user();
        $order = Order::with(['orderItems.productVariant.product'])
            ->where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'completed') // Chỉ đơn hàng đã hoàn thành mới được đánh giá
            ->firstOrFail();

        // Kiểm tra sản phẩm có trong đơn hàng không
        $orderItem = $order->orderItems()
            ->whereHas('productVariant', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->first();

        if (!$orderItem) {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'Sản phẩm không có trong đơn hàng này!');
        }

        $product = $orderItem->productVariant->product;

        // Kiểm tra đã đánh giá chưa
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->where('order_id', $order->id)
            ->first();

        if ($existingReview) {
            return redirect()->route('user.reviews.edit', $existingReview->id)
                ->with('info', 'Bạn đã đánh giá sản phẩm này. Bạn có thể chỉnh sửa đánh giá.');
        }

        return view('user.reviews.create-single', compact('order', 'orderItem', 'product'));
    }

    /**
     * Lưu đánh giá mới từ đơn hàng
     */
    public function store(Request $request, $orderId)
    {
        $user = Auth::user();
        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_positive' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $productId = $request->product_id;
        
        // Kiểm tra sản phẩm có trong đơn hàng không
        $hasProductInOrder = $order->orderItems()
            ->whereHas('productVariant', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })
            ->exists();

        if (!$hasProductInOrder) {
            return redirect()->back()
                ->with('error', 'Sản phẩm không có trong đơn hàng này!');
        }

        // Kiểm tra xem user đã đánh giá sản phẩm này chưa (unique constraint trên user_id + product_id)
        $existingReview = Review::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        // Xử lý is_positive
        $isPositive = true;
        if ($request->has('is_positive')) {
            $isPositive = $request->is_positive == '1' || $request->is_positive === true || $request->is_positive === 'true';
        } else {
            // Mặc định: rating >= 4 là tích cực
            $isPositive = $request->rating >= 4;
        }

        // Nếu đã có review, cập nhật (vì unique constraint chỉ cho phép 1 review/user/product)
        if ($existingReview) {
            $existingReview->update([
                'order_id' => $order->id, // Cập nhật order_id nếu chưa có
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_positive' => $isPositive,
                'status' => true,
            ]);

            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Đã cập nhật đánh giá sản phẩm!');
        }

        // Nếu chưa có, tạo mới
        Review::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_positive' => $isPositive,
            'status' => true,
        ]);

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }

    /**
     * Hiển thị form chỉnh sửa đánh giá
     */
    public function edit($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->with(['product', 'order'])
            ->firstOrFail();

        return view('user.reviews.edit', compact('review'));
    }

    /**
     * Cập nhật đánh giá
     */
    public function update(Request $request, $id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'is_positive' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Xử lý is_positive
        $isPositive = true;
        if ($request->has('is_positive')) {
            $isPositive = $request->is_positive == '1' || $request->is_positive === true || $request->is_positive === 'true';
        } else {
            // Mặc định: rating >= 4 là tích cực
            $isPositive = $request->rating >= 4;
        }

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'is_positive' => $isPositive,
        ]);

        return redirect()->route('user.orders.show', $review->order_id)
            ->with('success', 'Đã cập nhật đánh giá!');
    }

    /**
     * Xóa đánh giá
     */
    public function destroy($id)
    {
        $review = Review::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $orderId = $review->order_id;
        $review->delete();

        return redirect()->route('user.orders.show', $orderId)
            ->with('success', 'Đã xóa đánh giá!');
    }
}
