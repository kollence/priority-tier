@extends('layouts.admin')
@section('title', __('Show Data Import'))
@section('content')
<h1 class="mt-4">{{ __('Show Data Import')}}</h1>
<div class="card mb-4">
    <div class="card-body">

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="input-group w-50">
                    <input type="search" class="form-control" id="search" placeholder="Search...">
                    <button class="btn btn-primary" id="searchBtn">Filter</button>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>File</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($imports as $import)
                        <tr>
                            <td>{{ $import->created_at }}</td>
                            <td>{{ $import->user->name }}</td>
                            <td>{{ $import->type }}</td>
                            <td>{{ $import->filename }}</td>
                            <td>
                                <span class="badge bg-{{ $import->status === 'successful' ? 'success' : 'danger' }}">
                                    {{ $import->status }}
                                </span>
                            </td>
                            <td>
                                <button onclick="showLogs('{{ $import->id }}')" class="btn btn-sm btn-info">
                                    <i class="fas fa-list"></i> Logs
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $imports->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Logs Modal -->
<div class="modal fade" id="logsModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Import Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table" id="logsTable">
                    <thead>
                        <tr>
                            <th>Row</th>
                            <th>Column</th>
                            <th>Value</th>
                            <th>Error</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    $(document).ready(function() {
        $('#searchBtn').click(function() {
            var query = $('#search').val();
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('search', query);
            window.location.href = currentUrl.toString();
        });
        function showLogs(importId) {
        const url = "{{ route('imports.logs', ':id') }}";
        const urlpars = url.replace(':id', importId);
        $.ajax({
            url: urlpars,
            type: 'GET',
            success: function(logs) {
                const tbody = $('#logsTable tbody');
                tbody.empty();

                logs.forEach(log => {
                    tbody.append(`
                    <tr>
                        <td>${log.row_number}</td>
                        <td>${log.column_name}</td>
                        <td>${log.invalid_value}</td>
                        <td>${log.validation_message}</td>
                    </tr>
                `);
                });

                $('#logsModal').modal('show');
            }
        });
    }
    });
</script>
@endpush

@endsection