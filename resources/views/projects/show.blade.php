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
                    <h5 class="mb-1">{{ $project->name }}</h5>
                    <p class="mb-0 text-sm">Project Details and Information</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <span class="badge badge-sm bg-gradient-{{ $project->status === 'completed' ? 'success' : ($project->status === 'active' ? 'info' : ($project->status === 'on_hold' ? 'warning' : ($project->status === 'cancelled' ? 'danger' : 'secondary'))) }}">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                    <span class="badge badge-sm bg-gradient-{{ $project->priority === 'critical' ? 'danger' : ($project->priority === 'high' ? 'warning' : ($project->priority === 'medium' ? 'info' : 'secondary')) }} ms-2">
                        {{ ucfirst($project->priority) }} Priority
                    </span>
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
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Progress</p>
                                <h5 class="font-weight-bolder mb-0">{{ $project->progress ?? 0 }}%</h5>
                                <div class="progress progress-sm mt-2">
                                    <div class="progress-bar bg-gradient-{{ $project->progress >= 75 ? 'success' : ($project->progress >= 50 ? 'info' : ($project->progress >= 25 ? 'warning' : 'danger')) }}" 
                                         style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-chart-line text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Budget Used</p>
                                <h5 class="font-weight-bolder mb-0">
                                    @if($project->budget && $project->budget > 0)
                                        {{ number_format(($project->spent_budget ?? 0) / $project->budget * 100, 1) }}%
                                    @else
                                        N/A
                                    @endif
                                </h5>
                                @if($project->budget && $project->budget > 0)
                                    <p class="mb-0 text-sm">
                                        ${{ number_format($project->spent_budget ?? 0, 2) }} / ${{ number_format($project->budget, 2) }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-dollar-sign text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Duration</p>
                                <h5 class="font-weight-bolder mb-0">
                                    @if($project->start_date && $project->end_date)
                                        {{ $project->start_date->diffInDays($project->end_date) }} days
                                    @else
                                        N/A
                                    @endif
                                </h5>
                                @if($project->start_date && $project->end_date)
                                    <p class="mb-0 text-sm">
                                        @if($project->end_date->isPast())
                                            <span class="text-danger">Overdue</span>
                                        @elseif($project->end_date->isToday())
                                            <span class="text-warning">Due Today</span>
                                        @else
                                            {{ $project->end_date->diffForHumans() }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-calendar-alt text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Team Size</p>
                                <h5 class="font-weight-bolder mb-0">{{ $project->team_size ?? 'N/A' }}</h5>
                                @if($project->manager_name)
                                    <p class="mb-0 text-sm">Manager: {{ $project->manager_name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Information -->
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Project Information</h5>
                            <p class="text-sm mb-0">Detailed project information and specifications</p>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Project Name</label>
                                <p class="form-control-static">{{ $project->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Department</label>
                                <p class="form-control-static">
                                    @if($project->department)
                                        <a href="{{ route('departments.show', $project->department) }}" class="text-primary">
                                            {{ $project->department->parent ? $project->department->parent->name . ' > ' : '' }}{{ $project->department->name }}
                                        </a>
                                    @else
                                        <span class="text-muted">No department assigned</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    @if($project->description)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <p class="form-control-static">{{ $project->description }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Status</label>
                                <p class="form-control-static">
                                    <span class="badge badge-sm bg-gradient-{{ $project->status === 'completed' ? 'success' : ($project->status === 'active' ? 'info' : ($project->status === 'on_hold' ? 'warning' : ($project->status === 'cancelled' ? 'danger' : 'secondary'))) }}">
                                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Priority</label>
                                <p class="form-control-static">
                                    <span class="badge badge-sm bg-gradient-{{ $project->priority === 'critical' ? 'danger' : ($project->priority === 'high' ? 'warning' : ($project->priority === 'medium' ? 'info' : 'secondary')) }}">
                                        {{ ucfirst($project->priority) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Progress</label>
                                <p class="form-control-static">
                                    {{ $project->progress ?? 0 }}%
                                    <div class="progress progress-sm mt-1">
                                        <div class="progress-bar bg-gradient-{{ $project->progress >= 75 ? 'success' : ($project->progress >= 50 ? 'info' : ($project->progress >= 25 ? 'warning' : 'danger')) }}" 
                                             style="width: {{ $project->progress ?? 0 }}%"></div>
                                    </div>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    @if($project->start_date || $project->end_date)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Start Date</label>
                                <p class="form-control-static">
                                    {{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">End Date</label>
                                <p class="form-control-static">
                                    @if($project->end_date)
                                        {{ $project->end_date->format('M d, Y') }}
                                        @if($project->end_date->isPast() && $project->status !== 'completed')
                                            <span class="badge badge-sm bg-gradient-danger ms-2">Overdue</span>
                                        @elseif($project->end_date->isToday())
                                            <span class="badge badge-sm bg-gradient-warning ms-2">Due Today</span>
                                        @endif
                                    @else
                                        Not set
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Budget Information -->
                    @if($project->budget || $project->spent_budget)
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Total Budget</label>
                                <p class="form-control-static">
                                    {{ $project->budget ? '$' . number_format($project->budget, 2) : 'Not set' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Spent Budget</label>
                                <p class="form-control-static">
                                    {{ $project->spent_budget ? '$' . number_format($project->spent_budget, 2) : '$0.00' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="form-control-label">Remaining Budget</label>
                                <p class="form-control-static">
                                    @if($project->budget)
                                        @php
                                            $remaining = $project->budget - ($project->spent_budget ?? 0);
                                        @endphp
                                        <span class="{{ $remaining < 0 ? 'text-danger' : ($remaining < $project->budget * 0.1 ? 'text-warning' : 'text-success') }}">
                                            ${{ number_format($remaining, 2) }}
                                        </span>
                                    @else
                                        Not applicable
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Team Information -->
                    @if($project->manager_name || $project->team_size)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Project Manager</label>
                                <p class="form-control-static">
                                    {{ $project->manager_name ?? 'Not assigned' }}
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Team Size</label>
                                <p class="form-control-static">
                                    {{ $project->team_size ?? 'Not specified' }}
                                    @if($project->team_size)
                                        {{ $project->team_size == 1 ? 'member' : 'members' }}
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($project->notes)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Notes</label>
                                <p class="form-control-static">{{ $project->notes }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Created</label>
                                <p class="form-control-static">
                                    {{ $project->created_at->format('M d, Y \a\t g:i A') }}
                                    <small class="text-muted">({{ $project->created_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Last Updated</label>
                                <p class="form-control-static">
                                    {{ $project->updated_at->format('M d, Y \a\t g:i A') }}
                                    <small class="text-muted">({{ $project->updated_at->diffForHumans() }})</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-cogs me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('projects.edit', $project) }}" class="btn bg-gradient-primary btn-sm">
                            <i class="fas fa-edit me-2"></i>Edit Project
                        </a>
                        
                        @if($project->status !== 'completed')
                            <form action="{{ route('projects.update', $project) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <input type="hidden" name="progress" value="100">
                                <button type="submit" class="btn bg-gradient-success btn-sm w-100" 
                                        onclick="return confirm('Mark this project as completed?')">
                                    <i class="fas fa-check me-2"></i>Mark as Completed
                                </button>
                            </form>
                        @endif
                        
                        @if($project->status === 'on_hold')
                            <form action="{{ route('projects.update', $project) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="active">
                                <button type="submit" class="btn bg-gradient-info btn-sm w-100" 
                                        onclick="return confirm('Resume this project?')">
                                    <i class="fas fa-play me-2"></i>Resume Project
                                </button>
                            </form>
                        @elseif($project->status === 'active')
                            <form action="{{ route('projects.update', $project) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="on_hold">
                                <button type="submit" class="btn bg-gradient-warning btn-sm w-100" 
                                        onclick="return confirm('Put this project on hold?')">
                                    <i class="fas fa-pause me-2"></i>Put on Hold
                                </button>
                            </form>
                        @endif
                        
                        <a href="{{ route('projects.index') }}" class="btn bg-gradient-secondary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Projects
                        </a>
                    </div>
                </div>
            </div>

            <!-- Project Timeline -->
            @if($project->start_date || $project->end_date)
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-timeline me-2"></i>Project Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        @if($project->start_date)
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="fas fa-play text-success text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Project Start</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $project->start_date->format('M d, Y') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="fas fa-info text-info text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Current Status</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }} - {{ $project->progress ?? 0 }}% Complete
                                </p>
                            </div>
                        </div>
                        
                        @if($project->end_date)
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="fas fa-flag text-{{ $project->end_date->isPast() && $project->status !== 'completed' ? 'danger' : 'warning' }} text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">Target Completion</h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                    {{ $project->end_date->format('M d, Y') }}
                                    @if($project->end_date->isPast() && $project->status !== 'completed')
                                        <span class="text-danger">(Overdue)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Associated Assets -->
    @if($project->assets && $project->assets->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Associated Assets</h5>
                            <p class="text-sm mb-0">Assets assigned to this project</p>
                        </div>
                        <div>
                            <span class="badge badge-sm bg-gradient-info">{{ $project->assets->count() }} {{ $project->assets->count() == 1 ? 'Asset' : 'Assets' }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asset</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Category</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Value</th>
                                    <th class="text-secondary opacity-7"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($project->assets as $asset)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $asset->name }}</h6>
                                                @if($asset->asset_tag)
                                                    <p class="text-xs text-secondary mb-0">{{ $asset->asset_tag }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $asset->category->name ?? 'N/A' }}</p>
                                    </td>
                                    <td class="align-middle text-center text-sm">
                                        <span class="badge badge-sm bg-gradient-{{ $asset->status === 'active' ? 'success' : ($asset->status === 'maintenance' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($asset->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $asset->purchase_price ? '$' . number_format($asset->purchase_price, 2) : 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('assets.show', $asset) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View asset">
                                            <i class="fas fa-eye"></i>
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
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4 bg-gradient-danger">
                <div class="card-header pb-0">
                    <h6 class="text-white"><i class="fas fa-exclamation-triangle me-2"></i>Danger Zone</h6>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h6 class="text-white mb-1">Delete Project</h6>
                            <p class="text-white text-sm mb-0">
                                Permanently delete this project and all associated data. This action cannot be undone.
                                @if($project->assets && $project->assets->count() > 0)
                                    <br><strong>Warning:</strong> This project has {{ $project->assets->count() }} associated {{ $project->assets->count() == 1 ? 'asset' : 'assets' }}.
                                @endif
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" class="d-inline" 
                                  onsubmit="return confirm('Are you absolutely sure you want to delete this project? This action cannot be undone.{{ $project->assets && $project->assets->count() > 0 ? ' This will also unassign ' . $project->assets->count() . ' associated assets.' : '' }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn bg-gradient-light btn-sm">
                                    <i class="fas fa-trash me-2"></i>Delete Project
                                </button>
                            </form>
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
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
    
    // Auto-refresh progress if project is active
    @if($project->status === 'active')
    setInterval(function() {
        // You can implement auto-refresh logic here if needed
        console.log('Project is active - consider implementing auto-refresh');
    }, 300000); // 5 minutes
    @endif
});
</script>
@endpush