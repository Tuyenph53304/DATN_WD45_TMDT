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
                                <td>
                                    <strong>{{ Str::limit($banner->title, 50) }}</strong>
                                    @if($banner->description)
                                        <br><small class="text-muted">{{ Str::limit($banner->description, 100) }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($banner->image_url)
                                        <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}" style="max-width: 100px; max-height: 50px;">
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ $banner->order }}</td>
                                <td>
                                    @if($banner->is_active)
                                        <span class="badge badge-success">Hoạt động</span>
                                    @else
                                        <span class="badge badge-warning">Tạm dừng</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.banners.show', $banner) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa banner này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Xóa
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Chưa có banner nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($banners->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $banners->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
