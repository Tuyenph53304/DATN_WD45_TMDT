@extends('admin.layout')

@section('title', 'Tạo User mới')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6">
        <a href="{{ route('admin.users') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC] mb-4 inline-block">
            ← Quay lại danh sách
        </a>
        <h1 class="text-2xl font-semibold mt-2">Tạo User mới</h1>
    </div>

    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
        @if ($errors->any())
            <div class="mb-4 p-3 bg-[#fff2f2] dark:bg-[#1D0002] border border-[#f53003] dark:border-[#F61500] rounded-sm text-sm text-[#f53003] dark:text-[#FF4433]">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium mb-2">Họ và tên</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    class="w-full px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c] dark:placeholder:text-[#A1A09A] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
                    placeholder="Nguyễn Văn A"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-medium mb-2">Email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    class="w-full px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c] dark:placeholder:text-[#A1A09A] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
                    placeholder="name@example.com"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-2">Mật khẩu</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    required
                    minlength="8"
                    class="w-full px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c] dark:placeholder:text-[#A1A09A] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
                    placeholder="Tối thiểu 8 ký tự"
                >
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium mb-2">Xác nhận mật khẩu</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                    minlength="8"
                    class="w-full px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c] dark:placeholder:text-[#A1A09A] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
                    placeholder="Nhập lại mật khẩu"
                >
            </div>

            <div>
                <label for="role" class="block text-sm font-medium mb-2">Role</label>
                <select
                    id="role"
                    name="role"
                    required
                    class="w-full px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
                >
                    <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>User</option>
                    <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <div class="flex items-center gap-4 pt-4">
                <button
                    type="submit"
                    class="px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] text-white rounded-sm font-medium hover:bg-black dark:hover:bg-white dark:hover:border-white transition-colors"
                >
                    Tạo User
                </button>
                <a href="{{ route('admin.users') }}"
                   class="px-5 py-2 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm font-medium hover:bg-[#dbdbd7] dark:hover:bg-[#62605b] transition-colors">
                    Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

