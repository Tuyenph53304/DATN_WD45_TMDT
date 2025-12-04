<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Hiển thị trang profile của người dùng
     */
    public function profile()
    {
        $user = Auth::user();
        $user = $user->load('shippingAddresses');

        // Thống kê
        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('final_amount');
        $pendingOrders = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();

        return view('user.profile.index', compact('user', 'totalOrders', 'totalSpent', 'pendingOrders'));
    }

    /**
     * Hiển thị form chỉnh sửa profile
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('user.profile.edit', compact('user'));
    }

    /**
     * Cập nhật thông tin profile
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'current_password' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Cập nhật thông tin cơ bản
        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->address = $request->address;

        // Cập nhật mật khẩu nếu có
        if ($request->filled('current_password') && $request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Mật khẩu hiện tại không đúng')
                    ->withInput();
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.profile')
            ->with('success', 'Cập nhật thông tin thành công!');
    }

    /**
     * Hiển thị danh sách đơn hàng
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::with(['orderItems.productVariant.product', 'shippingAddress'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user.orders.index', compact('orders'));
    }

    /**
     * Hiển thị chi tiết đơn hàng
     */
    public function orderDetail($id)
    {
        $user = Auth::user();
        $order = Order::with(['orderItems.productVariant.product', 'orderItems.productVariant.attributeValues', 'shippingAddress'])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return view('user.orders.show', compact('order'));
    }
}
