@extends('admin.layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chi tiết Banner</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.banners.index') }}">Banner</a></li>
                    <li class="breadcrumb-item active">Chi tiết</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin Banner</h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.banners.edit', $banner) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Chỉnh sửa
                            </a>
                            <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa banner này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <dl class="row">
                            <dt class="col-sm-3">ID:</dt>
                            <dd class="col-sm-9">{{ $banner->id }}</dd>

                            <dt class="col-sm-3">Tiêu đề:</dt>
                            <dd class="col-sm-9">{{ $banner->title }}</dd>

                            <dt class="col-sm-3">Mô tả:</dt>
                            <dd class="col-sm-9">{{ $banner->description ?: 'Không có mô tả' }}</dd>

                            <dt class="col-sm-3">Link đích:</dt>
                            <dd class="col-sm-9">
                                @if($banner->link)
                                    <a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a>
                                @else
                                    Không có link
                                @endif
                            </dd>

                            <dt class="col-sm-3">Thứ tự:</dt>
                            <dd class="col-sm-9">{{ $banner->order }}</dd>

                            <dt class="col-sm-3">Trạng thái:</dt>
                            <dd class="col-sm-9">
                                @if($banner->is_active)
                                    <span class="badge badge-success">Kích hoạt</span>
                                @else
                                    <span class="badge badge-danger">Không kích hoạt</span>
                                @endif
                            </dd>

                            <dt class="col-sm-3">Ngày tạo:</dt>
                            <dd class="col-sm-9">{{ $banner->created_at->format('d/m/Y H:i') }}</dd>

                            <dt class="col-sm-3">Cập nhật cuối:</dt>
                            <dd class="col-sm-9">{{ $banner->updated_at->format('d/m/Y H:i') }}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Ảnh Banner</h3>
                    </div>
                    <div class="card-body">
                        @if($banner->image_url)
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->title }}"
                                 class="img-fluid rounded" style="max-width: 100%; height: auto;">
                        @else
                            <div class="text-center text-muted">
                                <i class="fas fa-image fa-3x"></i>
                                <p>Không có ảnh</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
