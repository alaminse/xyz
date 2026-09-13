<?php

namespace App\Http\Controllers\Backend;

use App\Exports\NoteDetailsExport;
use App\Http\Controllers\Controller;
use App\Imports\NoteDetailsImport;
use App\Models\Course;
use App\Models\Note;
use App\Models\NoteDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class NoteController extends Controller
{
    private function rules(bool $update = false): array
    {
        return [
            'course_ids' => 'required|array|min:1',
            'course_ids.*' => 'exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'status' => $update ? 'required|numeric' : 'nullable',
            'isPaid' => 'nullable',
        ];
    }

    private function detailRules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
        ];
    }

    public function index()
    {
        $courses = courseByModule('note');

        return view('backend.note.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('note');

        return view('backend.note.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());

        if (contentExists(Note::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Note already exists for this combination.');
        }

        $data['isPaid'] = $request->boolean('isPaid');
        $data['slug'] = checkSlug('notes');

        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        DB::beginTransaction();

        try {
            $note = Note::create($data);
            $note->courses()->sync($courseIds);

            DB::commit();

            return redirect()
                ->route('admin.notes.show', $note->id)
                ->with('success', 'Note created successfully. Now add sections.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Note Store Failed', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    public function edit(Note $note)
    {
        $note->load('courses');
        $courses = courseByModule('note');

        return view('backend.note.edit', compact('note', 'courses'));
    }

    public function update(Request $request, Note $note)
    {
        $data = $request->validate($this->rules(true));

        if (contentExists(Note::class, $data['chapter_id'], $data['lesson_id'], $data['course_ids'], $note->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Note already exists for this combination.');
        }

        $data['isPaid'] = $request->boolean('isPaid');

        $courseIds = $data['course_ids'];
        unset($data['course_ids']);

        DB::beginTransaction();

        try {
            $note->update($data);
            $note->courses()->sync($courseIds);
            DB::commit();

            return back()->with('success', 'Note updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Note Update Failed', ['error' => $e->getMessage()]);

            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    public function show(Note $note)
    {
        $note->load('details', 'courses', 'chapter', 'lesson');

        return view('backend.note.show', compact('note'));
    }

    public function destroy(Note $note)
    {
        DB::beginTransaction();

        try {
            $note->courses()->detach();
            $note->details()->delete();
            $note->delete();
            DB::commit();

            return back()->with('success', 'Note deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Note Delete Failed', ['error' => $e->getMessage()]);

            return back()->with('error', 'Delete failed!');
        }
    }

    public function status(Note $note)
    {
        try {
            $note->update(['status' => $note->status == 1 ? 2 : 1]);

            return redirect()->back()->with('success', 'Status Updated Successfully');
        } catch (\Exception $e) {
            Log::error('Error updating Note status', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }

    // ============ SECTION (NoteDetail) MANAGEMENT ============

    public function storeDetail(Request $request, Note $note)
    {
        $data = $request->validate($this->detailRules());
        $data['note_id'] = $note->id;
        $data['slug'] = checkSlug('note_details');

        $exists = NoteDetail::where('note_id', $note->id)
            ->where('title', $data['title'])
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A section with this title already exists!',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $detail = NoteDetail::create($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Section added successfully!',
                'detail' => $detail,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Note Detail Store Failed', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Failed to add section!'], 500);
        }
    }

    public function getDetail(NoteDetail $detail)
    {
        return response()->json([
            'success' => true,
            'detail' => $detail,
        ]);
    }

    public function updateDetail(Request $request, NoteDetail $detail)
    {
        $data = $request->validate($this->detailRules());

        $exists = NoteDetail::where('note_id', $detail->note_id)
            ->where('title', $data['title'])
            ->where('id', '!=', $detail->id)
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'A section with this title already exists!',
            ], 422);
        }

        DB::beginTransaction();

        try {
            $detail->update($data);
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Section updated successfully!',
                'detail' => $detail,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Note Detail Update Failed', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Update failed!'], 500);
        }
    }

    public function destroyDetail(NoteDetail $detail)
    {
        try {
            $detail->delete();

            return response()->json([
                'success' => true,
                'message' => 'Section deleted successfully!',
            ]);
        } catch (\Exception $e) {
            Log::error('Note Detail Delete Failed', ['error' => $e->getMessage()]);

            return response()->json(['success' => false, 'message' => 'Delete failed!'], 500);
        }
    }

    public function checkDuplicate(Request $request, Note $note)
    {
        $title = $request->input('title');
        $excludeId = $request->input('exclude_id');

        $query = NoteDetail::where('note_id', $note->id)
            ->where('title', $title);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $exists = $query->first();

        return response()->json([
            'exists' => $exists ? true : false,
            'detail' => $exists,
        ]);
    }

    // ============ EXISTING METHODS ============

    public function getData()
    {
        $slug = request('slug');

        if (! $slug) {
            return response()->json(['msg' => 'Course not selected!']);
        }

        $course = Course::select('id')->where('slug', $slug)->first();
        if (! $course) {
            return response()->json(['html' => '']);
        }

        $notes = $course->notes()
            ->withCount('details')
            ->with(['chapter:id,name', 'lesson:id,name'])
            ->latest()
            ->get();

        $html = view('backend.includes.note_rows', compact('notes'))->render();

        return response()->json(['html' => $html]);
    }

    public function bulkUploadStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new NoteDetailsImport;
        Excel::import($import, $request->file('file'));

        $message = "{$import->inserted} note(s) imported successfully.";

        if ($import->createdSets > 0) {
            $message .= " {$import->createdSets} new Note set(s) were auto-created.";
        }

        if ($import->skippedCount > 0) {
            return back()->with('warning', $message." {$import->skippedCount} row(s) skipped.")
                ->with('skipped', $import->skipped);
        }

        return back()->with('success', $message);
    }

    public function sampleDownload()
    {
        return response()->download(storage_path('app/templates/note_sample.xlsx'));
    }

    public function exportGetChapters(Request $request)
    {
        $request->validate(['course_id' => 'required|exists:courses,id']);
        $course = Course::findOrFail($request->course_id);

        $chapters = $course->chapters()->select('chapters.id', 'chapters.name')->orderBy('chapters.name')->get();

        return response()->json(['success' => true, 'chapters' => $chapters]);
    }

    public function exportGetLessons(Request $request)
    {
        $request->validate(['chapter_id' => 'required|exists:chapters,id']);
        $chapter = \App\Models\Chapter::findOrFail($request->chapter_id);

        $lessons = $chapter->lessons()->select('lessons.id', 'lessons.name')->orderBy('lessons.name')->get();

        return response()->json(['success' => true, 'lessons' => $lessons]);
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'nullable|exists:courses,id',
            'chapter_id' => 'nullable|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
        ]);

        $fileName = 'note-details-'.now()->format('Y-m-d-His').'.xlsx';

        return Excel::download(
            new NoteDetailsExport(
                $validated['course_id'] ?? null,
                $validated['chapter_id'] ?? null,
                $validated['lesson_id'] ?? null
            ),
            $fileName
        );
    }
}
