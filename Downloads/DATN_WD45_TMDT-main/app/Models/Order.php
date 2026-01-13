<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'total_price',
        'status',
        'cancelled_request',
        'cancel_reason',
        'payment_method',
        'payment_status',
        'transaction_id',
        'voucher_code',
        'discount_amount',
        'final_amount',
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'cancelled_request' => 'boolean',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    /**
     * Get the order items for the order.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the voucher for the order.
     */
    public function voucher()
    {
        if ($this->voucher_code) {
            return \App\Models\Voucher::where('code', $this->voucher_code)->first();
        }
        return null;
    }

    /**
     * Kiểm tra xem đơn hàng có thể chuyển sang trạng thái khác không
     */
    public function canTransitionTo($newStatus): bool
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return false;
        }

        return in_array($newStatus, $statusConfig['can_transition_to'] ?? []);
    }

    /**
     * Kiểm tra xem đơn hàng có ở trạng thái cuối cùng không (không thể thay đổi)
     */
    public function isFinalStatus(): bool
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            // Nếu không tìm thấy config, coi như không phải trạng thái cuối
            return false;
        }
        return $statusConfig['is_final'] ?? false;
    }

    /**
     * Kiểm tra xem khách hàng có thể hủy đơn hàng không
     */
    public function canCancelByCustomer(): bool
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return false;
        }
        return $statusConfig['can_cancel_by_customer'] ?? false;
    }

    /**
     * Kiểm tra xem khách hàng có thể xác nhận nhận hàng không
     */
    public function canConfirmByCustomer(): bool
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return false;
        }
        return $statusConfig['can_confirm_by_customer'] ?? false;
    }

    /**
     * Kiểm tra xem khách hàng có thể hoàn hàng không
     */
    public function canReturnByCustomer(): bool
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return false;
        }
        return $statusConfig['can_return_by_customer'] ?? false;
    }

    /**
     * Kiểm tra xem khách hàng có thể yêu cầu hủy không (cần admin xác nhận)
     */
    public function canRequestCancel(): bool
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return false;
        }
        return $statusConfig['can_request_cancel'] ?? false;
    }

    /**
     * Lấy label của trạng thái hiện tại
     */
    public function getStatusLabel(): string
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return $this->status;
        }
        return $statusConfig['label'] ?? $this->status;
    }

    /**
     * Lấy màu của trạng thái hiện tại
     */
    public function getStatusColor(): string
    {
        $statusConfig = config('constants.order_status.' . $this->status, null);
        if (!$statusConfig || !is_array($statusConfig)) {
            return '#6B7280';
        }
        return $statusConfig['color'] ?? '#6B7280';
    }
}
