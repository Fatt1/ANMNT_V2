<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Request $request)
    {
        $fileName = $request->query('file');

        if (!$fileName) {
            abort(400, 'File parameter is missing.');
        }

        // [VULNERABILITY: Path Traversal]
        // The user input is appended directly to the base directory path without any path sanitization.
        $filePath = storage_path('app/private/invoices') . '/' . $fileName;

        if (!file_exists($filePath)) {
            // For testing convenience, we will just proceed anyway or create a dummy response
            // abort(404, 'Invoice not found.');
        }

        return response()->download($filePath);
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
