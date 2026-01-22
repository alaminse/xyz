@foreach ($users as $key => $enrolle)
    <tr>
        <td>
            <img class="image-preview img-fluid" src="{{ getImageUrl($enrolle->course?->banner) }}"
                alt="{{ $enrolle->course?->title }}" style="height: 40px; width: 40px;">
        </td>
        <td>
            <div><strong>{{ $enrolle->course?->name }}</strong></div>
            {{ $enrolle->course?->detail?->duration }} {{ $enrolle->course?->detail?->type }}
        </td>
        <td>{{ $enrolle->user?->name }}</td>
        <td>{{ $enrolle->user?->email }}</td>
        <td>{{ \Carbon\Carbon::parse($enrolle->start_date)->format('M d, Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($enrolle->end_date)->format('M d, Y') }}</td>
        <td>{{ $enrolle->price }}</td>
        <td>{{ $enrolle->sell_price }}</td>
        <td>
            <button type="button"
                class="btn btn-{{ \App\Enums\Status::from($enrolle->status)->message() }} btn-sm  {{ $enrolle->status == 3 ? 'disabled' : '' }}"
                data-toggle="modal" data-target="#statusUpdate" data-id="{{ $enrolle->id }}"
                data-status="{{ $enrolle->status }}">
                {{ \App\Enums\Status::from($enrolle->status)->title() }}</button>
        </td>
        <td>
            <a class="btn btn-info btn-sm" target="__blank"
                href="{{ route('courses.invoice', $enrolle->slug) }}">Invoice</a>
        </td>
    </tr>
@endforeach
