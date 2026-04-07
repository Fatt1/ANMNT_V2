<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function create(): View
    {
        return view('support_tickets.create');
    }

    public function store(Request $request)
    {
        // =====================================================================
        // [BUG - LỖ HỔNG GỐC] Unrestricted File Upload
        // Validation chỉ kiểm tra file có tồn tại, KHÔNG kiểm tra extension.
        // Kẻ tấn công có thể upload file .php và truy cập qua URL để RCE.
        // =====================================================================
        $request->validate([
            'subject'       => 'required',
            'message'       => 'required',
            'evidence_file' => 'required|file'   // ← CHỈ CÓ VẬY, KHÔNG CHECK .php
        ]);

        $file = $request->file('evidence_file');

        // Lấy tên gốc từ client (có thể là "hacker.php") → move vào public/
        $originalFileName = $file->getClientOriginalName();
        $file->move(public_path('uploads/reviews'), $originalFileName);

        // Sau đó truy cập: /uploads/reviews/hacker.php?cmd=whoami → RCE!

        // Optionally, save to DB if you want
        // DB::table('support_tickets')->insert([
        //     'user_id'       => Auth::id() ?? 1,
        //     'subject'       => $request->subject,
        //     'message'       => $request->message,
        //     'evidence_file' => 'uploads/reviews/' . $originalFileName,
        //     'created_at'    => now(),
        //     'updated_at'    => now()
        // ]);

        return redirect()->back()->with('success', 'Ticket submitted successfully.');
    }
}
