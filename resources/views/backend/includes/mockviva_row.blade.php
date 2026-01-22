@foreach ($mocks as $index => $mock)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $mock->getCourseNames() }}</td>
        <td>{{ $mock->getChapterName() }}</td>
        <td>
            <a href="#" class="btn btn-{{ \App\Enums\IsPaid::from($mock->isPaid)->message() }} btn-sm">
                {{ \Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($mock->isPaid)->title(), 100, '....') }}
            </a>
        </td>
        <td>
            <a href="{{ route('admin.mockvivas.status', $mock->id) }}" class="btn btn-{{ \App\Enums\Status::from($mock->status)->message() }} btn-sm">
                {{ \App\Enums\Status::from($mock->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ route('admin.mockvivas.show', $mock->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.mockvivas.edit', $mock->id) }}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger {{ $mock->status == 3 ? 'disabled' : '' }} btn-sm" onclick="showDeleteConfirmation(event)" href="{{ route('admin.mockvivas.destroy', $mock->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
