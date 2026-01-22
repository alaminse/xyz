<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use App\Models\Course;
use App\Models\UserFlashProgress;
use App\Models\UserMcqProgress;
use App\Models\UserSbaProgress;
use App\Models\EnrollUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Traits\Upload;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use Upload;
    public function profile()
    {
        $user = Auth::user();
        $courses = EnrollUser::where('user_id', $user->id)->latest()->get();
        // Query once for all progress data for all courses
        foreach ($courses as $course) {
            // Get flash progress
            $flash = UserFlashProgress::where('user_id', $user->id)
                ->where('course_id', $course->course_id)
                ->selectRaw('SUM(total) as total_sum, SUM(current_question_index) as index_sum, SUM(correct) as correct_sum')
                ->first();
            $progressFlash = 0;
            if($flash != null) {
                $progressFlash = $flash ? ($flash->index_sum > 0 ? ($flash->correct_sum / $flash->index_sum) * 100 : 0) : 0;
            }

            // Get MCQ progress
            $mcq = UserMcqProgress::where('user_id', $user->id)
                ->where('course_id', $course->course_id)
                ->selectRaw('SUM(total) as total_sum, SUM(progress_cut) as cut_sum, SUM(correct) as correct_sum')
                ->first();
            $progressMcq = 0;
            if($mcq != null) {
                $progressMcq = $mcq ? ($mcq->cut_sum > 0 ? ($mcq->correct_sum / $mcq->cut_sum) * 100 : 0) : 0;
            }
            // Get SBA progress
            $sba = UserSbaProgress::where('user_id', $user->id)
                ->where('course_id', $course->course_id)
                ->selectRaw('SUM(total) as total_sum, SUM(current_question_index) as index_sum, SUM(correct) as correct_sum')
                ->first();
            $progressSba = 0;
            if($sba != null) {
                $progressSba = $sba ? ($sba->total_sum > 0 ? ($sba->correct_sum / $sba->total_sum) * 100 : 0) : 0;
            }
            // Combine progress percentages
            $courseProgress = number_format(($progressFlash + $progressMcq + $progressSba) / 3, 2);

            // Add progress to course
            $course['progress'] =  $courseProgress;

        }

        return view('frontend.settings.profile', compact('user', 'courses'));
    }

    public function update_profile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lname' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|digits_between:10,15',
            'dob' => 'nullable|date|before:today',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender' => 'nullable|in:Male,Female',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $user = User::findOrFail($id);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $this->uploadFile($request->photo, 'profile', 300, 300);
        }

        try {
            DB::beginTransaction();

            // Update the User record
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            // Update or Create the Profile record
            Profile::updateOrCreate(
                ['user_id' => $user->id], // Matching condition
                $validated  // Fields to update or insert
            );

            DB::commit();
            return redirect()->back()->with('success', 'Profile Updated Successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function password_change()
    {
        $user = Auth::user();
        return view('frontend.settings.password_change', compact('user'));
    }

    public function update_password(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6|confirmed', // Use 'confirmed'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();
        Auth::logout();

        return redirect()->route('login')->with('success', 'Password updated successfully. Please log in again.');
    }

    public function profile_reset($course)
    {
        if (empty($course)) {
            return redirect()->back()->with('error', 'Something went wrong! Please try again.');
        }
        $auth = Auth::id();
        $cour = Course::where('slug', $course)->first(); 
        if (!$cour) {
            return redirect()->back()->with('error', 'Course not found.');
        }

        DB::beginTransaction();
        try {
            UserFlashProgress::where('user_id', $auth)
                ->where('course_id', $cour->id)
                ->delete();

            UserMcqProgress::where('user_id', $auth)
                ->where('course_id', $cour->id)
                ->delete();

            UserSbaProgress::where('user_id', $auth)
                ->where('course_id', $cour->id)
                ->delete();
            DB::commit();
            return redirect()->back()->with('success', 'Progress for the selected course has been successfully reset.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
