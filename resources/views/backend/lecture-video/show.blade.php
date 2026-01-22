@extends('layouts.backend')
@section('title', 'Video Details')

@section('css')
    <style>
        .detail-card {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .detail-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: #fff;
            padding: 15px 20px;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
        }
        .detail-card-body {
            padding: 20px;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
            align-items: center;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #666;
            width: 180px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .video-container {
            background: #000;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            min-height: 400px;
        }
        .video-container iframe,
        .video-container video {
            width: 100%;
            height: 500px;
            border: none;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        .status-active {
            background: #28a745;
            color: #fff;
        }
        .status-inactive {
            background: #6c757d;
            color: #fff;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .course-badge {
            display: inline-block;
            background: #e3f2fd;
            color: #1976d2;
            padding: 5px 12px;
            border-radius: 4px;
            margin: 4px;
            font-size: 13px;
        }
    </style>
@endsection

@section('content')
    <div class="col-md-12">
        <div class="x_panel">
            <div class="x_title">
                <h2><i class="fa fa-video-camera"></i> {{ $video->title }}</h2>
                <ul class="nav navbar-right panel_toolbox">
                    <li>
                        <a class="btn btn-warning text-white" href="{{ route('admin.lecturevideos.index') }}">
                            <i class="fa fa-arrow-left"></i> Back
                        </a>
                    </li>
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="x_content">
                @include('backend.includes.message')

                <div class="row">
                    {{-- Video Player --}}
                    <div class="col-md-8 mb-4">
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <i class="fa fa-play-circle"></i> Video Player
                            </div>
                            <div class="detail-card-body p-0">
                                <div class="video-container">
                                    @if ($video->youtube_link)
                                        {{-- YouTube Video --}}
                                        <iframe
                                            src="{{ $video->youtube_link }}"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                        </iframe>

                                    @elseif ($video->uploaded_link)
                                        {{-- Bunny.net Video - Play in Admin Panel --}}
                                       <div class="video-container">
                                            @if ($video->youtube_link)
                                                {{-- YouTube Video --}}
                                                <iframe
                                                    src="{{ $video->youtube_link }}"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen>
                                                </iframe>

                                            @elseif ($video->uploaded_link)
                                                {{-- Bunny.net Video Player --}}
                                                @php
                                                    $libraryId = env('BUNNY_VIDEO_LIBRARY_ID');
                                                    $videoId = $video->uploaded_link;
                                                    $bunnyUrl = "https://iframe.mediadelivery.net/embed/{$libraryId}/{$videoId}";
                                                @endphp

                                                <iframe
                                                    src="{{ $bunnyUrl }}"
                                                    loading="lazy"
                                                    allow="accelerometer; gyroscope; autoplay; encrypted-media; picture-in-picture"
                                                    allowfullscreen>
                                                </iframe>

                                            @else
                                                {{-- No Video --}}
                                                <div style="padding: 80px 20px; text-align: center; background: #f8f9fa;">
                                                    <i class="fa fa-film" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                                                    <h4 class="text-muted">No Video Available</h4>
                                                    <p class="text-muted">Upload a video to see it here</p>
                                                    <a href="{{ route('admin.lecturevideos.edit', $video->id) }}" class="btn btn-primary mt-3">
                                                        <i class="fa fa-upload"></i> Upload Video
                                                    </a>
                                                </div>
                                            @endif
                                        </div>

                                    @else
                                        {{-- No Video --}}
                                        <div style="padding: 80px 20px; text-align: center; background: #f8f9fa;">
                                            <i class="fa fa-film" style="font-size: 64px; color: #ccc; margin-bottom: 20px;"></i>
                                            <h4 class="text-muted">No Video Available</h4>
                                            <p class="text-muted">Upload a video to see it here</p>
                                            <a href="{{ route('admin.lecturevideos.edit', $video->id) }}" class="btn btn-primary mt-3">
                                                <i class="fa fa-upload"></i> Upload Video
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Video Information --}}
                    <div class="col-md-4 mb-4">
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <i class="fa fa-info-circle"></i> Video Information
                            </div>
                            <div class="detail-card-body">
                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-book"></i> Courses</div>
                                    <div class="info-value">
                                        @foreach($video->courses as $course)
                                            <span class="course-badge">{{ $course->name }}</span>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-list"></i> Chapter</div>
                                    <div class="info-value">{{ $video->getChapterName() }}</div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-file-text"></i> Lesson</div>
                                    <div class="info-value">{{ $video->getLessonName() }}</div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-money"></i> Type</div>
                                    <div class="info-value">
                                        @if($video->isPaid)
                                            <span class="badge badge-warning">
                                                <i class="fa fa-lock"></i> Paid
                                            </span>
                                        @else
                                            <span class="badge badge-success">
                                                <i class="fa fa-unlock"></i> Free
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-toggle-on"></i> Status</div>
                                    <div class="info-value">
                                        <span class="status-badge {{ $video->status == 1 ? 'status-active' : 'status-inactive' }}">
                                            {{ $video->status == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-video-camera"></i> Source</div>
                                    <div class="info-value">
                                        @if($video->youtube_link)
                                            <span class="badge badge-danger">
                                                <i class="fa fa-youtube-play"></i> YouTube
                                            </span>
                                        @elseif($video->uploaded_link)
                                            <span class="badge badge-primary">
                                                <i class="fa fa-cloud"></i> Bunny.net
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">No video</span>
                                        @endif
                                    </div>
                                </div>

                                @if($video->uploaded_link)
                                    <div class="info-row">
                                        <div class="info-label"><i class="fa fa-code"></i> Video ID</div>
                                        <div class="info-value">
                                            <code style="font-size: 11px;">{{ $video->uploaded_link }}</code>
                                        </div>
                                    </div>
                                @endif

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-calendar"></i> Created</div>
                                    <div class="info-value">
                                        <small>{{ $video->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                </div>

                                <div class="info-row">
                                    <div class="info-label"><i class="fa fa-clock-o"></i> Updated</div>
                                    <div class="info-value">
                                        <small>{{ $video->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="detail-card">
                            <div class="detail-card-header">
                                <i class="fa fa-cog"></i> Actions
                            </div>
                            <div class="detail-card-body">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.lecturevideos.edit', $video->id) }}" class="btn btn-primary btn-block">
                                        <i class="fa fa-edit"></i> Edit Video
                                    </a>

                                    <form action="{{ route('admin.lecturevideos.destroy', $video->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure? This action cannot be undone!');"
                                          class="w-100">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-block">
                                            <i class="fa fa-trash"></i> Delete Video
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
