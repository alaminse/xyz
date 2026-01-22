<?php

namespace App\Http\Controllers\Backend;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    function __construct()
    {
         $this->middleware('permission:permission-list|permission-create|permission-edit|permission-update|permission-delete', ['only' => ['index','store']]);
         $this->middleware('permission:permission-create', ['only' => ['create','store']]);
         $this->middleware('permission:permission-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:permission-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $permissions = Permission::orderBy('id','DESC')->get();
        return view('backend.permission.index',compact('permissions'));
    }

    public function create()
    {
        return view('backend.permission.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name'
        ]);

        Permission::create(['name' => $request->input('name')]);

        return redirect()->back()
                        ->with('success','Permission created successfully');
    }

    public function edit($id)
    {
        $permission = Permission::find($id);
        return view('backend.permission.edit',compact('permission'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $Permission = Permission::find($id);
        $Permission->name = $request->input('name');
        $Permission->save();

        return redirect()->back()
                        ->with('success','Permission updated successfully');
    }

    public function destroy($id)
    {
        DB::table("permissions")->where('id',$id)->delete();
        return redirect()->back()
                        ->with('success','Permission deleted successfully');
    }
}
