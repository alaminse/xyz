@foreach ($mcqs as $index => $mcq)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            <span class="badge badge-info">
                <i class="fa fa-list"></i> {{ $mcq->questions_count }} Questions
            </span>
        </td>
        <td>
            @forelse($mcq->courses as $course)
                <span class="badge badge-primary">{{ $course->name }}</span>
            @empty
                <span class="text-muted">N/A</span>
            @endforelse
        </td>
        <td>
            <span class="badge badge-secondary">
                {{ $mcq->chapter?->name ?? 'N/A' }}
            </span>
        </td>
        <td>
            <span class="badge badge-light text-dark">
                {{ $mcq->lesson?->name ?? 'N/A' }}
            </span>
        </td>
        <td>
            @if($mcq->isPaid)
                <span class="badge badge-warning">
                    <i class="fa fa-lock"></i> Paid
                </span>
            @else
                <span class="badge badge-success">
                    <i class="fa fa-unlock"></i> Free
                </span>
            @endif
        </td>
        <td>
            <a href="{{ route('admin.mcqs.status', $mcq->id) }}"
               class="btn btn-sm btn-{{ \App\Enums\Status::from($mcq->status)->message() }}">
                {{ \App\Enums\Status::from($mcq->status)->title() }}
            </a>
        </td>
        <td>
            <div class="btn-group" role="group">
                <a class="btn btn-info btn-sm"
                   href="{{ route('admin.mcqs.show', $mcq->id) }}"
                   title="View & Manage Questions">
                    <i class="fa fa-eye"></i>
                </a>
                <a class="btn btn-primary btn-sm"
                   href="{{ route('admin.mcqs.edit', $mcq->id) }}"
                   title="Edit">
                    <i class="fa fa-edit"></i>
                </a>
                <a class="btn btn-danger btn-sm"
                   onclick="showDeleteConfirmation(event)"
                   href="{{ route('admin.mcqs.destroy', $mcq->id) }}"
                   title="Delete">
                    <i class="fa fa-trash"></i>
                </a>
            </div>
        </td>
    </tr>
@endforeach
