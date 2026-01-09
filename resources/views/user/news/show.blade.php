@extends('user.layout')

@section('title', $news->title . ' - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}" class="text-decoration-none">Tin tức</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 50) }}</li>
                </ol>
            </nav>

            <!-- News Content -->
            <article class="card border-0 shadow-sm mb-4">
                @if($news->image)
                <div class="position-relative overflow-hidden" style="height: 400px;">
                    <img src="{{ $news->image }}" class="card-img-top w-100 h-100" alt="{{ $news->title }}" style="object-fit: cover;">
                </div>
                @endif
                <div class="card-body p-4">
                    <h1 class="card-title fw-bold mb-3">{{ $news->title }}</h1>
                    
                    <div class="d-flex align-items-center mb-4 text-muted small">
                        <i class="bi bi-calendar3 me-2"></i>
                        <span>{{ $news->created_at->format('d/m/Y H:i') }}</span>
                        <span class="mx-2">•</span>
                        <i class="bi bi-eye me-2"></i>
                        <span>{{ $news->views }} lượt xem</span>
                        @if($news->author)
                        <span class="mx-2">•</span>
                        <i class="bi bi-person me-2"></i>
                        <span>{{ $news->author->name }}</span>
                        @endif
                    </div>

                    @if($news->excerpt)
                    <div class="alert alert-light border-start border-primary border-3 mb-4">
                        <p class="mb-0 fw-semibold">{{ $news->excerpt }}</p>
                    </div>
                    @endif

                    <div class="news-content">
                        {!! nl2br(e($news->content)) !!}
                    </div>
                </div>
            </article>

            <!-- Related News -->
            @if($relatedNews->count() > 0)
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-newspaper me-2"></i> Tin tức liên quan</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        @foreach($relatedNews as $related)
                        <div class="col-md-6">
                            <a href="{{ route('news.show', $related->slug) }}" class="text-decoration-none">
                                <div class="d-flex gap-3">
                                    @if($related->image)
                                    <img src="{{ $related->image }}" class="rounded" style="width: 100px; height: 80px; object-fit: cover;" alt="{{ $related->title }}">
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold text-dark mb-1" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                            {{ $related->title }}
                                        </h6>
                                        <small class="text-muted">{{ $related->created_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i> Danh mục</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <a href="{{ route('news.index') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-arrow-right me-2"></i> Tất cả tin tức
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('products.index') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-arrow-right me-2"></i> Sản phẩm
                            </a>
                        </li>
                        <li class="mb-2">
                            <a href="{{ route('vouchers.index') }}" class="text-decoration-none text-dark">
                                <i class="bi bi-arrow-right me-2"></i> Khuyến mãi
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.news-content {
    line-height: 1.8;
    font-size: 1.05rem;
}

.news-content p {
    margin-bottom: 1.5rem;
}
</style>
@endsection

