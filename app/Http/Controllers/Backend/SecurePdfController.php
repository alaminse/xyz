<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\SecurePdf;
use App\Services\SecurePdfService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SecurePdfController extends Controller
{
    public function __construct(private SecurePdfService $service) {}

    public function index()
    {
        $courses = courseByModule('secure_pdf');
        return view('backend.secure-pdfs.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('secure_pdf');
        return view('backend.secure-pdfs.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'course_ids'   => 'required|array',
            'course_ids.*' => 'exists:courses,id',
            'chapter_id'   => 'required|exists:chapters,id',
            'lesson_id'    => 'nullable|exists:lessons,id',
            'title'        => 'required|string|max:500',
            'description'  => 'nullable|string',
            'total_pages'  => 'required|integer|min:1',
            'pdf_file'     => 'required|file|mimes:pdf|max:51200',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $data = $validator->validated();
            $data['isPaid']      = $request->has('isPaid') ? 1 : 0;
            $data['allow_print'] = $request->has('allow_print') ? 1 : 0;

            $pdf = $this->service->store(
                $request->file('pdf_file'),
                $data,
                $request->user()->id
            );

            $pdf->courses()->sync($request->course_ids);

            return redirect()
                ->route('admin.secure-pdfs.index')
                ->with('success', 'PDF uploaded successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Upload failed: ' . $e->getMessage());
        }
    }

    public function show(SecurePdf $securePdf)
    {
        $securePdf->load('courses', 'chapter', 'lesson');
        $securePdf->loadCount('accessLogs');
        $recentLogs = $securePdf->accessLogs()
            ->with('user')
            ->latest('accessed_at')
            ->take(10)
            ->get();

        return view('backend.secure-pdfs.show',
            compact('securePdf', 'recentLogs'));
    }

    public function edit(SecurePdf $securePdf)
    {
        $courses = courseByModule('secure_pdf');
        $securePdf->load('courses', 'chapter', 'lesson');
        return view('backend.secure-pdfs.edit',
            compact('securePdf', 'courses'));
    }

    public function update(Request $request, SecurePdf $securePdf)
    {
        $validator = Validator::make($request->all(), [
            'course_ids'   => 'required|array',
            'course_ids.*' => 'exists:courses,id',
            'chapter_id'   => 'required|exists:chapters,id',
            'lesson_id'    => 'nullable|exists:lessons,id',
            'title'        => 'required|string|max:500',
            'description'  => 'nullable|string',
            'total_pages'  => 'nullable|integer|min:0',
            'pdf_file'     => 'nullable|file|mimes:pdf|max:51200',
            'is_active'    => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $securePdf->update([
                'title'       => $request->title,
                'description' => $request->description,
                'chapter_id'  => $request->chapter_id,
                'lesson_id'   => $request->lesson_id,
                'total_pages' => $request->total_pages ?? $securePdf->total_pages,
                'isPaid'      => $request->has('isPaid') ? 1 : 0,
                'allow_print' => $request->has('allow_print') ? 1 : 0,
                'is_active'   => $request->is_active ?? 1,
            ]);

            $securePdf->courses()->sync($request->course_ids);

            if ($request->hasFile('pdf_file')) {
                $this->service->replaceFile(
                    $securePdf,
                    $request->file('pdf_file')
                );
            }

            return redirect()
                ->route('admin.secure-pdfs.index')
                ->with('success', 'Updated successfully.');

        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Update failed: ' . $e->getMessage());
        }
    }

    public function destroy(SecurePdf $securePdf)
    {
        try {
            $this->service->delete($securePdf);
            return redirect()
                ->route('admin.secure-pdfs.index')
                ->with('success', 'PDF deleted.');
        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed: ' . $e->getMessage());
        }
    }

    public function toggleStatus(SecurePdf $securePdf)
    {
        $securePdf->update(['is_active' => !$securePdf->is_active]);
        return response()->json([
            'success'   => true,
            'is_active' => $securePdf->is_active,
            'label'     => $securePdf->is_active ? 'Active' : 'Inactive',
        ]);
    }

    public function accessLogs(SecurePdf $securePdf)
    {
        $logs = $securePdf->accessLogs()
            ->with('user')
            ->latest('accessed_at')
            ->paginate(25);

        return view('backend.secure-pdfs.access-logs',
            compact('securePdf', 'logs'));
    }

    public function getData(Request $request)
    {
        $slug = $request->get('slug');
        if (!$slug) return response()->json(['html' => '']);

        $course = Course::where('slug', $slug)->first();
        if (!$course) return response()->json(['html' => '']);

        $pdfs = $course->securePdfs()
            ->with(['chapter', 'lesson'])
            ->withCount('accessLogs')
            ->orderBy('id', 'desc')
            ->get();

        $html = '';
        foreach ($pdfs as $i => $pdf) {
            $sc = $pdf->is_active ? 'success' : 'danger';
            $sl = $pdf->is_active ? 'Active' : 'Inactive';

            $html .= '
            <tr>
                <td>' . ($i + 1) . '</td>
                <td>
                    <strong>' . e($pdf->title) . '</strong><br>
                    <small class="text-muted">' . e($pdf->original_name) . '</small>
                </td>
                <td>' . e($pdf->chapter?->name ?? '—') . '</td>
                <td>' . e($pdf->lesson?->name ?? '—') . '</td>
                <td>' . $pdf->total_pages . '</td>
                <td>' . $pdf->file_size_formatted . '</td>
                <td>' . ($pdf->isPaid
                    ? '<span class="badge badge-warning">Paid</span>'
                    : '<span class="badge badge-success">Free</span>') . '
                </td>
                <td>
                    <button class="btn btn-xs btn-' . $sc . ' btn-toggle"
                        data-id="' . $pdf->id . '">' . $sl . '</button>
                </td>
                <td>
                    <a href="' . route('admin.secure-pdfs.show', $pdf->id) . '"
                        class="btn btn-xs btn-info">View</a>
                    <a href="' . route('admin.secure-pdfs.edit', $pdf->id) . '"
                        class="btn btn-xs btn-warning">Edit</a>
                    <a href="' . route('admin.secure-pdfs.access-logs', $pdf->id) . '"
                        class="btn btn-xs btn-default">Logs</a>
                    <button class="btn btn-xs btn-danger btn-delete"
                        data-url="' . route('admin.secure-pdfs.destroy', $pdf->id) . '"
                        data-title="' . e($pdf->title) . '">Delete</button>
                </td>
            </tr>';
        }

        return response()->json(['html' => $html]);
    }
}
