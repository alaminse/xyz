@foreach ($ospestations as $index => $ospe)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $ospe->getChapterName() }}</td>
        <td>{{ $ospe->getLessonName() }}</td>
        <td>
            @if($ospe->questions->first() && $ospe->questions->first()->image)
                <img src="{{ asset('uploads/'.$ospe->questions->first()->image) }}" alt="OSPE Image" width="100">
            @else
                <span>No image</span>
            @endif
        </td>
        <td>
            <a href="#" class="btn btn-{{ \App\Enums\IsPaid::from($ospe->isPaid)->message() }} btn-sm">
                {{ \Illuminate\Support\Str::limit(\App\Enums\IsPaid::from($ospe->isPaid)->title(), 100, '....') }}
            </a>
        </td>

        <td>
            <a href="{{ route('admin.ospestations.status', $ospe->id) }}" class="btn btn-sm btn-{{ \App\Enums\Status::from($ospe->status)->message() }}">
                {{ \App\Enums\Status::from($ospe->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-sm btn-info" href="{{ route('admin.ospestations.show', $ospe->id) }}"><i class="fa fa-eye"></i></a>
            <a class="btn btn-sm btn-primary" href="{{ route('admin.ospestations.edit', $ospe->id) }}"><i class="fa fa-edit"></i></a>
            <a class="btn btn-sm btn-danger {{ $ospe->status == 3 ? 'disabled' : '' }}" onclick="showDeleteConfirmation(event)" href="{{ route('admin.ospestations.destroy', $ospe->id) }}">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
