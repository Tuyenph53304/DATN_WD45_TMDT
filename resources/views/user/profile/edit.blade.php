@extends('user.layout')

@section('title', 'Chỉnh sửa thông tin - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-person me-2"></i> Thông tin tài khoản
            </a>
            <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-receipt me-2"></i> Đơn hàng của tôi
            </a>
            <a href="{{ route('user.profile.edit') }}" class="list-group-item list-group-item-action active">
              <i class="bi bi-pencil me-2"></i> Chỉnh sửa thông tin
            </a>
            <form action="{{ route('logout') }}" method="POST" class="list-group-item p-0">
              @csrf
              <button type="submit" class="list-group-item list-group-item-action border-0 w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-pencil me-2"></i> Chỉnh sửa thông tin tài khoản</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            @if(session('error'))
            <div class="alert alert-danger">
              {{ session('error') }}
            </div>
            @endif

            <!-- Basic Information -->
            <h6 class="fw-bold mb-3">Thông tin cơ bản</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                <small class="text-muted">Email không thể thay đổi</small>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="phone_number" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $user->address) }}">
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <hr class="my-4">

            <!-- Change Password -->
            <h6 class="fw-bold mb-3">Đổi mật khẩu</h6>
            <p class="text-muted small mb-3">Để trống nếu không muốn thay đổi mật khẩu</p>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
              </div>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i> Cập nhật
              </button>
              <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i> Hủy
              </a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

