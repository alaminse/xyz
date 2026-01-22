@extends('frontend.dashboard.app')
@section('title', 'Dashboard')
@section('css')
<style>
    .table td, .table th {
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-semibold ps-3 pt-3">My Courses</h5>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table align-middle table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width:60px;">Item</th>
                        <th>Course</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Price</th>
                        <th>Sell</th>
                        <th>Status</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($courses as $course)
                        <tr>
                            <td>
                                <img
                                    src="{{ getImageUrl($course->course?->banner) }}"
                                    alt="{{ $course->course?->name }}"
                                    class="rounded object-fit-cover"
                                    style="width:45px;height:45px;"
                                >
                            </td>

                            <td>
                                <div class="fw-semibold">{{ $course->course?->name }}</div>
                                <small class="text-muted">
                                    {{ $course->course?->detail?->duration }}
                                    {{ $course->course?->detail?->type }}
                                </small>
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($course->start_date)->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <span class="text-muted">
                                    {{ \Carbon\Carbon::parse($course->end_date)->format('d M Y') }}
                                </span>
                            </td>

                            <td>
                                <span class="fw-medium">৳{{ number_format($course->price, 2) }}</span>
                            </td>

                            <td>
                                <span class="fw-medium text-success">
                                    ৳{{ number_format($course->sell_price, 2) }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="badge bg-{{ \App\Enums\Status::from($course->status)->message() }}">
                                    {{ \App\Enums\Status::from($course->status)->title() }}
                                </span>
                            </td>

                            <td class="text-end">
                                <a
                                    target="_blank"
                                    href="{{ route('courses.invoice', $course->slug) }}"
                                    class="btn btn-sm btn-outline-primary"
                                >
                                    <i class="bi bi-receipt"></i> Invoice
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                No courses found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
