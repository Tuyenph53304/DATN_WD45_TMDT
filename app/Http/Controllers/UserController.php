<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function home()
    {
        // Lấy sản phẩm flash sale (có thể lấy sản phẩm có giá tốt nhất)
        $flashSaleProducts = Product::with(['variants' => function($query) {
                $query->orderBy('price', 'asc');
            }, 'variants.attributeValues.attribute', 'category'])
            ->where('status', true)
            ->has('variants')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $product->minPrice = $product->variants->min('price');
                $product->maxPrice = $product->variants->max('price');
                $product->defaultVariant = $product->variants->first();
                return $product;
            });

        // Lấy sản phẩm nổi bật
        $featuredProducts = Product::with(['variants' => function($query) {
                $query->orderBy('price', 'asc');
            }, 'variants.attributeValues.attribute', 'category'])
            ->where('status', true)
            ->has('variants')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $product->minPrice = $product->variants->min('price');
                $product->maxPrice = $product->variants->max('price');
                $product->defaultVariant = $product->variants->first();
                return $product;
            });

        // Lấy danh mục
        $categories = Category::where('status', true)->get();

        // Đếm số lượng sản phẩm trong giỏ hàng nếu đã đăng nhập
        $cartCount = 0;
        if (Auth::check()) {
            $cartCount = \App\Models\CartItem::where('user_id', Auth::id())->sum('quantity');
        }

        return view('user.home', compact('flashSaleProducts', 'featuredProducts', 'categories', 'cartCount'));
    }
}
