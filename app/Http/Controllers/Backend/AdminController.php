<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Course;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    use Upload;
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        $data['total_users'] = User::role('user')->count();
        $data['new_users'] = User::whereDate('created_at', Carbon::today())->count();
        $data['assessments'] = Assessment::where('status', 1)->count();
        $data['courses'] = Course::where('status', 1)->count();
        $data['users'] = User::role('user')->latest()->take(30)->get();

        // return $data;
        return view('backend.index', compact('data'));
    }

    public function summernote(Request $request)
    {
        $file_name = '';
        $url = '';

        if ($request->hasFile('image')) {
            $file_name = $this->uploadFile($request->image, 'summernote');
            $url = asset('uploads/' . $file_name);
        }

        return response()->json([
            'file_name' => $file_name,
            'uploaded' => 1,
            'url' => $url,
        ]);
    }

    public function uploadFile($file, $folder)
    {
        $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path("uploads/$folder"), $fileName);
        return "$folder/$fileName";
    }

    public function profile()
    {
        $user = Auth::user();
        return view('backend.profile', compact('user'));
    }
    public function edit_profile()
    {
        $user = Auth::user();
        return view('backend.edit_profile', compact('user'));
    }

    public function update_profile(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'          => 'required|string|max:255',
            'lname'         => 'nullable|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'phone'         => 'nullable|digits_between:10,15',
            'dob'           => 'nullable|date|before:today',
            'blood_group'   => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'gender'        => 'nullable|in:Male,Female',
            'photo'         => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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
                'name'  => $validated['name'],
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

    public function change_password()
    {
        $user = Auth::user();
        return view('backend.change_password', compact('user'));
    }

    public function update_password(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'old_password'  => 'required',
            'password'      => 'required|min:6|confirmed', // Use 'confirmed'
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

        return redirect()->route('admin.login')->with('success', 'Password updated successfully. Please log in again.');
    }
}
