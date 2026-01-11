<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    // Hiển thị form liên hệ
    public function index()
    {
        return view('user.contact.index');
    }

    // Xử lý gửi liên hệ
    public function store(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email',
            'phone'   => 'required|string|max:15',
            'message' => 'required|string'
        ]);

        // Lưu liên hệ
        Contact::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'message' => $request->message
        ]);

        return redirect()
            ->route('contact.index')
            ->with('success', 'Gửi liên hệ thành công! Chúng tôi sẽ phản hồi sớm.');
    }
}
