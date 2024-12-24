@extends('layouts.admin')
@section('title', 'Import History')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>Import History</h5>
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
                            <button onclick="showLogs('{{ $import->id }})')" class="btn btn-sm btn-info">
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
function showLogs(importId) {
    // $.get(`/imports/${importId}/logs`, function(logs) {
    //     const tbody = $('#logsTable tbody');
    //     tbody.empty();
        
    //     logs.forEach(log => {
    //         tbody.append(`
    //             <tr>
    //                 <td>${log.row_number}</td>
    //                 <td>${log.column_name}</td>
    //                 <td>${log.invalid_value}</td>
    //                 <td>${log.validation_message}</td>
    //             </tr>
    //         `);
    //     });
        
    //     $('#logsModal').modal('show');
    // });
}
</script>
@endpush