@extends('admin.layout')

@section('title', 'Chỉnh sửa Voucher - Admin Panel')
@section('page-title', 'Chỉnh sửa Voucher')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-pencil me-2"></i> Chỉnh sửa Voucher: {{ $voucher->code }}</h3>
    <div class="card-tools">
      <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="code" class="form-label">Mã voucher <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('code') is-invalid @enderror"
                   id="code" name="code" value="{{ old('code', $voucher->code) }}" required style="text-transform: uppercase;">
            @error('code')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="name" class="form-label">Tên voucher <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $voucher->name) }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="mb-3">
            <label for="type" class="form-label">Loại <span class="text-danger">*</span></label>
            <select class="form-control @error('type') is-invalid @enderror" id="type" name="type" required>
              <option value="percentage" {{ old('type', $voucher->type) === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
              <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Số tiền cố định (₫)</option>
            </select>
            @error('type')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-4">
          <div class="mb-3">
            <label for="value" class="form-label">Giá trị <span class="text-danger">*</span></label>
            <input type="number" step="0.01" class="form-control @error('value') is-invalid @enderror"
                   id="value" name="value" value="{{ old('value', $voucher->value) }}" required min="0">
            @error('value')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-4">
          <div class="mb-3">
            <label for="min_order" class="form-label">Đơn hàng tối thiểu (₫)</label>
            <input type="number" step="0.01" class="form-control @error('min_order') is-invalid @enderror"
                   id="min_order" name="min_order" value="{{ old('min_order', $voucher->min_order) }}" min="0">
            @error('min_order')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4">
          <div class="mb-3">
            <label for="max_discount" class="form-label">Giảm tối đa (₫)</label>
            <input type="number" step="0.01" class="form-control @error('max_discount') is-invalid @enderror"
                   id="max_discount" name="max_discount" value="{{ old('max_discount', $voucher->max_discount) }}" min="0">
            @error('max_discount')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-4">
          <div class="mb-3">
            <label for="usage_limit" class="form-label">Giới hạn sử dụng</label>
            <input type="number" class="form-control @error('usage_limit') is-invalid @enderror"
                   id="usage_limit" name="usage_limit" value="{{ old('usage_limit', $voucher->usage_limit) }}" min="1">
            @error('usage_limit')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="start_date" class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror"
                   id="start_date" name="start_date"
                   value="{{ old('start_date', $voucher->start_date->format('Y-m-d\TH:i')) }}" required>
            @error('start_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="end_date" class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
            <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror"
                   id="end_date" name="end_date"
                   value="{{ old('end_date', $voucher->end_date->format('Y-m-d\TH:i')) }}" required>
            @error('end_date')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="3">{{ old('description', $voucher->description) }}</textarea>
        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                 {{ old('status', $voucher->status) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">
            Kích hoạt voucher
          </label>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-1"></i> Cập nhật
        </button>
        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary">
          <i class="bi bi-x-circle me-1"></i> Hủy
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

