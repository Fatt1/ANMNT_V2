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
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
            'evidence_file' => 'required|file' 
        ]);

        $file = $request->file('evidence_file');

        // [VULNERABILITY: Unrestricted File Upload]
        // Get the original name without validating extension and move to public folder directly.
        $originalFileName = $file->getClientOriginalName();
        $file->move(public_path('uploads/reviews'), $originalFileName);

        // Optionally, save to DB if you want
        // DB::table('support_tickets')->insert([
        //     'user_id' => Auth::id() ?? 1,
        //     'subject' => $request->subject,
        //     'message' => $request->message,
        //     'evidence_file' => 'uploads/reviews/' . $originalFileName,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);

        return redirect()->back()->with('success', 'Ticket submitted! File uploaded to: /uploads/reviews/' . $originalFileName);
    }
}
