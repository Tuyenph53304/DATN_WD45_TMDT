@extends('admin.layout')

@section('title', 'Quản lý Users')

@section('content')
<div>
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold">Quản lý Users</h1>
        <a href="{{ route('admin.users.create') }}"
           class="px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] text-white rounded-sm font-medium hover:bg-black dark:hover:bg-white dark:hover:border-white transition-colors">
            + Tạo User mới
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6 mb-6">
        <form method="GET" action="{{ route('admin.users') }}" class="flex gap-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Tìm kiếm theo tên hoặc email..."
                class="flex-1 px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] placeholder:text-[#706f6c] dark:placeholder:text-[#A1A09A] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
            >
            <select
                name="role"
                class="px-4 py-2 bg-white dark:bg-[#0a0a0a] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-sm text-[#1b1b18] dark:text-[#EDEDEC] focus:outline-none focus:border-[#19140035] dark:focus:border-[#62605b] transition-colors"
            >
                <option value="">Tất cả roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button
                type="submit"
                class="px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] text-white rounded-sm font-medium hover:bg-black dark:hover:bg-white dark:hover:border-white transition-colors"
            >
                Tìm kiếm
            </button>
            @if (request('search') || request('role'))
                <a href="{{ route('admin.users') }}" class="px-5 py-2 bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-sm font-medium hover:bg-[#dbdbd7] dark:hover:bg-[#62605b] transition-colors">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-6">
        @if ($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A]">
                            <th class="text-left py-3 px-4 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">ID</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Tên</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Email</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Role</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Ngày tạo</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-[#706f6c] dark:text-[#A1A09A]">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr class="border-b border-[#e3e3e0] dark:border-[#3E3E3A] hover:bg-[#e3e3e0]/50 dark:hover:bg-[#3E3E3A]/50">
                                <td class="py-3 px-4 text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->id }}</td>
                                <td class="py-3 px-4 text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->name }}</td>
                                <td class="py-3 px-4 text-[#1b1b18] dark:text-[#EDEDEC]">{{ $user->email }}</td>
                                <td class="py-3 px-4">
                                    <span class="inline-block px-2 py-1 text-xs rounded-sm {{ $user->role === 'admin' ? 'bg-[#fff2f2] dark:bg-[#1D0002] text-[#f53003] dark:text-[#FF4433]' : 'bg-[#e3e3e0] dark:bg-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC]' }}">
                                        {{ $user->role === 'admin' ? 'Admin' : 'User' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">{{ $user->created_at->format('d/m/Y H:i') }}</td>
                                <td class="py-3 px-4">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                                            Sửa
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline"
                                                  onsubmit="return confirm('Bạn có chắc chắn muốn xóa user này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-[#f53003] dark:text-[#FF4433] hover:underline">
                                                    Xóa
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @else
            <p class="text-[#706f6c] dark:text-[#A1A09A] text-center py-8">Không tìm thấy user nào.</p>
        @endif
    </div>
</div>
@endsection

