<?php

use App\Enums\Status;
use App\Models\Assessment;
use App\Models\Chapter;
use App\Models\Course;
use App\Models\EnrollUser;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

if (! function_exists('getImageUrl')) {
    function getImageUrl(?string $path = null): string
    {
        $default = asset('default.png');
        if (! $path) {
            return $default;
        }

        $fullPath = public_path('uploads/'.$path);
        if (! file_exists($fullPath)) {
            return $default;
        }

        return asset('uploads/'.$path);
    }
}

// if (! function_exists('getCourse')) {
//     function getCourse($assessment_id = null)
//     {
//         if (! $assessment_id) {
//             return null;
//         }

//         $assessment = Assessment::with(['course', 'chapter'])
//             ->find($assessment_id);

//         if (! $assessment) {
//             return null;
//         }

//         return [
//             'assessment_name' => $assessment->name,
//             'course_name' => $assessment->course->name ?? 'Unknown Course',
//             'chapter_name' => $assessment->chapter->name ?? 'Unknown Chapter',
//         ];
//     }
// }

if (! function_exists('getProfile')) {
    function getProfile(?string $path = null): string
    {
        if ($path == null) {
            return asset('frontend/assets/profile.jpeg');
        }

        return asset('uploads/'.$path);
    }
}

if (! function_exists('contact_info')) {
    function contact_info(): string
    {
        $setting = Setting::where('title', 'contact')->first();

        return $setting ? $setting->value : '';
    }
}

if (! function_exists('checkslug')) {
    function checkSlug($table)
    {
        do {
            $slug = substr(md5(mt_rand()), 0, 8);
        } while (DB::table($table)->where('slug', $slug)->exists());

        return $slug;
    }
}

if (! function_exists('calculation_day')) {
    function calculation_day($duration, $type)
    {
        $day = 0;
        if ($type == 'day') {
            $day = 1;
        } elseif ($type == 'week') {
            $day = 7;
        } elseif ($type == 'month') {
            $day = 30;
        } elseif ($type == 'year') {
            $day = 365;
        }

        return $day = $day * $duration;
    }
}

if (! function_exists('checkEnrollment')) {
    function checkEnrollment($courseId, $userId = null)
    {
        $userId = $userId ?? Auth::id();

        // Check if user is enrolled
        $enrolled = EnrollUser::where('user_id', $userId)
            ->where('course_id', $courseId)
            ->first();

        if (! $enrolled) {
            return [
                'status' => false,
                'enrollment' => null,
                'message' => 'User not enrolled in this course',
            ];
        }

        $now = now();

        // Check if enrollment hasn't started yet
        if ($enrolled->start_date && $now->lessThan($enrolled->start_date)) {
            return [
                'status' => false,
                'enrollment' => $enrolled,
                'message' => 'Your enrollment will start on '.$enrolled->start_date->format('d M Y'),
            ];
        }

        // Check if enrollment has expired
        if ($enrolled->end_date && $now->greaterThan($enrolled->end_date)) {
            // Update status to expired
            if ($enrolled->status !== 'expired') {
                $enrolled->update(['status' => Status::EXPIRED()]);
            }

            return [
                'status' => false,
                'enrollment' => $enrolled,
                'message' => 'Your enrollment has expired on',
            ];
        }

        // Enrollment is valid
        return [
            'status' => true,
            'enrollment' => $enrolled,
            'message' => 'Enrollment is active',
        ];
    }
}

if (! function_exists('enrolled_courses')) {
    function enrolled_courses()
    {
        $expiredIds = EnrollUser::where('user_id', Auth::id())
            ->where(function ($q) {
                $q->where('start_date', '>', now())
                    ->orWhere('end_date', '<', now());
            })
            ->pluck('course_id')
            ->toArray();

        foreach ($expiredIds as $courseId) {
            checkEnrollment($courseId, Auth::id());
        }

        // 🔒 Guest safety
        if (! Auth::check()) {
            return collect();
        }

        $now = now();

        // ✅ Valid statuses (single source of truth)
        $validStatuses = [
            Status::ACTIVE(),
            Status::FREETRIAL(),
        ];

        return EnrollUser::query()
            ->select(['id', 'course_id', 'status', 'start_date', 'end_date'])
            ->with([
                'course:id,slug,name,status',
                'course.detail:id,course_id,sba,mcq,flush,note,written,videos,mock_viva,ospe,self_assessment',
            ])
            ->where('user_id', Auth::id())
            ->whereIn('status', $validStatuses)

            // 🔹 Course must be ACTIVE or FREETRIAL
            ->whereHas('course', fn ($q) => $q->whereIn('status', $validStatuses)
            )

            // 🔹 Enrollment date logic
            ->where(function ($query) use ($now) {
                $query
                    // ACTIVE → must be within date range
                    ->where(function ($q) use ($now) {
                        $q->where('status', Status::ACTIVE())
                            ->where('start_date', '<=', $now)
                            ->where('end_date', '>=', $now);
                    })
                    // FREETRIAL → no date restriction
                    ->orWhere('status', Status::FREETRIAL());
            })

            ->get()

            // 🔹 Extra safety (skip broken relations)
            ->filter(fn ($enroll) => $enroll->course)

            // 🔹 Transform for sidebar
            ->map(fn ($enroll) => [
                'id' => $enroll->course->id,
                'name' => $enroll->course->name,
                'slug' => $enroll->course->slug,
                'status' => $enroll->course->status,
                'sba' => (bool) $enroll->course->detail?->sba,
                'mcq' => (bool) $enroll->course->detail?->mcq,
                'flush' => (bool) $enroll->course->detail?->flush,
                'note' => (bool) $enroll->course->detail?->note,
                'written' => (bool) $enroll->course->detail?->written,
                'videos' => (bool) $enroll->course->detail?->videos,
                'mock_viva' => (bool) $enroll->course->detail?->mock_viva,
                'ospe' => (bool) $enroll->course->detail?->ospe,
                'self_assessment' => (bool) $enroll->course->detail?->self_assessment,
            ]);
    }
}

