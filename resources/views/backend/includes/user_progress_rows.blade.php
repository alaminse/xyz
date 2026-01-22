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
        <td>{{ $assessment->user_progress_count }} users attended</td>
        <td>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.assessments.leaderboard', $assessment->id) }}">
                <i class="fa fa-edit"></i> Details
            </a>
        </td>
    </tr>
@endforeach
