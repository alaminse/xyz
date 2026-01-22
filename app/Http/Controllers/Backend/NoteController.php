<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use Illuminate\Database\QueryException;
use App\Http\Controllers\Controller;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
{
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
        $input = Validator::make($request->all(), [
            'course_ids'        => 'nullable|array',
            'course_ids.*'      => 'exists:courses,id',
            'chapter_id'        => 'required|exists:chapters,id',
            'lesson_id'         => 'nullable|exists:lessons,id',
            'title'             => 'required|string|max:500',
            'description'       => 'required|string',
            'isPaid'            => 'nullable|numeric|in:0,1'
        ]);

        if ($input->fails()) {
            return redirect()->back()->withErrors($input)->withInput();
        }

        $data = $input->validated();
        $data['slug'] = checkslug('notes');
        $data['isPaid'] = $data['isPaid'] ?? 0;

        try {
            $note = Note::create($data);
            $note->courses()->sync($data['course_ids']);

            return redirect()->route('admin.notes.index')->with('success', 'Created Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(Note $note)
    {
        $note->load('courses');
        return view('backend.note.show', compact('note'));
    }

    public function edit(Note $note)
    {
        $courses = courseByModule('note');
        $note->load('courses');

        return view('backend.note.edit', compact('note', 'courses'));
    }

    public function update(Note $note, Request $request)
    {
        $input = Validator::make($request->all(), [
            'course_ids'        => 'required|array',
            'course_ids.*'      => 'exists:courses,id',
            'chapter_id'        => 'required|exists:chapters,id',
            'lesson_id'         => 'nullable|exists:lessons,id',
            'title'             => 'required|string|max:500',
            'description'       => 'required|string',
            'status'            => 'required|numeric',
            'isPaid'            => 'nullable|numeric|in:0,1'
        ]);

        if ($input->fails()) {
            return redirect()->back()->withErrors($input)->withInput();
        }
        $data = $input->validated();
        $data['isPaid'] = $data['isPaid'] ?? 0;

        try {
            $note->update($data);
            $note->courses()->sync($data['course_ids']);
            return redirect()->back()->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function getData()
    {
        $slug = request('slug');
        if ($slug == null) {
            return response()->json(['msg' => 'Course not selected!!']);
        }

        $course = Course::select('id', 'slug')->where('slug', $slug)->first();
        if ($course == null) {
            return response()->json(['html' => '']);
        }

        $notes = $course->notes()->with(['chapter', 'courses'])->orderBy('id', 'DESC')->get();
        $html = view('backend.includes.note_rows', compact('notes'))->render();

        return response()->json(['html' => $html]);
    }

    public function destroy($id)
    {
        try {
            $note = Note::findOrFail($id);

            // Optional: Check if note has relationships before deleting
            // This gives you more control over the error message
            if ($note->courses()->count() > 0) {
                return redirect()
                    ->route('admin.notes.index')
                    ->with('error', 'Cannot delete this note because it is associated with ' . $note->courses()->count() . ' course(s). Please remove the course associations first.');
            }

            // Attempt to delete
            $note->delete();

            return redirect()
                ->route('admin.notes.index')
                ->with('success', 'Note deleted successfully!');

        } catch (QueryException $e) {
            // Check if it's a foreign key constraint error
            if ($e->getCode() === '23000') {
                return redirect()
                    ->route('admin.notes.index')
                    ->with('error', 'Cannot delete this note because it is currently being used in courses or other modules. Please remove all associations first.');
            }

            // Handle other database errors
            return redirect()
                ->route('admin.notes.index')
                ->with('error', 'An error occurred while deleting the note. Please try again.');

        } catch (\Exception $e) {
            // Handle any other unexpected errors
            return redirect()
                ->route('admin.notes.index')
                ->with('error', 'An unexpected error occurred. Please contact support if this persists.');
        }
    }

    public function status(Note $note)
    {
        $update = $note->update(['status' => $note->status == 1 ? 2 : 1]);
        if (!$update)
            return redirect()->back()->with('error', 'Not Updated! Try Again!!!');

        return redirect()->back()->with('success', 'Status Updated Successfully');
    }
}
