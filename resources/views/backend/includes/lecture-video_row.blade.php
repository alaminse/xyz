@foreach ($videos as $index => $video)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $video->title }}</td>
        <td>
            @forelse($video->courses as $course)
                <span class="badge badge-primary">{{ $course->name }}</span>
            @empty
                <span class="text-muted">N/A</span>
            @endforelse
        </td>
        <td>{{ $video->getChapterName() }}</td>
        <td>
            <a href="#" class="btn btn-{{ \App\Enums\IsPaid::from($video->isPaid)->message() }} btn-sm">
                {{ \Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($video->isPaid)->title(), 100, '....') }}
            </a>
        </td>

        <td>
            <a href="{{ route('admin.lecturevideos.status', $video->id) }}" class="btn btn-{{ \App\Enums\Status::from($video->status)->message() }} btn-sm">
                {{ \App\Enums\Status::from($video->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-info btn-sm" href="{{ route('admin.lecturevideos.show', $video->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-primary btn-sm" href="{{ route('admin.lecturevideos.edit', $video->id) }}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-danger btn-sm {{ $video->status == 3 ? 'disabled' : '' }}" onclick="showDeleteConfirmation(event)" href="{{ route('admin.lecturevideos.destroy', $video->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
