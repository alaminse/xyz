@extends('layouts.backend')
@section('title', 'Slider Setting')
@section('content')
    <div class="x_panel">
      <div class="x_title">
        <h2>Slider Setting</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
        <div class="x_content">
            <form action="{{ route('admin.settings.slider.update', $slider->id ?? null) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('backend.includes.message')

                @php
                    $value = [];
                    if ($slider?->value) {
                        $value = json_decode($slider->value);
                    }
                @endphp

                <h4 class="text-dark"><strong>Slider One</strong></h4>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-4">
                        <label class="label-align">Slider One (1920 × 1280)<span class="required text-danger">*</span></label>
                        <input class="form-control" type="file" name="slider1" id="slider1" onchange="previewImage(this,'slider1_preview')" />
                        <div>
                            <img class="img-fluid" id="slider1_preview" src="{{ getImageUrl($value->slider1 ?? '') }}" alt="slider1" accept="image/png, image/jpeg" style="height: 175px; width: 686px">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8">
                        <div>
                            <label class="label-align">Heading<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="heading1" value="{{ $value->heading1 ?? '' }}" />
                        </div>
                        <div class="mt-2">
                            <label class="label-align" for="short_description1">Short Description<span class="required text-danger">*</span></label>
                            <textarea class="form-control w-100" name="short_description1" id="short_description1" cols="30" rows="5">{{ $value->short_description1 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <h4 class="text-dark"><strong>Slider Two</strong></h4>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-4">
                        <label class="label-align">Slider Two (1920 × 1280)<span class="required text-danger">*</span></label>
                        <input class="form-control" type="file" name="slider2" id="slider2" onchange="previewImage(this,'slider2_preview')" />
                        <div>
                            <img class="img-fluid" id="slider2_preview" src="{{ getImageUrl($value->slider2 ?? '') }}" alt="slider2" accept="image/png, image/jpeg" style="height: 175px; width: 686px">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8">
                        <div>
                            <label class="label-align">Heading<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="heading2" value="{{ $value->heading2 ?? '' }}" />
                        </div>
                        <div class="mt-2">
                            <label class="label-align" for="short_description2">Short Description<span class="required text-danger">*</span></label>
                            <textarea class="form-control w-100" name="short_description2" id="short_description2" cols="30" rows="5">{{ $value->short_description2 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <h4 class="text-dark"><strong>Slider Three</strong></h4>
                <div class="row mb-3">
                    <div class="col-sm-12 col-md-4">
                        <label class="label-align">Slider Three (1920 × 1280)<span class="required text-danger">*</span></label>
                        <input class="form-control" type="file" name="slider3" id="slider3" onchange="previewImage(this,'slider3_preview')" />
                        <div>
                            <img class="img-fluid" id="slider3_preview" src="{{ getImageUrl($value->slider3 ?? '') }}" alt="slider3" accept="image/png, image/jpeg" style="height: 175px; width: 686px">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8">
                        <div>
                            <label class="label-align">Heading<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="heading3" value="{{ $value->heading3 ?? '' }}" />
                        </div>
                        <div class="mt-2">
                            <label class="label-align" for="short_description3">Short Description<span class="required text-danger">*</span></label>
                            <textarea class="form-control w-100" name="short_description3" id="short_description3" cols="30" rows="5">{{ $value->short_description3 ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12 mt-3">
                        <button type='submit' class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="x_panel mt-5">
      <div class="x_title">
        <h2>Short Note</h2>
        <ul class="nav navbar-right panel_toolbox">
          <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
          </li>
          <li><a class="close-link"><i class="fa fa-close"></i></a>
          </li>
        </ul>
        <div class="clearfix"></div>
      </div>
        <div class="x_content">
            <form action="{{ route('admin.settings.snote.update', $snote->id ?? null) }}" method="POST" enctype="multipart/form-data">
                @csrf

                @include('backend.includes.message')

                @php
                    $value = [];
                    if ($snote?->value) {
                        $value = json_decode($snote->value);
                    }
                @endphp

                <div class="row mb-3">
                    <div class="col-sm-12 col-md-3">
                        <h5>Short Note One</h5>
                        <div class="div-one">
                            <label class="label-align">Note<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="note1" value="{{ $value->note1 ?? '' }}" />
                        </div>
                        <div  class="div-two">
                            <label class="label-align">Image One (56 × 70)<span class="required text-danger">*</span></label>
                            <input class="form-control" type="file" name="img1" id="img1" onchange="previewImage(this,'img1_preview')" />
                            <img class="img-fluid mt-1" id="img1_preview" src="{{ getImageUrl($value->img1 ?? '') }}" alt="img1" accept="image/png, image/jpeg" style="height: 70px; width: 56px">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <h5>Short Note Two</h5>
                        <div class="div-one">
                            <label class="label-align">Note<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="note2" value="{{ $value->note2 ?? '' }}" />
                        </div>
                        <div  class="div-two">
                            <label class="label-align">Image One (56 × 70)<span class="required text-danger">*</span></label>
                            <input class="form-control" type="file" name="img2" id="img2" onchange="previewImage(this,'img2_preview')" />
                            <img class="img-fluid mt-1" id="img2_preview" src="{{ getImageUrl($value->img2 ?? '') }}" alt="img2" accept="image/png, image/jpeg" style="height: 70px; width: 56px">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <h5>Short Note One</h5>
                        <div class="div-one">
                            <label class="label-align">Note<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="note3" value="{{ $value->note3 ?? '' }}" />
                        </div>
                        <div  class="div-two">
                            <label class="label-align">Image One (56 × 70)<span class="required text-danger">*</span></label>
                            <input class="form-control" type="file" name="img3" id="img3" onchange="previewImage(this,'img3_preview')" />
                            <img class="img-fluid mt-1" id="img3_preview" src="{{ getImageUrl($value->img3 ?? '') }}" alt="img3" accept="image/png, image/jpeg" style="height: 70px; width: 56px">
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-3">
                        <h5>Short Note One</h5>
                        <div class="div-one">
                            <label class="label-align">Note<span class="required text-danger">*</span></label>
                            <input class="form-control" type="text" name="note4" value="{{ $value->note4 ?? '' }}" />
                        </div>
                        <div  class="div-two">
                            <label class="label-align">Image One (56 × 70)<span class="required text-danger">*</span></label>
                            <input class="form-control" type="file" name="img4" id="img4" onchange="previewImage(this,'img4_preview')" />
                            <img class="img-fluid mt-1" id="img4_preview" src="{{ getImageUrl($value->img4 ?? '') }}" alt="img4" accept="image/png, image/jpeg" style="height: 70px; width: 56px">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-12 mt-3">
                        <button type='submit' class="btn btn-warning">Update</button>
                    </div>
                </div>
            </form>
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
@endpush
@endsection
