@extends('layouts.backend')
@section('title', 'Profile')
@section('content')
<div class="x_panel">
    <div class="x_title">
      <h2>Profile Update</h2>
      <div class="clearfix"></div>
    </div>
    <div class="x_content">
        <form action="{{ route('admin.update.profile', Auth::id()) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                @include('backend.includes.message')

                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Name<span class=" text-danger">*</span></label>
                    <input class="form-control" name="name" value="{{$user->name}}" required/>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Last Name</label>
                    <input class="form-control" name="lname" value="{{$user->profile?->lname}}"/>
                </div>

                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Email<span class=" text-danger">*</span></label>
                    <input class="form-control" name="email" class='email' value="{{$user->email}}" type="email" required/>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Phone</label>
                    <input class="form-control" name="phone" class='phone' value="{{$user->profile?->phone}}" type="text" />
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <div class="mb-3">
                        <label class="label-align">dob</label>
                        <input class="form-control" name="dob" class='dob' value="{{$user->profile?->dob}}" type="date" />
                    </div>

                    <div class="mb-3">
                        <label class="label-align">Blood Group</label>
                        <select name="blood_group" id="blood_group" class="form-control">
                            {!! generateBloodGroupOptions(old('blood_group', $user->profile?->blood_group ?? null)) !!}
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="label-align">Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            {!! generateGenderOptions(old('gender', $user->profile?->gender ?? null)) !!}
                        </select>
                    </div>
                </div>
                <div class="col-sm-12 col-md-6 mb-3">
                    <label class="label-align">Image</label>
                    <input type="file" name="photo" id="photo" class="form-control">
                    <img id="preview-image" src="{{ getImageUrl($user->profile?->photo) }}" alt="Image Preview" style="max-width: 150px; height: 150px; margin-top: 10px;">
                </div>
                <div class="col-12 mt-3">
                    <button type='submit' class="btn btn-warning text-light">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#photo').on('change', function () {
                let reader = new FileReader();
                reader.onload = (e) => {
                    $('#preview-image').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });
        });
    </script>
@endpush
@endsection
