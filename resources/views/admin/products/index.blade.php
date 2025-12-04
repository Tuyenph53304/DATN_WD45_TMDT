@extends('admin.layout')

@section('title', 'Quản lý Sản phẩm - Admin Panel')
@section('page-title', 'Quản lý Sản phẩm')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-box-seam me-2"></i> Danh sách Sản phẩm</h3>
    <div class="card-tools">
      <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tạo Sản phẩm mới
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Search and Filter -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-4">
          <input type="text" name="search" value="{{ request('search') }}"
                 class="form-control" placeholder="Tìm kiếm theo tên, mô tả...">
        </div>
        <div class="col-md-3">
          <select name="category_id" class="form-control">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $category)
              <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <select name="status" class="form-control">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
          </select>
        </div>
        <div class="col-md-2">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Tìm kiếm
          </button>
        </div>
      </div>
    </form>

    <!-- Products Table -->
    @if($products->count() > 0)
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên sản phẩm</th>
            <th>Danh mục</th>
            <th>Số variants</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($products as $product)
          <tr>
            <td>{{ $product->id }}</td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->category->name ?? '-' }}</td>
            <td><span class="badge bg-info">{{ $product->variants->count() }}</span></td>
            <td>
              <span class="badge {{ $product->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $product->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
            <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Sửa">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
      {{ $products->links() }}
    </div>
    @else
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i> Không tìm thấy sản phẩm nào.
    </div>
    @endif
  </div>
</div>
@endsection

