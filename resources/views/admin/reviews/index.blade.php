@extends('admin.layout')
@section('title', 'Quản lý đánh giá - Admin Panel')
@section('page-title', 'Quản lý đánh giá')



@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Danh sách đánh giá</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Người đánh giá</th>
                <th>Số sao</th>
                <th>Bình luận</th>
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reviews as $review)
            <tr>
                <td>{{ $review->id }}</td>
                <td>{{ $review->product->name }}</td>
                <td>{{ $review->user->name }}</td>
                <td>{{ $review->rating }} ★</td>
                <td>{{ $review->comment }}</td>
                <td>{{ $review->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $reviews->links() }}
</div>
@endsection
