<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Hiển thị danh sách products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants']);

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $products = $query->latest()->paginate(15);
        $categories = Category::where('status', true)->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Hiển thị form tạo product mới
     */
    public function create()
    {
        $categories = Category::where('status', true)->get();
        $attributes = Attribute::with('attributeValues')->get();
        return view('admin.products.create', compact('categories', 'attributes'));
    }

    /**
     * Lưu product mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.attribute_values' => 'required|array|min:1',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->has('status') ? $request->status : true,
        ]);

        // Tạo variants
        foreach ($request->variants as $variantData) {
            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'stock' => $variantData['stock'],
                'image' => $variantData['image'] ?? null,
            ]);

            // Gắn attribute values
            if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                $variant->attributeValues()->attach(array_filter($variantData['attribute_values']));
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Tạo sản phẩm thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa product
     */
    public function edit(Product $product)
    {
        $product->load(['variants.attributeValues', 'category']);
        $categories = Category::where('status', true)->get();
        $attributes = Attribute::with('attributeValues')->get();
        return view('admin.products.edit', compact('product', 'categories', 'attributes'));
    }

    /**
     * Cập nhật product
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'boolean',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->has('status') ? $request->status : $product->status,
        ]);

        return redirect()->route('admin.products.index')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    /**
     * Xóa product
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Xóa sản phẩm thành công!');
    }

    /**
     * Xem chi tiết product
     */
    public function show(Product $product)
    {
        $product->load(['category', 'variants.attributeValues.attribute']);
        return view('admin.products.show', compact('product'));
    }
}
