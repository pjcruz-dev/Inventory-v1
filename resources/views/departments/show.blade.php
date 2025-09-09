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
                    <h5 class="mb-1">{{ $department->name }}</h5>
                    <p class="mb-0 text-sm">Department Details and Hierarchy</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('departments.edit', $department) }}" class="btn bg-gradient-info btn-sm mb-0 me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('departments.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left me-2"></i>Back to Departments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
            <span class="alert-text">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Quick Statistics -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Sub-Departments</p>
                                <h5 class="font-weight-bolder mb-0">{{ $department->children->count() }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-layer-group text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Projects</p>
                                <h5 class="font-weight-bolder mb-0">{{ $department->projects->count() }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-project-diagram text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Hierarchy Level</p>
                                <h5 class="font-weight-bolder mb-0">{{ $department->parent ? 'Sub' : 'Root' }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-sitemap text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Created</p>
                                <h6 class="font-weight-bolder mb-0">{{ $department->created_at->format('M Y') }}</h6>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-calendar text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Department Information -->
        <div class="col-12 col-lg-8">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6>Department Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Department Name</label>
                                <p class="form-control-static font-weight-bold">{{ $department->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Parent Department</label>
                                <p class="form-control-static">
                                    @if($department->parent)
                                        <a href="{{ route('departments.show', $department->parent) }}" class="text-primary">
                                            <i class="fas fa-external-link-alt me-1"></i>{{ $department->parent->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">Root Department</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    @if($department->description)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Description</label>
                                    <p class="form-control-static">{{ $department->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Created Date</label>
                                <p class="form-control-static">{{ $department->created_at->format('F j, Y \\a\\t g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Last Updated</label>
                                <p class="form-control-static">{{ $department->updated_at->format('F j, Y \\a\\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="col-12 col-lg-4">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('departments.edit', $department) }}" class="btn bg-gradient-info btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Department
                        </a>
                        
                        @if($department->projects->count() === 0 && $department->children->count() === 0)
                            <button type="button" class="btn bg-gradient-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal">
                                <i class="fas fa-trash me-2"></i>Delete Department
                            </button>
                        @endif
                        
                        <a href="{{ route('projects.create', ['department_id' => $department->id]) }}" class="btn bg-gradient-success btn-sm">
                            <i class="fas fa-plus me-2"></i>Add Project
                        </a>
                        
                        <a href="{{ route('departments.create', ['parent_id' => $department->id]) }}" class="btn bg-gradient-primary btn-sm">
                            <i class="fas fa-plus me-2"></i>Add Sub-Department
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Hierarchy -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6>Department Hierarchy</h6>
                </div>
                <div class="card-body">
                    @php
                        $hierarchy = [];
                        $current = $department;
                        while ($current) {
                            array_unshift($hierarchy, $current);
                            $current = $current->parent;
                        }
                    @endphp
                    
                    <div class="hierarchy-tree">
                        @foreach($hierarchy as $index => $dept)
                            <div class="hierarchy-item {{ $dept->id === $department->id ? 'current-department' : '' }}" style="margin-left: {{ $index * 30 }}px;">
                                <div class="d-flex align-items-center mb-2">
                                    @if($index === 0)
                                        <i class="fas fa-building text-primary me-2"></i>
                                    @else
                                        <i class="fas fa-arrow-right text-secondary me-2"></i>
                                    @endif
                                    
                                    @if($dept->id === $department->id)
                                        <strong class="text-primary">{{ $dept->name }}</strong>
                                        <span class="badge badge-sm bg-gradient-primary ms-2">Current</span>
                                    @else
                                        <a href="{{ route('departments.show', $dept) }}" class="text-dark text-decoration-none">
                                            {{ $dept->name }}
                                        </a>
                                    @endif
                                    
                                    @if($dept->projects->count() > 0)
                                        <span class="badge badge-sm bg-gradient-success ms-2">{{ $dept->projects->count() }} projects</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        
                        <!-- Show sub-departments -->
                        @if($department->children->count() > 0)
                            @foreach($department->children as $child)
                                <div class="hierarchy-item" style="margin-left: {{ count($hierarchy) * 30 }}px;">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-arrow-right text-secondary me-2"></i>
                                        <a href="{{ route('departments.show', $child) }}" class="text-dark text-decoration-none">
                                            {{ $child->name }}
                                        </a>
                                        <span class="badge badge-sm bg-gradient-secondary ms-2">Sub-Department</span>
                                        @if($child->projects->count() > 0)
                                            <span class="badge badge-sm bg-gradient-success ms-2">{{ $child->projects->count() }} projects</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Projects -->
    @if($department->projects->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Associated Projects</h5>
                                <p class="text-sm mb-0">Projects assigned to this department</p>
                            </div>
                            <div>
                                <a href="{{ route('projects.create', ['department_id' => $department->id]) }}" class="btn bg-gradient-primary btn-sm mb-0">
                                    <i class="fas fa-plus me-2"></i>Add Project
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Project</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End Date</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($department->projects as $project)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $project->name }}</h6>
                                                        @if($project->description)
                                                            <p class="text-xs text-secondary mb-0">{{ Str::limit($project->description, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'planning' => 'secondary',
                                                        'active' => 'success',
                                                        'on_hold' => 'warning',
                                                        'completed' => 'info',
                                                        'cancelled' => 'danger'
                                                    ];
                                                    $statusColor = $statusColors[$project->status] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-sm bg-gradient-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">{{ $project->start_date ? $project->start_date->format('M j, Y') : 'Not set' }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">{{ $project->end_date ? $project->end_date->format('M j, Y') : 'Not set' }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('projects.show', $project) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View project">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('projects.edit', $project) }}" class="text-secondary font-weight-bold text-xs ms-2" data-toggle="tooltip" data-original-title="Edit project">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Sub-Departments -->
    @if($department->children->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h5 class="mb-0">Sub-Departments</h5>
                                <p class="text-sm mb-0">Departments under {{ $department->name }}</p>
                            </div>
                            <div>
                                <a href="{{ route('departments.create', ['parent_id' => $department->id]) }}" class="btn bg-gradient-primary btn-sm mb-0">
                                    <i class="fas fa-plus me-2"></i>Add Sub-Department
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Sub-Departments</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Projects</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($department->children as $child)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $child->name }}</h6>
                                                        @if($child->description)
                                                            <p class="text-xs text-secondary mb-0">{{ Str::limit($child->description, 50) }}</p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-xs font-weight-bold">{{ $child->children->count() }}</span>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                <span class="text-xs font-weight-bold">{{ $child->projects->count() }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $child->created_at->format('M j, Y') }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="{{ route('departments.show', $child) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View department">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('departments.edit', $child) }}" class="text-secondary font-weight-bold text-xs ms-2" data-toggle="tooltip" data-original-title="Edit department">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Danger Zone -->
    @if($department->projects->count() === 0 && $department->children->count() === 0)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <h6 class="text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger" role="alert">
                            <strong>Delete Department:</strong> This action cannot be undone. This will permanently delete the department and remove all associated data.
                        </div>
                        <button type="button" class="btn bg-gradient-danger" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal">
                            <i class="fas fa-trash me-2"></i>Delete Department
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @elseif($department->projects->count() > 0 || $department->children->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <h6 class="text-warning"><i class="fas fa-shield-alt me-2"></i>Department Protection</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning" role="alert">
                            <strong>Cannot Delete:</strong> This department cannot be deleted because it has:
                            <ul class="mb-0 mt-2">
                                @if($department->projects->count() > 0)
                                    <li>{{ $department->projects->count() }} associated project(s)</li>
                                @endif
                                @if($department->children->count() > 0)
                                    <li>{{ $department->children->count() }} sub-department(s)</li>
                                @endif
                            </ul>
                            <small class="d-block mt-2">Remove all associated projects and sub-departments before deleting this department.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Department Modal -->
@if($department->projects->count() === 0 && $department->children->count() === 0)
    <div class="modal fade" id="deleteDepartmentModal" tabindex="-1" aria-labelledby="deleteDepartmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteDepartmentModalLabel">Delete Department</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete the department <strong>{{ $department->name }}</strong>?</p>
                    <p class="text-danger"><strong>This action cannot be undone.</strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('departments.destroy', $department) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Department</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('styles')
<style>
.hierarchy-tree {
    font-family: 'Roboto', sans-serif;
}

.hierarchy-item {
    position: relative;
}

.hierarchy-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 8px;
    top: 25px;
    bottom: -10px;
    width: 1px;
    background-color: #dee2e6;
}

.current-department {
    background-color: rgba(52, 144, 220, 0.1);
    border-radius: 5px;
    padding: 5px;
    margin: 2px 0;
}

.form-control-static {
    padding-top: 7px;
    padding-bottom: 7px;
    margin-bottom: 0;
    min-height: 34px;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush