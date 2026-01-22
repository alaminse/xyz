<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\Course;
use App\Models\UserAssessmentProgress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function index()
    {
        $courses = courseByModule('self_assessment');

        return view('backend.assessment.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('self_assessment');

        return view('backend.assessment.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateAssessmentRequest($request);

        if (contentExists(\App\Models\Assessment::class, $validated['chapter_id'], $validated['lesson_id'], $validated['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Assessment already exists for this combination.');
        }

        try {
            DB::beginTransaction();

            $this->createAssessments($validated);

            DB::commit();

            return redirect()
                ->route('admin.assessments.index')
                ->with('success', 'Assessment added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    public function edit(Assessment $assessment_course)
    {
        $courses = courseByModule('self_assessment');
        $questionIds = is_string($assessment_course->question_ids)
                        ? json_decode($assessment_course->question_ids, true)
                        : $assessment_course->question_ids;

        $questions = null;
        if (empty($questionIds)) {
            $questions = collect();
        } else {
            $questions = AssessmentQuestion::whereIn('id', $questionIds)
                ->orderByRaw('FIELD(id, '.implode(',', $questionIds).')')
                ->get();
        }

        return view('backend.assessment.edit', compact('assessment_course', 'courses', 'questions'));
    }

    public function show(Assessment $assessment_course)
    {
        $questionIds = is_string($assessment_course->question_ids)
                        ? json_decode($assessment_course->question_ids, true)
                        : $assessment_course->question_ids;

        $questions = null;
        if (empty($questionIds)) {
            $questions = collect();
        } else {
            $questions = AssessmentQuestion::whereIn('id', $questionIds)
                ->orderByRaw('FIELD(id, '.implode(',', $questionIds).')')
                ->get();
        }

        return view('backend.assessment.show', compact('assessment_course', 'questions'));
    }

    public function update(Request $request, $id)
    {
        $validated = $this->validateAssessmentRequest($request);

        if (contentExists(\App\Models\Assessment::class, $validated['chapter_id'], $validated['lesson_id'], $validated['course_ids'], $id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Assessment already exists for this combination.');
        }

        try {
            DB::beginTransaction();

            $assessment = Assessment::findOrFail($id);

            // Update the main assessment record
            $this->updateAssessment($assessment, $validated);

            DB::commit();

            return redirect()
                ->route('admin.assessments.index')
                ->with('success', 'Assessment updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    private function validateAssessmentRequest(Request $request)
    {
        return $request->validate([
            'course_ids' => 'required|array',
            'course_ids.*' => 'exists:courses,id',
            'chapter_id' => 'required|exists:chapters,id',
            'lesson_id' => 'nullable|exists:lessons,id',
            'name' => 'required|string|max:255',
            'time' => 'required|numeric',
            'status' => 'required|integer',
            'start_date_time' => 'nullable|date',
            'end_date_time' => 'nullable|date',
            'isPaid' => 'required|boolean',
        ]);
    }

    private function createQuestions(array $questions)
    {
        $questionIds = [];
        $totalMarks = 0;

        foreach ($questions as $questionData) {
            $question = AssessmentQuestion::create([
                'question_type' => $questionData['type'],
                'mark_per_question' => $questionData['mark_per_question'],
                'minus_mark' => $questionData['minus_mark'],
                'questions' => json_encode($questionData),
                'explanation' => $questionData['explanation'] ?? null,
            ]);

            $questionIds[] = $question->id;
            $totalMarks += $this->calculateQuestionMarks($questionData);
        }

        return [
            'question_ids' => $questionIds,
            'total_marks' => $totalMarks,
        ];
    }

    private function calculateQuestionMarks(array $questionData)
    {
        if ($questionData['type'] === 'mcq') {
            // MCQ has 5 options, so multiply mark per question by 5
            return $questionData['mark_per_question'] * 5;
        }

        // SBA has only one correct answer
        return $questionData['mark_per_question'];
    }

    private function createAssessments(array $validated)
    {
        $assessment = Assessment::create([
            // 'course_ids'        => json_encode($validated['course_ids']),
            'chapter_id' => $validated['chapter_id'],
            'lesson_id' => $validated['lesson_id'] ?? null,
            'name' => $validated['name'],
            'slug' => checkslug('assessments'),
            'start_date_time' => $this->formatDateTime($validated['start_date_time'] ?? null),
            'end_date_time' => $this->formatDateTime($validated['end_date_time'] ?? null),
            'time' => $validated['time'],
            'isPaid' => $validated['isPaid'],
            'status' => $validated['status'],
        ]);

        // ✅ Courses attach করুন pivot table এ
        if (! empty($validated['course_ids'])) {
            $assessment->courses()->attach($validated['course_ids']);
        }

        return $assessment;
    }

    private function updateAssessment($assessment, array $validated)
    {
        $assessment->update([
            // 'course_ids'        => json_encode($validated['course_ids']),
            'chapter_id' => $validated['chapter_id'],
            'lesson_id' => $validated['lesson_id'] ?? null,
            'name' => $validated['name'],
            'start_date_time' => $this->formatDateTime($validated['start_date_time'] ?? null),
            'end_date_time' => $this->formatDateTime($validated['end_date_time'] ?? null),
            'time' => $validated['time'],
            'isPaid' => $validated['isPaid'],
            'status' => $validated['status'],
        ]);

        // ✅ Courses sync করুন (পুরানো remove হবে, নতুন add হবে)
        if (! empty($validated['course_ids'])) {
            $assessment->courses()->sync($validated['course_ids']);
        }

        return $assessment;
    }

    private function formatDateTime($dateTime)
    {
        return $dateTime ? Carbon::parse($dateTime)->format('Y-m-d H:i:s') : null;
    }

    public function status(Assessment $assessment)
    {
        try {
            $assessment->update(['status' => $assessment->status == 1 ? 2 : 1]);

            return redirect()->back()->with('success', 'Status Update successfully!!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function rank(Assessment $assessment)
    {
        $rank = UserAssessmentProgress::with('user')->select('achive_marks', 'assessment_id', 'user_id', 'course_id')->where('assessment_id', $assessment->id)
            ->orderBy('achive_marks', 'desc')
            ->get();

        return view('backend.assessment.rank', compact('rank', 'assessment'));
    }

    /**
     * Get MCQs data for a specific course
     */
    public function getData()
    {
        $slug = request('slug');

        $course = Course::where('slug', $slug)->first();
        if (! $course) {
            return response()->json(['html' => '']);
        }

        try {
            $assessments = $course->assessments()
                ->with(['chapter', 'lesson', 'courses'])
                ->orderBy('id', 'DESC')
                ->get();

            $html = view('backend.includes.assessment_rows', compact('assessments'))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified assessment from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Assessment $assessment)
    {
        DB::beginTransaction();

        try {
            $questionIds = json_decode($assessment->question_ids, true);

            if (is_array($questionIds) && count($questionIds) > 0) {
                AssessmentQuestion::whereIn('id', $questionIds)->delete();
            }

            $assessment->delete();

            DB::commit();

            return redirect()
                ->route('admin.assessments.index')
                ->with('success', 'Assessment and related questions deleted successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->route('admin.assessments.index')
                ->with('error', 'Delete failed: '.$e->getMessage());
        }
    }

    // User Progress

    public function user_progress()
    {
        $courses = courseByModule('self_assessment');

        // $progress = UserAssessmentProgress::latest()->get();
        return view('backend.assessment.user_progress', compact('courses'));
    }

    /**
     * Get MCQs data for a specific course
     */
    public function userProgressData()
    {
        $slug = request('slug');

        $course = Course::where('slug', $slug)->first();
        if (! $course) {
            return response()->json(['html' => '']);
        }

        try {
            $assessments = $course->assessments()
                ->with(['chapter', 'lesson', 'courses'])
                ->withCount(['userProgress' => function ($query) {
                    // Optional: only count users who actually started (e.g., answered at least one question)
                    // $query->where('answered_question', '>', 0);
                }])
                ->orderBy('id', 'DESC')
                ->get();

            $html = view('backend.includes.user_progress_rows', compact('assessments'))->render();

            return response()->json(['html' => $html]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function leaderboard(Assessment $assessment)
    {
        // সকল completed assessments নিয়ে আসা (status = 1)
        // সর্বোচ্চ marks অনুযায়ী descending order এ সাজানো
        // যদি marks সমান হয়, তাহলে যে আগে submit করেছে সে আগে আসবে
        $leaderboard = UserAssessmentProgress::select('id', 'assessment_id', 'user_id', 'slug', 'total_marks', 'achive_marks')
            ->where('assessment_id', $assessment->id)
            ->with('user')
            ->where('status', 1)
            ->orderByRaw('achive_marks DESC')  // সর্বোচ্চ marks প্রথমে
            ->orderBy('created_at', 'asc')      // same marks হলে যে আগে submit করেছে
            ->get();

        // Current logged in user এর rank খুঁজে বের করা (optional for admin)
        $userRank = null;
        if (auth()->check()) {
            $userRank = $leaderboard->firstWhere('user_id', auth()->id());
        }

        // Top 3 winners আলাদা করে নেওয়া
        $topThree = $leaderboard->take(3);

        // Total participants count
        $totalParticipants = $leaderboard->count();

        // Pass count (70% or more)
        $passedCount = $leaderboard->where('achive_marks', '>=', $assessment->total_marks * 0.7)->count();

        return view('backend.assessment.leaderboard', compact(
            'assessment',
            'leaderboard',
            'userRank',
            'topThree',
            'totalParticipants',
            'passedCount'
        ));
    }

    public function userAssessmentDetails($progressId)
    {
        $progress = UserAssessmentProgress::with(['user', 'assessment'])
            ->findOrFail($progressId);

        $assessment = $progress->assessment;

        // Calculate percentage
        $progress->percentage = $assessment->total_marks > 0
            ? round(($progress->achive_marks / $assessment->total_marks) * 100, 2)
            : 0;

        // Decode details if it's JSON string
        $details = is_string($progress->details)
            ? json_decode($progress->details, true)
            : $progress->details;

        return view('backend.assessment.user_details', compact('progress', 'assessment', 'details'));
    }

    /**
     * Store new question
     */
    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'type' => 'required|in:mcq,sba',
            'question' => 'required|string',
            'mark_per_question' => 'required|numeric',
            'minus_mark' => 'required|numeric',
            'option1' => 'required|string',
            'option2' => 'required|string',
            'option3' => 'nullable|string',
            'option4' => 'required|string',
            'option5' => 'nullable|string',
            'answers1' => 'required_if:type,mcq',
            'answers2' => 'required_if:type,mcq',
            'answers3' => 'nullable',
            'answers4' => 'required_if:type,mcq',
            'answers5' => 'nullable',
            'correct_option' => 'required_if:type,sba',
            'explanation' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $assessment = Assessment::findOrFail($validated['assessment_id']);

            // Create question
            $question = AssessmentQuestion::create([
                'question_type' => $validated['type'],
                'mark_per_question' => $validated['mark_per_question'],
                'minus_mark' => $validated['minus_mark'],
                'questions' => json_encode($validated),
                'explanation' => $validated['explanation'] ?? null,
            ]);

            // Update assessment
            $questionIds = json_decode($assessment->question_ids, true) ?? [];
            $questionIds[] = $question->id;

            $newTotalMarks = $assessment->total_marks + $this->calculateQuestionMarks($validated);

            $assessment->update([
                'question_ids' => json_encode($questionIds),
                'total_marks' => $newTotalMarks,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.assessments.show', $assessment->id)
                ->with('success', 'Question added successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update existing question
     */
    public function updateQuestion(Request $request, $questionId)
    {
        $validated = $request->validate([
            'assessment_id' => 'required|exists:assessments,id',
            'type' => 'required|in:mcq,sba',
            'question' => 'required|string',
            'mark_per_question' => 'required|numeric',
            'minus_mark' => 'required|numeric',
            'option1' => 'required|string',
            'option2' => 'required|string',
            'option3' => 'nullable|string',
            'option4' => 'required|string',
            'option5' => 'nullable|string',
            'answers1' => 'required_if:type,mcq',
            'answers2' => 'required_if:type,mcq',
            'answers3' => 'nullable',
            'answers4' => 'required_if:type,mcq',
            'answers5' => 'nullable',
            'correct_option' => 'required_if:type,sba',
            'explanation' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $question = AssessmentQuestion::findOrFail($questionId);
            $assessment = Assessment::findOrFail($validated['assessment_id']);

            // Get old marks
            $oldQuestionData = json_decode($question->questions, true);
            $oldMarks = $this->calculateQuestionMarks($oldQuestionData);

            // Update question
            $question->update([
                'question_type' => $validated['type'],
                'mark_per_question' => $validated['mark_per_question'],
                'minus_mark' => $validated['minus_mark'],
                'questions' => json_encode($validated),
                'explanation' => $validated['explanation'] ?? null,
            ]);

            // Update total marks in assessment
            $newMarks = $this->calculateQuestionMarks($validated);
            $marksDifference = $newMarks - $oldMarks;
            $newTotalMarks = $assessment->total_marks + $marksDifference;
            $assessment->update(['total_marks' => $newTotalMarks]);

            // ✅ Re-calculate marks for all users who attempted this assessment
            $this->recalculateUserAssessments($assessment->id, $questionId);

            DB::commit();

            return redirect()
                ->route('admin.assessments.show', $assessment->id)
                ->with('success', 'Question updated successfully. User marks recalculated.');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Re-calculate marks for all users who attempted this assessment
     */
    private function recalculateUserAssessments($assessmentId, $updatedQuestionId)
    {
        $userAssessments = UserAssessmentProgress::where('assessment_id', $assessmentId)->get();

        foreach ($userAssessments as $userAssessment) {
            $details = json_decode($userAssessment->details, true);
            $selectedOptions = json_decode($userAssessment->selectedOptions, true);

            if (! $details || ! $selectedOptions) {
                continue;
            }

            $achieved_marks = 0;
            $updatedDetails = [];

            // Get updated question data once
            $question = AssessmentQuestion::find($updatedQuestionId);
            if (! $question) {
                continue;
            }
            $questionData = json_decode($question->questions, true);

            foreach ($details as $index => $detail) {
                // শুধু updated question এর জন্য re-calculate করো
                if ($detail['question_id'] == $updatedQuestionId) {

                    // Find user's selected option for this question
                    $userSelection = collect($selectedOptions)->firstWhere('id', $updatedQuestionId);

                    // Update question basic info
                    $detail['mark_per_question'] = $question->mark_per_question;
                    $detail['minus_mark'] = $question->minus_mark;
                    $detail['question_type'] = $question->question_type;
                    $detail['explanation'] = $question->explanation ?? '';

                    if (! $userSelection) {
                        $detail['is_correct'] = false;
                        $detail['marks_obtained'] = 0;
                    } else {
                        // Re-calculate based on question type
                        if ($question->question_type === 'sba') {
                            $correctOption = $questionData['correct_option'] ?? null;
                            $userAnswer = $userSelection['option'] ?? null;

                            // Update options with new correct answer
                            $detail['options']['correct_option'] = $correctOption;
                            $detail['options']['user_option'] = $userAnswer;

                            if (! empty($userAnswer) && $userAnswer === $correctOption) {
                                $detail['is_correct'] = true;
                                $detail['marks_obtained'] = $question->mark_per_question;
                            } else {
                                $detail['is_correct'] = false;
                                $detail['marks_obtained'] = -$question->minus_mark;
                            }

                        } elseif ($question->question_type === 'mcq') {
                            $totalCorrect = 0;
                            $totalWrong = 0;
                            $totalAnswered = 0;
                            $totalAvailableOptions = 0;

                            // Update options with new correct answers
                            foreach (range(1, 5) as $optionIndex) {
                                $optionKey = 'option'.$optionIndex;
                                $answerKey = 'answers'.$optionIndex;

                                if (empty($questionData[$optionKey])) {
                                    continue;
                                }

                                $totalAvailableOptions++;

                                // Update the option text and correct answer
                                $detail['options'][$optionKey] = $questionData[$optionKey];
                                $detail['options'][$answerKey] = $questionData[$answerKey];

                                $userAnswer = $userSelection['options']['option'.$optionIndex] ?? null;
                                $correctAnswer = $questionData[$answerKey] == '1' ? 'true' : 'false';

                                // Keep user's original answer
                                $detail['options']['user_option'.$optionIndex] = $userAnswer;

                                if (isset($userAnswer) && ! empty($userAnswer)) {
                                    $totalAnswered++;
                                    if ($userAnswer == $correctAnswer) {
                                        $totalCorrect++;
                                    } else {
                                        $totalWrong++;
                                    }
                                }
                            }

                            $marksPerOption = $question->mark_per_question ?? 0;
                            $minusMarkPerOption = $question->minus_mark ?? 0;

                            $questionMarks = ($totalCorrect * $marksPerOption) - ($totalWrong * $minusMarkPerOption);

                            $detail['is_correct'] = $totalCorrect == $totalAvailableOptions && $totalWrong == 0;
                            $detail['marks_obtained'] = round($questionMarks, 2);
                            $detail['total_correct'] = $totalCorrect;
                            $detail['total_wrong'] = $totalWrong;
                            $detail['total_answered'] = $totalAnswered;
                            $detail['total_options'] = $totalAvailableOptions;
                            $detail['marks_per_option'] = round($marksPerOption, 2);
                        }
                    }

                    $achieved_marks += $detail['marks_obtained'];

                } else {
                    // অন্য প্রশ্নের জন্য পুরাতন marks add করো
                    $achieved_marks += $detail['marks_obtained'];
                }

                $updatedDetails[] = $detail;
            }

            // Prevent negative total marks
            $achieved_marks = max(0, $achieved_marks);

            // Recalculate percentage
            $assessment = Assessment::find($assessmentId);
            $percentage = $assessment->total_marks > 0
                ? round(($achieved_marks / $assessment->total_marks) * 100, 2)
                : 0;

            // Update user assessment
            $userAssessment->update([
                'total_marks' => $assessment->total_marks,
                'achive_marks' => $achieved_marks,
                'percentage' => $percentage,
                'details' => json_encode($updatedDetails),
            ]);
        }
    }

    /**
     * Delete question
     */
    public function deleteQuestion(Request $request, $questionId)
    {
        try {
            DB::beginTransaction();

            $question = AssessmentQuestion::findOrFail($questionId);
            $assessment = Assessment::findOrFail($request->assessment_id);

            // Calculate marks
            $questionData = json_decode($question->questions, true);
            $marksToRemove = $this->calculateQuestionMarks($questionData);

            // Remove from assessment
            $questionIds = json_decode($assessment->question_ids, true) ?? [];
            $questionIds = array_diff($questionIds, [$questionId]);

            $assessment->update([
                'question_ids' => json_encode(array_values($questionIds)),
                'total_marks' => max(0, $assessment->total_marks - $marksToRemove),
            ]);

            // Delete question
            $question->delete();

            DB::commit();

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
