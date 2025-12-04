@extends('admin.layout')

@section('title', 'Tạo User mới - Admin Panel')
@section('page-title', 'Tạo User mới')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-person-plus me-2"></i> Tạo User mới</h3>
    <div class="card-tools">
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.users.store') }}" method="POST">
      @csrf

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email') }}" required>
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
            <input type="password" class="form-control @error('password') is-invalid @enderror"
                   id="password" name="password" required>
            @error('password')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="password_confirmation" class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="phone_number" class="form-label">Số điện thoại</label>
            <input type="text" class="form-control @error('phone_number') is-invalid @enderror"
                   id="phone_number" name="phone_number" value="{{ old('phone_number') }}">
            @error('phone_number')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
            <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
              <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
              <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
              <option value="khách" {{ old('role') === 'khách' ? 'selected' : '' }}>Khách</option>
            </select>
            @error('role')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="address" class="form-label">Địa chỉ</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address" name="address" rows="2">{{ old('address') }}</textarea>
        @error('address')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                 {{ old('status', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">
            Kích hoạt tài khoản
          </label>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-1"></i> Tạo User
        </button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
          <i class="bi bi-x-circle me-1"></i> Hủy
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
