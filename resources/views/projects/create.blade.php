@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-project-diagram position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create New Project</h5>
                    <p class="mb-0 text-sm">Add a new project to the system</p>
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
                            <p class="text-sm mb-0">Fill in the details for the new project</p>
                        </div>
                        <div>
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

                    <form action="{{ route('projects.store') }}" method="POST">
                        @csrf
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Project Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
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
                                                {{ old('department_id', request('department_id')) == $department->id ? 'selected' : '' }}>
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
                                              placeholder="Enter project description, objectives, and scope">{{ old('description') }}</textarea>
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
                                        <option value="planning" {{ old('status', 'planning') == 'planning' ? 'selected' : '' }}>Planning</option>
                                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="on_hold" {{ old('status') == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
                                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                        <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                                        <option value="critical" {{ old('priority') == 'critical' ? 'selected' : '' }}>Critical</option>
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
                                           value="{{ old('progress', 0) }}" 
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
                                           value="{{ old('start_date') }}">
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
                                           value="{{ old('end_date') }}">
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
                                               value="{{ old('budget') }}" 
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
                                               value="{{ old('spent_budget', 0) }}" 
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
                                           value="{{ old('manager_name') }}" 
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
                                           value="{{ old('team_size') }}" 
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
                                              placeholder="Additional notes, requirements, or special considerations">{{ old('notes') }}</textarea>
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
                                    <a href="{{ route('projects.index') }}" class="btn btn-light me-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="fas fa-save me-2"></i>Create Project
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Creation Tips -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-lightbulb me-2"></i>Project Creation Tips</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-sm font-weight-bold">Best Practices:</h6>
                            <ul class="text-sm text-secondary">
                                <li>Choose a clear, descriptive project name</li>
                                <li>Set realistic start and end dates</li>
                                <li>Define clear objectives in the description</li>
                                <li>Assign appropriate priority based on business impact</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-sm font-weight-bold">Status Guidelines:</h6>
                            <ul class="text-sm text-secondary">
                                <li><strong>Planning:</strong> Project is being planned and prepared</li>
                                <li><strong>Active:</strong> Project is currently in progress</li>
                                <li><strong>On Hold:</strong> Project is temporarily paused</li>
                                <li><strong>Completed:</strong> Project has been finished successfully</li>
                                <li><strong>Cancelled:</strong> Project has been terminated</li>
                            </ul>
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
    // Auto-focus on project name field
    $('#name').focus();
    
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
        
        if (status === 'planning' && progressField.val() == '') {
            progressField.val(0);
        } else if (status === 'completed' && progressField.val() < 100) {
            if (confirm('Set progress to 100% for completed project?')) {
                progressField.val(100);
            }
        }
    });
    
    // Budget calculation helper
    $('#budget, #spent_budget').on('input', function() {
        const budget = parseFloat($('#budget').val()) || 0;
        const spent = parseFloat($('#spent_budget').val()) || 0;
        
        if (budget > 0 && spent > 0) {
            const percentage = Math.round((spent / budget) * 100);
            const remaining = budget - spent;
            
            // Show budget info (you can enhance this with a tooltip or info box)
            console.log(`Budget utilization: ${percentage}%, Remaining: $${remaining.toFixed(2)}`);
        }
    });
});
</script>
@endpush