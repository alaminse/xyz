@extends('layouts.backend')
@section('title', 'Dashboard')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <div class="row row-cols-2 row-cols-md-4">
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-user"></i> Total Users</h2>
                <div class="count green" style="font-size: 44px">{{ $data['total_users'] }}</div>
            </div>
          </div>
        </div>
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-user"></i> New User</h2>
                <div class="count green" style="font-size: 44px">{{ $data['new_users'] }}</div>
            </div>
          </div>
        </div>
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-play"></i> Course</h2>
                <div class="count green" style="font-size: 44px">{{ $data['assessments'] }}</div>
            </div>
          </div>
        </div>
        <div class="col mb-4">
          <div class="card">
            <div class="card-body tile_stats_count text-center">
                <h2><i class="fa fa-tasks"></i> Assessment</h2>
                <div class="count green" style="font-size: 44px">{{ $data['courses'] }}</div>
            </div>
          </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="card-box table-responsive">
                @include('backend.includes.message')
                <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Roles</th>
                            <th width="25%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data['users'] as $key => $user)
                        <tr>
                            <td>{{ ++$key }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                            @if(!empty($user->getRoleNames()))
                                @foreach($user->getRoleNames() as $v)
                                <label class="badge badge-success">{{ $v }}</label>
                                @endforeach
                            @endif
                            </td>
                            <td>
                                <form action="{{route('admin.users.destroy', $user->id)}}" method="post">
                                    @csrf
                                    @method('DELETE')

                                    <a class="btn btn-info" href="{{ route('admin.users.show', $user->id) }}"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-primary" href="{{ route('admin.users.edit', $user->id) }}"><i class="fa fa-edit"></i></a>
                                    <button class="btn btn-danger" type="submit" onclick="showDeleteConfirmation(event)"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@push('scripts')
    <!-- Datatables -->
    <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
@endpush
@endsection
