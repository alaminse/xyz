@extends('layouts.frontend')
@section('title', 'Notices')

@section('content')
<section class="notice-page-header">
    <div class="container">
        <h1 class="mb-2">Notices</h1>
        <p class="text-muted mb-0">Class updates, schedule changes, and announcements from MediManiac.</p>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @forelse ($notices as $notice)
                    <a href="{{ route('notices.show', $notice->slug) }}" class="notice-list-item text-decoration-none">
                        <span class="notice-type-badge badge-{{ $notice->type }}">{{ ucfirst($notice->type) }}</span>
                        <h3 class="notice-list-title">{{ $notice->title ?? 'Notice' }}</h3>
                        <span class="notice-list-date">{{ $notice->created_at->format('d M, Y') }}</span>
                        <p class="notice-list-excerpt">{{ Str::limit(strip_tags($notice->message), 140) }}</p>
                    </a>
                @empty
                    <div class="alert alert-info">No notices published yet.</div>
                @endforelse

                <div class="mt-4">
                    {{ $notices->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
