@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-tags position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Edit Asset Category</h5>
                    <p class="mb-0 text-sm">Update asset category information</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Category Information</h5>
                            <p class="text-sm mb-0">Update the details for {{ $category->name }}</p>
                        </div>
                        <div>
                            <a href="{{ route('asset-categories.index') }}" class="btn bg-gradient-secondary btn-sm mb-0 me-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Categories
                            </a>
                            <a href="{{ route('asset-categories.show', $category) }}" class="btn bg-gradient-info btn-sm mb-0">
                                <i class="fas fa-eye me-2"></i>View Category
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                            <span class="alert-text">
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('asset-categories.update', $category) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Category Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $category->name) }}" 
                                           placeholder="Enter category name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header pb-0">
                                        <h6 class="mb-0">Category Statistics</h6>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="font-weight-bolder text-info">{{ $category->assets->count() }}</h4>
                                                    <p class="mb-0 text-sm">Total Assets</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="font-weight-bolder text-success">{{ $category->logs->count() }}</h4>
                                                    <p class="mb-0 text-sm">Activity Logs</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="text-center">
                                                    <h4 class="font-weight-bolder text-secondary">{{ $category->created_at->format('M d, Y') }}</h4>
                                                    <p class="mb-0 text-sm">Created Date</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('asset-categories.index') }}" class="btn btn-light me-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="fas fa-save me-2"></i>Update Category
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var name = $('#name').val().trim();
        
        if (name === '') {
            e.preventDefault();
            $('#name').addClass('is-invalid');
            if (!$('#name').next('.invalid-feedback').length) {
                $('#name').after('<div class="invalid-feedback">Category name is required.</div>');
            }
            return false;
        }
    });
    
    // Remove validation errors on input
    $('#name').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
});
</script>
@endpush