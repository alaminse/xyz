@extends('layouts.backend')

@section('title', 'Create New Role')

@section('content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Create New Role <small><a class="btn btn-warning" href="{{ route('admin.roles.index') }}"> Back</a></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="{{route('admin.roles.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <span class="section">Role Info</span>
                @include('backend.includes.message')

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Role Name<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="name" placeholder="Role Name" required="required"/>
                    </div>
                </div>
                <div class="field item form-group mt-3">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Permission<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <div class="row">
                            @foreach($permission as $value)
                            <div class="col-sm-12 col-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="permission[]" value="{{ $value->id }}"> {{ $value->name }}
                                    </label>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type='submit' class="btn btn-primary">Create</button>
                            <button type='reset' class="btn btn-success">Reset</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
