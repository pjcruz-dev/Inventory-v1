@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-edit position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Edit Project: {{ $project->name }}</h5>
                    <p class="mb-0 text-sm">Update project information and settings</p>
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
                            <h5 class="mb-0">Project Information</h5>
                            <p class="text-sm mb-0">Update the details for this project</p>
                        </div>
                        <div>
                            <a href="{{ route('projects.show', $project) }}" class="btn bg-gradient-info btn-sm mb-0 me-2">
                                <i class="fas fa-eye me-2"></i>View Project
                            </a>
                            <a href="{{ route('projects.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                                <i class="fas fa-arrow-left me-2"></i>Back to Projects
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

                    <!-- Project Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                <i class="fas fa-calendar-alt text-dark text-sm"></i>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Created</p>
                                    <h6 class="mb-0">{{ $project->created_at->format('M d, Y') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                <i class="fas fa-clock text-dark text-sm"></i>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Last Updated</p>
                                    <h6 class="mb-0">{{ $project->updated_at->format('M d, Y') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                <i class="fas fa-chart-line text-dark text-sm"></i>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Progress</p>
                                    <h6 class="mb-0">{{ $project->progress ?? 0 }}%</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card card-body border card-plain border-radius-lg d-flex align-items-center flex-row">
                                <i class="fas fa-dollar-sign text-dark text-sm"></i>
                                <div class="ms-3">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Budget Used</p>
                                    <h6 class="mb-0">
                                        @if($project->budget && $project->budget > 0)
                                            {{ number_format(($project->spent_budget ?? 0) / $project->budget * 100, 1) }}%
                                        @else
                                            N/A
                                        @endif
                                    </h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('projects.update', $project) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Project Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $project->name) }}" 
                                           placeholder="Enter project name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Choose a clear, descriptive name for the project</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="department_id" class="form-control-label">Department <span class="text-danger">*</span></label>
                                    <select class="form-control @error('department_id') is-invalid @enderror" 
                                            id="department_id" 
                                            name="department_id" 
                                            required>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}" 
                                                {{ old('department_id', $project->department_id) == $department->id ? 'selected' : '' }}>
                                                {{ $department->parent ? $department->parent->name . ' > ' : '' }}{{ $department->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('department_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Select the department responsible for this project</small>
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
                                              placeholder="Enter project description, objectives, and scope">{{ old('description', $project->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Describe the project's objectives, scope, and expected outcomes</small>
                                </div>
                            </div>
                        </div>

                        <!-- Project Status and Priority -->
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status" 
                                            required>
                                        <option value="planning" {{ old('status', $project->status) == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status', $project->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="on_hold" {{ old('status', $project->status) == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                        <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status', $project->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Current status of the project</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="priority" class="form-control-label">Priority <span class="text-danger">*</span></label>
                                    <select class="form-control @error('priority') is-invalid @enderror" 
                                            id="priority" 
                                            name="priority" 
                                            required>
                                        <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="critical" {{ old('priority', $project->priority) == 'critical' ? 'selected' : '' }}>Critical</option>
                                    </select>
                                    @error('priority')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Priority level of the project</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="progress" class="form-control-label">Progress (%)</label>
                                    <input class="form-control @error('progress') is-invalid @enderror" 
                                           type="number" 
                                           id="progress" 
                                           name="progress" 
                                           value="{{ old('progress', $project->progress) }}" 
                                           min="0" 
                                           max="100" 
                                           placeholder="0">
                                    @error('progress')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Current completion percentage (0-100)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Project Timeline -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="start_date" class="form-control-label">Start Date</label>
                                    <input class="form-control @error('start_date') is-invalid @enderror" 
                                           type="date" 
                                           id="start_date" 
                                           name="start_date" 
                                           value="{{ old('start_date', $project->start_date ? $project->start_date->format('Y-m-d') : '') }}">
                                    @error('start_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">When the project is scheduled to start</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="end_date" class="form-control-label">End Date</label>
                                    <input class="form-control @error('end_date') is-invalid @enderror" 
                                           type="date" 
                                           id="end_date" 
                                           name="end_date" 
                                           value="{{ old('end_date', $project->end_date ? $project->end_date->format('Y-m-d') : '') }}">
                                    @error('end_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Expected completion date</small>
                                </div>
                            </div>
                        </div>

                        <!-- Budget Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="budget" class="form-control-label">Budget</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input class="form-control @error('budget') is-invalid @enderror" 
                                               type="number" 
                                               id="budget" 
                                               name="budget" 
                                               value="{{ old('budget', $project->budget) }}" 
                                               step="0.01" 
                                               min="0" 
                                               placeholder="0.00">
                                    </div>
                                    @error('budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Total allocated budget for the project</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="spent_budget" class="form-control-label">Spent Budget</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input class="form-control @error('spent_budget') is-invalid @enderror" 
                                               type="number" 
                                               id="spent_budget" 
                                               name="spent_budget" 
                                               value="{{ old('spent_budget', $project->spent_budget) }}" 
                                               step="0.01" 
                                               min="0" 
                                               placeholder="0.00">
                                    </div>
                                    @error('spent_budget')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Amount already spent on the project</small>
                                </div>
                            </div>
                        </div>

                        <!-- Project Manager and Team -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="manager_name" class="form-control-label">Project Manager</label>
                                    <input class="form-control @error('manager_name') is-invalid @enderror" 
                                           type="text" 
                                           id="manager_name" 
                                           name="manager_name" 
                                           value="{{ old('manager_name', $project->manager_name) }}" 
                                           placeholder="Enter project manager name">
                                    @error('manager_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Name of the person managing this project</small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="team_size" class="form-control-label">Team Size</label>
                                    <input class="form-control @error('team_size') is-invalid @enderror" 
                                           type="number" 
                                           id="team_size" 
                                           name="team_size" 
                                           value="{{ old('team_size', $project->team_size) }}" 
                                           min="1" 
                                           placeholder="1">
                                    @error('team_size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Number of team members working on this project</small>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes" class="form-control-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Additional notes, requirements, or special considerations">{{ old('notes', $project->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Any additional notes, requirements, or special considerations</small>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('projects.show', $project) }}" class="btn btn-light me-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="fas fa-save me-2"></i>Update Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Change Warning -->
    @if($project->status === 'completed' && $project->progress < 100)
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 bg-gradient-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-exclamation-triangle text-white me-3" style="font-size: 24px;"></i>
                        <div>
                            <h6 class="text-white mb-1">Status Inconsistency Detected</h6>
                            <p class="text-white mb-0 text-sm">
                                This project is marked as "Completed" but progress is only {{ $project->progress ?? 0 }}%. 
                                Consider updating the progress to 100% or changing the status.
                            </p>
                        </div>
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
    // Form validation
    $('form').on('submit', function(e) {
        const name = $('#name').val().trim();
        const departmentId = $('#department_id').val();
        const status = $('#status').val();
        const priority = $('#priority').val();
        const startDate = $('#start_date').val();
        const endDate = $('#end_date').val();
        const budget = parseFloat($('#budget').val()) || 0;
        const spentBudget = parseFloat($('#spent_budget').val()) || 0;
        const progress = parseInt($('#progress').val()) || 0;
        
        // Validate required fields
        if (name === '') {
            e.preventDefault();
            $('#name').addClass('is-invalid');
            if (!$('#name').next('.invalid-feedback').length) {
                $('#name').after('<div class="invalid-feedback">Project name is required.</div>');
            }
            return false;
        }
        
        if (departmentId === '') {
            e.preventDefault();
            $('#department_id').addClass('is-invalid');
            if (!$('#department_id').next('.invalid-feedback').length) {
                $('#department_id').after('<div class="invalid-feedback">Please select a department.</div>');
            }
            return false;
        }
        
        // Validate date range
        if (startDate && endDate && new Date(startDate) > new Date(endDate)) {
            e.preventDefault();
            $('#end_date').addClass('is-invalid');
            if (!$('#end_date').next('.invalid-feedback').length) {
                $('#end_date').after('<div class="invalid-feedback">End date must be after start date.</div>');
            }
            return false;
        }
        
        // Validate budget
        if (spentBudget > budget && budget > 0) {
            e.preventDefault();
            $('#spent_budget').addClass('is-invalid');
            if (!$('#spent_budget').next('.invalid-feedback').length) {
                $('#spent_budget').after('<div class="invalid-feedback">Spent budget cannot exceed total budget.</div>');
            }
            return false;
        }
        
        // Validate progress
        if (progress < 0 || progress > 100) {
            e.preventDefault();
            $('#progress').addClass('is-invalid');
            if (!$('#progress').next('.invalid-feedback').length) {
                $('#progress').after('<div class="invalid-feedback">Progress must be between 0 and 100.</div>');
            }
            return false;
        }
        
        // Warn about status changes
        const originalStatus = '{{ $project->status }}';
        if (status !== originalStatus) {
            if (status === 'completed' && progress < 100) {
                if (!confirm('You are marking this project as completed but progress is less than 100%. Continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            if (status === 'cancelled' && originalStatus !== 'cancelled') {
                if (!confirm('Are you sure you want to cancel this project? This action should be carefully considered.')) {
                    e.preventDefault();
                    return false;
                }
            }
        }
    });
    
    // Remove validation errors on input
    $('input, textarea, select').on('input change', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
    
    // Auto-calculate progress based on status
    $('#status').on('change', function() {
        const status = $(this).val();
        const progressField = $('#progress');
        const originalStatus = '{{ $project->status }}';
        
        if (status === 'completed' && progressField.val() < 100) {
            if (confirm('Set progress to 100% for completed project?')) {
                progressField.val(100);
            }
        } else if (status === 'planning' && originalStatus !== 'planning' && progressField.val() > 0) {
            if (confirm('Reset progress to 0% for planning status?')) {
                progressField.val(0);
            }
        }
    });
    
    // Budget calculation and warnings
    $('#budget, #spent_budget').on('input', function() {
        const budget = parseFloat($('#budget').val()) || 0;
        const spent = parseFloat($('#spent_budget').val()) || 0;
        
        if (budget > 0 && spent > 0) {
            const percentage = Math.round((spent / budget) * 100);
            const remaining = budget - spent;
            
            // Show budget warnings
            if (percentage > 100) {
                $('#spent_budget').addClass('is-invalid');
                if (!$('#spent_budget').next('.invalid-feedback').length) {
                    $('#spent_budget').after('<div class="invalid-feedback">Spent budget exceeds total budget by $' + Math.abs(remaining).toFixed(2) + '</div>');
                }
            } else if (percentage > 90) {
                $('#spent_budget').removeClass('is-invalid').addClass('is-warning');
                console.log('Warning: Budget utilization is ' + percentage + '%');
            }
        }
    });
    
    // Department change warning
    $('#department_id').on('change', function() {
        const originalDepartment = '{{ $project->department_id }}';
        const newDepartment = $(this).val();
        
        if (originalDepartment && newDepartment !== originalDepartment) {
            const departmentName = $(this).find('option:selected').text();
            if (!confirm('Are you sure you want to move this project to "' + departmentName + '"? This may affect project visibility and permissions.')) {
                $(this).val(originalDepartment);
            }
        }
    });
});
</script>
@endpush