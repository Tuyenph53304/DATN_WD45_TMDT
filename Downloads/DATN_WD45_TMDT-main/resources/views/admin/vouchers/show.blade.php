@extends('admin.layout')

@section('title', 'Chi tiết Voucher - Admin Panel')
@section('page-title', 'Chi tiết Voucher')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-ticket-perforated me-2"></i> Thông tin Voucher</h3>
        <div class="card-tools">
          <a href="{{ route('admin.vouchers.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th width="200">Mã voucher</th>
            <td><code>{{ $voucher->code }}</code></td>
          </tr>
          <tr>
            <th>Tên voucher</th>
            <td>{{ $voucher->name }}</td>
          </tr>
          <tr>
            <th>Loại</th>
            <td>
              <span class="badge {{ $voucher->type === 'percentage' ? 'bg-info' : 'bg-primary' }}">
                {{ $voucher->type === 'percentage' ? 'Phần trăm' : 'Số tiền cố định' }}
              </span>
            </td>
          </tr>
          <tr>
            <th>Giá trị</th>
            <td>
              @if($voucher->type === 'percentage')
                {{ $voucher->value }}%
              @else
                {{ number_format($voucher->value) }}₫
              @endif
            </td>
          </tr>
          <tr>
            <th>Đơn hàng tối thiểu</th>
            <td>{{ number_format($voucher->min_order) }}₫</td>
          </tr>
          @if($voucher->max_discount)
          <tr>
            <th>Giảm tối đa</th>
            <td>{{ number_format($voucher->max_discount) }}₫</td>
          </tr>
          @endif
          <tr>
            <th>Giới hạn sử dụng</th>
            <td>{{ $voucher->usage_limit ? $voucher->usage_limit . ' lần' : 'Không giới hạn' }}</td>
          </tr>
          <tr>
            <th>Đã sử dụng</th>
            <td><span class="badge bg-info">{{ $voucher->used_count }} lần</span></td>
          </tr>
          <tr>
            <th>Ngày bắt đầu</th>
            <td>{{ $voucher->start_date->format('d/m/Y H:i') }}</td>
          </tr>
          <tr>
            <th>Ngày kết thúc</th>
            <td>{{ $voucher->end_date->format('d/m/Y H:i') }}</td>
          </tr>
          <tr>
            <th>Trạng thái</th>
            <td>
              <span class="badge {{ $voucher->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $voucher->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
          </tr>
          @if($voucher->description)
          <tr>
            <th>Mô tả</th>
            <td>{{ $voucher->description }}</td>
          </tr>
          @endif
        </table>

        <div class="mt-3">
          <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Chỉnh sửa
          </a>
          <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa voucher này?');">
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

