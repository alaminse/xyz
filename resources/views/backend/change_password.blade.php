@extends('layouts.backend')
@section('title', 'Password Change')
@section('content')
<div class="x_panel">
    <div class="x_title">
      <h2>Password Change</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('update.password', Auth::id()) }}" method="post">
            @csrf
            @include('backend.includes.message')
            <div class="row">
                <div class="col-sm-12 col-md-3"></div>
                <div class="col-sm-12 col-md-6">
                    <div class="mb-3">
                        <label>Old Password<span class="text-danger">*</span></label>
                        <input type="password" name="old_password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>New Password<span class="text-danger">*</span></label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Confirm New Password<span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>

                </div>
                <div class="col-sm-12 col-md-3"></div>
            </div>
        </form>
    </div>
</div>
@endsection
