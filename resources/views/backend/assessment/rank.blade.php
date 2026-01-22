@extends('layouts.backend')
@section('title', 'Rank')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>User Rank <small></small></h2>
        <ul class="nav navbar-right panel_toolbox">
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
                <div class="mb-3" style="display: flex; justify-content: space-between; align-items: center;">
                    <h4>Assessment Name: <strong>{{ $assessment->name }}</strong></h4>
                    <h4>Course Name: <strong>{{ $assessment->getCourseName() }}</strong></h4>
                    <h4>Total Mark: <strong>{{ $assessment->total_marks }}</strong></h4>
                </div>

                <div class="card-box table-responsive">
                    <table id="datatable-responsive" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>User Name</th>
                                <th>Achived Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rank as $key => $p)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $p->getUserName() }}</td>
                                <td>{{ $p->achive_marks }}</td>
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
