@extends('frontend.dashboard.app')
@section('title', 'Profile Update')
@section('content')
    <form action="{{ route('update.profile', Auth::id()) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-body">
                <div class="row">
                    @include('backend.includes.message')

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Name<span class=" text-danger">*</span></label>
                        <input class="form-control" name="name" value="{{ $user->name }}" required />
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Last Name</label>
                        <input class="form-control" name="lname" value="{{ $user->profile?->lname }}" />
                    </div>

                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Email<span class=" text-danger">*</span></label>
                        <input class="form-control" name="email" class='email' value="{{ $user->email }}" type="email"
                            required />
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <label class="label-align">Phone</label>
                        <input class="form-control" name="phone" class='phone' value="{{ $user->profile?->phone }}"
                            type="text" />
                    </div>
                    <div class="col-sm-12 col-md-6 mb-3">
                        <div class="mb-3">
                            <label class="label-align">dob</label>
                            <input class="form-control" name="dob" class='dob' value="{{ $user->profile?->dob }}"
                                type="date" />
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
                        <img id="preview-image" src="{{ getImageUrl($user->profile?->photo) }}" alt="Image Preview"
                            style="max-width: 150px; height: 150px; margin-top: 10px;">
                    </div>
                    <div class="col-12 mt-3">
                        <button type='submit' class="btn btn-warning text-light">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card mt-3">
        <div class="card-body">
            <h2>User Progress</h2>

            @foreach ($courses as $course)
                <div class="card mt-2">
                    <div class="card-body recent-card">
                        @php
                            $progressItem = $course->progress ?? 0;
                            $progressItemClass = '';

                            if ($progressItem >= 75) {
                                $progressItemClass = 'bg-success';
                            } elseif ($progressItem >= 50) {
                                $progressItemClass = 'bg-warning';
                            } elseif ($progressItem >= 25) {
                                $progressItemClass = 'bg-info';
                            } else {
                                $progressItemClass = 'bg-danger';
                            }
                        @endphp

                        <div class="d-flex align-items-center mt-2">
                            <div class="flex-grow-1">
                                <!-- Course Name -->
                                <h6 class="mb-2">{{ $course->course?->name }}</h6>

                                <!-- Progress Bar -->
                                <div class="progress">
                                    <div class="progress-bar progress-bar-striped {{ $progressItemClass }}"
                                        role="progressbar" style="width: {{ $progressItem }}%;"
                                        aria-valuenow="{{ $progressItem }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ number_format($progressItem, 0) }}%
                                    </div>
                                </div>
                            </div>
                            <!-- Reset Button -->
                            <a href="{{ route('profile.reset', $course->course?->slug) }}"
                                class="btn btn-sm button-yellow ms-2"
                                style="{{$progressItem <= 0 ? 'pointer-events: none;' : ''}}">
                                Reset
                             </a>                             
                        </div>
                    </div>
                </div>
            @endforeach
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
