@extends('layouts.backend')
@section('title', 'Social Media')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Social Media Management</h2>
        <ul class="nav navbar-right panel_toolbox">
            <li><button class="btn btn-sm btn-success text-white" type="button" data-toggle="modal" data-target=".bs-example-modal-lg" ><i class="fa fa-plus"></i> Add New</button>
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
                                <th>No</th>
                                <th>Name</th>
                                <th>Url</th>
                                <th>Icon</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($socials as $key => $social)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $social->name }}</td>
                                <td>{{ $social->url }}</td>
                                <td>{{ $social->icon }}</td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $social->id }}"><i class="fa fa-edit"></i></button>
                                    <form action="{{route('socials.destroy', $social->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger" type="submit" onclick="showDeleteConfirmation(event)" {{ $social->status == 3 ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            {{-- Update Modal --}}
                            <div class="modal fade" id="editModal{{ $social->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Information</h4>
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                    </button>
                                    </div>
                                    <form action="{{route('socials.update', $social->id)}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                        <div class="modal-body">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Name <span class="required text-danger">*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" name="name" value="{{ $social->name }}" required="required"/>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Url <span class="required text-danger">*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" name="url" value="{{ $social->url }}" required="required"/>
                                                </div>
                                            </div>
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Icon <span class="required text-danger">*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" name="icon" value="{{ $social->icon }}" required="required"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type='submit' class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
              </div>
            </div>
        </div>
    </div>
</div>

{{-- Create Modal --}}
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">

        <div class="modal-header">
          <h4 class="modal-title" id="myModalLabel">Information</h4>
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
          </button>
        </div>
        <form action="{{route('socials.store')}}" method="POST" enctype="multipart/form-data">
          @csrf
            <div class="modal-body">
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Name <span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="name" placeholder="Enter Name" required="required"/>
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Url <span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="url" placeholder="Enter url" required="required"/>
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Icon <span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="icon" placeholder="Enter icon class" required="required"/>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type='submit' class="btn btn-primary">Create</button>
            </div>
        </form>
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
