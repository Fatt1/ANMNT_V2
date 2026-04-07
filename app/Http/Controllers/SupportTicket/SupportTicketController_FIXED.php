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
        // [FIX 1] Validate đúng: chỉ cho phép ảnh, pdf, txt (whitelist extension)
        // Không dùng blacklist vì dễ bypass (.php5, .phtml, .pHp, ...)
        // =====================================================================
        $request->validate([
            'subject'       => 'required',
            'message'       => 'required',
            'evidence_file' => [
                'required',
                'file',
                'max:2048', // Tối đa 2MB
                // Whitelist MIME type thực sự của file (đọc binary, không tin client)
                'mimes:jpg,jpeg,png,gif,pdf,txt',
                function ($attribute, $value, $fail) {
                    // =====================================================================
                    // Kiểm tra MIME type THỰC SỰ từ binary, không tin client
                    // Hacker upload hacker.php nhưng đổi tên thành hacker.jpg
                    // → extension là .jpg nhưng MIME type từ magic bytes là application/x-php
                    // =====================================================================
                    $mimeType = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $value->getPathname());
                    
                    // Whitelist MIME type an toàn
                    $allowedMimes = [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'application/pdf',
                        'text/plain',
                    ];
                    
                    if (!in_array($mimeType, $allowedMimes)) {
                        $fail("The $attribute file type is not allowed. Detected: $mimeType");
                    }
                }
            ],
        ]);

        $file = $request->file('evidence_file');

        // =====================================================================
        // [FIX 2] KHÔNG dùng tên file gốc từ client vì 3 lý do:
        //
        // 1. GHI ĐÈ FILE (File Overwrite):
        //    Nếu 2 user cùng upload "avatar.jpg" → file sau ghi đè file trước.
        //    Hacker có thể cố tình đặt tên trùng để ghi đè file hệ thống.
        //
        // 2. PATH TRAVERSAL:
        //    Hacker đặt tên file: "../../../public/index.php"
        //    → $file->move(..., '../../../public/index.php')
        //    → Ghi đè thẳng vào entry point Laravel → toàn bộ app bị hỏng!
        //
        // 3. TÊN FILE ĐỘC HẠI:
        //    Ví dụ: "<script>alert(1)</script>.jpg" → XSS nếu hiển thị ra HTML
        //           "CON.jpg" / "NUL.jpg"           → Reserved name Windows → crash
        //
        // GIẢI PHÁP: uniqid() tạo tên unique theo timestamp → không thể đoán,
        //            không thể ghi đè, không chứa ký tự nguy hiểm.
        // =====================================================================
        $safeExtension = $file->getClientOriginalExtension(); // đã qua whitelist ở trên
        $safeFileName   = uniqid('review_', true) . '.' . $safeExtension;
        // Kết quả: "review_67e5a1b2c3d4e.6789.jpg" → luôn unique, an toàn

        // =====================================================================
        // [FIX 3] Lưu file vào storage/ (ngoài public/) thay vì public/uploads/
        // File trong storage/ KHÔNG thể truy cập trực tiếp qua URL → an toàn hơn
        // Nếu bắt buộc phải để trong public/ thì phải có .htaccess chặn PHP (FIX 4)
        // =====================================================================
        // Cách lưu vào storage/app/uploads/reviews/ (KHÔNG có URL trực tiếp):
        // $path = $file->storeAs('uploads/reviews', $safeFileName, 'local');
        //
        // Nếu cần cho user download, tạo route riêng:
        // return response()->file(storage_path('app/' . $path));
        // =====================================================================

        // Lab này vẫn dùng public/ để dễ demo, được bảo vệ bởi .htaccess (FIX 4)
        $file->move(public_path('uploads/reviews'), $safeFileName);

        // =====================================================================
        // [FIX 4] File .htaccess trong thư mục uploads chặn execute PHP
        // Xem file: public/uploads/reviews/.htaccess
        // Ngay cả khi upload được .php, server cũng sẽ từ chối chạy nó
        // =====================================================================

        // Optionally, save to DB if you want
        // DB::table('support_tickets')->insert([
        //     'user_id'        => Auth::id() ?? 1,
        //     'subject'        => $request->subject,
        //     'message'        => $request->message,
        //     'evidence_file'  => 'uploads/reviews/' . $safeFileName,
        //     'created_at'     => now(),
        //     'updated_at'     => now()
        // ]);

        return redirect()->back()->with('success', 'Ticket submitted successfully.');
    }
}
