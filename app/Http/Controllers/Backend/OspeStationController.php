<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use App\Traits\Upload;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\OspeQuestion;
use App\Models\OspeStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OspeStationController extends Controller
{
    use Upload;

    /**
     * Shared validation rules for store and update
     */
    private function validationRules($isUpdate = false)
    {
        return [
            'course_ids'                        => 'required|array',
            'course_ids.*'                      => 'exists:courses,id',
            'chapter_id'                        => 'required|exists:chapters,id',
            'lesson_id'                         => 'nullable|exists:lessons,id',
            'note_id'                           => 'nullable|exists:notes,id',
            'status'                            => 'required|in:0,1,2',
            'isPaid'                            => 'nullable|in:0,1'
        ];
    }

    /**
     * Process and save question groups with images
     */
    private function processQuestions(OspeStation $ospe, array $questions)
    {
        foreach ($questions as $groupIndex => $group) {
            $imagePath = null;

            // Handle image upload
            if (request()->hasFile("questions.$groupIndex.image")) {
                $file = request()->file("questions.$groupIndex.image");
                $imagePath = $this->uploadFile($file, 'ospe');
            }

            // Create question entry
            if (isset($group['questions']) && is_array($group['questions'])) {
                OspeQuestion::create([
                    'ospe_station_id' => $ospe->id,
                    'image'           => $imagePath,
                    'questions'       => json_encode($group['questions'])
                ]);
            }
        }
    }

    /**
     * Save or update OSPE station
     */
    private function saveOspeStation(OspeStation $ospe = null, array $validated, array $courseIds)
    {
        DB::beginTransaction();

        try {
            // Create or update OspeStation
            if ($ospe) {
                $ospe->update($validated);
            } else {
                $validated['slug'] = checkslug('ospe_stations');
                $ospe = OspeStation::create($validated);
            }

            // ✅ Sync courses without timestamps
            $ospe->courses()->sync($courseIds, false);
            DB::commit();
            return $ospe;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Delete image file if exists
     */
    private function deleteImageFile($imagePath)
    {
        if ($imagePath && file_exists(public_path($imagePath))) {
            unlink(public_path($imagePath));
        }
    }

    public function index()
    {
        $courses = courseByModule('ospe');
        return view('backend.ospe-station.index', compact('courses'));
    }

    public function create()
    {
        $courses = courseByModule('ospe');
        return view('backend.ospe-station.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validationRules());

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (contentExists(\App\Models\OspeStation::class, $validated['chapter_id'], $validated['lesson_id'], $validated['course_ids'])) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ospe Station already exists for this combination.');
        }

        $courseIds = $validated['course_ids'];
        unset($validated['course_ids']);

        try {
            $this->saveOspeStation(null, $validated, $courseIds);
            return redirect()->route('admin.ospestations.index')->with('success', 'Added successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(OspeStation $ospe)
    {
        return view('backend.ospe-station.show', compact('ospe'));
    }

    public function edit(OspeStation $ospe)
    {
        $courses = courseByModule('ospe');
        return view('backend.ospe-station.edit', compact('ospe', 'courses'));
    }

    public function update(Request $request, OspeStation $ospe)
    {
        $validator = Validator::make($request->all(), $this->validationRules(true));

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        if (contentExists(\App\Models\OspeStation::class, $validated['chapter_id'], $validated['lesson_id'], $validated['course_ids'], $ospe->id)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ospe Station already exists for this combination.');
        }

        $courseIds = $validated['course_ids'];
        unset($validated['course_ids']);

        try {
            $this->saveOspeStation($ospe, $validated, $courseIds);
            return redirect()->route('admin.ospestations.index')->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function questionUpdate(Request $request, OspeQuestion $question)
    {
        $validator = Validator::make($request->all(), [
            'questions'                         => 'required|array|min:1',
            'questions.*.question'              => 'nullable|string',
            'questions.*.answer'                => 'nullable|string',
            'questions.*.questions'             => 'nullable|array|min:1',
            'questions.*.questions.*.question'  => 'required_with:questions.*.questions|string',
            'questions.*.questions.*.answer'    => 'required_with:questions.*.questions|string',
            'image'                             => 'nullable|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            DB::beginTransaction();

            $updateData = [
                'questions' => json_encode(array_values($validated['questions']))
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imagePath = $this->uploadFile($file, 'ospe');

                // Delete old image
                $this->deleteImageFile($question->image);

                $updateData['image'] = $imagePath;
            }

            $question->update($updateData);

            DB::commit();
            return redirect()->back()->with('success', 'Updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function questionDelete(OspeQuestion $question)
    {
        try {
            $this->deleteImageFile($question->image);
            $question->delete();

            return redirect()->back()->with('success', 'Delete Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Delete Failed: ' . $e->getMessage());
        }
    }

    public function destroy(OspeStation $ospe)
    {
        DB::beginTransaction();

        try {
            // Delete all questions and their images
            foreach ($ospe->questions as $question) {
                $this->deleteImageFile($question->image);
                $question->delete();
            }

            // Detach courses
            $ospe->courses()->detach();

            // Delete OSPE station
            $ospe->delete();

            DB::commit();
            return redirect()->back()->with('success', 'Delete Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.ospestations.index')
                ->with('error', 'Delete Failed: ' . $e->getMessage());
        }
    }

    public function status(OspeStation $ospe)
    {
        try {
            $ospe->update(['status' => $ospe->status == 1 ? 2 : 1]);
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

        $ospestations = OspeStation::whereHas('courses', function ($q) use ($course) {
                $q->where('course_id', $course->id);
            })
            ->with(['chapter', 'lesson'])
            ->orderByDesc('id')
            ->get();

        $html = view('backend.includes.ospe_station_row', compact('ospestations'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Store new question group for existing OSPE station
     */
    public function storeQuestionGroup(Request $request, OspeStation $ospe)
    {
        $validator = Validator::make($request->all(), [
            'image'                  => 'required|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'questions'              => 'required|array|min:1',
            'questions.*.question'   => 'required|string',
            'questions.*.answer'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            DB::beginTransaction();

            // Upload image
            $imagePath = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imagePath = $this->uploadFile($file, 'ospe');
            }

            // Create question group
            OspeQuestion::create([
                'ospe_station_id' => $ospe->id,
                'image'           => $imagePath,
                'questions'       => json_encode(array_values($validated['questions']))
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Question group added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Update existing question group
     */
    public function updateQuestionGroup(Request $request, OspeQuestion $question)
    {
        $validator = Validator::make($request->all(), [
            'image'                  => 'nullable|file|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'questions'              => 'required|array|min:1',
            'questions.*.question'   => 'required|string',
            'questions.*.answer'     => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        try {
            DB::beginTransaction();

            $updateData = [
                'questions' => json_encode(array_values($validated['questions']))
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $imagePath = $this->uploadFile($file, 'ospe');

                // Delete old image
                $this->deleteImageFile($question->image);

                $updateData['image'] = $imagePath;
            }

            $question->update($updateData);

            DB::commit();
            return redirect()->back()->with('success', 'Question group updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
