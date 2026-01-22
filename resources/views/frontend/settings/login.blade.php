@extends('layouts.frontend')
@section('title', 'Log In')
@section('content')
<div class="d-flex justify-content-center">
    <div class="colsm-12 col-md-4 log-reg-form p-5">
        <form action="">
            <h3 class="text-center">Login To Your Account</h3>
            <div class="mb-3">
                <input type="text" class="form-control" id="username" placeholder="Username">
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" id="password" placeholder="Password">
            </div>
            <div class="mb-3 d-flex justify-content-center">
                <button type="submit">Login</button>
            </div>
        </form>
        <div class="mb-3 links">
            <div class="d-flex">
                <div class="p-2 flex-grow-1">
                    <input type="checkbox" name="" id=""> Remember Me
                </div>
                <div class="p-2">
                    <a href="{{ route('register') }}">Create New Account?</a>
                    <br>
                    <a href="#">Forget Password?</a>
                </div>
            </div>
        </div>
        <div class="mb-3">
            <a href="#" class="btn btn-outline-info login-with">Login With Facebook</a>
        </div>
        <div class="mb-3">
            <a href="#" class="btn btn-outline-danger login-with">Login With Google</a>
        </div>
    </div>
</div>
@endsection
