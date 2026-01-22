@extends('layouts.backend')
@section('title', 'Chapters')
@section('css')
    <link href="{{ asset('backend/vendors/select2/dist/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>Chapters <small>
            </small>
        </h2>

        <ul class="nav navbar-right panel_toolbox">
          <li><button class="btn btn-sm btn-success " type="button" data-toggle="modal" data-target=".bs-chapter-modal-lg" ><i class="fa fa-plus"></i> Add Chapter</button>
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
                                <th>Chapter</th>
                                <th>Lesson</th>
                                <th>Status</th>
                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($chapters as $key => $chapter)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $chapter->name }}</td>
                                <td>
                                    @if ($chapter->lessons !== null && count($chapter->lessons) > 0)
                                        @foreach ($chapter->lessons as $lesson)
                                        <span class="badge badge-success">{{$lesson->name}}</span>
                                        @endforeach
                                    @else
                                        ---
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.chapters.status', $chapter->id ) }}" class="btn btn-{{ $chapter->status == 1 ? 'success' : ($chapter->status == 3 ? 'danger' : 'warning') }} btn-sm  {{ $chapter->status == 3 ? 'disabled' : ''}}">{{\App\Enums\Status::from($chapter->status)->title()}}</a>
                                </td>
                                <td>
                                    <a class="btn btn-primary" href="{{route('admin.chapters.edit', $chapter->id)}}"> <i class="fa fa-edit"></i></a>
                                    <a class="btn btn-danger {{ $chapter->status == 3 ? 'disabled' : ''}}" onclick="showDeleteConfirmation(event)" href="{{route('admin.chapters.destroy', $chapter->id)}}"><i class="fa fa-trash"></i></a>
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

{{-- Create Modal --}}
<div class="modal fade bs-chapter-modal-lg" id="createModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-sm">

            {{-- Header --}}
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-bold">
                    <i class="fa fa-book mr-1"></i> Chapter Information
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>

            {{-- Form --}}
            <form action="{{ route('admin.chapters.store') }}" method="POST">
                @csrf

                <div class="modal-body px-4">

                    {{-- Chapter Name --}}
                    <div class="form-group">
                        <label class="font-weight-semibold">
                            Chapter Name <span class="text-danger">*</span>
                        </label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Enter chapter name"
                            value="{{ old('name') }}"
                            required
                        >
                    </div>

                    <div class="row">
                        {{-- Lesson --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-semibold">Lesson</label>
                                <select name="lesson_ids[]" class="form-control js-example-basic-multiple" multiple>
                                    @foreach ($lessons as $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Course --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-semibold">Course</label>
                                <select name="course_ids[]" class="form-control js-example-basic-multiple" multiple>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Divider --}}
                    <hr class="my-4">

                    {{-- Features --}}
                    <div class="form-group">
                        <label class="font-weight-bold mb-3 d-block">
                            <i class="fa fa-cogs mr-1"></i> Available Features
                        </label>

                        <div class="card border-0 bg-light">
                            <div class="card-body py-3">
                                <div class="row">

                                    @php
                                        $features = [
                                            'sba'             => 'SBA',
                                            'note'            => 'Note',
                                            'mcq'             => 'MCQ',
                                            'flush'           => 'Flush',
                                            'videos'          => 'Videos',
                                            'ospe'            => 'OSPE',
                                            'written'         => 'Written',
                                            'mock_viva'       => 'Mock Viva',
                                            'self_assessment' => 'Self Assessment',
                                        ];
                                    @endphp

                                    @foreach($features as $key => $label)
                                        <div class="col-md-4 col-sm-6 mb-2">
                                            <div class="custom-control custom-checkbox">
                                                <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="{{ $key }}"
                                                    name="{{ $key }}"
                                                    value="1"
                                                >
                                                <label class="custom-control-label" for="{{ $key }}">
                                                    {{ $label }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fa fa-save mr-1"></i> Create Chapter
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>



@push('scripts')
    <script src="{{ asset('backend/vendors/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#createModal').on('shown.bs.modal', function () {
                $('.js-example-basic-multiple').select2();
            });
        });
    </script>

    <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
@endpush
@endsection
