<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
     public function store(Request $request, $product_id)
    {
        // Validate dữ liệu
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để đánh giá sản phẩm');
        }

        // Kiểm tra sản phẩm tồn tại
        $product = Product::findOrFail($product_id);

        // Lưu review
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $product_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }


    /**
     * Xóa đánh giá (Admin hoặc chính chủ)
     */
    public function destroy($id)
    {
        $review = Review::findOrFail($id);

        // Admin hoặc chủ review mới được xóa
        if (Auth::user()->role !== 'admin' && $review->user_id != Auth::id()) {
            return redirect()->back()->with('error', 'Bạn không có quyền xóa đánh giá này');
        }

        $review->delete();

        return redirect()->back()->with('success', 'Đã xóa đánh giá');
    }


    /**
     * Trang admin quản lý tất cả review
     */
    public function adminIndex()
    {
        $reviews = Review::with(['user', 'product'])
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }
}
