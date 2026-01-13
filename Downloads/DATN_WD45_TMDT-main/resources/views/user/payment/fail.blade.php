@extends('user.layout')

@section('title', 'Thanh toán thất bại - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-lg-6">
      <div class="card border-0 shadow-lg">
        <div class="card-body text-center p-5">
          <!-- Fail Icon -->
          <div class="mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-danger" style="width: 100px; height: 100px;">
              <i class="bi bi-x-circle-fill text-white" style="font-size: 4rem;"></i>
            </div>
          </div>

          <h2 class="fw-bold text-danger mb-3">Thanh toán thất bại!</h2>

          @if(session('error'))
          <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
          </div>
          @else
          <p class="text-muted mb-4">Giao dịch của bạn không thể hoàn tất. Vui lòng thử lại.</p>
          @endif

          <!-- Actions -->
          <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
            <a href="{{ route('cart.index') }}" class="btn btn-danger btn-lg">
              <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
            </a>
            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
              <i class="bi bi-house"></i> Về trang chủ
            </a>
          </div>

          <!-- Help -->
          <div class="mt-4 pt-4 border-top">
            <p class="small text-muted mb-0">
              <i class="bi bi-info-circle"></i> Nếu bạn gặp vấn đề, vui lòng liên hệ
              <a href="mailto:{{ config('constants.contact.email') }}">{{ config('constants.contact.email') }}</a>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

