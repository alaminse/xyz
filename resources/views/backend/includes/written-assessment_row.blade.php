@foreach ($writtenassessments as $index => $written)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $written->question }}</td>
        <td>{{ $written->getCourseNames() }}</td>
        <td>{{ $written->getChapterName() }}</td>
        <td>
            <a href="#" class="btn btn-{{ \App\Enums\IsPaid::from($written->isPaid)->message() }} btn-sm">
                {{ \Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($written->isPaid)->title(), 100, '....') }}
            </a>
        </td>

        <td>
            <a href="{{ route('admin.writtenassessments.status', $written->id) }}" class="btn btn-{{ \App\Enums\Status::from($written->status)->message() }} btn-sm">
                {{ \App\Enums\Status::from($written->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ route('admin.writtenassessments.show', $written->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.writtenassessments.edit', $written->id) }}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger btn-sm {{ $written->status == 3 ? 'disabled' : '' }}" onclick="showDeleteConfirmation(event)" href="{{ route('admin.writtenassessments.destroy', $written->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
