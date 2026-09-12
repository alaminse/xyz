@extends('layouts.backend')
@section('title', 'Create Notice')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
@endsection
@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Create Notice</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li><a class="btn btn-warning text-white" href="{{ route('admin.notices.index') }}"> Back</a></li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('backend.includes.message')
            <form action="{{ route('admin.notices.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-sm-12 mb-3">
                        <label for="message" class="form-label">Message <span class="required text-danger">*</span></label>
                        <textarea class="form-control summernote" name="message" rows="4" required>{!! old('message') !!}</textarea>
                        @error('message')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="type" class="form-label">Type <span class="required text-danger">*</span></label>
                        <select name="type" class="form-control" required>
                            <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>Info (blue)</option>
                            <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>Warning (yellow)</option>
                            <option value="urgent" {{ old('type') == 'urgent' ? 'selected' : '' }}>Urgent (red)</option>
                        </select>
                    </div>

                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="start_at" class="form-label">Start At (optional)</label>
                        <input type="datetime-local" class="form-control" name="start_at" value="{{ old('start_at') }}">
                    </div>

                    <div class="col-sm-12 col-md-4 mb-3">
                        <label for="end_at" class="form-label">End At (optional)</label>
                        <input type="datetime-local" class="form-control" name="end_at" value="{{ old('end_at') }}">
                    </div>

                    <div class="col-sm-12 mb-3">
                        <label for="is_active" class="form-label">Active</label>
                        <br>
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create Notice</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    $(document).ready(function () {
        $('.summernote').summernote({ height: 150 });
    });
</script>
@endpush
