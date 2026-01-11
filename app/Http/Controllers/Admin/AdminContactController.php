<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;

class AdminContactController extends Controller
{
    // Danh sách liên hệ
    public function index()
    {
        $contacts = Contact::latest()->paginate(10);

        return view('admin.contacts.index', compact('contacts'));
    }

    // Xem chi tiết
    public function show($id)
    {
        $contact = Contact::findOrFail($id);

        return view('admin.contacts.show', compact('contact'));
    }

    // Xóa liên hệ
    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();

        return redirect()
            ->route('admin.contacts.index')
            ->with('success', 'Xóa liên hệ thành công');
    }
}
