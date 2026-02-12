<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use App\Models\Course;
use App\Http\Controllers\Controller;
use App\Models\EnrollUser;
use Illuminate\Http\Request;

class EnrolleController extends Controller
{
    public function index()
    {
        $courses = Course::whereNotNull('parent_id')->get(); // Fixed the query
        return view('backend.enrolle.index', compact('courses'));
    }

    public function status(Request $request)
    {
        $enrolle = EnrollUser::findOrFail($request->enrolle_id);
        try {
            $enrolle->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Status Update successfully!!');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function transection()
    {
        return view('backend.enrolle.transection');
    }

    public function report(Request $request)
    {
        $query = EnrollUser::query(); // Start with a query builder

        // MODIFIED: Filter by course if provided
        if ($request->filled('course_id')) {
            $query->where('course_id', $request->input('course_id'));
        }

        // MODIFIED: Filter by date range if provided
        if ($request->filled('startDate') && $request->filled('endDate')) {
            // Use whereDate to compare only the date part of the timestamp
            $query->whereDate('start_date', '>=', $request->input('startDate'))
                ->whereDate('start_date', '<=', $request->input('endDate'));
        }

        $users = $query->latest()->paginate(1000);
        $earning = $users->sum('sell_price');
        $total = $users->count();
        $active = $users->where('status', Status::ACTIVE())->count();

        $html = view('backend.enrolle.table', compact('users'))->render();

        // It's better to use the pagination links provided by Laravel
        $pagination = $users->links('pagination::bootstrap-4')->toHtml();

        return response()->json([
            'html'          => $html,
            'pagination'    => $pagination,
            'earning'       => $earning,
            'total'         => $total,
            'active'        => $active,
        ]);
    }

    // public function report(Request $request)
    // {
    //     $startDate = $request->input('startDate');
    //     $endDate = $request->input('endDate');

    //     $users = EnrollUser::whereBetween('start_date', [$startDate, $endDate])->latest()->paginate(1000);
    //     $earning = $users->sum('sell_price');
    //     $total = $users->count();
    //     $active = $users->where('status', Status::ACTIVE())->count();

    //     $html = view('backend.enrolle.table', compact('users'))->render();  // Correct Blade rendering

    //     $pagination = '<nav aria-label="Page navigation"><ul class="pagination">';
    //     $pagination .= '<li class="page-item"><a class="page-link" href="' . $users->previousPageUrl() . '">Previous</a></li>';
    //     foreach (range(1, $users->lastPage()) as $page) {
    //         $pagination .= '<li class="page-item ' . ($users->currentPage() == $page ? 'active' : '') . '"><a class="page-link" href="' . $users->url($page) . '">' . $page . '</a></li>';
    //     }
    //     $pagination .= '<li class="page-item"><a class="page-link" href="' . $users->nextPageUrl() . '">Next</a></li>';
    //     $pagination .= '</ul></nav>';

    //     return response()->json([
    //         'startDate'     => $startDate,
    //         'endDate'       => $endDate,
    //         'html'          => $html,
    //         'pagination'    => $pagination,
    //         'earning'       => $earning,
    //         'total'         => $total,
    //         'active'        => $active,
    //     ]);
    // }
}
