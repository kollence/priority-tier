@extends('layouts.admin')
@section('title', __('Show Data Import'))
@section('content')
<h1 class="mt-4">{{ $type }} {{$file}}</h1>
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
                <a href="{{ route('data-import.export', [$type, $file]) }}" class="btn btn-success">Export</a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            @foreach($headers as $header)
                            <th>{{ $header['label'] }}</th>
                            @endforeach
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $row)
                        <tr>
                            @foreach($headers as $key => $header)
                            
                            <td>{{ $row->$key }}</td>
                            @endforeach
                            <td>
                                <button class="btn btn-sm btn-info"
                                    onclick="showAudits('{{ $row->id }}')">
                                    Audits
                                </button>
                                @can($importType['permission_required'])
                                <button class="btn btn-sm btn-danger"
                                    onclick="deleteRecord('{{ $row->id }}')">
                                    Delete
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
        <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#auditModal">
            <i class="fas fa-download me-2"></i>Audit
        </a>
        <div class="alert alert-danger d-none" id="errorAlert"></div>
        <div class="alert alert-success d-none" id="successAlert"></div>
        <div class="mt-4 d-none" id="importProgress">
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated"
                    role="progressbar"
                    style="width: 0%"
                    aria-valuenow="0"
                    aria-valuemin="0"
                    aria-valuemax="100">0%</div>
            </div>
            <small class="text-muted mt-2 d-block" id="importStatus">Preparing import...</small>
        </div>
    </div>
</div>

<div class="modal fade" id="auditModal" tabindex="-1" aria-labelledby="auditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="auditModalLabel">Download Import Template</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Select the template type you want to download:</p>
                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Users Import Template
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i>Products Import Template
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="fas fa-user-tie me-2"></i>Customers Import Template
                    </a>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
$(document).ready(function() {
    $('#searchBtn').click(function() {
        var query = $('#search').val();
        $.ajax({
            url: '{{ route("data-import.search") }}',
            type: 'GET',
            data: { query: query },
            success: function(response) {
                // Handle the response here
                console.log(response);
            },
            error: function(xhr) {
                // Handle error here
                console.error(xhr);
            }
        });
    });
});
</script>
@endpush

@endsection