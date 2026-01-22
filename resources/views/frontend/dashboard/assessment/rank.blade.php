@extends('frontend.dashboard.app')
@section('title', 'Assessments')
@section('css')
    <link rel="stylesheet" href="{{ asset('frontend/css/ranking.css') }}">
@endsection

@section('content')
    <div class="container">
        <div class="assessment-ranking">
            <h2>{{ $assessment->name }} - Ranking</h2>
            <p>Total Marks: {{ $assessment->total_marks }} | Time: {{ $assessment->time }} Minutes</p>

            @if ($userRank)
                <div class="your-rank-card">

                    {{-- User Header --}}
                    <div class="rank-card-header-main">
                        <div class="user-avatar">
                            @if (isset($userRank->user->profile_photo_path) && $userRank->user->profile_photo_path)
                                <img src="{{ asset('storage/' . $userRank->user->profile_photo_path) }}"
                                    alt="{{ $userRank->user->name }}">
                            @else
                                <div class="avatar-placeholder">
                                    {{ strtoupper(substr($userRank->user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
                        <div class="user-info">
                            <h3>{{ $userRank->user->name }}</h3>
                            <p class="user-email">{{ $userRank->user->email }}</p>
                        </div>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="rank-stats-grid">
                        <div class="stat-box stat-total">
                            <div class="stat-icon">👥</div>
                            <div class="stat-content">
                                <div class="stat-label">Total Participants</div>
                                <div class="stat-value">{{ $totalParticipants ?? $leaderboard->count() }}</div>
                            </div>
                        </div>

                        <div class="stat-box stat-rank">
                            <div class="stat-icon">✅</div>
                            <div class="stat-content">
                                <div class="stat-label">Passed Participants</div>
                                <div class="stat-value">
                                    {{ $leaderboard->where('achive_marks', '>=', $assessment->total_marks * 0.7)->count() }}
                                </div>
                            </div>
                        </div>

                        <div class="stat-box stat-marks">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-content">
                                <div class="stat-label">Total Marks</div>
                                <div class="stat-value">{{ $assessment->total_marks }}</div>
                            </div>
                        </div>

                        <div class="stat-box stat-marks">
                            <div class="stat-icon">⭐</div>
                            <div class="stat-content">
                                <div class="stat-label">Cut Mark 70%</div>
                                <div class="stat-value">{{ $assessment->total_marks * 0.7 }}</div>
                            </div>
                        </div>

                        <div class="stat-box stat-your-marks">
                            <div class="stat-icon">📊</div>
                            <div class="stat-content">
                                <div class="stat-label">Your Marks</div>
                                <div class="stat-value">{{ $userRank->achive_marks }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Your Performance --}}
                    <div class="your-performance">
                        <h4>🏆 Your Performance</h4>
                        <div class="performance-grid">

                            <div class="performance-item">
                                <div class="performance-item-label">Your Rank</div>
                                <div class="performance-item-value">#{{ $userRank->rank ?? $loop->iteration }}</div>
                                <div class="performance-item-sub">
                                    Out of {{ $totalParticipants ?? $leaderboard->count() }} Participants
                                </div>
                            </div>

                            <div class="performance-item">
                                <div class="performance-item-label">Score</div>
                                <div class="performance-item-value">
                                    {{ $userRank->achive_marks }}/{{ $assessment->total_marks }}
                                </div>
                                <div class="performance-item-sub">{{ $userRank->percentage }}%</div>
                            </div>

                            <div class="performance-item">
                                <div class="performance-item-label">Result</div>
                                <div class="performance-item-value">
                                    @if ($userRank->achive_marks >= $assessment->total_marks * 0.7)
                                        <span class="result-badge pass">✓ Passed</span>
                                    @else
                                        <span class="result-badge fail">✗ Failed</span>
                                    @endif
                                </div>
                            </div>

                            <div class="performance-item">
                                <div class="performance-item-label">Your Position</div>
                                <div class="performance-item-value">
                                    @php
                                        $position = round(
                                            ($userRank->rank / ($totalParticipants ?? $leaderboard->count())) * 100,
                                        );
                                    @endphp
                                    @if ($position <= 10)
                                        Top 10%
                                    @elseif($position <= 25)
                                        Top 25%
                                    @elseif($position <= 50)
                                        Top 50%
                                    @else
                                        Bottom 50%
                                    @endif
                                </div>
                                <div class="performance-item-sub">
                                    {{ $userRank->created_at->format('d M Y, h:i A') }}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <h4>📝 Assessment Not Completed</h4>
                    <p>You have not completed this assessment yet. Click the button below to start.</p>
                    <a href="{{ route('assessment.start', $assessment->slug) }}" class="btn btn-primary">
                        Start Assessment
                    </a>
                </div>
            @endif

            {{-- Top 3 Winners --}}
            @if (isset($topThree) && $topThree->count() > 0)
                <div class="top-winners pt-3">
                    <h3>🥇 Top 3 Performers</h3>
                    <div class="winners-row pb-4">
                        @foreach ($topThree as $winner)
                            <div class="winner-card">
                                <div class="rank-badge">{{ $loop->iteration }}</div>
                                <h4>{{ $winner->user->name }}</h4>
                                <p>{{ $winner->achive_marks }} Marks</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Full Leaderboard --}}
            <div class="leaderboard-table p-4">
                <h3>Complete Ranking List</h3>

                {{-- Desktop Table --}}
                <table class="table table-desktop">
                    <thead>
                        <tr>
                            <th>Rank</th>
                            <th>Name</th>
                            <th>Score</th>
                            <th>Percentage</th>
                            <th>Submitted At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($leaderboard as $progress)
                            <tr class="{{ $progress->user_id == auth()->id() ? 'highlight-row' : '' }}">
                                <td><span class="rank-badge">#{{ $loop->iteration }}</span></td>
                                <td>
                                    {{ $progress->user->name }}
                                    @if ($progress->user_id == auth()->id())
                                        <span class="badge-primary">You</span>
                                    @endif
                                </td>
                                <td>{{ $progress->achive_marks }} / {{ $assessment->total_marks }}</td>
                                <td>{{ $progress->percentage }}%</td>
                                <td>{{ $progress->created_at->format('d M Y, h:i A') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">
                                    No one has completed this assessment yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Mobile View --}}
                <div class="table-mobile">
                    @forelse($leaderboard as $progress)
                        <div class="rank-card {{ $progress->user_id == auth()->id() ? 'highlight-row' : '' }}">
                            <div class="rank-card-header">
                                <span class="rank-badge">#{{ $loop->iteration }}</span>
                                <span class="rank-card-name">
                                    {{ $progress->user->name }}
                                    @if ($progress->user_id == auth()->id())
                                        <span class="badge-primary">You</span>
                                    @endif
                                </span>
                            </div>
                            <div class="rank-card-info">
                                <div class="rank-card-info-row">
                                    <span class="rank-card-info-label">Score:</span>
                                    <span class="rank-card-info-value">
                                        {{ $progress->achive_marks }} / {{ $assessment->total_marks }}
                                    </span>
                                </div>
                                <div class="rank-card-info-row">
                                    <span class="rank-card-info-label">Percentage:</span>
                                    <span class="rank-card-info-value">{{ $progress->percentage }}%</span>
                                </div>
                                <div class="rank-card-info-row">
                                    <span class="rank-card-info-label">Submitted:</span>
                                    <span class="rank-card-info-value">
                                        {{ $progress->created_at->format('d M Y, h:i A') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center" style="padding: 20px;">
                            No one has completed this assessment yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="stats">
                <p><strong>Total Participants:</strong> {{ $totalParticipants ?? $leaderboard->count() }}</p>
            </div>
        </div>

    </div>
@endsection
