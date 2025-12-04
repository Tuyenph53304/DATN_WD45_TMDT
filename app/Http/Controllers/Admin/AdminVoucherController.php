<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;

class AdminVoucherController extends Controller
{
    /**
     * Hiển thị danh sách vouchers
     */
    public function index(Request $request)
    {
        $query = Voucher::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
            });
        }

        // Lọc theo status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Lọc theo type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        $vouchers = $query->latest()->paginate(15);

        return view('admin.vouchers.index', compact('vouchers'));
    }

    /**
     * Hiển thị form tạo voucher mới
     */
    public function create()
    {
        return view('admin.vouchers.create');
    }

    /**
     * Lưu voucher mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code',
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'boolean',
            'description' => 'nullable|string',
        ]);

        Voucher::create([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'min_order' => $request->min_order ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->has('status') ? $request->status : true,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Tạo voucher thành công!');
    }

    /**
     * Hiển thị form chỉnh sửa voucher
     */
    public function edit(Voucher $voucher)
    {
        return view('admin.vouchers.edit', compact('voucher'));
    }

    /**
     * Cập nhật voucher
     */
    public function update(Request $request, Voucher $voucher)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:vouchers,code,' . $voucher->id,
            'name' => 'required|string|max:255',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'boolean',
            'description' => 'nullable|string',
        ]);

        $voucher->update([
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'value' => $request->value,
            'min_order' => $request->min_order ?? 0,
            'max_discount' => $request->max_discount,
            'usage_limit' => $request->usage_limit,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->has('status') ? $request->status : $voucher->status,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.vouchers.index')->with('success', 'Cập nhật voucher thành công!');
    }

    /**
     * Xóa voucher
     */
    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Xóa voucher thành công!');
    }

    /**
     * Xem chi tiết voucher
     */
    public function show(Voucher $voucher)
    {
        return view('admin.vouchers.show', compact('voucher'));
    }
}
