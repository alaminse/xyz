@extends('layouts.backend')
@section('title', 'People Query')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>People Query</h2>
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
                                <th>No</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Subject</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($message as $key => $massages)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $massages->name }}</td>
                                <td>{{ $massages->email }}</td>
                                <td>{{ $massages->subject }}</td>
                                <td>{{ $massages->description }}</td>
                                <td>
                                    <a class="btn btn-danger" onclick="showDeleteConfirmation(event)"
                                     href="{{route('message.destroy', $massages->id)}}"><i class="fa fa-trash"></i>
                                    </a>

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

