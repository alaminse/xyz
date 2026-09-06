@forelse ($notes as $key => $note)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>
            <span class="badge badge-info">{{ $note->details_count }} section(s)</span>
        </td>
        <td>
            @forelse ($note->courses as $course)
                <span class="badge badge-primary mr-1">{{ $course->name }}</span>
            @empty
                <span class="text-muted">N/A</span>
            @endforelse
        </td>
        <td>{{ $note->chapter->name ?? '-' }}</td>
        <td>{{ $note->lesson->name ?? '-' }}</td>
        <td>
            @if ($note->isPaid)
                <span class="badge badge-success">Paid</span>
            @else
                <span class="badge badge-secondary">Free</span>
            @endif
        </td>
        <td>
            <a href="{{ route('admin.notes.status', $note->id) }}"
               onclick="event.preventDefault(); document.getElementById('status-form-{{ $note->id }}').submit();">
                @if ($note->status == 1)
                    <span class="badge badge-success">Active</span>
                @elseif ($note->status == 2)
                    <span class="badge badge-warning">Inactive</span>
                @else
                    <span class="badge badge-danger">Deleted</span>
                @endif
            </a>
            <form id="status-form-{{ $note->id }}" action="{{ route('admin.notes.status', $note->id) }}" method="POST" class="d-none">
                @csrf
                @method('PATCH')
            </form>
        </td>
        <td>
            <a href="{{ route('admin.notes.show', $note->id) }}" class="btn btn-sm btn-info">
                <i class="fa fa-eye"></i>
            </a>
            <a href="{{ route('admin.notes.edit', $note->id) }}" class="btn btn-sm btn-primary">
                <i class="fa fa-edit"></i>
            </a>
            <a href="{{ route('admin.notes.destroy', $note->id) }}"
               class="btn btn-sm btn-danger"
               onclick="event.preventDefault();
                        if (confirm('Delete this note and all its sections?')) {
                            document.getElementById('delete-form-{{ $note->id }}').submit();
                        }">
                <i class="fa fa-trash"></i>
            </a>
            <form id="delete-form-{{ $note->id }}" action="{{ route('admin.notes.destroy', $note->id) }}" method="POST" class="d-none">
                @csrf
                @method('DELETE')
            </form>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center">No notes found for this course.</td>
    </tr>
@endforelse
