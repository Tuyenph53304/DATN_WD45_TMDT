@extends('admin.layout')

@section('title', 'Chỉnh sửa Danh mục - Admin Panel')
@section('page-title', 'Chỉnh sửa Danh mục')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-pencil me-2"></i> Chỉnh sửa Danh mục: {{ $category->name }}</h3>
    <div class="card-tools">
      <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
        <input type="text" class="form-control @error('name') is-invalid @enderror"
               id="name" name="name" value="{{ old('name', $category->name) }}" required>
        @error('name')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="image" class="form-label">URL ảnh</label>
        <input type="text" class="form-control @error('image') is-invalid @enderror"
               id="image" name="image" value="{{ old('image', $category->image) }}" placeholder="https://...">
        @error('image')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                 {{ old('status', $category->status) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">
            Kích hoạt danh mục
          </label>
        </div>
      </div>

      <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-1"></i> Cập nhật
        </button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
          <i class="bi bi-x-circle me-1"></i> Hủy
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

