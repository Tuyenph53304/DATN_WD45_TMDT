<?php

namespace App\Http\Controllers;

use App\Models\WishlistItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm yêu thích của người dùng.
     */
    public function index()
    {
        $wishlistItems = Auth::user()->wishlistItems()->with('product.variants')->latest()->get();

        return view('user.wishlist.index', compact('wishlistItems'));
    }

    /**
     * Thêm hoặc xóa sản phẩm khỏi Wishlist (Toggle action).
     */
    public function toggle(Request $request)
    {
        // Yêu cầu product_id
        $request->validate(['product_id' => 'required|exists:products,id']);

        $productId = $request->product_id;
        $userId = Auth::id();

        // Kiểm tra xem sản phẩm đã có trong wishlist chưa
        $item = WishlistItem::where('user_id', $userId)
                             ->where('product_id', $productId)
                             ->first();

        if ($item) {
            // Đã có, tiến hành xóa (Un-wishlist)
            $item->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'Đã xóa sản phẩm khỏi danh sách yêu thích.',
                'count' => Auth::user()->wishlistItems()->count()
            ]);
        } else {
            // Chưa có, tiến hành thêm (Wishlist)
            WishlistItem::create([
                'user_id' => $userId,
                'product_id' => $productId,
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'Đã thêm sản phẩm vào danh sách yêu thích.',
                'count' => Auth::user()->wishlistItems()->count()
            ]);
        }
    }
}