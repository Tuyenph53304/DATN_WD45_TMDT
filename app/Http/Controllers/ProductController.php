<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm và xử lý lọc/tìm kiếm
     */
    public function index(Request $request)
    {
        // Khởi tạo query và lọc các sản phẩm đang hoạt động
        $query = Product::with(['category', 'approvedReviews'])
        ->where('status', true);

    // Lọc theo danh mục (Nếu có category trên URL, nó sẽ áp dụng tại đây)
    if ($request->filled('category')) {
        $query->where('category_id', $request->category);
    }

    // Tìm kiếm theo tên hoặc mô tả
    if ($request->filled('search')) {
        $searchTerm = '%' . $request->search . '%';
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', $searchTerm)
              ->orWhere('description', 'like', $searchTerm);
        });
    }

    // Xử lý lọc biến thể (RAM, CPU,...)
    // Loại bỏ các tham số không phải biến thể để xử lý mảng
    $filterParams = $request->except(['category', 'search', 'page']);

    if (!empty($filterParams)) {
        // Tìm sản phẩm có BIẾN THỂ thỏa mãn ĐỒNG THỜI các tiêu chí
        $query->whereHas('variants', function ($qVariant) use ($filterParams) {
            foreach ($filterParams as $attrName => $values) {
                if (is_array($values) && !empty($values)) {
                    // Ép các điều kiện vào cùng 1 variant
                    $qVariant->whereHas('attributeValues', function ($qAV) use ($attrName, $values) {
                        $qAV->whereHas('attribute', function ($qA) use ($attrName) {
                            $qA->where('name', $attrName);
                        })->whereIn('value', $values);
                    });
                }
            }
        });
    }

    // Load thêm dữ liệu biến thể để hiển thị ảnh/giá đúng cấu hình đã lọc
    $query->with(['variants' => function ($q) use ($filterParams) {
        if (!empty($filterParams)) {
            foreach ($filterParams as $attrName => $values) {
                $q->whereHas('attributeValues', function ($qAV) use ($attrName, $values) {
                    $qAV->whereHas('attribute', function ($qA) use ($attrName) {
                        $qA->where('name', $attrName);
                    })->whereIn('value', $values);
                });
            }
        }
    }, 'variants.attributeValues.attribute']);

    // Dữ liệu cho Filter Panel (Giữ nguyên logic của bạn)
    $availableFilters = DB::table('attribute_values')
        ->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
        ->join('variant_attribute_values', 'attribute_values.id', '=', 'variant_attribute_values.attribute_value_id')
        ->join('product_variants', 'variant_attribute_values.product_variant_id', '=', 'product_variants.id')
        ->join('products', 'product_variants.product_id', '=', 'products.id')
        ->where('products.status', true)
        ->select('attributes.name as attribute_name', 'attribute_values.value')
        ->distinct()
        ->get()
        ->groupBy('attribute_name');

    $products = $query->paginate(12)->withQueryString();
    $categories = Category::where('status', true)->get();

    return view('user.products.index', compact('products', 'categories', 'availableFilters'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'variants.attributeValues.attribute', 'approvedReviews.user'])
            ->where('slug', $slug)
            ->where('status', true)
            ->firstOrFail();

        // Lấy variant đầu tiên làm mặc định
        $defaultVariant = $product->variants->first();

        // Nhóm attribute values theo attribute
        $attributes = [];
        foreach ($product->variants as $variant) {
            foreach ($variant->attributeValues as $attrValue) {
                $attrName = $attrValue->attribute->name;
                if (!isset($attributes[$attrName])) {
                    $attributes[$attrName] = [];
                }
                if (!in_array($attrValue->value, $attributes[$attrName])) {
                    $attributes[$attrName][] = $attrValue->value;
                }
            }
        }

        // Sản phẩm liên quan
        $relatedProducts = Product::with(['variants'])
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', true)
            ->limit(4)
            ->get();

        // Lấy đánh giá (tất cả, không giới hạn)
        $reviews = $product->approvedReviews()->with('user')->orderBy('created_at', 'desc')->get();
        $averageRating = $product->average_rating;
        $totalReviews = $product->total_reviews;

        return view('user.products.show', compact('product', 'defaultVariant', 'attributes', 'relatedProducts', 'reviews', 'averageRating', 'totalReviews'));
    }
}
