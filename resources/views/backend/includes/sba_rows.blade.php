@foreach ($sbas as $index => $sba)
    <tr>
        <td>{{ $index + 1 }}</td>
        <td>
            <span class="badge badge-info">{{ $sba->questions_count }} Questions</span>
        </td>
        <td>
            @foreach($sba->courses as $c)
                <span class="badge badge-primary">{{ $c->name }}</span>
            @endforeach
        </td>
        <td>{{ $sba->chapter?->name ?? 'N/A' }}</td>
        <td>{{ $sba->lesson?->name ?? 'N/A' }}</td>
        <td>{{ $sba->isPaid ? 'Paid' : 'Free' }}</td>
        <td>
            <a href="{{ route('admin.sbas.status', $sba->id) }}"
               class="btn btn-{{ \App\Enums\Status::from($sba->status)->message() }}">
                {{ \App\Enums\Status::from($sba->status)->title() }}
            </a>
        </td>
        <td>
            <a class="btn btn-info" href="{{ route('admin.sbas.show', $sba->id) }}" title="View & Manage Questions">
                <i class="fa fa-eye"></i>
            </a>
            <a class="btn btn-primary" href="{{ route('admin.sbas.edit', $sba->id) }}" title="Edit">
                <i class="fa fa-edit"></i>
            </a>
            <a class="btn btn-danger" onclick="showDeleteConfirmation(event)"
               href="{{ route('admin.sbas.destroy', $sba->id) }}" title="Delete">
                <i class="fa fa-trash"></i>
            </a>
        </td>
    </tr>
@endforeach
