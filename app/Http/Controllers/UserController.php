<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\Review;
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
            }, 'variants.attributeValues.attribute', 'category', 'approvedReviews'])
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
            }, 'variants.attributeValues.attribute', 'category', 'approvedReviews'])
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

        // Lấy sản phẩm Laptop Gaming
        $gamingProducts = Product::with(['variants' => function($query) {
                $query->orderBy('price', 'asc');
            }, 'variants.attributeValues.attribute', 'category', 'approvedReviews'])
            ->where('status', true)
            ->whereHas('category', function($query) {
                $query->where('slug', 'laptop-gaming');
            })
            ->has('variants')
            ->limit(4)
            ->get()
            ->map(function($product) {
                $product->minPrice = $product->variants->min('price');
                $product->maxPrice = $product->variants->max('price');
                $product->defaultVariant = $product->variants->first();
                return $product;
            });

        // Lấy sản phẩm Laptop Văn Phòng
        $officeProducts = Product::with(['variants' => function($query) {
                $query->orderBy('price', 'asc');
            }, 'variants.attributeValues.attribute', 'category', 'approvedReviews'])
            ->where('status', true)
            ->whereHas('category', function($query) {
                $query->where('slug', 'laptop-van-phong');
            })
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

        return view('user.home', compact('flashSaleProducts', 'featuredProducts', 'gamingProducts', 'officeProducts', 'categories', 'cartCount'));
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
            ->where('status', 'pending_confirmation')
            ->count();

        return view('user.profile.index', compact('user', 'totalOrders', 'totalSpent', 'pendingOrders'));
    }

    /**
     * Hiển thị form chỉnh sửa profile
     */
    public function editProfile()
    {
        $user = Auth::user();
        $user->load('shippingAddresses');
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
        $order = Order::with([
            'orderItems.productVariant.product', 
            'orderItems.productVariant.attributeValues', 
            'shippingAddress'
        ])
            ->where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Lấy đánh giá cho các sản phẩm trong đơn hàng
        $reviews = Review::where('order_id', $order->id)
            ->where('user_id', $user->id)
            ->with('product')
            ->get()
            ->keyBy('product_id');

        return view('user.orders.show', compact('order', 'reviews'));
    }

    /**
     * Lưu địa chỉ giao hàng mới
     */
    public function storeShippingAddress(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'default' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->route('user.profile.edit')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin địa chỉ.');
        }

        // Nếu đặt làm mặc định, bỏ mặc định của các địa chỉ khác
        $isDefault = $request->has('default') && $request->default == '1';
        if ($isDefault) {
            ShippingAddress::where('user_id', $user->id)
                ->update(['default' => false]);
        }

        // Nếu đây là địa chỉ đầu tiên, tự động đặt làm mặc định
        if (ShippingAddress::where('user_id', $user->id)->count() == 0) {
            $isDefault = true;
        }

        ShippingAddress::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'default' => $isDefault,
        ]);

        return redirect()->route('user.profile.edit')
            ->with('success', 'Thêm địa chỉ giao hàng thành công!');
    }

    /**
     * Cập nhật địa chỉ giao hàng
     */
    public function updateShippingAddress(Request $request, $id)
    {
        $user = Auth::user();
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:255',
            'default' => 'nullable',
        ]);

        if ($validator->fails()) {
            return redirect()->route('user.profile.edit')
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Vui lòng kiểm tra lại thông tin địa chỉ.');
        }

        // Nếu đặt làm mặc định, bỏ mặc định của các địa chỉ khác
        $isDefault = $request->has('default') && $request->default == '1';
        if ($isDefault) {
            ShippingAddress::where('user_id', $user->id)
                ->where('id', '!=', $id)
                ->update(['default' => false]);
        }

        $address->update([
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'default' => $isDefault ? true : $address->default,
        ]);

        return redirect()->route('user.profile.edit')
            ->with('success', 'Cập nhật địa chỉ giao hàng thành công!');
    }

    /**
     * Xóa địa chỉ giao hàng
     */
    public function deleteShippingAddress($id)
    {
        $user = Auth::user();
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $address->delete();

        return redirect()->route('user.profile.edit')
            ->with('success', 'Xóa địa chỉ giao hàng thành công!');
    }

    /**
     * Đặt địa chỉ làm mặc định
     */
    public function setDefaultShippingAddress($id)
    {
        $user = Auth::user();
        $address = ShippingAddress::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Bỏ mặc định của tất cả địa chỉ
        ShippingAddress::where('user_id', $user->id)
            ->update(['default' => false]);

        // Đặt địa chỉ này làm mặc định
        $address->update(['default' => true]);

        return redirect()->route('user.profile.edit')
            ->with('success', 'Đã đặt địa chỉ làm mặc định!');
    }

    /**
     * Trang giới thiệu
     */
    public function about()
    {
        return view('user.about');
    }

    /**
     * Danh sách tin tức
     */
    public function news()
    {
        $newsList = \App\Models\News::where('status', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('user.news.index', compact('newsList'));
    }

    /**
     * Chi tiết tin tức
     */
    public function newsDetail($slug)
    {
        $news = \App\Models\News::where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Tăng lượt xem
        $news->incrementViews();

        // Lấy tin tức liên quan
        $relatedNews = \App\Models\News::where('status', true)
            ->where('id', '!=', $news->id)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('user.news.show', compact('news', 'relatedNews'));
    }

    /**
     * Khách hàng hủy đơn hàng
     * - Nếu đơn hàng ở trạng thái "chờ xác nhận": hủy trực tiếp
     * - Nếu đơn hàng từ "đã xác nhận" trở đi: yêu cầu hủy, cần admin xác nhận
     */
    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Validate lý do hủy
        $request->validate([
            'cancel_reason' => 'required|string|max:1000',
        ]);

        // Nếu đơn hàng ở trạng thái "chờ xác nhận": hủy trực tiếp
        if ($order->status === 'pending_confirmation' && $order->canCancelByCustomer()) {
            $order->status = 'cancelled';
            $order->cancel_reason = $request->cancel_reason;
            $order->cancelled_request = false;
            $order->save();
            
            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Đơn hàng đã được hủy thành công!');
        }

        // Nếu đơn hàng từ "đã xác nhận" trở đi: yêu cầu hủy
        if ($order->canRequestCancel() && !$order->cancelled_request) {
            $order->cancelled_request = true;
            $order->cancel_reason = $request->cancel_reason;
            $order->save();
            
            return redirect()->route('user.orders.show', $order->id)
                ->with('success', 'Yêu cầu hủy đơn hàng đã được gửi. Vui lòng chờ admin xác nhận!');
        }

        return redirect()->route('user.orders.show', $order->id)
            ->with('error', 'Không thể hủy đơn hàng này!');
    }

    /**
     * Khách hàng xác nhận nhận hàng (khi đơn hàng ở trạng thái "đã giao hàng")
     */
    public function confirmReceived($id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Chỉ cho phép xác nhận khi đơn hàng ở trạng thái "đã giao hàng"
        if (!$order->canConfirmByCustomer()) {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'Bạn chỉ có thể xác nhận nhận hàng khi đơn hàng đã được giao!');
        }

        // Chuyển trạng thái sang "thành công"
        $updateData = ['status' => 'completed'];
        
        // Nếu là COD, cập nhật payment_status sang "paid" khi giao hàng thành công
        if ($order->payment_method === 'cod' && $order->payment_status !== 'paid') {
            $updateData['payment_status'] = 'paid';
        }
        
        $order->update($updateData);

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Cảm ơn bạn đã xác nhận nhận hàng! Bạn có thể đánh giá sản phẩm ngay bây giờ.');
    }

    /**
     * Khách hàng hoàn hàng (khi đơn hàng ở trạng thái "đã giao hàng")
     */
    public function returnOrder($id)
    {
        $user = Auth::user();
        $order = Order::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Chỉ cho phép hoàn hàng khi đơn hàng ở trạng thái "đã giao hàng"
        if (!$order->canReturnByCustomer()) {
            return redirect()->route('user.orders.show', $order->id)
                ->with('error', 'Bạn chỉ có thể hoàn hàng khi đơn hàng đã được giao!');
        }

        // Chuyển trạng thái sang "giao hàng không thành công"
        $order->update(['status' => 'delivery_failed']);

        return redirect()->route('user.orders.show', $order->id)
            ->with('success', 'Yêu cầu hoàn hàng đã được ghi nhận!');
    }
}
