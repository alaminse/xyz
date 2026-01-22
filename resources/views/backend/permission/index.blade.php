@extends('layouts.backend')
@section('title', 'Permission Lists')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Permission Management <small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="btn btn-sm btn-success text-white" href="{{ route('admin.permissions.create') }}"><i class="fa fa-plus"></i> Add New</a>
          </li>
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
        <div class="x_content">
            <div class="row">
              <div class="col-sm-12">
                <div class="card-box table-responsive">
                    @include('backend.includes.message')
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="10%">No</th>
                                <th width="60%">Name</th>
                                <th width="30%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $key => $permission)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $permission->name }}</td>
                                <td>
                                    <form action="{{route('admin.permissions.destroy', $permission->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <a class="btn btn-primary" href="{{ route('admin.permissions.edit', $permission->id) }}"><i class="fa fa-edit"></i></a>
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
