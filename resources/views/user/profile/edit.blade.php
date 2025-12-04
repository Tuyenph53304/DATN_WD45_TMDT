@extends('user.layout')

@section('title', 'Chỉnh sửa thông tin - ' . config('constants.site.name'))

@push('styles')
<style>
  .address-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 15px;
    transition: all 0.3s ease;
  }

  .address-card:hover {
    border-color: #667eea;
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.1);
  }

  .address-card.default {
    border-color: #667eea;
    background: linear-gradient(135deg, rgba(102, 126, 234, 0.05) 0%, rgba(118, 75, 162, 0.05) 100%);
  }
</style>
@endpush

@section('content')
<div class="container py-5">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-lg-3 mb-4">
      <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
          <div class="list-group list-group-flush">
            <a href="{{ route('user.profile') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-person me-2"></i> Thông tin tài khoản
            </a>
            <a href="{{ route('user.orders') }}" class="list-group-item list-group-item-action">
              <i class="bi bi-receipt me-2"></i> Đơn hàng của tôi
            </a>
            <a href="{{ route('user.profile.edit') }}" class="list-group-item list-group-item-action active">
              <i class="bi bi-pencil me-2"></i> Chỉnh sửa thông tin
            </a>
            <form action="{{ route('logout') }}" method="POST" class="list-group-item p-0">
              @csrf
              <button type="submit" class="list-group-item list-group-item-action border-0 w-100 text-start">
                <i class="bi bi-box-arrow-right me-2"></i> Đăng xuất
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="col-lg-9">
      <!-- User Info Form -->
      <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-pencil me-2"></i> Chỉnh sửa thông tin tài khoản</h5>
        </div>
        <div class="card-body">
          <form action="{{ route('user.profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
              <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
              <i class="bi bi-exclamation-triangle me-2"></i>
              <strong>Lỗi:</strong>
              <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
              <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Basic Information -->
            <h6 class="fw-bold mb-3">Thông tin cơ bản</h6>
            <div class="row mb-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" value="{{ $user->email }}" disabled>
                <small class="text-muted">Email không thể thay đổi</small>
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="phone_number" class="form-label">Số điện thoại</label>
                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}">
                @error('phone_number')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="address" class="form-label">Địa chỉ</label>
                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $user->address) }}">
                @error('address')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <hr class="my-4">

            <!-- Change Password -->
            <h6 class="fw-bold mb-3">Đổi mật khẩu</h6>
            <p class="text-muted small mb-3">Để trống nếu không muốn thay đổi mật khẩu</p>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                @error('current_password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="row mb-3">
              <div class="col-md-6">
                <label for="password" class="form-label">Mật khẩu mới</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
              </div>
            </div>

            <div class="d-flex gap-2 mt-4">
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i> Cập nhật
              </button>
              <a href="{{ route('user.profile') }}" class="btn btn-outline-secondary">
                <i class="bi bi-x-circle me-2"></i> Hủy
              </a>
            </div>
          </form>
        </div>
      </div>

      <!-- Shipping Addresses -->
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h5 class="mb-0"><i class="bi bi-geo-alt me-2"></i> Địa chỉ giao hàng</h5>
        </div>
        <div class="card-body">
          <!-- Form thêm địa chỉ mới -->
          <div class="card bg-light mb-4">
            <div class="card-header bg-primary text-white">
              <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i> Thêm địa chỉ mới</h6>
            </div>
            <div class="card-body">
              <form action="{{ route('user.shipping-address.store') }}" method="POST" id="addAddressForm">
                @csrf
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="add_full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror"
                           id="add_full_name" name="full_name" value="{{ old('full_name') }}" required>
                    @error('full_name')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="add_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                           id="add_phone" name="phone" value="{{ old('phone') }}" required>
                    @error('phone')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8 mb-3">
                    <label for="add_address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('address') is-invalid @enderror"
                           id="add_address" name="address" value="{{ old('address') }}" required>
                    @error('address')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="add_city" class="form-label">Thành phố/Tỉnh <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                           id="add_city" name="city" value="{{ old('city') }}" required>
                    @error('city')
                      <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="add_default" name="default" value="1" {{ old('default') ? 'checked' : '' }}>
                    <label class="form-check-label" for="add_default">
                      Đặt làm địa chỉ mặc định
                    </label>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i> Thêm địa chỉ
                  </button>
                  <button type="reset" class="btn btn-outline-secondary" onclick="resetAddForm()">
                    <i class="bi bi-arrow-clockwise me-2"></i> Làm mới
                  </button>
                </div>
              </form>
            </div>
          </div>

          <!-- Danh sách địa chỉ -->
          <h6 class="mb-3"><i class="bi bi-list-ul me-2"></i> Danh sách địa chỉ</h6>
          @if($user->shippingAddresses->count() > 0)
            @foreach($user->shippingAddresses as $address)
            <div class="address-card {{ $address->default ? 'default' : '' }} mb-3">
              <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                  <div class="d-flex align-items-center mb-2">
                    <h6 class="mb-0 me-2">{{ $address->full_name }}</h6>
                    @if($address->default)
                      <span class="badge bg-primary">Mặc định</span>
                    @endif
                  </div>
                  <div class="text-muted small mb-1">
                    <i class="bi bi-telephone me-1"></i> {{ $address->phone }}
                  </div>
                  <div class="text-muted small">
                    <i class="bi bi-geo-alt me-1"></i> {{ $address->address }}, {{ $address->city }}
                  </div>
                </div>
                <div class="d-flex gap-2">
                  @if(!$address->default)
                  <form action="{{ route('user.shipping-address.set-default', $address->id) }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-primary" title="Đặt làm mặc định">
                      <i class="bi bi-star"></i>
                    </button>
                  </form>
                  @endif
                  <button type="button" class="btn btn-sm btn-outline-primary"
                          onclick="showEditForm({{ $address->id }}, '{{ addslashes($address->full_name) }}', '{{ addslashes($address->phone) }}', '{{ addslashes($address->address) }}', '{{ addslashes($address->city) }}', {{ $address->default ? 'true' : 'false' }})">
                    <i class="bi bi-pencil"></i>
                  </button>
                  <form action="{{ route('user.shipping-address.delete', $address->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa địa chỉ này?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Xóa">
                      <i class="bi bi-trash"></i>
                    </button>
                  </form>
                </div>
              </div>
            </div>
            @endforeach
          @else
            <p class="text-muted text-center py-4">
              <i class="bi bi-inbox" style="font-size: 3rem; opacity: 0.3;"></i>
              <br>Bạn chưa có địa chỉ giao hàng nào.
            </p>
          @endif

          <!-- Form chỉnh sửa địa chỉ (ẩn mặc định) -->
          <div id="editAddressSection" class="card bg-light mt-4" style="display: none;">
            <div class="card-header bg-warning text-dark">
              <h6 class="mb-0"><i class="bi bi-pencil me-2"></i> Chỉnh sửa địa chỉ</h6>
            </div>
            <div class="card-body">
              <form id="editAddressForm" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="edit_full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_full_name" name="full_name" required>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="edit_phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_phone" name="phone" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-8 mb-3">
                    <label for="edit_address" class="form-label">Địa chỉ <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_address" name="address" required>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="edit_city" class="form-label">Thành phố/Tỉnh <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="edit_city" name="city" required>
                  </div>
                </div>
                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="edit_default" name="default" value="1">
                    <label class="form-check-label" for="edit_default">
                      Đặt làm địa chỉ mặc định
                    </label>
                  </div>
                </div>
                <div class="d-flex gap-2">
                  <button type="submit" class="btn btn-warning">
                    <i class="bi bi-check-circle me-2"></i> Cập nhật
                  </button>
                  <button type="button" class="btn btn-outline-secondary" onclick="hideEditForm()">
                    <i class="bi bi-x-circle me-2"></i> Hủy
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
function showEditForm(id, fullName, phone, address, city, isDefault) {
  document.getElementById('editAddressForm').action = '{{ route("user.shipping-address.update", ":id") }}'.replace(':id', id);
  document.getElementById('edit_full_name').value = fullName;
  document.getElementById('edit_phone').value = phone;
  document.getElementById('edit_address').value = address;
  document.getElementById('edit_city').value = city;
  document.getElementById('edit_default').checked = isDefault;

  // Hiển thị form edit và scroll đến đó
  document.getElementById('editAddressSection').style.display = 'block';
  document.getElementById('editAddressSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function hideEditForm() {
  document.getElementById('editAddressSection').style.display = 'none';
  document.getElementById('editAddressForm').reset();
}

function resetAddForm() {
  document.getElementById('addAddressForm').reset();
}
</script>
@endpush
@endsection
