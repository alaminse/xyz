<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Mcq;
use App\Models\McqQuestion;
use App\Models\Course;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class McqController extends Controller
{
    private function rules(bool $update = false): array
    {
        return [
            'course_ids'      => 'required|array|min:1',
            'course_ids.*'    => 'exists:courses,id',
            'chapter_id'      => 'required|exists:chapters,id',
            'lesson_id'       => 'nullable|exists:lessons,id',
            'status'          => $update ? 'required|numeric' : 'nullable',
            'isPaid'          => 'nullable',
        ];
    }

    private function questionRules(): array
    {
        return [
            'note_id'   => 'nullable|exists:notes,id',
            'question'  => 'required|string|max:1000',
            'option1'   => 'required|string|max:500',
            'answer1'   => 'required|boolean',
            'option2'   => 'required|string|max:500',
            'answer2'   => 'required|boolean',
            'option3'   => 'required|string|max:500',
            'answer3'   => 'required|boolean',
            'option4'   => 'required|string|max:500',
            'answer4'   => 'required|boolean',
            'option5'   => 'required|string|max:500',
            'answer5'   => 'required|boolean',
            'explain'   => 'required|string|max:50000',
        ];
    }

    public function index()
    {
        $courses = courseByModule('mcq');
        return view('backend.mcq.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('mcq');
        return view('backend.mcq.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if (contentExists(\App\Models\Mcq::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mcq already exists for this combination.');
        }

        $data['isPaid'] = $request->boolean('isPaid');

        $data['slug']   = checkSlug('mcqs');
        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        DB::beginTransaction();

        try {
            $mcq = Mcq::create($data);
            $mcq->courses()->sync($courseIds);

            DB::commit();

            return redirect()
                ->route('admin.mcqs.show', $mcq->id)
                ->with('success', 'MCQ created successfully. Now add questions.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MCQ Store Failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    public function edit(Mcq $mcq)
    {
        $courses = courseByModule('mcq');
        return view('backend.mcq.edit', compact('mcq', 'courses'));
    }

    public function update(Request $request, Mcq $mcq)
    {
        $data = $request->validate($this->rules(true));

        if (contentExists(\App\Models\Mcq::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'], $mcq->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mcq already exists for this combination.');
        }

        $data['isPaid'] = $request->boolean('isPaid');

        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        DB::beginTransaction();

        try {
            $mcq->update($data);
            $mcq->courses()->sync($courseIds);
            DB::commit();

            return back()->with('success', 'MCQ updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MCQ Update Failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    public function show(Mcq $mcq)
    {
        $mcq->load('questions');
        return view('backend.mcq.show', compact('mcq'));
    }

    public function destroy(Mcq $mcq)
    {
        DB::beginTransaction();

        try {
            $mcq->courses()->detach();
            $mcq->questions()->delete();
            $mcq->delete();
            DB::commit();

            return back()->with('success', 'MCQ deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('MCQ Delete Failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Delete failed!');
        }
    }

    public function status(Mcq $mcq)
    {
        try {
            $mcq->update(['status' => $mcq->status == 1 ? 2 : 1]);
            return redirect()->back()->with('success', 'Status Updated Successfully');
        } catch (\Exception $e) {
            Log::error('Error updating MCQ status', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    // ============ QUESTION MANAGEMENT ============

    public function storeQuestion(Request $request, Mcq $mcq)
    {
        $data = $request->validate($this->questionRules());
        $data['mcq_id'] = $mcq->id;
        $data['slug'] = checkSlug('mcq_questions');

        // Validate at least one correct answer

        $hasCorrect = false;
        for ($i = 1; $i <= 5; $i++) {
            if ($data["answer{$i}"]) {
                $hasCorrect = true;
                break;
            }
        }

        if (!$hasCorrect) {
            return response()->json([
                'success' => false,
                'message' => 'At least one answer must be correct!'
            ], 422);
        }

        // Check duplicate question
        $exists = McqQuestion::where('mcq_id', $mcq->id)
            ->where('question', $data['question'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This question already exists!'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $question = McqQuestion::create($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Question added successfully!',
                'question' => $question
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Question Store Failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to add question!'], 500);
        }
    }

    public function getQuestion(McqQuestion $question)
    {
        return response()->json([
            'success' => true,
            'question' => $question
        ]);
    }

    public function updateQuestion(Request $request, McqQuestion $question)
    {
        $data = $request->validate($this->questionRules());

        // Validate at least one correct answer
        $hasCorrect = false;
        for ($i = 1; $i <= 5; $i++) {
            if ($data["answer{$i}"]) {
                $hasCorrect = true;
                break;
            }
        }

        if (!$hasCorrect) {
            return response()->json([
                'success' => false,
                'message' => 'At least one answer must be correct!'
            ], 422);
        }

        // Check duplicate (except current)
        $exists = McqQuestion::where('mcq_id', $question->mcq_id)
            ->where('question', $data['question'])
            ->where('id', '!=', $question->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'This question already exists!'
            ], 422);
        }

        DB::beginTransaction();

        try {
            $question->update($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Question updated successfully!',
                'question' => $question
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Question Update Failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Update failed!'], 500);
        }
    }

    public function destroyQuestion(McqQuestion $question)
    {
        try {
            $question->delete();
            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Question Delete Failed', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Delete failed!'], 500);
        }
    }

    public function checkDuplicate(Request $request, Mcq $mcq)
    {
        $question = $request->input('question');
        $excludeId = $request->input('exclude_id');

        $query = McqQuestion::where('mcq_id', $mcq->id)
            ->where('question', $question);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->first();

        return response()->json([
            'exists' => $exists ? true : false,
            'question' => $exists
        ]);
    }

    // ============ EXISTING METHODS ============

    public function getNotes(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_ids'   => 'required|array|min:1',
                'course_ids.*' => 'exists:courses,id',
                'chapter_id'   => 'nullable|exists:chapters,id',
                'lesson_id'    => 'nullable|exists:lessons,id',
            ]);

            $query = Note::select('notes.id', 'notes.title', 'notes.slug', 'notes.chapter_id', 'notes.lesson_id', 'notes.isPaid')
                ->join('course_note', 'notes.id', '=', 'course_note.note_id')
                ->whereIn('course_note.course_id', $validated['course_ids'])
                ->where('notes.status', Status::ACTIVE())
                ->distinct();

            if (!empty($validated['chapter_id'])) {
                $query->where('notes.chapter_id', $validated['chapter_id']);
            }

            if (!empty($validated['lesson_id'])) {
                $query->where('notes.lesson_id', $validated['lesson_id']);
            }

            $notes = $query->orderBy('notes.title')->get();

            return response()->json([
                'success' => true,
                'notes'   => $notes,
                'count'   => $notes->count()
            ]);
        } catch (\Exception $e) {
            Log::error('MCQ getNotes error', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Failed to load notes'], 500);
        }
    }

    public function getData()
    {
        $slug = request('slug');

        if (!$slug) {
            return response()->json(['msg' => 'Course not selected!']);
        }

        $course = Course::select('id')->where('slug', $slug)->first();
        if (!$course) {
            return response()->json(['html' => '']);
        }

        $mcqs = $course->mcqs()
            ->withCount('questions')
            ->with(['chapter:id,name', 'lesson:id,name'])
            ->latest()
            ->get();

        $html = view('backend.includes.mcq_rows', compact('mcqs'))->render();

        return response()->json(['html' => $html]);
    }
}
