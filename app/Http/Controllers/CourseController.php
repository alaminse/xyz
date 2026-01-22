<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use Illuminate\Support\Facades\DB;
use App\Models\Billing;
use App\Models\Course;
use App\Models\EnrollUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::whereNull('parent_id')->where('status', Status::ACTIVE())->get();
        return view('frontend.settings.course', compact('courses'));
    }

    public function details($slug)
    {
        $course = Course::with('courses')->where('slug', $slug)->first();
        return view('frontend.settings.course-details', compact('course'));
    }

    public function getCourse($slug)
    {
        $course = Course::select('id', 'slug')->where('slug', $slug)->first();
        if (!$course) {
            return response()->json(['error' => true, 'html' => '']);
        }

        $courses = Course::select('id', 'parent_id', 'slug', 'banner', 'name', 'status', 'is_pricing')
            ->where('parent_id', $course->id)
            ->where('status', Status::ACTIVE())
            ->get();

        $pricing_zero_courses = $courses->filter(function ($course) {
            return $course->is_pricing == 0;
        });

        if ($pricing_zero_courses->isNotEmpty()) {
            foreach ($pricing_zero_courses as $pricing_zero_course) {
                $child_courses = Course::select('id', 'parent_id', 'slug', 'banner', 'name', 'status', 'is_pricing')
                    ->where('parent_id', $pricing_zero_course->id)
                    ->where('status', Status::ACTIVE())
                    ->get();
                $courses = $courses->merge($child_courses);
            }
        }

        $view = view('frontend.includes.course', compact('courses'))->render();

        return response()->json(['success' => true, 'html' => $view]);
    }

    public function checkout($slug, $isTrial = null)
    {
        if (!Auth::check()) {
            session()->put('redirectAfterLogin', url()->current());
            return redirect()->route('login');
        }

        $countries = DB::table('countries')->get();
        $course = Course::where('slug', $slug)->first();

        if (!$course) {
            return redirect()->back()->with('error', 'Course Not Found!!');
        }
        return view('frontend.settings.checkout', compact('course', 'countries', 'isTrial'));
    }

    public function checkout_store(Request $request, $slug)
    {
        $input = Validator::make($request->all(), [
            'firstName'     => 'required|string',
            'lastName'      => 'nullable|string',
            'email'         => 'required|email',
            'phone'         => 'required|string',
            'address'       => 'required|string',
            'address2'      => 'nullable|string',
            'country_id'    => 'required|exists:countries,id',
            'state_id'      => 'required|exists:cities,id',
            'zip'           => 'nullable|string',
            'paymentMethod' => 'nullable|numeric',
            'p-phone'       => 'nullable|string',
            'p-t_id'        => 'nullable|string',
            'p-amount'      => 'nullable|numeric',
            'isTrial'       => 'nullable|string'
        ]);

        if ($input->fails()) {
            return redirect()->back()->withErrors($input)->withInput();
        }


        $validated = $input->validated();
        $validated['slug'] = checkslug('courses');

        $course = Course::with('detail')->where('slug', $slug)->where('status', Status::ACTIVE())->first();
        if (!$course) {
            return redirect()->back()->with('error', 'Course not found!!')->withInput();
        }

        $day = calculation_day($course->detail?->duration, $course->detail?->type);
        $start_date = Carbon::now()->format('Y-m-d');
        $end_date = Carbon::parse($start_date)->addDays($day)->format('Y-m-d');

        $has_enrolled = EnrollUser::where('course_id', $course->id)
            ->where('user_id', Auth::user()->id)
            ->whereIn('status', [Status::ACTIVE(), Status::PENDING()])
            ->exists();


        if ($has_enrolled) {
            return redirect()->back()->with('error', 'You already have an active subscription for this course.');
        }

        $data = [
            'slug'          => checkslug('enroll_users'),
            'course_id'     => $course->id,
            'user_id'       => Auth::user()->id,
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'price'         => $course->detail->price,
            'sell_price'    => $course->detail->sell_price,
            'status'        => 0,
        ];

        if($validated['isTrial'] == 'free-trial')
        {
            $data['start_date'] = $start_date;
            $data['end_date']   = null;
            $data['status']     = 6;
            $data['price']      = 0;
            $data['sell_price'] = 0;
        } else {
            $course_price = $course->detail?->sell_price > 0 ? $course->detail?->sell_price : $course->detail?->price;

            if ($course_price != $validated['p-amount']) {
                return redirect()->back()->withInput()->with('error', 'Amounts are inconsistent!!');
            }
        }

        DB::beginTransaction();
        try {
            $sub = EnrollUser::updateOrCreate(
                ['course_id' => $course->id, 'user_id' => Auth::user()->id],
                $data
            );

            $validated['enroll_user_id'] = $sub->id;
            Billing::create($validated);

            DB::commit();
            return redirect()->route('enrollments')->with('success', 'Order Complete.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function invoice($slug)
    {
        $invoice = EnrollUser::with('course', 'bill')->where('slug', $slug)->first();

        // return view('frontend.settings.invoice', compact('invoice'));

        // Load the view and generate the PDF
        $pdf = Pdf::loadView('frontend.settings.invoice', compact('invoice'));

        // // Optional settings
        $pdf->setPaper('A4', 'portrait');
        $pdf->set_option('isHtml5ParserEnabled', true);
        $pdf->set_option('isRemoteEnabled', true);
        // // Return the PDF as a download or stream
        return $pdf->stream($invoice->slug.'.pdf');
    }

}
