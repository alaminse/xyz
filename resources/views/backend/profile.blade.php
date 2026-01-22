@extends('layouts.backend')
@section('title', 'Profile')
@section('content')
    <div class="x_panel">
        <div class="x_title">
            <div class="x_title">
                <h2>Profile Info</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-sm btn-success text-light" href="{{ route('admin.edit.profile') }}"><i
                                class="fa fa-edit"></i>
                            Edit Profile</a>
                    </li>
                    <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
        <div class="x_content">
            <div class="row">
                <div class="col-sm-12 col-md-8">
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Name:</label>
                        <p class="form-control-static">{{ $user->name }}</p>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Last Name:</label>
                        <p class="form-control-static">{{ $user->profile?->lname ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Email:</label>
                        <p class="form-control-static">{{ $user->email }}</p>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Phone:</label>
                        <p class="form-control-static">{{ $user->profile?->phone ?? 'N/A' }}</p>
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Date of Birth:</label>
                        <p class="form-control-static">{{ $user->profile?->dob ?? 'N/A' }}</p>
                        <label class="label-align">Blood Group:</label>
                        <p class="form-control-static">{{ $user->profile?->blood_group ?? 'N/A' }}</p>
                        <label class="label-align">Gender:</label>
                        <p class="form-control-static">{{ $user->profile?->gender ?? 'N/A' }}</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4 mb-3">
                    @if ($user->profile?->photo)
                        <img src="{{ getImageUrl($user->profile->photo) }}" alt="Profile Image"
                            style="max-width: 150px; height: 150px; margin-top: 10px;">
                    @else
                        <p class="form-control-static">No Image Available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function() {
                $('#photo').on('change', function() {
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
