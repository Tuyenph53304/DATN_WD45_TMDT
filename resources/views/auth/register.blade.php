<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng ký - {{ config('constants.site.name') }}</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-container {
            max-width: 500px;
            width: 100%;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
        }

        .register-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .register-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .register-header p {
            margin: 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .register-body {
            padding: 40px 35px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 18px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .btn-register {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5);
            color: white;
        }

        .alert-modern {
            border-radius: 12px;
            border: none;
            padding: 14px 18px;
            margin-bottom: 25px;
        }

        .alert-success-modern {
            background: #d1fae5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-danger-modern {
            background: #fee2e2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .divider {
            text-align: center;
            margin: 25px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            right: 0;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            background: white;
            padding: 0 15px;
            color: #94a3b8;
            font-size: 0.9rem;
            position: relative;
        }

        .footer-links {
            text-align: center;
            margin-top: 25px;
        }

        .footer-links a {
            color: #667eea;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .back-home {
            text-align: center;
            margin-top: 20px;
        }

        .back-home a {
            color: #64748b;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .back-home a:hover {
            color: #667eea;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            z-index: 1;
        }

        .input-icon .form-control {
            padding-left: 50px;
        }

        .password-hint {
            font-size: 0.85rem;
            color: #64748b;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-card">
            <div class="register-header">
                <h1><i class="bi bi-person-plus me-2"></i>Đăng ký</h1>
                <p>Tạo tài khoản mới để bắt đầu</p>
            </div>

            <div class="register-body">
                @if (session('success'))
                    <div class="alert alert-success-modern alert-modern alert-dismissible fade show">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger-modern alert-modern">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Lỗi:</strong>
                        <ul class="mb-0 mt-2" style="padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">
                            <i class="bi bi-person me-1"></i>Họ và tên
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-person"></i>
                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="{{ old('name') }}"
                                placeholder="Nhập họ và tên của bạn"
                                required
                                autofocus
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope me-1"></i>Email
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-envelope"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                placeholder="Nhập email của bạn"
                                required
                            >
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock me-1"></i>Mật khẩu
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-lock"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Tối thiểu 8 ký tự"
                                required
                                minlength="8"
                            >
                        </div>
                        <div class="password-hint">
                            <i class="bi bi-info-circle me-1"></i>Mật khẩu phải có ít nhất 8 ký tự
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i>Xác nhận mật khẩu
                        </label>
                        <div class="input-icon">
                            <i class="bi bi-lock-fill"></i>
                            <input
                                type="password"
                                id="password_confirmation"
                                name="password_confirmation"
                                class="form-control"
                                placeholder="Nhập lại mật khẩu"
                                required
                                minlength="8"
                            >
                        </div>
                    </div>

                    <button type="submit" class="btn btn-register">
                        <i class="bi bi-person-plus me-2"></i>Đăng ký
                    </button>
                </form>

                <div class="divider">
                    <span>hoặc</span>
                </div>

                <div class="footer-links">
                    <p class="mb-0">
                        Đã có tài khoản?
                        <a href="{{ route('login') }}">Đăng nhập ngay</a>
                    </p>
                </div>

                <div class="back-home">
                    <a href="{{ route('home') }}">
                        <i class="bi bi-arrow-left me-1"></i>Quay về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
