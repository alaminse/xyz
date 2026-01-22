@extends('layouts.backend')
@section('title', 'Slider')
@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Slider Management</h2>
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
                                <th>Text</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($sliders as $key => $slider)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $slider->text }}</td>
                                <td>
                                    <img class="img-fluid" id="image_preview" src="{{ getImageUrl($slider->image) }}" alt="Profile Image" accept="image/png, image/jpeg" height="50px" width="200px">
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-info" data-toggle="modal" data-target="#editModal{{ $slider->id }}"><i class="fa fa-edit"></i></button>
                                    <form action="{{route('sliders.destroy', $slider->id)}}" method="post">
                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-sm btn-danger" type="submit" onclick="showDeleteConfirmation(event)" {{ $slider->status == 3 ? 'disabled' : '' }}><i class="fa fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $slider->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                <div class="modal-content">

                                    <div class="modal-header">
                                    <h4 class="modal-title" id="myModalLabel">Information</h4>
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                    </button>
                                    </div>
                                    <form action="{{route('sliders.update', $slider->id)}}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                        <div class="modal-body">
                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Text <span class="required text-danger">*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" name="text" value="{{ $slider->text }}" required="required"/>
                                                </div>
                                            </div>

                                            <div class="field item form-group">
                                                <label class="col-form-label col-md-3 col-sm-3  label-align">Image (1920 × 925)<span class="required text-danger">*</span></label>
                                                <div class="col-md-6 col-sm-6">
                                                    <input class="form-control" type="file" name="image" id="image" onchange="previewImage(this,'image_preview{{ $slider->id }}')" value="{{ old('image') }}"/>
                                                    <br>
                                                    <img class="img-fluid" id="image_preview{{ $slider->id }}" src="{{ getImageUrl($slider->image) }}"
                                                        alt="Profile Image" accept="image/png, image/jpeg" height="285px" width="765px">
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
        <form action="{{route('sliders.store')}}" method="POST" enctype="multipart/form-data">
          @csrf

            <div class="modal-body">
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Text <span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" name="text" placeholder="Enter text" required="required"/>
                    </div>
                </div>
                <div class="field item form-group">
                    <label class="col-form-label col-md-3 col-sm-3  label-align">Image (1920 × 925)<span class="required text-danger">*</span></label>
                    <div class="col-md-6 col-sm-6">
                        <input class="form-control" type="file" name="image" onchange="previewImage(this,'image_preview')" value="{{ old('image') }}"/>
                        <br>
                        <img class="img-fluid" id="image_preview" src="{{ getImageUrl() }}"
                            alt="Profile Image" accept="image/png, image/jpeg" height="285px" width="765px">
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
    <script>
        function previewImage(input, prewiew_id)
        {
            let img = input.files[0];
            if (img) {
                let preview_img = document.getElementById(prewiew_id);

                let picture = new FileReader();
                picture.readAsDataURL(img);
                picture.addEventListener('load', function(event) {
                    preview_img.setAttribute('src', event.target.result);
                    preview_img.style.display = 'block';
                });
            }
        }
    </script>
    <!-- Datatables -->
    <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
@endpush
@endsection
