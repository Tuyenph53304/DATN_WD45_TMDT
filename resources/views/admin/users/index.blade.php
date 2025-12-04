@extends('admin.layout')

@section('title', 'Quản lý Users - Admin Panel')
@section('page-title', 'Quản lý Users')

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-people-fill me-2"></i> Danh sách Users</h3>
    <div class="card-tools">
      <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tạo User mới
      </a>
    </div>
  </div>
  <div class="card-body">
    <!-- Search and Filter -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="mb-3">
      <div class="row">
        <div class="col-md-4">
          <input type="text" name="search" value="{{ request('search') }}"
                 class="form-control" placeholder="Tìm kiếm theo tên, email, số điện thoại...">
        </div>
        <div class="col-md-3">
          <select name="role" class="form-control">
            <option value="">Tất cả roles</option>
            <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
            <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="khách" {{ request('role') === 'khách' ? 'selected' : '' }}>Khách</option>
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

    <!-- Users Table -->
    @if($users->count() > 0)
    <div class="table-responsive">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Số điện thoại</th>
            <th>Role</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th>Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone_number ?? '-' }}</td>
            <td>
              <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : ($user->role === 'khách' ? 'bg-warning' : 'bg-primary') }}">
                {{ ucfirst($user->role) }}
              </span>
            </td>
            <td>
              <span class="badge {{ $user->status ? 'bg-success' : 'bg-secondary' }}">
                {{ $user->status ? 'Hoạt động' : 'Không hoạt động' }}
              </span>
            </td>
            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <div class="btn-group">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="Xem chi tiết">
                  <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning" title="Sửa">
                  <i class="bi bi-pencil"></i>
                </a>
                @if($user->id !== auth()->id())
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa user này?');">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                    <i class="bi bi-trash"></i>
                  </button>
                </form>
                @endif
              </div>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="mt-3">
      {{ $users->links() }}
    </div>
    @else
    <div class="alert alert-info text-center">
      <i class="bi bi-info-circle me-2"></i> Không tìm thấy user nào.
    </div>
    @endif
  </div>
</div>
@endsection
