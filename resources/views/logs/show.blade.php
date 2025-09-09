@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-file-alt position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Log Details</h5>
                    <p class="mb-0 text-sm">View detailed information about this log entry</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <span class="badge badge-sm bg-gradient-{{ 
                        $log->action === 'created' ? 'success' : 
                        ($log->action === 'updated' ? 'info' : 
                        ($log->action === 'deleted' ? 'danger' : 
                        ($log->action === 'login' ? 'primary' : 
                        ($log->action === 'logout' ? 'secondary' : 'dark'))))
                    }}">{{ ucfirst($log->action) }}</span>
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

    <!-- Log Information -->
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-info-circle me-2"></i>Log Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Timestamp</label>
                                <div class="form-control-static">
                                    <strong>{{ $log->created_at->format('F d, Y g:i:s A') }}</strong>
                                    <br><small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Action</label>
                                <div class="form-control-static">
                                    <span class="badge badge-lg bg-gradient-{{ 
                                        $log->action === 'created' ? 'success' : 
                                        ($log->action === 'updated' ? 'info' : 
                                        ($log->action === 'deleted' ? 'danger' : 
                                        ($log->action === 'login' ? 'primary' : 
                                        ($log->action === 'logout' ? 'secondary' : 'dark'))))
                                    }}">
                                        <i class="fas fa-{{ 
                                            $log->action === 'created' ? 'plus' : 
                                            ($log->action === 'updated' ? 'edit' : 
                                            ($log->action === 'deleted' ? 'trash' : 
                                            ($log->action === 'login' ? 'sign-in-alt' : 
                                            ($log->action === 'logout' ? 'sign-out-alt' : 'cog'))))
                                        }} me-2"></i>
                                        {{ ucfirst($log->action) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">User</label>
                                <div class="form-control-static">
                                    @if($log->user_name)
                                        <strong>{{ $log->user_name }}</strong>
                                        @if($log->user_email)
                                            <br><small class="text-muted">{{ $log->user_email }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">IP Address</label>
                                <div class="form-control-static">
                                    <code>{{ $log->ip_address ?? 'N/A' }}</code>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Model Type</label>
                                <div class="form-control-static">
                                    @if($log->model_type)
                                        <span class="badge bg-gradient-secondary">{{ $log->model_type }}</span>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Model ID</label>
                                <div class="form-control-static">
                                    @if($log->model_id)
                                        <code>#{{ $log->model_id }}</code>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <div class="form-control-static">
                                    <div class="alert alert-light border">
                                        {{ $log->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    @if($log->user_agent)
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">User Agent</label>
                                <div class="form-control-static">
                                    <small class="text-muted font-monospace">{{ $log->user_agent }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            
            @if($log->changes && is_array($log->changes) && count($log->changes) > 0)
            <!-- Changes Details -->
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-exchange-alt me-2"></i>Changes Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Field</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Old Value</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">New Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($log->changes as $field => $change)
                                <tr>
                                    <td><strong>{{ ucfirst(str_replace('_', ' ', $field)) }}</strong></td>
                                    <td>
                                        @if(isset($change['old']))
                                            <span class="text-danger">
                                                @if(is_array($change['old']))
                                                    <code>{{ json_encode($change['old']) }}</code>
                                                @else
                                                    {{ $change['old'] ?: '(empty)' }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if(isset($change['new']))
                                            <span class="text-success">
                                                @if(is_array($change['new']))
                                                    <code>{{ json_encode($change['new']) }}</code>
                                                @else
                                                    {{ $change['new'] ?: '(empty)' }}
                                                @endif
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Quick Actions -->
        <div class="col-md-4">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-bolt me-2"></i>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('logs.index') }}" class="btn bg-gradient-primary btn-sm">
                            <i class="fas fa-arrow-left me-2"></i>Back to Logs
                        </a>
                        
                        @if($log->model_type && $log->model_id)
                            @php
                                $modelRoute = null;
                                switch($log->model_type) {
                                    case 'Asset':
                                        $modelRoute = route('assets.show', $log->model_id);
                                        break;
                                    case 'AssetCategory':
                                        $modelRoute = route('asset-categories.show', $log->model_id);
                                        break;
                                    case 'Vendor':
                                        $modelRoute = route('vendors.show', $log->model_id);
                                        break;
                                    case 'Department':
                                        $modelRoute = route('departments.show', $log->model_id);
                                        break;
                                    case 'Project':
                                        $modelRoute = route('projects.show', $log->model_id);
                                        break;
                                    case 'User':
                                        $modelRoute = route('users.show', $log->model_id);
                                        break;
                                }
                            @endphp
                            
                            @if($modelRoute && $log->action !== 'deleted')
                                <a href="{{ $modelRoute }}" class="btn bg-gradient-info btn-sm">
                                    <i class="fas fa-external-link-alt me-2"></i>View {{ $log->model_type }}
                                </a>
                            @endif
                        @endif
                        
                        <button onclick="exportLogDetails()" class="btn bg-gradient-success btn-sm">
                            <i class="fas fa-download me-2"></i>Export Details
                        </button>
                        
                        <button onclick="copyLogDetails()" class="btn bg-gradient-secondary btn-sm">
                            <i class="fas fa-copy me-2"></i>Copy Details
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Related Logs -->
            @if($relatedLogs && $relatedLogs->count() > 0)
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-link me-2"></i>Related Logs</h6>
                    <p class="text-sm mb-0">Other logs for the same {{ $log->model_type ?? 'item' }}</p>
                </div>
                <div class="card-body">
                    <div class="timeline timeline-one-side">
                        @foreach($relatedLogs->take(5) as $relatedLog)
                        <div class="timeline-block mb-3">
                            <span class="timeline-step">
                                <i class="fas fa-{{ 
                                    $relatedLog->action === 'created' ? 'plus' : 
                                    ($relatedLog->action === 'updated' ? 'edit' : 
                                    ($relatedLog->action === 'deleted' ? 'trash' : 'cog'))
                                }} text-{{ 
                                    $relatedLog->action === 'created' ? 'success' : 
                                    ($relatedLog->action === 'updated' ? 'info' : 
                                    ($relatedLog->action === 'deleted' ? 'danger' : 'secondary'))
                                }} text-gradient"></i>
                            </span>
                            <div class="timeline-content">
                                <h6 class="text-dark text-sm font-weight-bold mb-0">
                                    {{ ucfirst($relatedLog->action) }}
                                </h6>
                                <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                    {{ $relatedLog->created_at->diffForHumans() }}
                                </p>
                                <p class="text-sm mt-2 mb-2">
                                    {{ Str::limit($relatedLog->description, 80) }}
                                </p>
                                @if($relatedLog->id !== $log->id)
                                    <a href="{{ route('logs.show', $relatedLog->id) }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                @else
                                    <span class="badge bg-gradient-primary">Current Log</span>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        @if($relatedLogs->count() > 5)
                        <div class="text-center">
                            <a href="{{ route('logs.index', ['model_type' => $log->model_type, 'model_id' => $log->model_id]) }}" 
                               class="btn btn-outline-secondary btn-sm">
                                View All {{ $relatedLogs->count() }} Related Logs
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Log Statistics -->
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6><i class="fas fa-chart-bar me-2"></i>Log Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="font-weight-bolder">{{ $userLogsCount ?? 0 }}</h4>
                                <span class="text-sm">User's Total Logs</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="font-weight-bolder">{{ $todayLogsCount ?? 0 }}</h4>
                                <span class="text-sm">Today's Logs</span>
                            </div>
                        </div>
                    </div>
                    <hr class="horizontal dark">
                    <div class="row">
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="font-weight-bolder">{{ $modelLogsCount ?? 0 }}</h4>
                                <span class="text-sm">{{ $log->model_type }} Logs</span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h4 class="font-weight-bolder">{{ $actionLogsCount ?? 0 }}</h4>
                                <span class="text-sm">{{ ucfirst($log->action) }} Actions</span>
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
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});

function exportLogDetails() {
    const logData = {
        id: {{ $log->id }},
        timestamp: '{{ $log->created_at->format('Y-m-d H:i:s') }}',
        user: '{{ $log->user_name ?? 'System' }}',
        email: '{{ $log->user_email ?? '' }}',
        action: '{{ $log->action }}',
        model_type: '{{ $log->model_type ?? '' }}',
        model_id: '{{ $log->model_id ?? '' }}',
        description: '{{ addslashes($log->description) }}',
        ip_address: '{{ $log->ip_address ?? '' }}',
        user_agent: '{{ addslashes($log->user_agent ?? '') }}',
        @if($log->changes)
        changes: @json($log->changes)
        @endif
    };
    
    const dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(JSON.stringify(logData, null, 2));
    const downloadAnchorNode = document.createElement('a');
    downloadAnchorNode.setAttribute("href", dataStr);
    downloadAnchorNode.setAttribute("download", "log_{{ $log->id }}_details.json");
    document.body.appendChild(downloadAnchorNode);
    downloadAnchorNode.click();
    downloadAnchorNode.remove();
}

function copyLogDetails() {
    const logText = `Log ID: {{ $log->id }}
Timestamp: {{ $log->created_at->format('Y-m-d H:i:s') }}
User: {{ $log->user_name ?? 'System' }}
Action: {{ $log->action }}
Model: {{ $log->model_type ?? 'N/A' }} (ID: {{ $log->model_id ?? 'N/A' }})
Description: {{ $log->description }}
IP Address: {{ $log->ip_address ?? 'N/A' }}`;
    
    navigator.clipboard.writeText(logText).then(function() {
        // Show success message
        const alert = document.createElement('div');
        alert.className = 'alert alert-success alert-dismissible fade show position-fixed';
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-text">Log details copied to clipboard!</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(alert);
        
        // Auto-remove after 3 seconds
        setTimeout(() => {
            if (alert.parentNode) {
                alert.parentNode.removeChild(alert);
            }
        }, 3000);
    }, function(err) {
        console.error('Could not copy text: ', err);
        alert('Failed to copy log details to clipboard.');
    });
}
</script>
@endpush