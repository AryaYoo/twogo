<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class AdminFeedbackController extends Controller
{
    public function index()
    {
        $feedbacks = Feedback::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.feedback.index', compact('feedbacks'));
    }

    public function toggleRead(Feedback $feedback)
    {
        $feedback->update(['is_read' => !$feedback->is_read]);
        return back()->with('success', 'Status pesan berhasil diperbarui.');
    }

    public function destroy(Feedback $feedback)
    {
        $feedback->delete();
        return back()->with('success', 'Pesan kritik & saran berhasil dihapus.');
    }
}
