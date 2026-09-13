@extends('layouts.frontend')
@section('title', $notice->title ?? 'Notice')

@section('content')
<section class="notice-detail-header">
    <div class="container">
        <a href="{{ route('notices.index') }}" class="d-inline-flex align-items-center gap-2 mb-3 text-muted text-decoration-none">
            <i class="bi bi-arrow-left"></i> All notices
        </a>
        <span class="notice-type-badge badge-{{ $notice->type }}">
            {{ ucfirst($notice->type) }}
        </span>
        <h1 class="mb-2">{{ $notice->title ?? 'Notice' }}</h1>
        <span class="text-muted">{{ $notice->created_at->format('d M, Y') }}</span>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row gy-5">
            <div class="col-lg-8">
                <div class="notice-detail-body">
                    {!! $notice->message !!}
                </div>
            </div>

            <div class="col-lg-4">
                <h6 class="notice-sidebar-heading">Other notices</h6>
                @forelse ($recentNotices as $item)
                    <a href="{{ route('notices.show', $item->slug) }}" class="notice-sidebar-item">
                        {{ $item->title ?? Str::limit(strip_tags($item->message), 60) }}
                    </a>
                @empty
                    <p class="text-muted">No other notices right now.</p>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection
