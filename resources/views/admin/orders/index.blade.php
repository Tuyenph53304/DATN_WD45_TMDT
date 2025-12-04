@extends('admin.layout')

@section('title', 'Quản lý Đơn hàng - Admin Panel')
@section('page-title', 'Quản lý Đơn hàng')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-receipt me-2"></i> Danh sách Đơn hàng</h3>
  </div>
  <div class="card-body">
    <!-- Search and Filter -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-3">
          <input type="text" name="search" value="{{ request('search') }}"
                 class="form-control" placeholder="Tìm kiếm theo mã đơn, tên khách hàng...">
        </div>
        <div class="col-md-2">
          <select name="status" class="form-control">
            <option value="">Tất cả trạng thái</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
            <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
            <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>Đang giao</option>
            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="payment_status" class="form-control">
            <option value="">Tất cả thanh toán</option>
            <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
            <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
            <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Thất bại</option>
          </select>
        </div>
        <div class="col-md-2">
          <select name="payment_method" class="form-control">
            <option value="">Tất cả phương thức</option>
            <option value="momo" {{ request('payment_method') === 'momo' ? 'selected' : '' }}>MoMo</option>
            <option value="cod" {{ request('payment_method') === 'cod' ? 'selected' : '' }}>COD</option>
          </select>
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100">
            <i class="bi bi-search me-1"></i> Tìm kiếm
          </button>
        </div>
      </div>
    </form>

    <!-- Orders Table -->
    @if($orders->count() > 0)
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>Mã đơn</th>
            <th>Khách hàng</th>
            <th>Tổng tiền</th>
            <th>Trạng thái</th>
            <th>Thanh toán</th>
            <th>Phương thức</th>
            <th>Ngày đặt</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($orders as $order)
          <tr>
            <td>#{{ $order->id }}</td>
            <td>{{ $order->user->name ?? 'N/A' }}</td>
            <td>{{ number_format($order->final_amount) }}₫</td>
            <td>
              @if($order->status === 'pending')
                <span class="badge bg-warning">Chờ xử lý</span>
              @elseif($order->status === 'processing')
                <span class="badge bg-info">Đang xử lý</span>
              @elseif($order->status === 'shipped')
                <span class="badge bg-primary">Đang giao</span>
              @elseif($order->status === 'completed')
                <span class="badge bg-success">Hoàn thành</span>
              @elseif($order->status === 'cancelled')
                <span class="badge bg-danger">Đã hủy</span>
              @endif
            </td>
            <td>
              @if($order->payment_status === 'paid')
                <span class="badge bg-success">Đã thanh toán</span>
              @elseif($order->payment_status === 'pending')
                <span class="badge bg-warning">Chưa thanh toán</span>
              @else
                <span class="badge bg-danger">Thất bại</span>
              @endif
            </td>
            <td>
              @if($order->payment_method === 'cod')
                <span class="badge bg-secondary">COD</span>
              @else
                <span class="badge bg-primary">MoMo</span>
              @endif
            </td>
            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                <i class="bi bi-eye"></i>
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
      {{ $orders->links() }}
    </div>
    @else
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i> Không tìm thấy đơn hàng nào.
    </div>
    @endif
  </div>
</div>
@endsection

