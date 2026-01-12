@extends('admin.layout')

@section('title', 'Chi tiết Danh mục - Admin Panel')
@section('page-title', 'Chi tiết Danh mục')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-folder-fill me-2"></i> Thông tin Danh mục</h3>
        <div class="card-tools">
          <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th width="200">ID</th>
            <td>{{ $category->id }}</td>
          </tr>
          <tr>
            <th>Tên danh mục</th>
            <td>{{ $category->name }}</td>
          </tr>
          <tr>
            <th>Mô tả</th>
            <td>{{ $category->description ?? '-' }}</td>
          </tr>
          <tr>
            <th>Ảnh</th>
            <td>
              @if($category->image)
                <img src="{{ $category->image }}" alt="{{ $category->name }}" style="max-width: 200px;">
              @else
                -
              @endif
            </td>
          </tr>
          <tr>
            <th>Trạng thái</th>
            <td>
              <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $category->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
          </tr>
          <tr>
            <th>Số sản phẩm</th>
            <td><span class="badge bg-info">{{ $category->products->count() }}</span></td>
          </tr>
        </table>

        <div class="mt-3">
          <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Chỉnh sửa
          </a>
          <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash me-1"></i> Xóa
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

