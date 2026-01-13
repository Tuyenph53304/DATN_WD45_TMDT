@extends('admin.layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết Tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $news->title }}</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.news.edit', $news) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> Chỉnh sửa
                    </a>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-list"></i> Danh sách
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        @if($news->image_url)
                            <div class="mb-3">
                                <img src="{{ $news->image_url }}" alt="{{ $news->title }}" class="img-fluid rounded">
                            </div>
                        @endif

                        @if($news->excerpt)
                            <div class="mb-3">
                                <h5>Trích đoạn:</h5>
                                <p class="text-muted">{{ $news->excerpt }}</p>
                            </div>
                        @endif

                        <div class="mb-3">
                            <h5>Nội dung:</h5>
                            <div class="border p-3 rounded">
                                {!! nl2br(e($news->content)) !!}
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border-info">
                            <div class="card-header bg-info">
                                <h5 class="card-title mb-0">Thông tin</h5>
                            </div>
                            <div class="card-body">
                                <dl class="row">
                                    <dt class="col-sm-5">ID:</dt>
                                    <dd class="col-sm-7">{{ $news->id }}</dd>

                                    <dt class="col-sm-5">Slug:</dt>
                                    <dd class="col-sm-7">{{ $news->slug }}</dd>

                                    <dt class="col-sm-5">Trạng thái:</dt>
                                    <dd class="col-sm-7">
                                        @if($news->is_published)
                                            <span class="badge badge-success">Đã xuất bản</span>
                                        @else
                                            <span class="badge badge-warning">Nháp</span>
                                        @endif
                                    </dd>

                                    <dt class="col-sm-5">Ngày tạo:</dt>
                                    <dd class="col-sm-7">{{ $news->created_at->format('d/m/Y H:i') }}</dd>

                                    <dt class="col-sm-5">Ngày xuất bản:</dt>
                                    <dd class="col-sm-7">{{ $news->published_at->format('d/m/Y H:i') }}</dd>

                                    <dt class="col-sm-5">Cập nhật:</dt>
                                    <dd class="col-sm-7">{{ $news->updated_at->format('d/m/Y H:i') }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
