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
        $query = Product::with(['category', 'variants.attributeValues.attribute', 'approvedReviews', 'images'])
            ->where('status', true);

        // Lọc theo category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Tìm kiếm theo tên & biến thể
        if ($request->has('search') && $request->search) {
            $searchTerm = '%' . $request->search . '%';

            $query->where(function ($q) use ($searchTerm) {
                // 1. Tìm kiếm theo tên sản phẩm (Sử dụng 'where' cho điều kiện đầu tiên)
                $q->where('name', 'like', $searchTerm);
                // 2. Tìm kiếm theo mô tả sản phẩm
                $q->orWhereHas('variants.attributeValues', function ($qVariantAttr) use ($searchTerm) {
                    $qVariantAttr->where('value', 'like', $searchTerm);
                });
            });
        }

        $filterAttributes = $request->except(['category', 'search', 'page']);

        if (!empty($filterAttributes)) {
            // Lọc theo từng thuộc tính (Color, RAM, Storage, ...)
            foreach ($filterAttributes as $attributeName => $values) {
                if (is_array($values) && !empty($values)) {

                    $attribute = Attribute::where('name', $attributeName)->first();

                    if ($attribute) {
                        // Áp dụng bộ lọc CONJUNCTIVE (AND) - sản phẩm phải thỏa mãn
                        // tất cả các loại thuộc tính được chọn 
                        $query->whereHas('variants', function ($qVariant) use ($attribute, $values) {
                            $qVariant->whereHas('attributeValues', function ($qAttrValue) use ($attribute, $values) {
                                $qAttrValue->where('attribute_id', $attribute->id)
                                           ->whereIn('value', $values);
                            });
                        });
                    }
                }
            }
        }
        
        // LẤY DỮ LIỆU CẦN THIẾT CHO FILTER PANEL
        $availableFilters = DB::table('attribute_values')
            ->select('attributes.name as attribute_name', 'attribute_values.value')
            ->join('variant_attribute_values', 'attribute_values.id', '=', 'variant_attribute_values.attribute_value_id')
            ->join('product_variants', 'variant_attribute_values.product_variant_id', '=', 'product_variants.id')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->join('attributes', 'attribute_values.attribute_id', '=', 'attributes.id')
            ->where('products.status', true)
            ->distinct()
            ->orderBy('attribute_name')
            ->get()
            ->groupBy('attribute_name');

        $products = $query->paginate(12);
        $categories = Category::where('status', true)->get();

        return view('user.products.index', compact('products', 'categories', 'availableFilters'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'variants.attributeValues.attribute', 'approvedReviews.user', 'images'])
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
