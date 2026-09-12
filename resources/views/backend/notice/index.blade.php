@extends('layouts.backend')
@section('title', 'Notices')

@section('content')
<div class="col-md-12 col-sm-12">
    <div class="x_panel">
        <div class="x_title">
            <h2>Floating Notices</h2>
            <ul class="nav navbar-right panel_toolbox">
                <li>
                    <a class="btn btn-sm btn-success text-light" href="{{ route('admin.notices.create') }}">
                        <i class="fa fa-plus"></i> Add New
                    </a>
                </li>
                <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                <li><a class="close-link"><i class="fa fa-close"></i></a></li>
            </ul>
            <div class="clearfix"></div>
        </div>
        <div class="x_content">
            @include('backend.includes.message')

            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Message</th>
                        <th>Type</th>
                        <th>Active Window</th>
                        <th>Status</th>
                        <th width="20%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($notices as $key => $notice)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ Str::limit(strip_tags($notice->message), 80) }}</td>
                            <td>
                                <span class="badge
                                    @if($notice->type == 'urgent') badge-danger
                                    @elseif($notice->type == 'warning') badge-warning
                                    @else badge-info @endif">
                                    {{ ucfirst($notice->type) }}
                                </span>
                            </td>
                            <td>
                                @if ($notice->start_at || $notice->end_at)
                                    {{ $notice->start_at?->format('d M Y') ?? 'Always' }}
                                    &ndash;
                                    {{ $notice->end_at?->format('d M Y') ?? 'No end' }}
                                @else
                                    <span class="text-muted">Always active</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.notices.status', $notice->id) }}"
                                   onclick="event.preventDefault(); document.getElementById('status-form-{{ $notice->id }}').submit();">
                                    @if ($notice->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-secondary">Inactive</span>
                                    @endif
                                </a>
                                <form id="status-form-{{ $notice->id }}" action="{{ route('admin.notices.status', $notice->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('PATCH')
                                </form>
                            </td>
                            <td>
                                <a href="{{ route('admin.notices.edit', $notice->id) }}" class="btn btn-sm btn-primary">
                                    <i class="fa fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.notices.destroy', $notice->id) }}"
                                   class="btn btn-sm btn-danger"
                                   onclick="event.preventDefault();
                                            if (confirm('Delete this notice?')) {
                                                document.getElementById('delete-form-{{ $notice->id }}').submit();
                                            }">
                                    <i class="fa fa-trash"></i>
                                </a>
                                <form id="delete-form-{{ $notice->id }}" action="{{ route('admin.notices.destroy', $notice->id) }}" method="POST" class="d-none">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No notices yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
