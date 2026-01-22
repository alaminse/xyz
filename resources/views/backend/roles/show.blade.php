@extends('layouts.backend')

@section('title', 'Role Details')

@section('content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Role Details <small><a class="btn btn-warning" href="{{ route('admin.roles.index') }}"> Back</a></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
        </div>
        <div class="x_content">
                <span class="section">Role Details</span>
                @include('backend.includes.message')

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Role Name</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control"value="{{$role->name}}" disabled/>
                    </div>
                </div>
                <div class="field item form-group mt-3">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Permission</label>
                    <div class="col-md-6 col-sm-6">
                        <div class="row">
                            @if(!empty($rolePermissions))
                                @foreach($rolePermissions as $value)
                                <div class="col-sm-12 col-md-4">
                                    <label>
                                        <h6>{{ $value->name }}</h6>
                                    </label>
                                </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <form action="{{route('admin.roles.destroy', $role->id)}}" method="post">
                                @csrf
                                @method('DELETE')

                                <a class="btn btn-info" href="{{ route('admin.roles.show', $role->id) }}"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-primary" href="{{ route('admin.roles.edit', $role->id) }}"><i class="fa fa-edit"></i></a>
                                <button class="btn btn-danger" type="submit" onclick="showDeleteConfirmation(event)" {{ $role->name == 'Admin' ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
