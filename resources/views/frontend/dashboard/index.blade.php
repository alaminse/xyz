@extends('frontend.dashboard.app')
@section('title', 'Dashboard')
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">Assessment</h5>
                    </div>
                    @if ($assessment)
                        <div class="card mt-2">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <nav aria-label="breadcrumb">
                                        <ol class="breadcrumb">
                                            @if (isset($assessment->assessment))
                                                <li class="breadcrumb-item">
                                                    {{ $assessment->assessment->name }}</li>
                                            @else
                                                <li class="breadcrumb-item">{{ $assessment->name }}
                                                </li>
                                            @endif
                                        </ol>
                                    </nav>
                                    @if (isset($assessment->assessment))
                                        <a href="{{ route('assessments.print', $assessment->slug) }}"
                                            target="__blank" class="btn button-yellow btn-sm">Show</a>
                                    @else
                                        <a href="{{ route('assessments.show', $assessment->slug) }}"
                                            class="btn button-yellow btn-sm">Start</a>
                                    @endif
                                </div>
                                @if (isset($assessment->assessment))
                                    <p>
                                        <span>Total: {{ (int) $assessment->total_marks }} Marks</span>
                                        <span style="color: #117afa; margin-left: 30px">Achive:
                                            {{ $assessment->achive_marks }} Marks</span>
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">SBA</h5>
                    </div>
                    @if ($sba)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                  <li class="breadcrumb-item">{{$sba->getCourseName()}}</li>
                                  <li class="breadcrumb-item">{{$sba->getChapterName()}}</li>
                                  <li class="breadcrumb-item text-secondary" aria-current="page">{{$sba->getLessonName()}}</li>
                                    </ol>
                                </nav>
                                <a href="{{route('sbas.review', $sba->slug)}}" class="btn btn-sm button-yellow">Review</a>
                            </div>
                            @php
                                $progressItem = $sba->total > 0 ? ($sba->current_question_index / $sba->total) * 100 : 0;
                                $progressItemClass = '';

                                if ($progressItem >= 75) {
                                    $progressItemClass = 'bg-success';
                                } elseif ($progressItem >= 50) {
                                    $progressItemClass = 'bg-warning';
                                } elseif ($progressItem >= 25) {
                                    $progressItemClass = 'bg-info';
                                } else {
                                    $progressItemClass = 'bg-danger';
                                }
                            @endphp

                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped {{ $progressItemClass }}" role="progressbar"
                                    style="width: {{ $progressItem }}%;" aria-valuenow="{{ $progressItem }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($progressItem, 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">MCQ</h5>
                    </div>
                    @if ($mcq)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                  <li class="breadcrumb-item">{{$mcq->getCourseName()}}</li>
                                  <li class="breadcrumb-item">{{$mcq->getChapterName()}}</li>
                                  <li class="breadcrumb-item text-secondary" aria-current="page">{{$mcq->getLessonName()}}</li>
                                    </ol>
                                </nav>
                                <a href="{{route('mcqs.review', $mcq->slug)}}" class="btn btn-sm button-yellow">Review</a>
                            </div>
                            @php
                                $progressItem = $mcq->progress > 0 ? ($mcq->progress_cut / $mcq->progress) * 100 : 0;
                                $progressItemClass = '';

                                if ($progressItem >= 75) {
                                    $progressItemClass = 'bg-success';
                                } elseif ($progressItem >= 50) {
                                    $progressItemClass = 'bg-warning';
                                } elseif ($progressItem >= 25) {
                                    $progressItemClass = 'bg-info';
                                } else {
                                    $progressItemClass = 'bg-danger';
                                }
                            @endphp

                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped {{ $progressItemClass }}" role="progressbar"
                                    style="width: {{ $progressItem }}%;" aria-valuenow="{{ $progressItem }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($progressItem, 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title">FLASH CARD</h5>
                    </div>
                    @if ($flash)
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <nav aria-label="breadcrumb">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item">{{ $flash->getCourseName() }}</li>
                                        <li class="breadcrumb-item">{{ $flash->getChapterName() }}</li>
                                        <li class="breadcrumb-item text-secondary" aria-current="page">
                                            {{ $flash->getLessonName() }}</li>
                                    </ol>
                                </nav>

                                <a href="{{ route('flashs.review', $flash->slug) }}" class="btn btn-sm button-yellow">Review</a>
                            </div>
                            @php
                                $progress =
                                    $flash->total > 0 ? ($flash->current_question_index / $flash->total) * 100 : 0;
                                $progressClass = '';

                                if ($progress >= 75) {
                                    $progressClass = 'bg-success';
                                } elseif ($progress >= 50) {
                                    $progressClass = 'bg-warning';
                                } elseif ($progress >= 25) {
                                    $progressClass = 'bg-info';
                                } else {
                                    $progressClass = 'bg-danger';
                                }
                            @endphp

                            <div class="progress mt-2">
                                <div class="progress-bar progress-bar-striped {{ $progressClass }}" role="progressbar"
                                    style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                                    aria-valuemin="0" aria-valuemax="100">
                                    {{ number_format($progress, 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">My Courses</h5>
            <table class="table">
                <thead>
                    <tr> <!-- Changed th to tr -->
                        <th>ITEM</th> <!-- Changed td to th for table headers -->
                        <th>Name</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Price</th>
                        <th>Sell Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr> <!-- Closing tr -->
                </thead>
                <tbody>
                    @foreach ($courses as $course)
                    <tr>
                        <td>
                            <img class="image-preview img-fluid" src="{{ getImageUrl($course->course?->banner) }}" alt="{{ $course->course?->title }}" style="height: 40px; width: 40px;">
                        </td>
                        <td>
                            <div><strong>{{ $course->course?->name }}</strong></div>
                            {{ $course->course?->detail?->duration }} {{ $course->course?->detail?->type }}
                        </td>
                        <td>
                            {{ $course->start_date }}
                        </td>
                        <td>
                            {{ $course->end_date }}
                        </td>
                        <td>
                            {{ $course->price }}
                        </td>
                        <td>
                            {{ $course->sell_price }}
                        </td>
                        <td>
                            <a href="#"
                                class="btn btn-{{ \App\Enums\Status::from($course->status)->message() }} btn-sm  {{ $course->status == 3 ? 'disabled' : '' }}">
                                {{ \App\Enums\Status::from($course->status)->title() }}</a>
                        </td>
                        <td>
                            <a target="__blank" class="btn btn-info btn-sm" href="{{route('courses.invoice', $course->slug)}}">Invoice</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    @endpush
@endsection
