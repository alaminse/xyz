<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NoticeController extends Controller
{
    private function rules(): array
    {
        return [
            'message'    => 'required|string',
            'type'       => 'required|in:info,warning,urgent',
            'is_active'  => 'nullable',
            'start_at'   => 'nullable|date',
            'end_at'     => 'nullable|date|after_or_equal:start_at',
        ];
    }

    public function index()
    {
        $notices = Notice::latest()->get();

        return view('backend.notice.index', compact('notices'));
    }

    public function create()
    {
        return view('backend.notice.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');
        $data['created_by'] = Auth::id();

        try {
            Notice::create($data);

            return redirect()->route('admin.notices.index')->with('success', 'Notice created successfully.');
        } catch (\Exception $e) {
            Log::error('Notice Store Failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Something went wrong!');
        }
    }

    public function edit(Notice $notice)
    {
        return view('backend.notice.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $data = $request->validate($this->rules());
        $data['is_active'] = $request->boolean('is_active');

        try {
            $notice->update($data);

            return redirect()->route('admin.notices.index')->with('success', 'Notice updated successfully.');
        } catch (\Exception $e) {
            Log::error('Notice Update Failed', ['error' => $e->getMessage()]);
            return back()->withInput()->with('error', 'Update failed!');
        }
    }

    public function destroy(Notice $notice)
    {
        try {
            $notice->delete();
            return back()->with('success', 'Notice deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Notice Delete Failed', ['error' => $e->getMessage()]);
            return back()->with('error', 'Delete failed!');
        }
    }

    public function status(Notice $notice)
    {
        try {
            $notice->update(['is_active' => ! $notice->is_active]);
            return redirect()->back()->with('success', 'Status Updated Successfully');
        } catch (\Exception $e) {
            Log::error('Notice status update failed', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Failed to update status.');
        }
    }
}
