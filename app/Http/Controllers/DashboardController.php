<?php

namespace App\Http\Controllers;

use App\Models\EnrollUser;
use App\Models\UserAssessmentProgress;
use App\Models\UserFlashProgress;
use App\Models\UserMcqProgress;
use App\Models\UserSbaProgress;
use App\Models\Assessment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $assessment = UserAssessmentProgress::with('assessment')
            ->where('user_id', Auth::user()->id)
            ->latest()
            ->first();

        $mcq = UserMcqProgress::where('user_id', Auth::user()->id)->latest()->first();
        $sba = UserSbaProgress::where('user_id', Auth::user()->id)->latest()->first();
        $flash = UserFlashProgress::where('user_id', Auth::user()->id)->latest()->first();

        $courses = EnrollUser::where('user_id', Auth::user()->id)->get();
        return view('frontend.dashboard.index', compact('courses', 'assessment', 'mcq', 'sba', 'flash'));
    }

    public function states($id)
    {
        $states = DB::table('cities')->where('country_id', $id)->get();

        return response()->json($states);
    }
}
