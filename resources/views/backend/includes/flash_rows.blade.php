@foreach ($flashs as $index => $flash)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            <span class="badge badge-info">
                <i class="fa fa-list"></i> {{ $flash->questions_count }} Questions
            </span>
        </td>
        <td>
            @forelse($flash->courses as $course)
                <span class="badge badge-primary">{{ $course->name }}</span>
            @empty
                <span class="text-muted">N/A</span>
            @endforelse
        </td>
        <td>
            <span class="badge badge-secondary">
                {{ $flash->chapter?->name ?? 'N/A' }}
            </span>
        </td>
        <td>
            <span class="badge badge-light text-dark">
                {{ $flash->lesson?->name ?? 'N/A' }}
            </span>
        </td>
        <td>
            @if($flash->isPaid)
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
            <a href="{{ route('admin.flashs.status', $flash->id) }}"
               class="btn btn-sm btn-{{ \App\Enums\Status::from($flash->status)->message() }}">
                {{ \App\Enums\Status::from($flash->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ route('admin.flashs.show', $flash->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.flashs.edit', $flash->id) }}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger {{ $flash->status == 3 ? 'disabled' : '' }} btn-sm" onclick="showDeleteConfirmation(event)" href="{{ route('admin.flashs.destroy', $flash->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
