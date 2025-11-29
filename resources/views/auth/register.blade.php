<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Đăng ký - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] dark:text-[#EDEDEC] min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-[#161615] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-lg p-8">
            <div class="mb-6">
                <h1 class="text-2xl font-semibold mb-2">Đăng ký</h1>
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Tạo tài khoản mới để bắt đầu sử dụng</p>
            </div>

            @if (session('success'))
                <div class="mb-4 p-3 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-sm text-sm text-green-800 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 p-3 bg-[#fff2f2] dark:bg-[#1D0002] border border-[#f53003] dark:border-[#F61500] rounded-sm text-sm text-[#f53003] dark:text-[#FF4433]">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
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
                    <p class="mt-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">Mật khẩu phải có ít nhất 8 ký tự</p>
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

                <button
                    type="submit"
                    class="w-full px-5 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] dark:text-[#1C1C1A] border border-black dark:border-[#eeeeec] text-white rounded-sm font-medium hover:bg-black dark:hover:bg-white dark:hover:border-white transition-colors"
                >
                    Đăng ký
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
                    Đã có tài khoản?
                    <a href="{{ route('login') }}" class="text-[#f53003] dark:text-[#FF4433] font-medium underline underline-offset-4 hover:text-[#d42903] dark:hover:text-[#ff5533]">
                        Đăng nhập ngay
                    </a>
                </p>
            </div>

            <div class="mt-4 text-center">
                <a href="{{ route('home') }}" class="text-sm text-[#706f6c] dark:text-[#A1A09A] hover:text-[#1b1b18] dark:hover:text-[#EDEDEC]">
                    ← Quay về trang chủ
                </a>
            </div>
        </div>
    </div>
</body>
</html>

