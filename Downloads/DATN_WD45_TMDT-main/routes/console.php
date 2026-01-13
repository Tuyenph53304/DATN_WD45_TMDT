<?php

use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('admin:create', function () {
    $name = $this->ask('Nhập tên admin');
    $email = $this->ask('Nhập email admin');
    $password = $this->secret('Nhập mật khẩu admin');

    if (User::where('email', $email)->exists()) {
        $this->error('Email đã tồn tại!');
        return;
    }

    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
        'role' => 'admin',
    ]);

    $this->info('Admin user đã được tạo thành công!');
    $this->info('ID: ' . $user->id);
    $this->info('Email: ' . $user->email);
})->purpose('Tạo user admin mới');

Artisan::command('admin:promote {email}', function () {
    $email = $this->argument('email');
    $user = User::where('email', $email)->first();

    if (!$user) {
        $this->error('Không tìm thấy user với email: ' . $email);
        return;
    }

    if ($user->role === 'admin') {
        $this->warn('User này đã là admin rồi!');
        return;
    }

    $user->update(['role' => 'admin']);
    $this->info('Đã cập nhật user "' . $user->name . '" thành admin thành công!');
})->purpose('Cập nhật user thành admin bằng email');
