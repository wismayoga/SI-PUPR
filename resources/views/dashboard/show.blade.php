@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $report->report_name }}</h1>
        <p>Deadline: {{ \Carbon\Carbon::parse($report->deadline)->format('d M Y') }}</p>

        @if ($report->status == 'submitted')
            <div class="alert alert-info">Report already submitted. You can upload a new file to replace it.</div>
        @endif

        <!-- Form for Uploading Report -->
        <form action="{{ route('user.reports.update', $report->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="report_file" class="form-label">Upload Report</label>
                <input type="file" class="form-control" id="report_file" name="report_file" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>

        @if ($report->status == 'submitted')
            <!-- Option to Delete Report -->
            <form action="{{ route('user.reports.destroy', $report->id) }}" method="POST" class="mt-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete Report</button>
            </form>
        @endif
    </div>
@endsection
