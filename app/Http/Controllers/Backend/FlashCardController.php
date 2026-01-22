<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Enums\Status;
use Illuminate\Support\Facades\Log;
use App\Models\FlashCardQuestion;
use Illuminate\Support\Str;
use App\Models\Note;
use App\Models\Course;
use App\Models\FlashCard;
use Illuminate\Http\Request;

class FlashCardController extends Controller
{
    public function index()
    {
        $courses = courseByModule('flush');
        return view('backend.flash.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('flush');
        return view('backend.flash.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'course_ids'    => 'required|array',
            'course_ids.*'  => 'exists:courses,id',
            'chapter_id'    => 'required|exists:chapters,id',
            'lesson_id'     => 'required|exists:lessons,id'
        ]);

        if (contentExists(\App\Models\FlashCard::class, $request->chapter_id, $request->lesson_id, $request->course_ids)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Flash Card already exists for this combination.');
        }

        $flashCard = FlashCard::create([
            'chapter_id'    => $request->chapter_id,
            'lesson_id'     => $request->lesson_id,
            'isPaid'        => $request->has('isPaid') ? 1 : 0,
            'status'        => 1,
        ]);

        $flashCard->courses()->sync($request->course_ids);

        return redirect()->route('admin.flashs.show', $flashCard->id)
            ->with('success', 'Flash Card created successfully. Now add questions.');
    }

    public function show(FlashCard $flash)
    {
        $flash->load(['courses', 'chapter', 'lesson', 'questions']);
        return view('backend.flash.show', compact('flash'));
    }

    public function edit(FlashCard $flash)
    {
        $flash->load(['courses', 'chapter', 'lesson']);

        $courses = courseByModule('flush');
        return view('backend.flash.edit', compact('flash', 'courses'));
    }

    public function update(Request $request, FlashCard $flash)
    {
        $request->validate([
            'course_ids'    => 'required|array',
            'course_ids.*'  => 'exists:courses,id',
            'chapter_id'    => 'required|exists:chapters,id',
            'lesson_id'     => 'required|exists:lessons,id'
        ]);

        if (contentExists(\App\Models\FlashCard::class, $request->chapter_id, $request->lesson_id, $request->course_ids, $flash->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Flash Card already exists for this combination.');
        }

        $flash->update([
            'chapter_id'    => $request->chapter_id,
            'lesson_id'     => $request->lesson_id,
            'isPaid'        => $request->has('isPaid') ? 1 : 0,
        ]);

        $flash->courses()->sync($request->course_ids);

        return redirect()->route('admin.flashs.show', $flash->id)
            ->with('success', 'Flash Card updated successfully.');
    }

    public function destroy(FlashCard $flash)
    {
        $flash->delete();
        return redirect()->route('admin.flashs.index')
            ->with('success', 'Flash Card deleted successfully.');
    }

    public function status(FlashCard $flash)
    {
        $flash->update(['status' => !$flash->status]);
        return response()->json(['success' => true, 'status' => $flash->status]);
    }

    // Question Management Methods
    public function storeQuestion(Request $request, FlashCard $flash)
    {
        $request->validate([
            'question'  => 'required|string|max:1000',
            'answer'    => 'required|string',
        ]);

        $question = $flash->questions()->create([
            'question'  => $request->question,
            'answer'    => $request->answer,
            'slug'      => Str::slug($request->question) . '-' . Str::random(6),
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Question added successfully.',
            'question'  => $question
        ]);
    }

    public function getQuestion(FlashCardQuestion $question)
    {
        return response()->json([
            'success'   => true,
            'question'  => $question
        ]);
    }

    public function updateQuestion(Request $request, FlashCardQuestion $question)
    {
        $request->validate([
            'question'  => 'required|string|max:1000',
            'answer'    => 'required|string',
        ]);

        $question->update([
            'question'  => $request->question,
            'answer'    => $request->answer,
        ]);

        return response()->json([
            'success'   => true,
            'message'   => 'Question updated successfully.',
            'question'  => $question
        ]);
    }

    public function destroyQuestion(FlashCardQuestion $question)
    {
        $question->delete();

        return response()->json([
            'success' => true,
            'message' => 'Question deleted successfully.'
        ]);
    }

    public function checkDuplicate(Request $request, FlashCard $flash)
    {
        $question = $request->question;
        $excludeId = $request->exclude_id;

        $exists = FlashCardQuestion::where('flash_card_id', $flash->id)
            ->where('question', 'LIKE', "%{$question}%")
            ->when($excludeId, function ($query) use ($excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->first();

        return response()->json([
            'exists' => $exists ? true : false,
            'question' => $exists
        ]);
    }

    /**
     * Get flashcards data for a specific course
     */
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

        try {
            $flashs = $course->flashCards()
                            ->withCount('questions')
                            ->with(['chapter:id,name', 'lesson:id,name'])
                            ->latest()
                            ->get();

            $html = view('backend.includes.flash_rows', compact('flashs'))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

