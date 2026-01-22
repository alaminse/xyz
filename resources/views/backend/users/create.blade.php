@extends('layouts.backend')

@section('title', 'Create New User')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<link href="../" rel="stylesheet">
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Create New User <small><a class="btn btn-warning" href="{{ route('admin.users.index') }}"> Back</a></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
        </div>
        <div class="x_content">
            <form action="{{route('admin.users.store')}}" method="POST" enctype="multipart/form-data">
                @csrf

                <span class="section">Personal Info</span>

                @include('backend.includes.message')

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Name<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="name" placeholder="Enter User Name" required="required"/>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Email<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control email" name="email" required="required" type="email" />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Password<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="password" name="password" id="password" required />

                        <span style="position: absolute;right:15px;top:7px;" onclick="hideshow()" >
                            <i id="slash" class="fa fa-eye-slash"></i>
                            <i id="eye" class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Repeat password<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="password" name="confirm-password" required='required'/>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Role<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <select name="roles[]" class="form-control" required>
                            @foreach ($roles as $role)
                                <option value="{{$role}}">{{$role}}</option>
                            @endforeach
                        </select>
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
@push('scripts')
    <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
    <script>
        function hideshow(){
            var password = document.getElementById("password");
            var slash = document.getElementById("slash");
            var eye = document.getElementById("eye");

            if(password.type === 'password'){
                password.type = "text";
                slash.style.display = "block";
                eye.style.display = "none";
            }
            else{
                password.type = "password";
                slash.style.display = "none";
                eye.style.display = "block";
            }

        }
    </script>
@endpush
@endsection
