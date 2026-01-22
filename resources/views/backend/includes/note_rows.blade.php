@foreach ($notes as $index => $note)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $note->title }}</td>
        <td>{{ $note->getCourseName() }}</td>
        <td>{{ $note->getChapterName() }}</td>
        <td>
            <a href="#" class="btn btn-{{ \App\Enums\IsPaid::from($note->isPaid)->message() }} btn-sm">
                {{ \Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($note->isPaid)->title(), 100, '....') }}
            </a>
        </td>

        <td>
            <a href="{{ route('admin.notes.status', $note->id) }}" class="btn btn-{{ \App\Enums\Status::from($note->status)->message() }} btn-sm">
                {{ \App\Enums\Status::from($note->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ route('admin.notes.show', $note->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.notes.edit', $note->id) }}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger {{ $note->status == 3 ? 'disabled' : '' }} btn-sm" onclick="showDeleteConfirmation(event)" href="{{ route('admin.notes.destroy', $note->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
