<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\WrittenAssessment;
use App\Models\WrittenAssessmentQuestion;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WrittenAssessmentController extends Controller
{
    use Upload;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = courseByModule('written');
        return view('backend.written-assessment.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = courseByModule('written');
        return view('backend.written-assessment.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = $this->validateWrittenAssessment($request);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $validator->validated();
        $courseIds = $input['course_ids'];
        unset($input['course_ids']);

        try {
            DB::beginTransaction();

            // Generate unique slug
            $input['slug'] = checkslug('written_assessments');

            // Create written assessment
            $writtenAssessment = WrittenAssessment::create($input);

            // Sync courses
            $writtenAssessment->courses()->sync($courseIds);

            DB::commit();

            return redirect()
                ->route('admin.writtenassessments.index')
                ->with('success', 'Written Assessment created successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Written Assessment Creation Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create written assessment. Please try again.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(WrittenAssessment $written)
    {
        $written->load(['courses', 'chapter', 'lesson']);
        return view('backend.written-assessment.show', compact('written'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WrittenAssessment $written)
    {
        $courses = courseByModule('written');
        $written->load(['courses', 'chapter', 'lesson']);

        return view('backend.written-assessment.edit', compact('written', 'courses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WrittenAssessment $written)
    {
        // Validate request
        $validator = $this->validateWrittenAssessment($request, true);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $input = $validator->validated();
        $courseIds = $input['course_ids'];
        unset($input['course_ids']);

        // Set default isPaid value
        $input['isPaid'] = $input['isPaid'] ?? 0;

        try {
            DB::beginTransaction();

            // Update written assessment
            $written->update($input);

            // Sync courses
            $written->courses()->sync($courseIds);

            DB::commit();

            return redirect()->back()
                ->with('success', 'Written Assessment updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Written Assessment Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update written assessment. Please try again.']);
        }
    }

    /**
     * Get data for specific course
     */
    public function getData(Request $request)
    {
        $slug = $request->input('slug');

        if (empty($slug)) {
            return response()->json([
                'success' => false,
                'message' => 'Course not selected'
            ], 400);
        }

        // Find course
        $course = Course::select('id', 'slug')
            ->where('slug', $slug)
            ->first();

        if (!$course) {
            return response()->json([
                'success' => false,
                'html' => '',
                'message' => 'Course not found'
            ], 404);
        }

        try {
            // Get written assessments for this course
            $writtenassessments = WrittenAssessment::whereHas('courses', function($query) use ($course) {
                    $query->where('course_id', $course->id);
                })
                ->with(['chapter', 'lesson', 'courses'])
                ->orderBy('id', 'DESC')
                ->get();

            // Render HTML
            $html = view('backend.includes.written-assessment_row', compact('writtenassessments'))
                ->render();

            return response()->json([
                'success' => true,
                'html' => $html,
                'count' => $writtenassessments->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Get Written Assessment Data Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load data',
                'html' => ''
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WrittenAssessment $written)
    {
        try {
            DB::beginTransaction();

            // Delete associated image if exists
            if ($written->image) {
                $this->deleteFile($written->image, 'note');
            }

            // Detach courses relationship
            $written->courses()->detach();

            // Delete written assessment
            $written->delete();

            DB::commit();

            return redirect()
                ->route('admin.writtenassessments.index')
                ->with('success', 'Written Assessment deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Written Assessment Deletion Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to delete written assessment. Please try again.');
        }
    }

    /**
     * Toggle status of the resource.
     */
    public function status(WrittenAssessment $written)
    {
        try {
            $newStatus = $written->status == 1 ? 2 : 1;

            $written->update(['status' => $newStatus]);

            return redirect()->back()
                ->with('success', 'Status updated successfully');

        } catch (\Exception $e) {
            Log::error('Written Assessment Status Update Error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Validation rules for written assessment
     *
     * @param Request $request
     * @param bool $isUpdate
     * @return \Illuminate\Contracts\Validation\Validator
     */
    private function validateWrittenAssessment(Request $request, bool $isUpdate = false)
    {
        $rules = [
            'course_ids'        => 'required|array|min:1',
            'course_ids.*'      => 'required|exists:courses,id',
            'chapter_id'        => 'required|exists:chapters,id',
            'lesson_id'         => 'nullable|exists:lessons,id',
            'isPaid'            => 'nullable|boolean'
        ];

        // Add status validation for update
        if ($isUpdate) {
            $rules['status'] = 'required|numeric|in:1,2';
        }

        $messages = [
            'course_ids.required'       => 'Please select at least one course',
            'course_ids.*.exists'       => 'Selected course is invalid',
            'chapter_id.required'       => 'Chapter is required',
            'chapter_id.exists'         => 'Selected chapter is invalid',
            'lesson_id.exists'          => 'Selected lesson is invalid',
            'status.in'                 => 'Invalid status value',
        ];

        return Validator::make($request->all(), $rules, $messages);
    }



    public function storeQuestionGroup(Request $request, $written)
    {
        $request->validate([
            'questions'          => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answer'   => 'required|string',
        ]);

        WrittenAssessmentQuestion::create([
            'written_assessment_id' => $written,
            'questions'             => json_encode($request->questions),
        ]);

        return redirect()->route('admin.writtenassessments.show', $written)
            ->with('success', 'Question group added.');
    }

    public function updateQuestionGroup(Request $request, $questionGroup)
    {
        $group = WrittenAssessmentQuestion::findOrFail($questionGroup);
        $writtenId = $group->written_assessment_id;

        $questions = $request->input('questions', []);

        if (empty($questions)) {
            $group->delete();
            return redirect()->route('admin.writtenassessments.show', $writtenId)
                ->with('success', 'Question group deleted as it had no questions remaining.');
        }

        $request->validate([
            'questions'            => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.answer'   => 'required|string',
        ]);

        $group->update([
            'questions' => json_encode(array_values($questions)),
        ]);

        return redirect()->route('admin.writtenassessments.show', $writtenId)
            ->with('success', 'Question group updated.');
    }

    public function destroyQuestionGroup($questionGroup)
    {
        $group = WrittenAssessmentQuestion::findOrFail($questionGroup);
        $writtenId = $group->written_assessment_id;
        $group->delete();

        return redirect()->route('admin.writtenassessments.show', $writtenId)
            ->with('success', 'Question group deleted.');
    }
}
