@extends('layouts.backend')
@section('title', 'Contact Info')
@section('content')
    <div class="x_panel">
        <div class="x_title">
            <h2>Site Setting</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
            </ul>
            <div class="clearfix"></div>
        </div>
        @include('backend.includes.message')
        <div class="x_content">
            <form action="{{ route('admin.settings.contact.update', $contact->id ?? null) }}" method="POST">
                @csrf

                @php
                    $value = [];
                    if ($contact?->value) {
                        $value = json_decode($contact->value);
                    }
                @endphp

                <span class="section">Contact Info</span>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Address</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="address"
                            value= "{{ $value->address ?? '' }}" />
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Phone Number </label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="number" name="phone"
                            value="{{ $value->phone ?? '' }}" />
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align"> Email </label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="email" name="email"
                            value="{{ $value->email ?? '' }}" />
                    </div>
                </div>

                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type='submit' class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </div>
            </form>

            <form action="{{ route('admin.settings.socials.update', $socials->id ?? null) }}" method="POST">
                @csrf

                @php
                    $svalue = [];
                    if ($socials?->value) {
                        $svalue = json_decode($socials->value);
                    }
                @endphp

                <span class="section mt-5">Social Info</span>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Facebook <span
                            class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="facebook"
                            value= "{{ $svalue->facebook ?? '' }}" />
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Instagram</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="instagram"
                            value="{{ $svalue->instagram ?? '' }}" />
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3 label-align">Linked In</label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="text" name="linkedin"
                            value="{{ $svalue->linkedin ?? '' }}" />
                    </div>
                </div>

                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <button type='submit' class="btn btn-warning">Update</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection
