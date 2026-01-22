@extends('frontend.dashboard.app')
@section('title', 'Password Change')
@section('content')
<form action="{{ route('update.password', Auth::id()) }}" method="post">
    @csrf

    <div class="card">
        <div class="card-body">
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
    </div>
    </div>
</form>

@endsection
