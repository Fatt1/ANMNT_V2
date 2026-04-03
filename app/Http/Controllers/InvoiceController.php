<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Request $request)
    {
        // [BUG] Path Traversal (Directory Traversal)
        // Người dùng có thể truyền chuỗi như ../../../.env để đọc file ngoài thư mục invoices.   http://127.0.0.1:8000/account/invoice/download?file=../../.../../.env
        $fileName = $request->query('file');
        if (!$fileName) {
            abort(400, 'File parameter is missing.');
        }
        // [VULNERABLE] Ghép trực tiếp input người dùng vào đường dẫn file.
        // Ví dụ payload: ?file=../../.../../.env 
        $filePath = storage_path('app/private/invoices') . '/' . $fileName;
        if (!file_exists($filePath)) {
            abort(404, 'Invoice not found.');
        }
        return response()->download($filePath);

        // [FIX] Path Traversal (Directory Traversal)
        // Các lớp phòng thủ:
        // 1) basename() loại bỏ ../ hoặc path lồng nhau
        // 2) realpath() chuẩn hóa và xác minh file thật tồn tại
        // 3) str_starts_with() đảm bảo file phải nằm trong thư mục invoices
        // 4) Trả thông báo chung chung, không lộ path nội bộ
     
        // $baseDir = realpath(storage_path('app/private/invoices'));
        // if ($baseDir === false) {
        //     abort(500, 'Invoice storage is not configured.');
        // }
        // $inputName = (string) $request->query('file', '');
        // if ($inputName === '') {
        //     abort(400, 'File parameter is missing.');
        // }
        // // Chỉ lấy tên file cuối cùng, loại bỏ toàn bộ path do user truyền vào. 
        // $safeFileName = basename(str_replace('\\', '/', $inputName));
        // $candidatePath = $baseDir . DIRECTORY_SEPARATOR . $safeFileName;
        // $resolvedPath = realpath($candidatePath);

        // if ($resolvedPath === false || !str_starts_with($resolvedPath, $baseDir . DIRECTORY_SEPARATOR) || !is_file($resolvedPath)) {
        //     abort(404, 'Invoice not found - "đừng thử nữa "');
        // }
        // return response()->download($resolvedPath);
    }

    public function sendEmail(Request $request)
    {
        $orderId = $request->input('order_id', '');

        // [VULNERABILITY: Out-of-Band (OOB) / Time-based Blind SQLi]
        // This simulates a background job. The backend receives the input and queries the database, 
        // silently failing/succeeding without showing the user the DB result directly.
        try {
            $sql = "SELECT * FROM orders WHERE id = " . $orderId;
            \Illuminate\Support\Facades\DB::select($sql);
        } catch (\Exception $e) {
            // Swallowing the error to simulate an async job where the user only sees "Success"
        }

        return redirect()->back()->with('status', 'Hóa đơn đang được xử lý và sẽ gửi vào email của bạn trong ít phút!');
    }
}
