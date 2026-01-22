<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Sba;
use App\Models\SbaQuestion;
use App\Models\Course;
use App\Models\Note;
use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SbaController extends Controller
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
            'note_id'         => 'nullable|exists:notes,id',
            'question'        => 'required|string',
            'option1'         => 'required|string',
            'option2'         => 'required|string',
            'option3'         => 'required|string',
            'option4'         => 'required|string',
            'option5'         => 'required|string',
            'correct_option'  => 'required|string|in:option1,option2,option3,option4,option5',
            'explain'         => 'required|string'
        ];
    }

    public function index()
    {
        $courses = courseByModule('sba');
        return view('backend.sba.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('sba');
        return view('backend.sba.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if (contentExists(\App\Models\Sba::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mcq already exists for this combination.');
        }

        $data['isPaid'] = $request->boolean('isPaid');

        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        DB::beginTransaction();

        try {
            $sba = Sba::create($data);
            $sba->courses()->sync($courseIds);

            DB::commit();

            return redirect()
                ->route('admin.sbas.show', $sba->id)
                ->with('success', 'SBA created successfully. Now add questions.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SBA Store Failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    public function edit(Sba $sba)
    {
        $courses = courseByModule('sba');
        return view('backend.sba.edit', compact('sba', 'courses'));
    }

    public function update(Request $request, Sba $sba)
    {
        $data = $request->validate($this->rules(true));

        if (contentExists(\App\Models\Sba::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'], $sba->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Mcq already exists for this combination.');
        }

        $data['isPaid'] = $request->boolean('isPaid');

        $data['slug']   = checkSlug('sbas');
        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        DB::beginTransaction();

        try {
            $sba->update($data);
            $sba->courses()->sync($courseIds);
            DB::commit();

            return back()->with('success', 'SBA updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SBA Update Failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    public function show(Sba $sba)
    {
        $sba->load('questions');
        return view('backend.sba.show', compact('sba'));
    }

    public function destroy(Sba $sba)
    {
        DB::beginTransaction();

        try {
            $sba->courses()->detach();
            $sba->questions()->delete();
            $sba->delete();
            DB::commit();

            return back()->with('success', 'SBA deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SBA Delete Failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Delete failed!');
        }
    }

    public function status(Sba $sba)
    {
        try {
            $sba->update(['status' => $sba->status == 1 ? 2 : 1]);
            return redirect()->back()->with('success', 'Status Updated Successfully');
        } catch (\Exception $e) {
            Log::error('Error updating SBA status', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    // ============ QUESTION MANAGEMENT ============

    public function storeQuestion(Request $request, Sba $sba)
    {
        $data = $request->validate($this->questionRules());
        $data['sba_id'] = $sba->id;
        $data['slug'] = checkSlug('sba_questions');

        // Check duplicate question
        $exists = SbaQuestion::where('sba_id', $sba->id)
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
            $question = SbaQuestion::create($data);
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

    public function getQuestion(SbaQuestion $question)
    {
        return response()->json([
            'success' => true,
            'question' => $question
        ]);
    }

    public function updateQuestion(Request $request, SbaQuestion $question)
    {
        $data = $request->validate($this->questionRules());

        // Check duplicate (except current)
        $exists = SbaQuestion::where('sba_id', $question->sba_id)
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

    public function destroyQuestion(SbaQuestion $question)
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

    public function checkDuplicate(Request $request, Sba $sba)
    {
        $question = $request->input('question');
        $excludeId = $request->input('exclude_id');

        $query = SbaQuestion::where('sba_id', $sba->id)
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
            Log::error('SBA getNotes error', ['error' => $e->getMessage()]);
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

        $sbas = $course->sbas()
            ->withCount('questions')
            ->with(['chapter:id,name', 'lesson:id,name'])
            ->latest()
            ->get();

        $html = view('backend.includes.sba_rows', compact('sbas'))->render();

        return response()->json(['html' => $html]);
    }
}
