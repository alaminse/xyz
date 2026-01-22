@extends('layouts.backend')
@section('title', 'Privacy Policy')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
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
    <div class="x_content">
        <form action="{{ route('admin.settings.privacy.update', $privacy->id ?? null) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <span class="section">Privacy Policy</span>
            @include('backend.includes.message')

            <div class="row">
                <div class="col-12 mb-3">
                    <label class="label-align">Privacy Policy <span class="required text-danger">*</span></label>
                    <textarea class="form-control summernote" name="description" cols="30" rows="5">{{ old('description', $privacy->value ?? '') }}</textarea>
                </div>
                <div class="col-12">
                    <button type='submit' class="btn btn-warning">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

        <script>
            function uploadImage(file, summernoteInstance) {
                var formData = new FormData();
                formData.append('image', file);
                let csrfToken = '{{ csrf_token() }}';
                let url = '{{ route('admin.summernote.upload') }}?_token=' + csrfToken;
                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        summernoteInstance.summernote('insertImage', response.url);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            }

            $(`.summernote`).summernote({
                callbacks: {
                    onImageUpload: function(files) {
                        uploadImage(files[0], $(this));
                    }
                }
            });
        </script>
    @endpush
@endsection
