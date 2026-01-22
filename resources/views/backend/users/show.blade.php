@extends('layouts.backend')

@section('title', 'Show User')
@section('content')

<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Show User <small><a class="btn btn-warning" href="{{ route('admin.users.index') }}"> Back</a></small></h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                </li>
                <li><a class="close-link"><i class="fa fa-close"></i></a>
                </li>
              </ul>
              <div class="clearfix"></div>
        </div>
        <div class="x_content">
                <span class="section">User Info</span>

                @include('backend.includes.message')

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Name<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" value="{{$user->name}}" readonly/>
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Email<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" class='email' value="{{$user->email}}"  readonly />
                    </div>
                </div>

                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Role<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6 mt-2">
                        @if(!empty($user->getRoleNames()))
                            @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                            @endforeach
                        @endif
                    </div>
                </div>
                <div class="ln_solid">
                    <div class="form-group">
                        <div class="col-md-6 offset-md-3 mt-3">
                            <form action="{{route('admin.users.destroy', $user->id)}}" method="post">
                                @csrf
                                @method('DELETE')

                                <a class="btn btn-info" href="{{ route('admin.users.show', $user->id) }}"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-primary" href="{{ route('admin.users.edit', $user->id) }}"><i class="fa fa-edit"></i></a>
                                <button class="btn btn-danger" type="submit" onclick="showDeleteConfirmation(event)"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

