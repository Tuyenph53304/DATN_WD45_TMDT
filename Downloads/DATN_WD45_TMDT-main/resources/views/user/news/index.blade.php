@extends('user.layout')

@section('title', 'Tin tức - ' . config('constants.site.name'))

@section('content')
<div class="container py-5">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold"><i class="bi bi-newspaper text-primary me-2"></i> Tin tức</h2>
            <p class="text-muted">Cập nhật những tin tức mới nhất về công nghệ và sản phẩm</p>
        </div>
    </div>

    @if($newsList->count() > 0)
    <div class="row g-4">
        @foreach($newsList as $news)
        <div class="col-lg-4 col-md-6">
            <a href="{{ route('news.show', $news->slug) }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100" style="transition: all 0.3s; cursor: pointer; border-radius: var(--radius-xl); overflow: hidden;">
                    @if($news->image)
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <img src="{{ $news->image }}" class="card-img-top w-100 h-100" alt="{{ $news->title }}" style="object-fit: cover; transition: transform 0.3s;">
                    </div>
                    @endif
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-2 text-muted small">
                            <i class="bi bi-calendar3 me-2"></i>
                            <span>{{ $news->created_at->format('d/m/Y') }}</span>
                            <span class="mx-2">•</span>
                            <i class="bi bi-eye me-2"></i>
                            <span>{{ $news->views }} lượt xem</span>
                        </div>
                        <h5 class="card-title fw-bold text-dark mb-3" style="min-height: 60px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $news->title }}
                        </h5>
                        @if($news->excerpt)
                        <p class="card-text text-muted small" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $news->excerpt }}
                        </p>
                        @endif
                        <div class="mt-3">
                            <span class="text-primary small fw-semibold">
                                Đọc thêm <i class="bi bi-arrow-right ms-1"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($newsList instanceof \Illuminate\Pagination\LengthAwarePaginator && $newsList->hasPages())
    <div class="row mt-5">
        <div class="col-12">
            {{ $newsList->links() }}
        </div>
    </div>
    @endif
    @else
    <div class="text-center py-5">
        <i class="bi bi-newspaper" style="font-size: 4rem; color: #ddd;"></i>
        <p class="text-muted mt-3">Chưa có tin tức nào</p>
    </div>
    @endif
</div>

<style>
a:hover .card {
    transform: translateY(-5px);
    box-shadow: var(--shadow-xl) !important;
}

a:hover .card-img-top img {
    transform: scale(1.1);
}
</style>
@endsection

