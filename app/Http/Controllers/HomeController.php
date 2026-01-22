<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\EnrollUser;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Enums\Status;

class HomeController extends Controller
{
    public function index()
    {
        $slider = Setting::where('title', 'slider')->first();

        $sliders = [];
        if($slider) {
            $sliders = json_decode($slider->value);
        }
        $snote = Setting::where('title', 'snote')->first();

        $snotes = [];
        if ($snote?->value) {
            $snotes = json_decode($snote->value);
        }

        $courses = Course::where('status', Status::ACTIVE())->get();

        return view('index', compact('courses', 'sliders', 'snotes'));
    }

    public function contact()
    {
        $faqs = Setting::where('title', 'faqs')->first();
        return view('frontend.settings.contact', compact('faqs'));
    }

    public function about()
    {
        $about = Setting::where('title', 'about')->first();

        $value = [];
        if($about) {
            $value = json_decode($about->value);
        }
        return view('frontend.settings.about', compact('value'));
    }

    public function privacy()
    {
        $privacy = Setting::where('title', 'privacy')->first();
        $value = [];
        if($privacy) {
            $value = $privacy->value;
        }

        return view('frontend.settings.privacy',compact('value'));
    }
    public function terms()
    {
        $privacy = Setting::where('title', 'terms')->first();
        $value = [];
        if($privacy) {
            $value = $privacy->value;
        }
        return view('frontend.settings.terms',compact('value'));
    }

    public function enrollments()
    {
        $courses = EnrollUser::where('user_id', Auth::user()->id)->get();
        return view('frontend.dashboard.enrollments', compact('courses'));
    }

    public function allCourses()
    {
        $courses = Course::where('status', Status::ACTIVE())
            ->with(['detail', 'courses'])
            ->get();

        return view('frontend.dashboard.courses', compact('courses'));
    }

    public function courseDetails($slug)
    {
        $course = Course::where('slug', $slug)
            ->where('status', 1)
            ->with(['detail', 'courses.detail'])
            ->firstOrFail();

        return view('frontend.dashboard.course-details', compact('course'));
    }

}
