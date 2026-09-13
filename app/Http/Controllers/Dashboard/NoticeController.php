<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::where('is_active', true)
            ->latest()
            ->paginate(10);

        return view('frontend.notices.index', compact('notices'));
    }

    public function show($slug)
    {
        $notice = Notice::where('slug', $slug)->firstOrFail();

        $recentNotices = Notice::where('is_active', true)
            ->where('id', '!=', $notice->id)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.notices.show', compact('notice', 'recentNotices'));
    }
}
