<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Hiển thị danh sách sản phẩm
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants.attributeValues.attribute'])
            ->where('status', true);

        // Lọc theo category
        if ($request->has('category') && $request->category) {
            $query->where('category_id', $request->category);
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(12);
        $categories = Category::where('status', true)->get();

        return view('user.products.index', compact('products', 'categories'));
    }

    /**
     * Hiển thị chi tiết sản phẩm
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'variants.attributeValues.attribute'])
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

        return view('user.products.show', compact('product', 'defaultVariant', 'attributes', 'relatedProducts'));
    }
}
