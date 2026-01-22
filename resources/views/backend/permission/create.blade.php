@extends('layouts.backend')

@section('title', 'Create Permission')

@section('content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Create Permission <small><a class="btn btn-warning" href="{{ route('admin.permissions.index') }}"> Back</a></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="{{route('admin.permissions.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('backend.includes.message')

                <span class="section">Permission Info</span>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Name<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="name" placeholder="permission-lists" required="required"/>
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
