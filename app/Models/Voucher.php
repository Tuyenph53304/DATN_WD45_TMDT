<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Voucher extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'min_order',
        'max_discount',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'status',
        'description',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'value' => 'decimal:2',
        'min_order' => 'decimal:2',
        'max_discount' => 'decimal:2',
    ];

    /**
     * Kiểm tra voucher có hợp lệ không
     */
    public function isValid($orderTotal = 0): bool
    {
        // Kiểm tra trạng thái
        if (!$this->status) {
            return false;
        }

        // Kiểm tra thời gian
        $now = Carbon::now();
        if ($now->lt($this->start_date) || $now->gt($this->end_date)) {
            return false;
        }

        // Kiểm tra số lần sử dụng
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        // Kiểm tra đơn hàng tối thiểu
        if ($orderTotal < $this->min_order) {
            return false;
        }

        return true;
    }

    /**
     * Tính số tiền giảm
     */
    public function calculateDiscount($orderTotal): float
    {
        if (!$this->isValid($orderTotal)) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = ($orderTotal * $this->value) / 100;
            // Áp dụng giảm tối đa nếu có
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
        } else {
            // Fixed amount
            $discount = $this->value;
            // Không được giảm nhiều hơn tổng đơn hàng
            if ($discount > $orderTotal) {
                $discount = $orderTotal;
            }
        }

        return round($discount, 2);
    }

    /**
     * Tăng số lần sử dụng
     */
    public function incrementUsage(): void
    {
        $this->increment('used_count');
    }
}
