@extends('admin.layout')

@section('title', 'Quản lý Voucher - Admin Panel')
@section('page-title', 'Quản lý Voucher')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-ticket-perforated me-2"></i> Danh sách Voucher</h3>
    <div class="card-tools">
      <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tạo Voucher mới
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Search and Filter -->
    <form method="GET" action="{{ route('admin.vouchers.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-3">
          <input type="text" name="search" value="{{ request('search') }}"
                 class="form-control" placeholder="Tìm kiếm theo mã, tên...">
        </div>
        <div class="col-md-2">
          <select name="status" class="form-control">
            <option value="">Tất cả trạng thái</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hoạt động</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Không hoạt động</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="type" class="form-control">
            <option value="">Tất cả loại</option>
            <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Phần trăm</option>
            <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Số tiền cố định</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Tìm kiếm
          </button>
        </div>
      </div>
    </form>

    <!-- Vouchers Table -->
    @if($vouchers->count() > 0)
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Mã</th>
            <th>Tên</th>
            <th>Loại</th>
            <th>Giá trị</th>
            <th>Đã dùng</th>
            <th>Trạng thái</th>
            <th>Ngày bắt đầu</th>
            <th>Ngày kết thúc</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($vouchers as $voucher)
          <tr>
            <td><code>{{ $voucher->code }}</code></td>
            <td>{{ $voucher->name }}</td>
            <td>
              <span class="badge {{ $voucher->type === 'percentage' ? 'bg-info' : 'bg-primary' }}">
                {{ $voucher->type === 'percentage' ? 'Phần trăm' : 'Số tiền' }}
              </span>
            </td>
            <td>
              @if($voucher->type === 'percentage')
                {{ $voucher->value }}%
              @else
                {{ number_format($voucher->value) }}₫
              @endif
            </td>
            <td>
              {{ $voucher->used_count }} / {{ $voucher->usage_limit ?? '∞' }}
            </td>
            <td>
              <span class="badge {{ $voucher->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $voucher->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
            <td>{{ $voucher->start_date->format('d/m/Y') }}</td>
            <td>{{ $voucher->end_date->format('d/m/Y') }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('admin.vouchers.show', $voucher) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-sm btn-warning" title="Sửa">
                  <i class="bi bi-pencil"></i>
                </a>
                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này?');">
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
      {{ $vouchers->links() }}
    </div>
    @else
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i> Không tìm thấy voucher nào.
    </div>
    @endif
  </div>
</div>
@endsection

