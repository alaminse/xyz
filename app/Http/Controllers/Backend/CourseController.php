<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseDetails;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Traits\Upload;

class CourseController extends Controller
{
    use Upload;

    /**
     * Display a listing of courses
     */
    public function index()
    {
        $courses = Course::with(['detail', 'assignedUsers'])
            ->orderByDesc('id')
            ->get();

        return view('backend.course.index', compact('courses'));
    }

    /**
     * Show the form for creating a new course
     */
    public function create()
    {
        $instructors = User::whereHas('roles', function ($query) {
            $query->where('name', 'Director');
        })->select('id', 'name', 'email')->get();

        $courses = Course::where('is_pricing', 0)
            ->orderByDesc('id')
            ->select('id', 'name')
            ->get();

        return view('backend.course.create', compact('courses', 'instructors'));
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        $validated = $this->validateCourse($request);

        DB::beginTransaction();
        try {
            // Prepare course data
            $courseData = $this->prepareCourseData($validated, $request);

            // Create course
            $course = Course::create($courseData);

            // Create course details
            $this->createOrUpdateCourseDetails($course->id, $validated);

            // Sync assigned users
            if (!empty($validated['assign_to'])) {
                $course->assignedUsers()->sync($validated['assign_to']);
            }

            DB::commit();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course created successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Course creation failed', [
                'error' => $e->getMessage(),
                'data' => $request->except('banner')
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create course. Please try again.');
        }
    }

    /**
     * Show the form for editing a course
     */
    public function edit(Course $course)
    {
        $course->load(['detail', 'assignedUsers']);

        $instructors = User::whereHas('roles', function ($query) {
            $query->where('name', 'Director');
        })->select('id', 'name', 'email')->get();

        $courses = Course::where('id', '!=', $course->id)
            ->orderByDesc('id')
            ->select('id', 'name')
            ->get();

        return view('backend.course.edit', compact('course', 'courses', 'instructors'));
    }

    /**
     * Update the specified course
     */
    public function update(Request $request, Course $course)
    {
        $validated = $this->validateCourse($request, $course->id);

        DB::beginTransaction();
        try {
            // Prepare course data
            $courseData = $this->prepareCourseData($validated, $request, $course);

            // Update course
            $course->update($courseData);

            // Update or create course details
            $this->createOrUpdateCourseDetails($course->id, $validated);

            // Sync assigned users
            if (!empty($validated['assign_to'])) {
                $course->assignedUsers()->sync($validated['assign_to']);
            } else {
                $course->assignedUsers()->detach();
            }

            DB::commit();

            return redirect()
                ->route('admin.courses.index')
                ->with('success', 'Course updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Course update failed', [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
                'data' => $request->except('banner')
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update course. Please try again.');
        }
    }

    /**
     * Remove the specified course
     */
    public function destroy(Course $course)
    {
        DB::beginTransaction();
        try {
            // Delete banner if exists
            if ($course->banner) {
                $this->deleteFile($course->banner, 'course');
            }

            // Delete course details
            $course->detail()->delete();

            // Detach assigned users
            $course->assignedUsers()->detach();

            // Delete course
            $course->delete();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Course deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Course deletion failed', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to delete course. Please try again.');
        }
    }

    /**
     * Toggle course status
     */
    public function status(Course $course)
    {
        try {
            $newStatus = $course->status == Status::ACTIVE()->value
                ? Status::INACTIVE()->value
                : Status::ACTIVE()->value;

            $course->update(['status' => $newStatus]);

            return redirect()
                ->back()
                ->with('success', 'Status updated successfully');

        } catch (\Exception $e) {
            Log::error('Status update failed', [
                'course_id' => $course->id,
                'error' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Failed to update status. Please try again.');
        }
    }

    /**
     * Validate course request
     */
    private function validateCourse(Request $request, ?int $courseId = null): array
    {
        $rules = [
            'name'              => 'required|string|max:255',
            'parent_id'         => 'nullable|exists:courses,id',
            'details'           => 'nullable|string',
            'description'       => 'nullable|string',
            'status'            => 'nullable|boolean',
            'is_pricing'        => 'nullable|boolean',
            'banner'            => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2400',
        ];

        // Add course details validation rules
        $rules = array_merge($rules, $this->getCourseDetailsValidationRules());

        // Add assignment validation rules
        $rules = array_merge($rules, $this->getAssignmentValidationRules());

        return $request->validate($rules);
    }

    /**
     * Get course details validation rules
     */
    private function getCourseDetailsValidationRules(): array
    {
        return [
            'duration'          => 'nullable|numeric|min:0',
            'type'              => 'nullable|string|max:50',
            'price'             => 'nullable|numeric|min:0',
            'sell_price'        => 'nullable|numeric|min:0|lte:price',
            'sba'               => 'nullable|boolean',
            'note'              => 'nullable|boolean',
            'mcq'               => 'nullable|boolean',
            'flush'             => 'nullable|boolean',
            'written'           => 'nullable|boolean',
            'videos'            => 'nullable|boolean',
            'mock_viva'         => 'nullable|boolean',
            'ospe'              => 'nullable|boolean',
            'self_assessment'   => 'nullable|boolean',
        ];
    }

    /**
     * Get assignment validation rules
     */
    private function getAssignmentValidationRules(): array
    {
        return [
            'assign_to'     => 'nullable|array',
            'assign_to.*'   => 'exists:users,id',
        ];
    }

    /**
     * Prepare course data for creation/update
     */
    private function prepareCourseData(array $validated, Request $request, ?Course $course = null): array
    {
        $data = [
            'name'          => $validated['name'],
            'parent_id'     => $validated['parent_id'] ?? null,
            'details'       => $validated['details'] ?? null,
            'description'   => $validated['description'] ?? null,
            'status'        => $validated['status'] ?? 0,
            'is_pricing'    => $validated['is_pricing'] ?? 0,
        ];

        // Generate slug only for new courses
        if (!$course) {
            $data['slug'] = checkslug('courses');
        }

        // Handle banner upload
        if ($request->hasFile('banner')) {
            // Delete old banner if updating
            if ($course && $course->banner) {
                $this->deleteFile($course->banner, 'course');
            }
            $data['banner'] = $this->uploadFile($request->file('banner'), 'course', 1700, 750);
        }

        return $data;
    }

    /**
     * Create or update course details
     */
    private function createOrUpdateCourseDetails(int $courseId, array $validated): void
    {
        $detailsData = [
            'duration'          => $validated['duration'] ?? null,
            'type'              => $validated['type'] ?? null,
            'price'             => $validated['price'] ?? null,
            'sell_price'        => $validated['sell_price'] ?? null,
            'sba'               => $validated['sba'] ?? 0,
            'note'              => $validated['note'] ?? 0,
            'mcq'               => $validated['mcq'] ?? 0,
            'flush'             => $validated['flush'] ?? 0,
            'written'           => $validated['written'] ?? 0,
            'videos'            => $validated['videos'] ?? 0,
            'mock_viva'         => $validated['mock_viva'] ?? 0,
            'ospe'              => $validated['ospe'] ?? 0,
            'self_assessment'   => $validated['self_assessment'] ?? 0,
        ];

        CourseDetails::updateOrCreate(
            ['course_id' => $courseId],
            $detailsData
        );
    }
}
