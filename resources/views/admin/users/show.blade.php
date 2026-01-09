@extends('admin.layout')

@section('title', 'Chi tiết User - Admin Panel')
@section('page-title', 'Chi tiết User')

@section('content')
<div class="row">
  <div class="col-md-8">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-person me-2"></i> Thông tin User</h3>
        <div class="card-tools">
          <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
          </a>
        </div>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <tr>
            <th width="200">ID</th>
            <td>{{ $user->id }}</td>
          </tr>
          <tr>
            <th>Họ và tên</th>
            <td>{{ $user->name }}</td>
          </tr>
          <tr>
            <th>Email</th>
            <td>{{ $user->email }}</td>
          </tr>
          <tr>
            <th>Số điện thoại</th>
            <td>{{ $user->phone_number ?? '-' }}</td>
          </tr>
          <tr>
            <th>Địa chỉ</th>
            <td>{{ $user->address ?? '-' }}</td>
          </tr>
          <tr>
            <th>Role</th>
            <td>
              <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'khách' ? 'bg-warning' : 'bg-primary') }}">
                {{ ucfirst($user->role) }}
              </span>
            </td>
          </tr>
          <tr>
            <th>Trạng thái</th>
            <td>
              <span class="badge {{ $user->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $user->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
          </tr>
          <tr>
            <th>Ngày tạo</th>
            <td>{{ $user->created_at->format('d/m/Y H:i:s') }}</td>
          </tr>
          <tr>
            <th>Cập nhật lần cuối</th>
            <td>{{ $user->updated_at->format('d/m/Y H:i:s') }}</td>
          </tr>
        </table>

        <div class="mt-3">
          <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">
            <i class="bi bi-pencil me-1"></i> Chỉnh sửa
          </a>
          @if($user->id !== auth()->id())
          <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Bạn có chắc chắn muốn xóa user này?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash me-1"></i> Xóa
            </button>
          </form>
          @endif
        </div>
      </div>
    </div>
  </div>

  <div class="col-md-4">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-receipt me-2"></i> Đơn hàng</h3>
      </div>
      <div class="card-body">
        <p class="mb-2"><strong>Tổng đơn hàng:</strong> {{ $user->orders->count() }}</p>
        <p class="mb-2"><strong>Tổng đã chi:</strong> {{ number_format($user->orders->where('payment_status', 'paid')->sum('final_amount')) }}₫</p>
        <a href="{{ route('admin.orders.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-primary">
          Xem tất cả đơn hàng
        </a>
      </div>
    </div>

    <div class="card mt-3">
      <div class="card-header">
        <h3 class="card-title"><i class="bi bi-geo-alt me-2"></i> Địa chỉ giao hàng</h3>
      </div>
      <div class="card-body">
        <p class="mb-2"><strong>Số địa chỉ:</strong> {{ $user->shippingAddresses->count() }}</p>
        @if($user->shippingAddresses->count() > 0)
          @foreach($user->shippingAddresses->take(3) as $address)
            <div class="border rounded p-2 mb-2">
              <strong>{{ $address->full_name }}</strong><br>
              <small>{{ $address->address }}, {{ $address->city }}</small>
            </div>
          @endforeach
        @else
          <p class="text-muted">Chưa có địa chỉ</p>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection

