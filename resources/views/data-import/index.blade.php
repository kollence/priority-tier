@extends('layouts.admin')
@section('title', __('Data Import'))
@section('content')
<h1 class="mt-4">{{ __('Data Import') }}</h1>
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

        <form  method="POST" action="{{ route('data-import.upload') }}" enctype="multipart/form-data" class="needs-validation" id="importForm" novalidate>
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="import_type" class="form-label">Import Type</label>
                        <select class="form-select @error('import_type') is-invalid @enderror" id="import_type" name="import_type" required>
                            <option value="">Select import type...</option>
                            @foreach($importTypes as $key1 => $type)
                                @can($type['permission_required'])
                                    @foreach($type['files'] as $key2 => $file)
                                    <option value="{{ $key1 }}-{{ $key2 }}">{{$type['label']}} {{$file['label']}}</option>
                                    @endforeach
                                @endcan
                            @endforeach
                        </select>
                        @error('import_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="mb-3">
                        <label for="file" class="form-label">Select File</label>
                        <input type="file"
                            class="form-control @error('file') is-invalid @enderror"
                            id="file" name="file[]" accept=".xlsx,.csv" multiple required>
                            <div id="configInfo" class="d-none mb-3">
                                <div class="alert-info">
                                <small>Required Headers:</small>
                                    <span class="required-headers mb-0">
                                    
                                    </span>
                                </div>
                            </div>
                        @error('file')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary" id="submitBtn">
                    <i class="fas fa-upload me-2"></i>Import
                </button>
                <a href="#" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#templateModal">
                    <i class="fas fa-download me-2"></i>Download Template
                </a>
            </div>
        </form>
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

<div class="modal fade" id="templateModal" tabindex="-1" aria-labelledby="templateModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="templateModalLabel">Download Import Template</h5>
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
// ■ The workflow should look like this:
// ■ User comes to the page, he is presented with a dropdown of only the import types he has a
// permission for
// ■ He then picks what he wants to import from the Import Orders dropdown, and based on what he
// selects, the "Required Headers" for that import type should be shown below the file input like in
// the picture
$(document).ready(function() {
    const importTypes = @json($importTypes);

    $('#import_type').change(function() {
        const type = $(this).val();
        const [importType, fileType] = type.split('-');
        // console.log(importType, fileType);
        
        const configInfo = $('#configInfo');
        const headersList = configInfo.find('.required-headers');
        
                
        if (importType && importTypes[importType]) {
            headersList.empty();
            const headers = importTypes[importType].files[fileType].headers_to_db;
            
            Object.entries(headers).forEach(([key, config]) => {
                const hasRequired = Array.isArray(config.validation) 
                    ? config.validation.includes('required')
                    : Object.values(config.validation).includes('required');
                const required = hasRequired ? '*' : '';
                
                headersList.append(`<small class="text-xs">${config.label}<b class="text-danger">${required}</b>, </small>`);
            });
            
            configInfo.removeClass('d-none');
        } else {
            configInfo.addClass('d-none');
        }
    });

    $('#importForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $('#submitBtn').prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Processing...');
        $('#errorAlert, #successAlert').addClass('d-none');

        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // ■ Display a notification to the user that the import is in progress and will be notified when it is complete.
                $('#successAlert').removeClass('d-none').text(response.success);
                $('#importForm')[0].reset();
            },
            error: function(xhr) {
                const message = xhr.responseJSON?.errors || 'An error occurred';
                $('#errorAlert').removeClass('d-none').text(message);
            },
            complete: function() {
                $('#submitBtn').prop('disabled', false).html('<i class="fas fa-upload me-2"></i>Import');
            }
        });
    });
});
</script>
@endpush

@endsection