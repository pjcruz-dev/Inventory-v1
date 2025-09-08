@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Import Assets</h6>
                        <a href="{{ route('assets.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to Assets
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('warning'))
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="ni ni-bell-55"></i></span>
                            <span class="alert-text">{{ session('warning') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            @if(session('errors'))
                                <div class="mt-3">
                                    <strong>Import Errors:</strong>
                                    <ul class="mb-0">
                                        @foreach(session('errors') as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="ni ni-support-16"></i></span>
                            <span class="alert-text">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Import Instructions -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card bg-gradient-light">
                                <div class="card-body">
                                    <h6 class="text-dark"><i class="fas fa-info-circle me-2"></i>Import Instructions</h6>
                                    <div class="text-sm text-dark">
                                        <ol class="mb-2">
                                            <li>Download the import template using the button below</li>
                                            <li>Fill in your asset data following the template format</li>
                                            <li>Save the file as Excel (.xlsx, .xls) or CSV format</li>
                                            <li>Upload the file using the form below</li>
                                        </ol>
                                        <p class="mb-0"><strong>Note:</strong> Asset tags must be unique. If an asset with the same tag exists, it will be updated with the new data.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Template -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Step 1: Download Template</h6>
                                </div>
                                <div class="card-body">
                                    <p class="text-sm mb-3">Download the asset import template to ensure your data is in the correct format.</p>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('export.template', ['type' => 'xlsx']) }}" class="btn bg-gradient-success btn-sm">
                                            <i class="fas fa-file-excel me-2"></i>Download Excel Template
                                        </a>
                                        <a href="{{ route('export.template', ['type' => 'csv']) }}" class="btn bg-gradient-info btn-sm">
                                            <i class="fas fa-file-csv me-2"></i>Download CSV Template
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Upload Form -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6>Step 2: Upload Your File</h6>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('import.process') }}" enctype="multipart/form-data">
                                        @csrf
                                        
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="file" class="form-control-label">Select File <span class="text-danger">*</span></label>
                                                    <input class="form-control @error('file') is-invalid @enderror" 
                                                           type="file" 
                                                           id="file" 
                                                           name="file" 
                                                           accept=".xlsx,.xls,.csv"
                                                           required>
                                                    <small class="form-text text-muted">
                                                        Supported formats: Excel (.xlsx, .xls) and CSV (.csv). Maximum file size: 10MB.
                                                    </small>
                                                    @error('file')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="form-group w-100">
                                                    <button type="submit" class="btn bg-gradient-primary w-100">
                                                        <i class="fas fa-upload me-2"></i>Import Assets
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Import Tips -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-gradient-secondary">
                                <div class="card-body">
                                    <h6 class="text-white"><i class="fas fa-lightbulb me-2"></i>Import Tips</h6>
                                    <div class="text-sm text-white">
                                        <ul class="mb-0">
                                            <li><strong>Asset Tag:</strong> Must be unique and is required for each asset</li>
                                            <li><strong>Asset Type:</strong> Must match an existing asset type name exactly</li>
                                            <li><strong>Status:</strong> Use one of: available, assigned, in_repair, disposed</li>
                                            <li><strong>Assigned User:</strong> Use the user's email address if status is 'assigned'</li>
                                            <li><strong>Dates:</strong> Use YYYY-MM-DD format (e.g., 2023-12-25)</li>
                                            <li><strong>Cost:</strong> Enter numeric values only (e.g., 1500.00)</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// File upload validation
document.getElementById('file').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const fileSize = file.size / 1024 / 1024; // Convert to MB
        const allowedTypes = ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 
                             'application/vnd.ms-excel', 
                             'text/csv'];
        
        if (fileSize > 10) {
            alert('File size must be less than 10MB');
            e.target.value = '';
            return;
        }
        
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid Excel or CSV file');
            e.target.value = '';
            return;
        }
    }
});
</script>
@endsection