@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-sitemap position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create Department</h5>
                    <p class="mb-0 text-sm">Add a new department to the organizational structure</p>
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
                            <h5 class="mb-0">Department Information</h5>
                            <p class="text-sm mb-0">Fill in the details for the new department</p>
                        </div>
                        <div>
                            <a href="{{ route('departments.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                                <i class="fas fa-arrow-left me-2"></i>Back to Departments
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

                    <form action="{{ route('departments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Department Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Enter department name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for the department</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="parent_id" class="form-control-label">Parent Department</label>
                                    <select class="form-control @error('parent_id') is-invalid @enderror" 
                                            id="parent_id" 
                                            name="parent_id">
                                        <option value="">Select Parent Department (Optional)</option>
                                        @foreach($parentDepartments as $parentDept)
                                            <option value="{{ $parentDept->id }}" {{ old('parent_id') == $parentDept->id ? 'selected' : '' }}>
                                                {{ $parentDept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Leave empty to create a root department</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="4" 
                                              placeholder="Enter department description, responsibilities, and objectives">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Describe the department's role, responsibilities, and objectives</small>
                                </div>
                            </div>
                        </div>

                        <!-- Department Hierarchy Preview -->
                        <div class="row" id="hierarchyPreview" style="display: none;">
                            <div class="col-12">
                                <div class="card bg-gradient-light">
                                    <div class="card-header pb-0">
                                        <h6 class="text-dark">Department Hierarchy Preview</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="hierarchyTree" class="text-sm">
                                            <!-- Hierarchy will be populated by JavaScript -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('departments.index') }}" class="btn btn-light me-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="fas fa-save me-2"></i>Create Department
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Help Section -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6>Department Structure Guidelines</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-item">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md mb-3">
                                    <i class="fas fa-building text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h6>Root Departments</h6>
                                <p class="text-sm">Create main organizational divisions like "Engineering", "Sales", or "HR" without selecting a parent.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md mb-3">
                                    <i class="fas fa-layer-group text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h6>Sub-Departments</h6>
                                <p class="text-sm">Create specialized teams within larger departments by selecting a parent department.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-item">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md mb-3">
                                    <i class="fas fa-project-diagram text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                <h6>Project Assignment</h6>
                                <p class="text-sm">Once created, departments can be assigned projects and manage their own resources.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-focus on the name field
    $('#name').focus();
    
    // Department hierarchy data
    const departments = @json($parentDepartments->map(function($dept) {
        return [
            'id' => $dept->id,
            'name' => $dept->name,
            'parent_id' => $dept->parent_id
        ];
    }));
    
    // Update hierarchy preview when parent is selected
    $('#parent_id').on('change', function() {
        const selectedParentId = $(this).val();
        updateHierarchyPreview(selectedParentId);
    });
    
    function updateHierarchyPreview(parentId) {
        const hierarchyPreview = $('#hierarchyPreview');
        const hierarchyTree = $('#hierarchyTree');
        
        if (!parentId) {
            hierarchyPreview.hide();
            return;
        }
        
        // Find the selected parent and build hierarchy
        const selectedParent = departments.find(dept => dept.id == parentId);
        if (!selectedParent) {
            hierarchyPreview.hide();
            return;
        }
        
        // Build hierarchy path
        let hierarchyPath = [];
        let currentDept = selectedParent;
        
        // Build path from selected parent to root
        while (currentDept) {
            hierarchyPath.unshift(currentDept);
            currentDept = departments.find(dept => dept.id == currentDept.parent_id);
        }
        
        // Add the new department
        const newDeptName = $('#name').val() || 'New Department';
        hierarchyPath.push({ name: newDeptName, id: 'new' });
        
        // Generate hierarchy HTML
        let hierarchyHtml = '';
        hierarchyPath.forEach((dept, index) => {
            const indent = '&nbsp;'.repeat(index * 4);
            const icon = index === 0 ? '<i class="fas fa-building me-2"></i>' : '<i class="fas fa-arrow-right me-2"></i>';
            const isNew = dept.id === 'new';
            const textClass = isNew ? 'text-primary font-weight-bold' : 'text-dark';
            
            hierarchyHtml += `<div class="${textClass}">${indent}${icon}${dept.name}</div>`;
        });
        
        hierarchyTree.html(hierarchyHtml);
        hierarchyPreview.show();
    }
    
    // Update preview when name changes
    $('#name').on('input', function() {
        const parentId = $('#parent_id').val();
        if (parentId) {
            updateHierarchyPreview(parentId);
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        const name = $('#name').val().trim();
        
        // Validate required fields
        if (name === '') {
            e.preventDefault();
            $('#name').addClass('is-invalid');
            if (!$('#name').next('.invalid-feedback').length) {
                $('#name').after('<div class="invalid-feedback">Department name is required.</div>');
            }
            return false;
        }
        
        // Check for duplicate names (basic client-side check)
        const parentId = $('#parent_id').val();
        const existingNames = departments
            .filter(dept => dept.parent_id == parentId || (!dept.parent_id && !parentId))
            .map(dept => dept.name.toLowerCase());
            
        if (existingNames.includes(name.toLowerCase())) {
            e.preventDefault();
            $('#name').addClass('is-invalid');
            if (!$('#name').next('.invalid-feedback').length) {
                $('#name').after('<div class="invalid-feedback">A department with this name already exists at this level.</div>');
            }
            return false;
        }
    });
    
    // Remove validation errors on input
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
});
</script>
@endpush