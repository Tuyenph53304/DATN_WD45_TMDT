@extends('admin.layout')

@section('title', 'Chi tiết Sản phẩm - Admin Panel')
@section('page-title', 'Chi tiết Sản phẩm')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-box-seam me-2"></i> Thông tin Sản phẩm</h3>
        <div class="card-tools">
          <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th width="200">ID</th>
            <td>{{ $product->id }}</td>
          </tr>
          <tr>
            <th>Tên sản phẩm</th>
            <td>{{ $product->name }}</td>
          </tr>
          <tr>
            <th>Danh mục</th>
            <td>{{ $product->category->name ?? '-' }}</td>
          </tr>
          <tr>
            <th>Mô tả</th>
            <td>{{ $product->description ?? '-' }}</td>
          </tr>
          <tr>
            <th>Trạng thái</th>
            <td>
              <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $product->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
          </tr>
          <tr>
            <th>Số variants</th>
            <td><span class="badge bg-info">{{ $product->variants->count() }}</span></td>
          </tr>
        </table>

        <div class="mt-3">
          <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Chỉnh sửa
          </a>
          <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
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

