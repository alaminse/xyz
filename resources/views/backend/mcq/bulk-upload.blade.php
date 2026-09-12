<div class="mb-3 p-3" style="border: 1px solid #ddd; border-radius: 6px;">
    <h4>Bulk Upload Questions</h4>

    <a href="{{ route('admin.mcqs.sample-download') }}" class="btn btn-info btn-sm mb-2">
        <i class="fa fa-download"></i> Sample Download
    </a>

    <form action="{{ route('admin.mcqs.bulk-upload.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-4">
                <label>Select MCQ Set</label>
                <select name="mcq_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    @foreach ($mcqs as $mcqOption)
                        <option value="{{ $mcqOption->id }}">
                            {{ $mcqOption->chapter->name ?? 'N/A' }} - {{ $mcqOption->lesson->name ?? 'N/A' }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label>Excel/CSV File</label>
                <input type="file" name="file" class="form-control" required accept=".xlsx,.xls,.csv">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </div>
    </form>
</div>
