@extends('admin.layout')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Thêm Tin tức</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.news.index') }}">Tin tức</a></li>
                    <li class="breadcrumb-item active">Thêm</li>
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
                        <h3 class="card-title">Thông tin Tin tức</h3>
                    </div>
                    <form action="{{ route('admin.news.store') }}" method="POST">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="title">Tiêu đề <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required value="{{ old('title') }}">
                                @error('title')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Mô tả ngắn <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="2" required placeholder="Mô tả ngắn gọn (tối đa 500 ký tự)">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="content">Nội dung <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="8" required>{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="image_url">Ảnh đại diện</label>
                                <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}" placeholder="https://example.com/image.jpg">
                                @error('image_url')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="author_id">Tác giả</label>
                                <select class="form-control @error('author_id') is-invalid @enderror" id="author_id" name="author_id">
                                    <option value="">Chọn tác giả (mặc định: tài khoản hiện tại)</option>
                                    @foreach ($authors as $author)
                                        <option value="{{ $author->id }}" {{ old('author_id') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                                    @endforeach
                                </select>
                                @error('author_id')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="published_at">Thời gian xuất bản</label>
                                <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at') }}">
                                @error('published_at')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_published">Xuất bản ngay</label>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Tạo Tin tức</button>
                            <a href="{{ route('admin.news.index') }}" class="btn btn-secondary">Hủy</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.js"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.css" rel="stylesheet">
<script>
    $(document).ready(function() {
        $('#content').summernote({
            height: 300,
            lang: 'vi-VN'
        });
    });
</script>
@endpush
@endsection
