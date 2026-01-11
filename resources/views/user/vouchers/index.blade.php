@extends('user.layout')

@section('title', 'Khuyến mãi - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-tag-fill text-danger me-2"></i> Khuyến mãi đang diễn ra</h2>
            <p class="text-muted">Các mã giảm giá và khuyến mãi hấp dẫn dành cho bạn</p>
        </div>
    </div>

    @if($vouchers->count() > 0)
    <div class="row g-4">
        @foreach($vouchers as $voucher)
        <div class="col-lg-4 col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: var(--radius-xl); overflow: hidden;">
                <div class="card-header bg-danger text-white p-4" style="background: linear-gradient(135deg, #F43F5E 0%, #EC4899 100%);">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1 fw-bold">{{ $voucher->name }}</h5>
                            <div class="badge bg-white text-danger px-3 py-2">
                                <strong>{{ $voucher->code }}</strong>
                            </div>
                        </div>
                        <i class="bi bi-tag-fill" style="font-size: 2.5rem; opacity: 0.3;"></i>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($voucher->description)
                    <p class="text-muted mb-3">{{ $voucher->description }}</p>
                    @endif

                    <div class="mb-3">
                        @if($voucher->type === 'percentage')
                            <div class="display-6 fw-bold text-danger">
                                Giảm {{ number_format($voucher->value) }}%
                            </div>
                            @if($voucher->max_discount)
                                <small class="text-muted">Tối đa {{ number_format($voucher->max_discount) }}₫</small>
                            @endif
                        @else
                            <div class="display-6 fw-bold text-danger">
                                Giảm {{ number_format($voucher->value) }}₫
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <div class="small text-muted mb-1">
                            <i class="bi bi-cart-check me-1"></i> Đơn hàng tối thiểu:
                        </div>
                        <strong>{{ number_format($voucher->min_order) }}₫</strong>
                    </div>

                    @if($voucher->usage_limit)
                    <div class="mb-3">
                        <div class="small text-muted mb-1">
                            <i class="bi bi-people me-1"></i> Số lượt sử dụng:
                        </div>
                        <strong>{{ $voucher->used_count }}/{{ $voucher->usage_limit }}</strong>
                        <div class="progress mt-2" style="height: 8px;">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                 style="width: {{ ($voucher->used_count / $voucher->usage_limit) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif

                    <div class="mb-3">
                        <div class="small text-muted mb-1">
                            <i class="bi bi-calendar me-1"></i> Thời gian:
                        </div>
                        <div>
                            <small>{{ $voucher->start_date->format('d/m/Y') }} - {{ $voucher->end_date->format('d/m/Y') }}</small>
                        </div>
                    </div>

                    <a href="{{ route('products.index') }}" class="btn btn-danger w-100">
                        <i class="bi bi-bag-check me-2"></i> Áp dụng ngay
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        @if($vouchers instanceof \Illuminate\Pagination\LengthAwarePaginator && $vouchers->hasPages())
            {{ $vouchers->links() }}
        @endif
    </div>
    @else
    <div class="text-center py-5">
        <i class="bi bi-tag" style="font-size: 4rem; color: #ddd;"></i>
        <h5 class="mt-3">Hiện tại không có khuyến mãi nào</h5>
        <p class="text-muted">Vui lòng quay lại sau!</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-left me-2"></i> Tiếp tục mua sắm
        </a>
    </div>
    @endif
</div>
@endsection

