@extends('layouts.backend')

@section('title', 'Note Details')

@section('content')
<div class="col-md-12">
    <div class="x_panel">

        <div class="x_title">
    <h2 class="pull-left">Note Details</h2>

    <ul class="nav navbar-right panel_toolbox">
        <li>
            <a href="{{ route('admin.notes.index') }}" class="btn btn-warning btn-sm text-white">
                <i class="fa fa-arrow-left"></i> Back
            </a>
        </li>
    </ul>

    <div class="clearfix"></div>
</div>


        {{-- Body --}}
        <div class="x_content">

            <div class="row">

                {{-- LEFT : Meta Info --}}
                <div class="col-md-4">

                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">
                            Information
                        </div>
                        <div class="card-body">

                            {{-- Courses --}}
                            <div class="mb-3">
                                <small class="text-muted d-block">Courses</small>
                                @forelse($note->courses as $course)
                                    <span class="badge badge-primary mr-1 mb-1">
                                        {{ $course->name }}
                                    </span>
                                @empty
                                    <span class="text-muted">N/A</span>
                                @endforelse
                            </div>

                            {{-- Chapter --}}
                            <div class="mb-3">
                                <small class="text-muted d-block">Chapter</small>
                                <strong>{{ $note->chapter->name ?? '-' }}</strong>
                            </div>

                            {{-- Lesson --}}
                            <div class="mb-3">
                                <small class="text-muted d-block">Lesson</small>
                                <strong>{{ $note->lesson->name ?? '-' }}</strong>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- RIGHT : Content --}}
                <div class="col-md-8">

                    {{-- Title --}}
                    <div class="card mb-3">
                        <div class="card-header font-weight-bold">
                            Note Title
                        </div>
                        <div class="card-body">
                            {{ $note->title }}
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="card">
                        <div class="card-header font-weight-bold">
                            Description
                        </div>
                        <div class="card-body bg-light">
                            {!! $note->description !!}
                        </div>
                    </div>

                </div>

            </div>

            {{-- Actions --}}
            <div class="text-right mt-4">
                <a href="{{ route('admin.notes.edit', $note->id) }}"
                   class="btn btn-primary">
                    <i class="fa fa-edit"></i> Edit
                </a>

                <a href="{{ route('admin.notes.destroy', $note->id) }}"
                   class="btn btn-danger {{ $note->status == 3 ? 'disabled' : '' }}"
                   onclick="showDeleteConfirmation(event)">
                    <i class="fa fa-trash"></i> Delete
                </a>
            </div>

        </div>
    </div>
</div>
@endsection
