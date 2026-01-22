@extends('layouts.backend')
@section('title', 'User Progress - ' . $assessment->name)

@section('css')
    <link href="{{ asset('backend/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('backend/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}"
        rel="stylesheet">

    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }

        .stat-card {
            transition: transform 0.3s, box-shadow 0.3s;
            border: none;
            border-radius: 10px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }

        .winner-card {
            position: relative;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: transform 0.3s;
        }

        .winner-card:hover {
            transform: translateY(-10px);
        }

        .winner-badge {
            position: absolute;
            top: -10px;
            right: 15px;
            font-size: 3rem;
            z-index: 1;
        }

        .user-avatar-large {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .user-avatar-medium {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #fff;
        }

        .user-avatar-small {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }

        .avatar-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            font-weight: bold;
            border-radius: 50%;
        }

        .rank-badge {
            display: inline-block;
            min-width: 40px;
            padding: 5px 10px;
            background: #667eea;
            color: white;
            border-radius: 20px;
            font-weight: 600;
            text-align: center;
        }

        .rank-1 {
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #333;
        }

        .rank-2 {
            background: linear-gradient(135deg, #c0c0c0, #e8e8e8);
            color: #333;
        }

        .rank-3 {
            background: linear-gradient(135deg, #cd7f32, #e9a76e);
            color: white;
        }

        .highlight-row {
            background-color: #e3f2fd !important;
            font-weight: 600;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .percentage-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .pass-badge {
            background-color: #d4edda;
            color: #155724;
        }

        .fail-badge {
            background-color: #f8d7da;
            color: #721c24;
        }

        .card-stats {
            border-left: 4px solid;
        }

        .card-stats.border-primary {
            border-left-color: #007bff;
        }

        .card-stats.border-success {
            border-left-color: #28a745;
        }

        .card-stats.border-warning {
            border-left-color: #ffc107;
        }

        .card-stats.border-info {
            border-left-color: #17a2b8;
        }

        .card-stats.border-danger {
            border-left-color: #dc3545;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">

        {{-- Page Header --}}
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="fa fa-trophy"></i> {{ $assessment->name }}
                    </h2>
                    <p class="mb-0">
                        <i class="fa fa-star"></i> মোট নম্বর: <strong>{{ $assessment->total_marks }}</strong> |
                        <i class="fa fa-clock-o"></i> সময়: <strong>{{ $assessment->time }} মিনিট</strong> |
                        <i class="fa fa-users"></i> অংশগ্রহণকারী: <strong>{{ $totalParticipants }}</strong>
                    </p>
                </div>
                <div class="col-md-4 text-right">
                    <a href="{{ route('admin.assessments.index') }}" class="btn btn-light btn-sm">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card card-stats border-primary shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <span class="stat-icon text-primary">👥</span>
                            </div>
                            <div class="col-9 text-right">
                                <p class="text-muted mb-1 small">মোট পরীক্ষার্থী</p>
                                <h3 class="mb-0 font-weight-bold">{{ $totalParticipants }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card card-stats border-success shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <span class="stat-icon text-success">✅</span>
                            </div>
                            <div class="col-9 text-right">
                                <p class="text-muted mb-1 small">উত্তীর্ণ পরীক্ষার্থী</p>
                                <h3 class="mb-0 font-weight-bold">{{ $passedCount }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card card-stats border-info shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <span class="stat-icon text-info">📊</span>
                            </div>
                            <div class="col-9 text-right">
                                <p class="text-muted mb-1 small">পাস রেট</p>
                                <h3 class="mb-0 font-weight-bold">
                                    {{ $totalParticipants > 0 ? round(($passedCount / $totalParticipants) * 100, 1) : 0 }}%
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3 col-sm-6 mb-3">
                <div class="card card-stats border-warning shadow-sm">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-3">
                                <span class="stat-icon text-warning">⭐</span>
                            </div>
                            <div class="col-9 text-right">
                                <p class="text-muted mb-1 small">সর্বোচ্চ মার্ক</p>
                                <h3 class="mb-0 font-weight-bold">
                                    {{ $leaderboard->max('achive_marks') ?? 0 }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Additional Stats Row --}}
        <div class="row mb-4">
            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">গড় মার্ক</h6>
                        <h3 class="text-primary font-weight-bold">
                            {{ $leaderboard->count() > 0 ? round($leaderboard->avg('achive_marks'), 2) : 0 }}
                        </h3>
                        <small class="text-muted">{{ $assessment->total_marks }} এর মধ্যে</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">কাট মার্ক (70%)</h6>
                        <h3 class="text-warning font-weight-bold">
                            {{ $assessment->total_marks * 0.7 }}
                        </h3>
                        <small class="text-muted">পাসের জন্য প্রয়োজনীয়</small>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card shadow-sm">
                    <div class="card-body text-center">
                        <h6 class="text-muted mb-2">সর্বনিম্ন মার্ক</h6>
                        <h3 class="text-danger font-weight-bold">
                            {{ $leaderboard->min('achive_marks') ?? 0 }}
                        </h3>
                        <small class="text-muted">{{ $assessment->total_marks }} এর মধ্যে</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top 3 Winners --}}
        @if ($topThree->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-gradient-primary text-white">
                            <h5 class="mb-0 text-dark">
                                <i class="fa fa-trophy"></i> শীর্ষ ৩ জন পরীক্ষার্থী
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($topThree as $winner)
                                    <div class="col-md-4 mb-3">
                                        <div class="card winner-card shadow-sm h-100">
                                            <span class="winner-badge">
                                                @if ($loop->iteration == 1)
                                                    🥇
                                                @elseif($loop->iteration == 2)
                                                    🥈
                                                @else
                                                    🥉
                                                @endif
                                            </span>
                                            <div class="card-body text-center pt-4">
                                                <div class="mb-3">
                                                    @if (isset($winner->user->profile_photo_path) && $winner->user->profile_photo_path)
                                                        <img src="{{ asset('storage/' . $winner->user->profile_photo_path) }}"
                                                            alt="{{ $winner->user->name }}" class="user-avatar-medium">
                                                    @else
                                                        <div class="avatar-placeholder user-avatar-medium d-inline-flex">
                                                            {{ strtoupper(substr($winner->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                </div>

                                                <span class="rank-badge rank-{{ $loop->iteration }} mb-2">
                                                    #{{ $loop->iteration }}
                                                </span>

                                                <h5 class="mt-2 mb-1">{{ $winner->user->name }}</h5>
                                                <p class="text-muted small mb-2">{{ $winner->user->email }}</p>

                                                <div class="mt-3">
                                                    <h4 class="text-primary font-weight-bold mb-0">
                                                        {{ $winner->achive_marks }} / {{ $assessment->total_marks }}
                                                    </h4>
                                                    <p class="text-muted mb-0">
                                                        <span
                                                            class="percentage-badge {{ $winner->percentage >= 70 ? 'pass-badge' : 'fail-badge' }}">
                                                            {{ $winner->percentage }}%
                                                        </span>
                                                    </p>
                                                </div>

                                                <small class="text-muted">
                                                    <i class="fa fa-clock-o"></i>
                                                    {{ $winner->created_at ? $winner->created_at->format('d M Y, h:i A') : 'N/A' }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Full Leaderboard Table --}}
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h5 class="mb-0">
                                    <i class="fa fa-list"></i> সম্পূর্ণ র‍্যাঙ্কিং তালিকা
                                </h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($leaderboard->count() > 0)
                            <div class="table-responsive">
                                <table id="leaderboardTable" class="table table-hover table-striped">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="80">র‍্যাঙ্ক</th>
                                            <th>পরীক্ষার্থীর নাম</th>
                                            <th>ইমেইল</th>
                                            <th class="text-center">প্রাপ্ত নম্বর</th>
                                            <th class="text-center">মোট নম্বর</th>
                                            <th class="text-center">শতাংশ</th>
                                            <th class="text-center">ফলাফল</th>
                                            <th class="text-center">জমা দেওয়ার সময়</th>
                                            <th class="text-center">অ্যাকশন</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($leaderboard as $progress)
                                            <tr class="{{ $progress->user_id == auth()->id() ? 'highlight-row' : '' }}">
                                                <td>
                                                    <span
                                                        class="rank-badge {{ $progress->rank <= 3 ? 'rank-' . $progress->rank : '' }}">
                                                        #{{ $progress->rank }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if (isset($progress->user->profile_photo_path) && $progress->user->profile_photo_path)
                                                            <img src="{{ asset('storage/' . $progress->user->profile_photo_path) }}"
                                                                alt="{{ $progress->user->name }}"
                                                                class="user-avatar-small mr-2">
                                                        @else
                                                            <div class="avatar-placeholder user-avatar-small mr-2">
                                                                {{ strtoupper(substr($progress->user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <span>
                                                            {{ $progress->user->name }}
                                                            @if ($progress->user_id == auth()->id())
                                                                <span class="badge badge-primary badge-sm ml-1">You</span>
                                                            @endif
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>{{ $progress->user->email }}</td>
                                                <td class="text-center">
                                                    <strong class="text-primary">{{ $progress->achive_marks }}</strong>
                                                </td>
                                                <td class="text-center">{{ $assessment->total_marks }}</td>
                                                <td class="text-center">
                                                    <span
                                                        class="percentage-badge {{ $progress->percentage >= 70 ? 'pass-badge' : 'fail-badge' }}">
                                                        {{ $progress->percentage }}%
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if ($progress->achive_marks >= $assessment->total_marks * 0.7)
                                                        <span class="badge badge-success">
                                                            <i class="fa fa-check"></i> উত্তীর্ণ
                                                        </span>
                                                    @else
                                                        <span class="badge badge-danger">
                                                            <i class="fa fa-times"></i> অনুত্তীর্ণ
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <small>
                                                        {{ $progress->created_at ? $progress->created_at->format('d M Y, h:i A') : 'N/A' }}</small><br>
                                                    <small
                                                        class="text-muted">{{ $progress->created_at ? $progress->created_at->format('h:i A') : 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group btn-group-sm">
                                                        <a href="{{ route('admin.assessments.user-details', $progress->id) }}"
                                                            class="btn btn-info" title="View Details">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info text-center">
                                <i class="fa fa-info-circle fa-2x mb-2"></i>
                                <h5>কোনো ডেটা পাওয়া যায়নি</h5>
                                <p class="mb-0">এখনো কেউ এই assessment টি সম্পন্ন করেনি।</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>


    @push('scripts')
        <script src="{{ asset('backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('backend/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>

        <script>
            $(document).ready(function() {
                // Initialize DataTable
                $('#leaderboardTable').DataTable({
                    responsive: true,
                    pageLength: 25,
                    order: [
                        [0, 'asc']
                    ], // Sort by rank
                    language: {
                        search: "খুঁজুন:",
                        lengthMenu: "প্রদর্শন করুন _MENU_ টি এন্ট্রি",
                        info: "প্রদর্শন করা হচ্ছে _START_ থেকে _END_ পর্যন্ত, মোট _TOTAL_ টি এন্ট্রি",
                        infoEmpty: "কোনো এন্ট্রি পাওয়া যায়নি",
                        infoFiltered: "(মোট _MAX_ টি এন্ট্রি থেকে ফিল্টার করা হয়েছে)",
                        paginate: {
                            first: "প্রথম",
                            last: "শেষ",
                            next: "পরবর্তী",
                            previous: "পূর্ববর্তী"
                        }
                    }
                });
            });
        </script>
    @endpush
@endsection
