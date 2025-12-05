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
                            <th>Tác giả</th>
                            <th width="100">Lượt xem</th>
                            <th width="100">Trạng thái</th>
                            <th width="200">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($news as $item)
                            <tr>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->author?->name ?? 'N/A' }}</td>
                                <td><span class="badge badge-info">{{ $item->views_count }}</span></td>
                                <td>
                                    <button class="btn btn-sm toggle-published" data-id="{{ $item->id }}" data-published="{{ $item->is_published ? 1 : 0 }}">
                                        @if ($item->is_published)
                                            <span class="badge badge-success">Xuất bản</span>
                                        @else
                                            <span class="badge badge-warning">Nháp</span>
                                        @endif
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <form method="POST" action="{{ route('admin.news.destroy', $item->id) }}" style="display:inline-block;">
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
                    {{ $news->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('click', '.toggle-published', function() {
        const button = $(this);
        const newsId = button.data('id');
        const isPublished = button.data('published');

        $.ajax({
            url: `/api/news/${newsId}/toggle-published`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                const newStatus = response.is_published ? 1 : 0;
                button.data('published', newStatus);

                if (response.is_published) {
                    button.html('<span class="badge badge-success">Xuất bản</span>');
                } else {
                    button.html('<span class="badge badge-warning">Nháp</span>');
                }
            }
        });
    });
</script>
@endpush
@endsection
