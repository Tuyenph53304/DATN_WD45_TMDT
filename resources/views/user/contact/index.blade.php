@extends('user.layout')

@section('title', 'Liên hệ - ' . config('constants.site.name'))

@section('content')
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('contact.store') }}">
    @csrf

    <div class="mb-3">
        <label>Họ tên</label>
        <input type="text" name="name" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Số điện thoại</label>
        <input type="number" name="phone" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nội dung</label>
        <textarea name="message" rows="4" class="form-control" required></textarea>
    </div>

    <button class="btn btn-primary">Gửi liên hệ</button>
</form>
@endsection

