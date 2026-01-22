<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\MockViva;
use App\Models\MockVivaQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\Upload;

class MockVivaController extends Controller
{
    use Upload;

    public function index()
    {
        $courses = courseByModule('mock_viva');
        return view('backend.mockviva.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('mock_viva');
        return view('backend.mockviva.create', compact('courses'));
    }

    /**
     * Shared validation rules for store and update
     */
    private function validationRules()
    {
        return [
            'course_ids'                       => 'required|array',
            'course_ids.*'                     => 'exists:courses,id',
            'chapter_id'                       => 'required|exists:chapters,id',
            'lesson_id'                        => 'nullable|exists:lessons,id',
            'status'                           => 'required|in:0,1,2',
            'isPaid'                           => 'nullable|in:0,1',
            'questions'                        => 'required|array|min:1',
            'questions.*.questions'            => 'required|array|min:1',
            'questions.*.questions.*.question' => 'required|string',
            'questions.*.questions.*.answer'   => 'required|string',
        ];
    }

    /**
     * Save or update mock viva with questions
     */
    private function saveMockViva(MockViva $mock = null, array $validated, array $courseIds)
    {
        DB::beginTransaction();

        try {
            // Create or update MockViva
            if ($mock) {
                $mock->update($validated);
            } else {
                $validated['slug'] = checkslug('mock_vivas');
                $mock = MockViva::create($validated);
            }

            $mock->courses()->sync($courseIds);

            // Delete old questions if updating
            if ($mock->wasRecentlyCreated === false) {
                $mock->questions()->delete();
            }

            // Insert new question groups
            foreach ($validated['questions'] as $group) {
                if (isset($group['questions']) && is_array($group['questions'])) {
                    MockVivaQuestion::create([
                        'mock_viva_id' => $mock->id,
                        'questions'    => json_encode($group['questions'])
                    ]);
                }
            }

            DB::commit();
            return $mock;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (contentExists(\App\Models\MockViva::class, $validated['chapter_id'], $validated['lesson_id'], $validated['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mock Viva already exists for this combination.');
        }

        $courseIds = $validated['course_ids'];
        unset($validated['course_ids']);

        try {
            $this->saveMockViva(null, $validated, $courseIds);
            return redirect()->route('admin.mockvivas.index')->with('success', 'Added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(MockViva $mock)
    {
        return view('backend.mockviva.show', compact('mock'));
    }

    public function edit(MockViva $mock)
    {
        $courses = courseByModule('mock_viva');
        return view('backend.mockviva.edit', compact('mock', 'courses'));
    }

    public function update(Request $request, MockViva $mock)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (contentExists(\App\Models\MockViva::class, $validated['chapter_id'], $validated['lesson_id'], $validated['course_ids'], $mock->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mock Viva already exists for this combination.');
        }

        $courseIds = $validated['course_ids'];
        unset($validated['course_ids']);

        try {
            $this->saveMockViva($mock, $validated, $courseIds);
            return redirect()->route('admin.mockvivas.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function destroy(MockViva $mock)
    {
        DB::beginTransaction();

        try {
            $mock->questions()->delete();
            $mock->courses()->detach();
            $mock->delete();

            DB::commit();

            return redirect()
                ->route('admin.mockvivas.index')
                ->with('success', 'Mock Viva deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.mockvivas.index')
                ->with('error', 'Delete Failed: ' . $e->getMessage());
        }
    }

    public function status(MockViva $mock)
    {
        try {
            $mock->update(['status' => $mock->status == 1 ? 2 : 1]);
            return redirect()->back()->with('success', 'Status Update successfully!!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getData()
    {
        $slug = request('slug');

        if (!$slug) {
            return response()->json(['msg' => 'Course not selected!!']);
        }

        $course = Course::select('id', 'slug')->where('slug', $slug)->first();

        if (!$course) {
            return response()->json(['html' => '']);
        }

        $mocks = MockViva::whereHas('courses', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->with(['chapter', 'lesson'])
            ->orderByDesc('id')
            ->get();

        $html = view('backend.includes.mockviva_row', compact('mocks'))->render();

        return response()->json(['html' => $html]);
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('uploads/ospe'), $imageName);

        return response()->json(['url' => asset('uploads/ospe/'.$imageName)]);
    }
}