if (! function_exists('textLogo')) {
    function textLogo()
    {
        $textLogo = '';
        $logo = Setting::where('title', 'logo')->first();

        if ($logo) {
            $logoData = json_decode($logo->value, true);
            $textLogo = $logoData['text_logo'] ?? '';
        }

        return $textLogo;
    }
}

if (! function_exists('contact')) {
    function contact()
    {
        $contact = '';
        $data = Setting::where('title', 'contact')->first();

        if ($data) {
            $contact = json_decode($data->value, true);
        }

        return $contact;
    }
}

if (! function_exists('socials')) {
    function socials()
    {
        $socials = '';
        $data = Setting::where('title', 'socials')->first();

        if ($data) {
            $socials = json_decode($data->value, true);
        }

        return $socials;
    }
}

if (! function_exists('generateBloodGroupOptions')) {
    function generateBloodGroupOptions($selected = null)
    {
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        $options = '';

        foreach ($bloodGroups as $group) {
            $isSelected = $selected === $group ? 'selected' : '';
            $options .= "<option value=\"{$group}\" {$isSelected}>{$group}</option>";
        }

        return $options;
    }
}

if (! function_exists('generateGenderOptions')) {
    function generateGenderOptions($selected = null)
    {
        $genders = ['Male', 'Female'];
        $options = '';

        foreach ($genders as $gender) {
            $isSelected = $selected === $gender ? 'selected' : '';
            $options .= "<option value=\"{$gender}\" {$isSelected}>{$gender}</option>";
        }

        return $options;
    }
}

if (! function_exists('courseByModule')) {
    function courseByModule(?string $module = null)
    {
        // Return empty collection if no module provided
        if (! $module) {
            return collect();
        }

        $courses = Course::query()
            ->select('id', 'parent_id', 'slug', 'name', 'status')
            ->whereNotNull('parent_id');
            // ->where('is_pricing', 1)
            // ->where('status', Status::ACTIVE())
            // ->whereHas('detail', function ($query) use ($module) {
            //     $query->where($module, 1);
            // });

        // Filter by assigned instructor if user is a director
        if (\Illuminate\Support\Facades\Gate::allows('director')) {
            $instructorId = auth()->id();
            $courses->whereHas('assignedUsers', function ($query) use ($instructorId) {
                $query->where('user_id', $instructorId);
            });
        }

        return $courses->get();
    }
}

if (! function_exists('course_chapters')) {
    /**
     * Get active chapters with active lessons by feature
     *
     * @param  \App\Models\Course  $course
     * @param  string  $feature  (self_assessment|flash|mcq|video etc.)
     * @return \Illuminate\Support\Collection
     */
    function course_chapters($course, string $feature)
    {
        return Chapter::select('chapters.id', 'chapters.slug', 'chapters.name', 'chapters.status')
            ->where('chapters.status', Status::ACTIVE())

            // ✅ Chapter belongs to course
            ->whereHas('courses', function ($q) use ($course) {
                $q->where('courses.id', $course->id);
            })

            // ✅ Chapter has lessons with feature enabled
            ->whereHas('lessons', function ($q) use ($feature) {
                $q->where('lessons.status', Status::ACTIVE())
                  ->where("chapter_lesson.$feature", 1);
            })

            // ✅ Load lessons
            ->with([
                'lessons' => function ($q) use ($feature) {
                    $q->select(
                        'lessons.id',
                        'lessons.slug',
                        'lessons.name',
                        'lessons.status'
                    )
                    ->where('lessons.status', Status::ACTIVE())
                    ->where("chapter_lesson.$feature", 1);
                },
            ])

            ->orderByDesc('chapters.id')
            ->get();
    }
}

if (! function_exists('contentExists')) {
    function contentExists(string $modelClass, int $chapterId, int $lessonId, array $courseIds, ?int $exceptId = null): bool
    {
        if (! class_exists($modelClass)) {
            return false;
        }

        $query = $modelClass::where('chapter_id', $chapterId)
            ->where('lesson_id', $lessonId);

        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }

        $existingContent = $query->whereHas('courses', function ($query) use ($courseIds) {
            $query->whereIn('courses.id', $courseIds);
        })->first();

        if (! $existingContent) {
            return false;
        }

        $existingCourseIds = $existingContent->courses()->pluck('courses.id')->sort()->values()->toArray();
        $requestCourseIds = collect($courseIds)->sort()->values()->toArray();

        return $existingCourseIds === $requestCourseIds;
    }
}
