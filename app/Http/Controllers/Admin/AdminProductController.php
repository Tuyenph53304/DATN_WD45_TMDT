<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    /**
     * Hiển thị danh sách products
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'variants', 'images']);

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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'variants' => 'required|array|min:1',
            'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.old_price' => 'nullable|numeric|min:0',
            'variants.*.attribute_values' => 'required|array|min:1',
            'variants.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product = Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->has('status') ? $request->status : true,
        ]);

        // Upload và lưu ảnh sản phẩm từ các slot (0-4)
        for ($slotIndex = 0; $slotIndex < 5; $slotIndex++) {
            $fileKey = "images.$slotIndex";
            if ($request->hasFile($fileKey)) {
                $image = $request->file($fileKey);
                if ($image && $image->isValid() && $image->getError() === UPLOAD_ERR_OK) {
                    try {
                        $imagePath = $image->store('products', 'public');
                        if ($imagePath) {
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $imagePath,
                                'sort_order' => (int)$slotIndex,
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error uploading product image at slot ' . $slotIndex . ': ' . $e->getMessage());
                    }
                }
            }
        }

        // Tạo variants
        foreach ($request->variants as $variantIndex => $variantData) {
            $variantImagePath = null;
            if ($request->hasFile("variants.$variantIndex.image")) {
                $variantImagePath = $request->file("variants.$variantIndex.image")->store('product-variants', 'public');
            } elseif (isset($variantData['image_url']) && !empty($variantData['image_url'])) {
                $variantImagePath = $variantData['image_url'];
            }

            $variant = ProductVariant::create([
                'product_id' => $product->id,
                'sku' => $variantData['sku'],
                'price' => $variantData['price'],
                'old_price' => $variantData['old_price'] ?? null,
                'stock' => $variantData['stock'],
                'image' => $variantImagePath,
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
        $product->load(['variants.attributeValues.attribute', 'category', 'images']);
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
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'existing_images' => 'nullable|array',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.sku' => 'required|string|max:255',
            'variants.*.price' => 'required|numeric|min:0',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.old_price' => 'nullable|numeric|min:0',
            'variants.*.attribute_values' => 'required|array|min:1',
        ]);

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'status' => $request->has('status') ? $request->status : $product->status,
        ]);

        // Xử lý ảnh sản phẩm
        $existingImageIds = [];
        
        // Cập nhật ảnh hiện có và sort_order
        if ($request->has('existing_images') && is_array($request->existing_images)) {
            foreach ($request->existing_images as $index => $imageData) {
                if (isset($imageData['id']) && $imageData['id']) {
                    $image = ProductImage::find($imageData['id']);
                    if ($image && $image->product_id == $product->id) {
                        $image->update([
                            'sort_order' => $imageData['sort_order'] ?? $index,
                        ]);
                        $existingImageIds[] = $image->id;
                    }
                }
            }
        }

        // Xóa ảnh được đánh dấu xóa hoặc không còn trong danh sách
        $deletedImageIds = $request->input('deleted_images', []);
        $allDeletedIds = array_unique(array_merge($deletedImageIds, array_diff($product->images->pluck('id')->toArray(), $existingImageIds)));
        
        if (!empty($allDeletedIds)) {
            $product->images()->whereIn('id', $allDeletedIds)->each(function($image) {
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $image->delete();
            });
        }

        // Thêm ảnh mới từ các slot
        // Kiểm tra tất cả các slot có thể có file (0-4)
        for ($slotIndex = 0; $slotIndex < 5; $slotIndex++) {
            $fileKey = "images.$slotIndex";
            if ($request->hasFile($fileKey)) {
                $image = $request->file($fileKey);
                if ($image && $image->isValid() && $image->getError() === UPLOAD_ERR_OK) {
                    try {
                        $imagePath = $image->store('products', 'public');
                        if ($imagePath) {
                            // Xác định sort_order dựa trên slot index
                            $sortOrder = (int)$slotIndex;
                            
                            ProductImage::create([
                                'product_id' => $product->id,
                                'image_path' => $imagePath,
                                'sort_order' => $sortOrder,
                            ]);
                        }
                    } catch (\Exception $e) {
                        \Log::error('Error uploading product image at slot ' . $slotIndex . ': ' . $e->getMessage());
                    }
                }
            }
        }

        // Cập nhật variants
        if ($request->has('variants')) {
            $existingVariantIds = [];
            foreach ($request->variants as $variantIndex => $variantData) {
                if (isset($variantData['id']) && $variantData['id']) {
                    // Cập nhật variant hiện có
                    $variant = ProductVariant::find($variantData['id']);
                    if ($variant && $variant->product_id == $product->id) {
                        $variantImagePath = $variant->image;
                        if ($request->hasFile("variants.$variantIndex.image")) {
                            // Xóa ảnh cũ nếu có
                            if ($variant->image && !str_starts_with($variant->image, 'http') && Storage::disk('public')->exists($variant->image)) {
                                Storage::disk('public')->delete($variant->image);
                            }
                            $variantImagePath = $request->file("variants.$variantIndex.image")->store('product-variants', 'public');
                        } elseif (isset($variantData['image_url']) && !empty($variantData['image_url'])) {
                            // Xóa ảnh cũ nếu có (chỉ khi thay đổi URL)
                            if ($variant->image && !str_starts_with($variant->image, 'http') && $variant->image != $variantData['image_url'] && Storage::disk('public')->exists($variant->image)) {
                                Storage::disk('public')->delete($variant->image);
                            }
                            $variantImagePath = $variantData['image_url'];
                        }

                        $variant->update([
                            'sku' => $variantData['sku'],
                            'price' => $variantData['price'],
                            'old_price' => $variantData['old_price'] ?? null,
                            'stock' => $variantData['stock'],
                            'image' => $variantImagePath,
                        ]);

                        // Cập nhật attribute values
                        if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                            $variant->attributeValues()->sync(array_filter($variantData['attribute_values']));
                        }

                        $existingVariantIds[] = $variant->id;
                    }
                } else {
                    // Tạo variant mới
                    $variantImagePath = null;
                    if ($request->hasFile("variants.$variantIndex.image")) {
                        $variantImagePath = $request->file("variants.$variantIndex.image")->store('product-variants', 'public');
                    } elseif (isset($variantData['image_url']) && !empty($variantData['image_url'])) {
                        $variantImagePath = $variantData['image_url'];
                    }

                    $variant = ProductVariant::create([
                        'product_id' => $product->id,
                        'sku' => $variantData['sku'],
                        'price' => $variantData['price'],
                        'old_price' => $variantData['old_price'] ?? null,
                        'stock' => $variantData['stock'],
                        'image' => $variantImagePath,
                    ]);

                    if (isset($variantData['attribute_values']) && is_array($variantData['attribute_values'])) {
                        $variant->attributeValues()->attach(array_filter($variantData['attribute_values']));
                    }

                    $existingVariantIds[] = $variant->id;
                }
            }

            // Xóa variants không còn trong danh sách
            $product->variants()->whereNotIn('id', $existingVariantIds)->delete();
        }

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
        $product->load(['category', 'variants.attributeValues.attribute', 'images']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Xóa ảnh sản phẩm
     */
    public function deleteImage(Product $product, ProductImage $image)
    {
        if ($image->product_id != $product->id) {
            return redirect()->back()->with('error', 'Ảnh không thuộc về sản phẩm này!');
        }

        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Xóa ảnh thành công!');
    }
}
