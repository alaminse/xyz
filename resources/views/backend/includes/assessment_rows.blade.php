@foreach ($assessments as $key => $assessment)
    <tr>
        <td>{{ ++$key }}</td>
        <td>{{ $assessment->name }}</td>
        <td>
            @foreach(explode(', ', $assessment->course_names) as $courseName)
                <span class="badge bg-success me-1 mb-1 px-2 text-white">{{ $courseName }}</span>
            @endforeach
        </td>
        <td>{{ $assessment->getChapterName() }}</td>
        <td>{{ $assessment->getLessonName() }}</td>
        <td>{{ $assessment->isPaid ? 'Paid' : 'Free' }}</td>
        <td>
            <a href="{{ route('admin.assessments.status', $assessment->id) }}" class="btn btn-{{ \App\Enums\Status::from($assessment->status)->message() }} btn-sm">
                {{ \App\Enums\Status::from($assessment->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.assessments.edit', $assessment->id) }}">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-info btn-sm" href="{{ route('admin.assessments.show', $assessment->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-danger btn-sm" onclick="showDeleteConfirmation(event)" href="{{ route('admin.assessments.destroy', $assessment->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
