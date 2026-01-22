<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Upload;
use App\Models\Contactus;
use App\Models\Setting;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class SettingsController extends Controller
{
    use Upload;

    public function logo()
    {
        $logo = Setting::where('title', 'logo')->first();
        return view('backend.settings.logo', compact('logo'));
    }

    public function logo_update(Request $request, Setting $setting = null)
    {

        $validator = Validator::make($request->all(), [
            'text_logo' => 'required|string|max:255',
            'logo'      => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'favicon'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:512'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator = $validator->validated();
        $old = [];
        if($setting) {
            $old = json_decode($setting->value);
        }

        if($request->file('logo'))
        {
            $validator['logo'] = $this->changeLogo($request->file('logo'), 'logo', 686, 175, 'logo');
        } else {
            if(!empty($old->logo)) {
                $validator['logo'] = $old->logo;
            } else {
                $validator['logo'] = '';
            }
        }

        if($request->file('favicon'))
        {
            $validator['favicon'] = $this->changeLogo($request->file('favicon'), 'logo', 32, 32, 'favicon');
        } else {
            if(!empty($old->favicon)) {
                $validator['favicon'] = $old->favicon;
            } else {
                $validator['favicon'] = '';
            }
        }

        $jsonData = json_encode($validator);

        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create([
                'title' => 'logo',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }

    private function changeLogo($file, $path, $width, $hight, $file_name)
    {
        if($file) {
            $filename = $file_name.'.png';
            $upload_path = "uploads/$path";
            $image = Image::make($file)->resize($width, $hight);
            $real_path = public_path($upload_path).'/'.$filename;
            $image->save($real_path);
            return $path.'/'.$filename;
        }
    }

    public function contact()
    {
        $contact = Setting::where('title', 'contact')->first();
        $socials = Setting::where('title', 'socials')->first();
        return view('backend.settings.contact', compact('contact', 'socials'));
    }

    public function contact_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'address'   => 'nullable|string|max:5255',
            'phone'     => 'nullable|string|max:255',
            'email'     => 'nullable|email|max:555',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator = $validator->validated();

        $jsonData = json_encode($validator);

        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create([
                'title' => 'contact',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }

    public function socials_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'facebook'   => 'nullable|string|max:5255',
            'instagram'     => 'nullable|string|max:5255',
            'linkedin'     => 'nullable|string|max:5255'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator = $validator->validated();

        $jsonData = json_encode($validator);

        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create([
                'title' => 'socials',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }

    public function faqs()
    {
        $faqs = Setting::where('title', 'faqs')->first();
        return view('backend.settings.faqs', compact('faqs'));
    }

    public function faqs_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string|min:5|max:255',
            'questions.*.answer' => 'required|string|min:5|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator = $validator->validated();

        $jsonData = json_encode($validator['questions']);

        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create(attributes: [
                'title' => 'faqs',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }
    public function about()
    {
        $about = Setting::where('title', 'about')->first();
        return view('backend.settings.about', compact('about'));
    }

    public function about_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'description'   => 'required|string', // Ensure the description is provided and is a string
            'banner'        => 'required|image|max:5048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator = $validator->validated();


        $old = [];
        if($setting) {
            $old = json_decode($setting->value);
        }

        if($request->file('banner')) {
            $validator['banner'] = $this->uploadFile($request->file('banner'), 'about', 590, 590);
        } else {
            if(!empty($old->banner)) {
                $validator['banner'] = $old->banner;
            } else {
                $validator['banner'] = '';
            }
        }

        $jsonData = json_encode($validator);
        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create(attributes: [
                'title' => 'about',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }

    public function terms()
    {
        $terms = Setting::where('title', 'terms')->first();
        return view('backend.settings.terms', compact('terms'));
    }

    public function terms_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'description'   => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator = $validator->validated();

        if ($setting) {
            $setting->update([
                'value' => $validator
            ]);
        } else {
            Setting::create(attributes: [
                'title' => 'terms',
                'value' => $validator['description']
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }
    public function privacy()
    {
        $privacy = Setting::where('title', 'privacy')->first();
        return view('backend.settings.privacy', compact('privacy'));
    }

    public function privacy_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'description'   => 'required|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validator = $validator->validated();

        if ($setting) {
            $setting->update([
                'value' => $validator
            ]);
        } else {
            Setting::create(attributes: [
                'title' => 'privacy',
                'value' => $validator['description']
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }

    public function slider()
    {
        $slider = Setting::where('title', 'slider')->first();
        $snote = Setting::where('title', 'snote')->first();
        return view('backend.settings.slider', compact('slider', 'snote'));
    }

    public function slider_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'heading1'              => 'nullable|string|max:255',
            'short_description1'    => 'nullable|string|max:500',
            'heading2'              => 'nullable|string|max:255',
            'short_description2'    => 'nullable|string|max:500',
            'heading3'              => 'nullable|string|max:255',
            'short_description3'    => 'nullable|string|max:500',
            'slider1'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4512',
            'slider2'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4048',
            'slider3'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:4512',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator = $validator->validated();
        $old = [];
        if($setting) {
            $old = json_decode($setting->value);
        }

        if($request->file('slider1'))
        {
            $validator['slider1'] = $this->changeLogo($request->file('slider1'), 'slider', 1920, 1280, 'slider1');
        } else {
            if(!empty($old->slider1)) {
                $validator['slider1'] = $old->slider1;
            } else {
                $validator['slider1'] = '';
            }
        }

        if($request->file('slider2'))
        {
            $validator['slider2'] = $this->changeLogo($request->file('slider2'), 'slider', 1920, 1280, 'slider2');
        } else {
            if(!empty($old->slider2)) {
                $validator['slider2'] = $old->slider2;
            } else {
                $validator['slider2'] = '';
            }
        }

        if($request->file('slider3'))
        {
            $validator['slider3'] = $this->changeLogo($request->file('slider3'), 'slider', 1920, 1280, 'slider3');;
        } else {
            if(!empty($old->slider3)) {
                $validator['slider3'] = $old->slider3;
            } else {
                $validator['slider3'] = '';
            }
        }

        $jsonData = json_encode($validator);

        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create([
                'title' => 'slider',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }
    public function snote_update(Request $request, Setting $setting = null)
    {
        $validator = Validator::make($request->all(), [
            'note1'              => 'nullable|string|max:255',
            'note2'              => 'nullable|string|max:255',
            'note3'              => 'nullable|string|max:255',
            'note4'              => 'nullable|string|max:255',
            'img1'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:512',
            'img2'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:548',
            'img3'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:512',
            'img4'               => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:512',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validator = $validator->validated();
        $old = [];
        if($setting) {
            $old = json_decode($setting->value);
        }

        if($request->file('img1'))
        {
            $validator['img1'] = $this->changeLogo($request->file('img1'), 'slider', 1920, 1280, 'img1');
        } else {
            if(!empty($old->img1)) {
                $validator['img1'] = $old->img1;
            } else {
                $validator['img1'] = '';
            }
        }
        if($request->file('img2'))
        {
            $validator['img2'] = $this->changeLogo($request->file('img2'), 'slider', 1920, 1280, 'img2');
        } else {
            if(!empty($old->img2)) {
                $validator['img2'] = $old->img2;
            } else {
                $validator['img2'] = '';
            }
        }
        if($request->file('img3'))
        {
            $validator['img3'] = $this->changeLogo($request->file('img3'), 'slider', 1920, 1280, 'img3');
        } else {
            if(!empty($old->img3)) {
                $validator['img3'] = $old->img3;
            } else {
                $validator['img3'] = '';
            }
        }
        if($request->file('img4'))
        {
            $validator['img4'] = $this->changeLogo($request->file('img4'), 'slider', 1920, 1280, 'img4');
        } else {
            if(!empty($old->img4)) {
                $validator['img4'] = $old->img4;
            } else {
                $validator['img4'] = '';
            }
        }

        $jsonData = json_encode($validator);

        if ($setting) {
            $setting->update([
                'value' => $jsonData
            ]);
        } else {
            Setting::create([
                'title' => 'snote',
                'value' => $jsonData
            ]);
        }

        return redirect()->back()->with('success', 'Update Successfully.');
    }
}
