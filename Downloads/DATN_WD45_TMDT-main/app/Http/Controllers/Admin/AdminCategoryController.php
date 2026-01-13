<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    /**
     * Hiển thị danh sách categories
     */
    public function index(Request $request)
    {
        $query = Category::withCount('products');

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $categories = $query->latest()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Hiển thị form tạo category mới
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Lưu category mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'status' => 'boolean',
        ]);

        Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'status' => $request->has('status') ? $request->status : true,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Tạo danh mục thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa category
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Cập nhật category
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:500',
            'status' => 'boolean',
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'image' => $request->image,
            'status' => $request->has('status') ? $request->status : $category->status,
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Cập nhật danh mục thành công!');
    }

    /**
     * Xóa category
     */
    public function destroy(Category $category)
    {
        // Kiểm tra xem category có sản phẩm không
        if ($category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')->with('error', 'Không thể xóa danh mục đang có sản phẩm!');
        }

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Xóa danh mục thành công!');
    }

    /**
     * Xem chi tiết category
     */
    public function show(Category $category)
    {
        $category->load(['products.variants']);
        return view('admin.categories.show', compact('category'));
    }
}
