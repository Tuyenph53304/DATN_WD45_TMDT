@extends('admin.layout')

@section('title', 'Quản lý Liên hệ - Admin Panel')
@section('page-title', 'Quản lý Liên hệ')

@section('content')
<div class="container-fluid">

    <div class="card">
        <div class="card-body">

            <p><b>Tên:</b> {{ $contact->name }}</p>
            <p><b>Email:</b> {{ $contact->email }}</p>
            <p><b>Số điện thoại:</b> {{ $contact->phone }}</p>
            <p><b>Nội dung:</b></p>

            <div class="border rounded p-3 bg-light">
                {{ $contact->message }}
            </div>

            <p class="mt-3 text-muted">
                Gửi lúc: {{ $contact->created_at->format('d/m/Y H:i') }}
            </p>

            <a href="{{ route('admin.contacts.index') }}"
               class="btn btn-secondary mt-3">
                Quay lại
            </a>
        </div>
    </div>

</div>
@endsection

