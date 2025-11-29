<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Hiển thị trang chủ
     */
    public function home()
    {
        return view('user.home');
    }
}

