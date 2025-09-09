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
                    <h5 class="mb-1">Edit Department</h5>
                    <p class="mb-0 text-sm">Update department information and hierarchy</p>
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
                            <p class="text-sm mb-0">Update the details for {{ $department->name }}</p>
                        </div>
                        <div>
                            <a href="{{ route('departments.show', $department) }}" class="btn bg-gradient-info btn-sm mb-0 me-2">
                                <i class="fas fa-eye me-2"></i>View
                            </a>
                            <a href="{{ route('departments.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                                <i class="fas fa-arrow-left me-2"></i>Back to Departments
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

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

                    <!-- Department Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-gradient-primary">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Sub-Departments</p>
                                                <h5 class="text-white font-weight-bolder mb-0">{{ $department->children->count() }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                <i class="fas fa-layer-group text-dark text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-success">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Projects</p>
                                                <h5 class="text-white font-weight-bolder mb-0">{{ $department->projects->count() }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                <i class="fas fa-project-diagram text-dark text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-info">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Created</p>
                                                <h6 class="text-white font-weight-bolder mb-0">{{ $department->created_at->format('M Y') }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                <i class="fas fa-calendar text-dark text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-warning">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Hierarchy Level</p>
                                                <h5 class="text-white font-weight-bolder mb-0">{{ $department->parent ? 'Sub' : 'Root' }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                <i class="fas fa-sitemap text-dark text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('departments.update', $department) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Department Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $department->name) }}" 
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
                                            <option value="{{ $parentDept->id }}" 
                                                {{ old('parent_id', $department->parent_id) == $parentDept->id ? 'selected' : '' }}>
                                                {{ $parentDept->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        @if($department->children->count() > 0)
                                            <span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>This department has {{ $department->children->count() }} sub-department(s)</span>
                                        @else
                                            Leave empty to make this a root department
                                        @endif
                                    </small>
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
                                              placeholder="Enter department description, responsibilities, and objectives">{{ old('description', $department->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Describe the department's role, responsibilities, and objectives</small>
                                </div>
                            </div>
                        </div>

                        <!-- Current Hierarchy Display -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card bg-gradient-light">
                                    <div class="card-header pb-0">
                                        <h6 class="text-dark">Current Department Hierarchy</h6>
                                    </div>
                                    <div class="card-body">
                                        <div id="currentHierarchy" class="text-sm">
                                            @php
                                                $hierarchy = [];
                                                $current = $department;
                                                while ($current) {
                                                    array_unshift($hierarchy, $current);
                                                    $current = $current->parent;
                                                }
                                            @endphp
                                            
                                            @foreach($hierarchy as $index => $dept)
                                                <div class="{{ $dept->id === $department->id ? 'text-primary font-weight-bold' : 'text-dark' }}">
                                                    {!! str_repeat('&nbsp;', $index * 4) !!}
                                                    @if($index === 0)
                                                        <i class="fas fa-building me-2"></i>
                                                    @else
                                                        <i class="fas fa-arrow-right me-2"></i>
                                                    @endif
                                                    {{ $dept->name }}
                                                    @if($dept->id === $department->id)
                                                        <span class="badge badge-sm bg-gradient-primary ms-2">Current</span>
                                                    @endif
                                                </div>
                                            @endforeach
                                            
                                            @if($department->children->count() > 0)
                                                @foreach($department->children as $child)
                                                    <div class="text-secondary">
                                                        {!! str_repeat('&nbsp;', count($hierarchy) * 4) !!}
                                                        <i class="fas fa-arrow-right me-2"></i>
                                                        {{ $child->name }}
                                                        <span class="badge badge-sm bg-gradient-secondary ms-2">Sub-Department</span>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('departments.show', $department) }}" class="btn btn-light me-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="fas fa-save me-2"></i>Update Department
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Warning for Hierarchy Changes -->
    @if($department->children->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <h6 class="text-warning"><i class="fas fa-exclamation-triangle me-2"></i>Hierarchy Change Warning</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <strong>Important:</strong> This department has {{ $department->children->count() }} sub-department(s). 
                            Changing the parent department will affect the entire hierarchy structure. 
                            Please ensure this change is intentional and review the impact on:
                            <ul class="mt-2 mb-0">
                                <li>Sub-department organization</li>
                                <li>Project assignments and access</li>
                                <li>User permissions and roles</li>
                            </ul>
                        </div>
                        
                        <h6 class="mt-3">Current Sub-Departments:</h6>
                        <div class="row">
                            @foreach($department->children as $child)
                                <div class="col-md-6 mb-2">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-arrow-right text-secondary me-2"></i>
                                        <span class="text-sm">{{ $child->name }}</span>
                                        @if($child->projects->count() > 0)
                                            <span class="badge badge-sm bg-gradient-info ms-2">{{ $child->projects->count() }} projects</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Department hierarchy data
    const departments = @json($parentDepartments->map(function($dept) {
        return [
            'id' => $dept->id,
            'name' => $dept->name,
            'parent_id' => $dept->parent_id
        ];
    }));
    
    const currentDepartment = {
        id: {{ $department->id }},
        name: '{{ $department->name }}',
        parent_id: {{ $department->parent_id ?? 'null' }}
    };
    
    // Form validation
    $('form').on('submit', function(e) {
        const name = $('#name').val().trim();
        const parentId = $('#parent_id').val();
        
        // Validate required fields
        if (name === '') {
            e.preventDefault();
            $('#name').addClass('is-invalid');
            if (!$('#name').next('.invalid-feedback').length) {
                $('#name').after('<div class="invalid-feedback">Department name is required.</div>');
            }
            return false;
        }
        
        // Prevent circular hierarchy (department cannot be its own parent or descendant)
        if (parentId && parentId == currentDepartment.id) {
            e.preventDefault();
            $('#parent_id').addClass('is-invalid');
            if (!$('#parent_id').next('.invalid-feedback').length) {
                $('#parent_id').after('<div class="invalid-feedback">A department cannot be its own parent.</div>');
            }
            return false;
        }
        
        // Check if the selected parent is a descendant of current department
        if (parentId && isDescendant(parseInt(parentId), currentDepartment.id)) {
            e.preventDefault();
            $('#parent_id').addClass('is-invalid');
            if (!$('#parent_id').next('.invalid-feedback').length) {
                $('#parent_id').after('<div class="invalid-feedback">Cannot select a sub-department as parent. This would create a circular hierarchy.</div>');
            }
            return false;
        }
        
        // Confirm hierarchy change if department has children
        @if($department->children->count() > 0)
            if (parentId != '{{ $department->parent_id ?? "" }}') {
                if (!confirm('This department has sub-departments. Changing the parent will affect the entire hierarchy. Are you sure you want to continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
        @endif
    });
    
    // Function to check if a department is a descendant of another
    function isDescendant(potentialParentId, departmentId) {
        // Get all departments that have the current department as ancestor
        const children = getDescendants(departmentId);
        return children.includes(potentialParentId);
    }
    
    // Get all descendants of a department
    function getDescendants(departmentId) {
        let descendants = [];
        
        // Find direct children
        const directChildren = departments.filter(dept => dept.parent_id == departmentId);
        
        directChildren.forEach(child => {
            descendants.push(child.id);
            // Recursively get descendants of children
            descendants = descendants.concat(getDescendants(child.id));
        });
        
        return descendants;
    }
    
    // Remove validation errors on input
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
});
</script>
@endpush