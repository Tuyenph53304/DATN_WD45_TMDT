@extends('admin.layout')

@section('title', 'Quản lý Danh mục - Admin Panel')
@section('page-title', 'Quản lý Danh mục')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-folder-fill me-2"></i> Danh sách Danh mục</h3>
    <div class="card-tools">
      <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tạo Danh mục mới
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Search and Filter -->
    <form method="GET" action="{{ route('admin.categories.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-4">
          <input type="text" name="search" value="{{ request('search') }}"
                 class="form-control" placeholder="Tìm kiếm theo tên, mô tả...">
        </div>
        <div class="col-md-3">
          <select name="status" class="form-control">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Tìm kiếm
          </button>
        </div>
      </div>
    </form>

    <!-- Categories Table -->
    @if($categories->count() > 0)
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên danh mục</th>
            <th>Số sản phẩm</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td><span class="badge bg-info">{{ $category->products_count }}</span></td>
            <td>
              <span class="badge {{ $category->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $category->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
            <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning" title="Sửa">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
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
    @if($categories instanceof \Illuminate\Pagination\LengthAwarePaginator && $categories->hasPages())
    <div class="mt-3">
      {{ $categories->links() }}
    </div>
    @endif
    @else
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i> Không tìm thấy danh mục nào.
    </div>
    @endif
  </div>
</div>
@endsection

