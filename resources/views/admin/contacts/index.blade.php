@extends('admin.layout')

@section('title', 'Quản lý Liên hệ - Admin Panel')
@section('page-title', 'Quản lý Liên hệ')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="bi bi-envelope-fill me-2"></i> Danh sách Liên hệ
        </h3>
    </div>

    <div class="card-body">

        <!-- Table -->
        @if($contacts->count() > 0)
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Họ tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Ngày gửi</th>
                        <th width="160">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>
                                {{ $contact->phone ?? 'N/A' }}
                        </td>
                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.contacts.show', $contact) }}"
                                   class="btn btn-sm btn-info"
                                   title="Xem chi tiết">
                                    <i class="bi bi-eye"></i>
                                </a>

                                <form action="{{ route('admin.contacts.destroy', $contact) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('Bạn có chắc chắn muốn xóa liên hệ này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Xóa">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($contacts instanceof \Illuminate\Pagination\LengthAwarePaginator && $contacts->hasPages())
        <div class="mt-3">
            {{ $contacts->links() }}
        </div>
        @endif

        @else
        <div class="alert alert-info text-center">
            <i class="bi bi-info-circle me-2"></i>
            Chưa có liên hệ nào.
        </div>
        @endif

    </div>
</div>
@endsection
