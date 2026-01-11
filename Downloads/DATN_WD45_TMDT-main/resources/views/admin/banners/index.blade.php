@extends('admin.layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Banner</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Banner</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        @if ($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                <strong>Thành công!</strong> {{ $message }}
            </div>
        @endif

        <div class="row mb-3">
            <div class="col-md-12">
                <a href="{{ route('admin.banners.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Banner
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách Banner</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Tiêu đề</th>
                            <th>Ảnh</th>
                            <th width="80">Thứ tự</th>
                            <th width="100">Trạng thái</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($banners as $banner)
                            <tr>
                                <td>{{ $banner->id }}</td>
                                <td>{{ $banner->title }}</td>
                                <td>
                                    <a href="{{ $banner->image_url }}" target="_blank">
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" height="40">
                                    </a>
                                </td>
                                <td>{{ $banner->order }}</td>
                                <td>
                                    <button class="btn btn-sm toggle-active" data-id="{{ $banner->id }}" data-active="{{ $banner->is_active ? 1 : 0 }}">
                                        @if ($banner->is_active)
                                            <span class="badge badge-success">Kích hoạt</span>
                                        @else
                                            <span class="badge badge-danger">Vô hiệu</span>
                                        @endif
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('admin.banners.edit', $banner->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('admin.banners.destroy', $banner->id) }}" style="display:inline-block;">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa?')">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center">
                    {{ $banners->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('click', '.toggle-active', function() {
        const button = $(this);
        const bannerId = button.data('id');
        const isActive = button.data('active');

        $.ajax({
            url: `/api/banners/${bannerId}/toggle-active`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const newStatus = response.is_active ? 1 : 0;
                button.data('active', newStatus);

                if (response.is_active) {
                    button.html('<span class="badge badge-success">Kích hoạt</span>');
                } else {
                    button.html('<span class="badge badge-danger">Vô hiệu</span>');
                }
            }
        });
    });
</script>
@endpush
@endsection
