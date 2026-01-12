@extends('admin.layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý Tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Tin tức</li>
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
                <a href="{{ route('admin.news.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Thêm Tin tức
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách Tin tức</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th width="60">#</th>
                            <th>Tiêu đề</th>
                            <th>Trích đoạn</th>
                            <th width="100">Trạng thái</th>
                            <th width="150">Ngày đăng</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>
                                    <strong>{{ Str::limit($item->title, 50) }}</strong>
                                    @if($item->image_url)
                                        <br><small><i class="fas fa-image text-muted"></i> Có ảnh</small>
                                    @endif
                                </td>
                                <td>{{ Str::limit($item->excerpt ?? strip_tags($item->content), 100) }}</td>
                                <td>
                                    @if($item->status)
                                        <span class="badge badge-success">Đã xuất bản</span>
                                    @else
                                        <span class="badge badge-warning">Nháp</span>
                                    @endif
                                </td>
                                <td>{{ $item->published_at ? $item->published_at->format('d/m/Y H:i') : $item->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.news.show', $item) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Xem
                                    </a>
                                    <a href="{{ route('admin.news.edit', $item) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form action="{{ route('admin.news.destroy', $item) }}" method="POST" class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xóa tin tức này?')">
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
                                <td colspan="6" class="text-center">Chưa có tin tức nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                @if($news->hasPages())
                    <div class="d-flex justify-content-center">
                        {{ $news->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
