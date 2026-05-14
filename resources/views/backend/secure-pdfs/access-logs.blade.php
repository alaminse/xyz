@extends('layouts.backend')
@section('title', 'Access Logs')

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>
                Access Logs
                <small>{{ $securePdf->title }}</small>
            </h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a href="{{ route('admin.secure-pdfs.show', $securePdf->id) }}"
                       class="btn btn-sm btn-default">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>

        <div class="x_content">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Action</th>
                            <th>IP Address</th>
                            <th>Browser</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $i => $log)
                        @php
                            $badges = [
                                'viewed'              => 'info',
                                'streamed'            => 'primary',
                                'token_generated'     => 'default',
                                'suspicious_activity' => 'danger',
                            ];
                            $badge = $badges[$log->action] ?? 'default';
                        @endphp
                        <tr class="{{ $log->action === 'suspicious_activity' ? 'danger' : '' }}">
                            <td>{{ $logs->firstItem() + $i }}</td>
                            <td>
                                <strong>{{ $log->user?->name }}</strong><br>
                                <small class="text-muted">{{ $log->user?->email }}</small>
                            </td>
                            <td>
                                <span class="badge badge-{{ $badge }}">
                                    {{ $log->action }}
                                </span>
                            </td>
                            <td><code>{{ $log->ip_address }}</code></td>
                            <td>
                                <small title="{{ $log->user_agent }}">
                                    {{ Str::limit($log->user_agent, 45) }}
                                </small>
                            </td>
                            <td>{{ $log->accessed_at->format('d M Y, h:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                No access logs found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $logs->links() }}

        </div>
    </div>
</div>
@endsection
