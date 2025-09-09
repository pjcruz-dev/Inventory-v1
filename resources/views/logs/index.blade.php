@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-history position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">System Logs</h5>
                    <p class="mb-0 text-sm">Monitor system activities and changes</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <span class="badge badge-sm bg-gradient-info">{{ $logs->total() }} Total Logs</span>
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

    <!-- Log Statistics -->
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Today's Logs</p>
                                <h5 class="font-weight-bolder mb-0">{{ $todayLogs ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-calendar-day text-lg opacity-10" aria-hidden="true"></i>
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
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">This Week</p>
                                <h5 class="font-weight-bolder mb-0">{{ $weekLogs ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-calendar-week text-lg opacity-10" aria-hidden="true"></i>
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
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Critical Events</p>
                                <h5 class="font-weight-bolder mb-0">{{ $criticalLogs ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-exclamation-triangle text-lg opacity-10" aria-hidden="true"></i>
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
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Users</p>
                                <h5 class="font-weight-bolder mb-0">{{ $activeUsers ?? 0 }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-users text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Logs Table -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">System Logs</h5>
                        </div>
                        <div>
                            <div class="dropdown">
                                <button class="btn bg-gradient-secondary dropdown-toggle btn-sm" type="button" id="logsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-cog me-2"></i>Options
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="logsDropdown">
                                    <li><a class="dropdown-item" href="#" onclick="refreshLogs()"><i class="fas fa-sync me-2"></i>Refresh</a></li>
                                    <li><a class="dropdown-item" href="{{ route('logs.export') }}"><i class="fas fa-download me-2"></i>Export Logs</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="confirmClearOldLogs()"><i class="fas fa-trash me-2"></i>Clear Old Logs</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="logs-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Timestamp</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">User</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Model</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Description</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">IP Address</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTables will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div class="modal fade" id="logDetailsModal" tabindex="-1" aria-labelledby="logDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logDetailsModalLabel">Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <div class="text-center py-3">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTable with server-side processing
    $('#logs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("logs.index") }}',
            type: 'GET'
        },
        columns: [
            {
                data: 'created_at',
                name: 'created_at',
                render: function(data, type, row) {
                    if (type === 'display') {
                        const date = new Date(data);
                        const dateStr = date.toLocaleDateString('en-US', { 
                            month: 'short', 
                            day: 'numeric', 
                            year: 'numeric' 
                        });
                        const timeStr = date.toLocaleTimeString('en-US', { 
                            hour: 'numeric', 
                            minute: '2-digit',
                            hour12: true 
                        });
                        return `<div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">${dateStr}</h6>
                                        <p class="text-xs text-secondary mb-0">${timeStr}</p>
                                    </div>
                                </div>`;
                    }
                    return data;
                }
            },
            {
                data: 'user_name',
                name: 'user_name',
                render: function(data, type, row) {
                    if (type === 'display') {
                        const userName = data || 'System';
                        const userEmail = row.user_email;
                        return `<div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">${userName}</h6>
                                        ${userEmail ? `<p class="text-xs text-secondary mb-0">${userEmail}</p>` : ''}
                                    </div>
                                </div>`;
                    }
                    return data;
                }
            },
            {
                data: 'action',
                name: 'action',
                render: function(data, type, row) {
                    if (type === 'display') {
                        const badgeClass = {
                            'created': 'success',
                            'updated': 'info',
                            'deleted': 'danger',
                            'login': 'primary',
                            'logout': 'secondary'
                        }[data] || 'dark';
                        return `<span class="badge badge-sm bg-gradient-${badgeClass}">${data.charAt(0).toUpperCase() + data.slice(1)}</span>`;
                    }
                    return data;
                }
            },
            {
                data: 'model_type',
                name: 'model_type',
                render: function(data, type, row) {
                    if (type === 'display') {
                        const modelType = data || 'N/A';
                        const modelId = row.model_id;
                        return `<div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <h6 class="mb-0 text-sm">${modelType}</h6>
                                        ${modelId ? `<p class="text-xs text-secondary mb-0">ID: ${modelId}</p>` : ''}
                                    </div>
                                </div>`;
                    }
                    return data;
                }
            },
            {
                data: 'description',
                name: 'description',
                render: function(data, type, row) {
                    if (type === 'display') {
                        const truncated = data.length > 60 ? data.substring(0, 60) + '...' : data;
                        return `<div class="d-flex px-2 py-1">
                                    <div class="d-flex flex-column justify-content-center">
                                        <p class="text-sm mb-0">${truncated}</p>
                                    </div>
                                </div>`;
                    }
                    return data;
                }
            },
            {
                data: 'ip_address',
                name: 'ip_address',
                className: 'text-center',
                render: function(data, type, row) {
                    if (type === 'display') {
                        return `<span class="text-secondary text-xs font-weight-bold">${data || 'N/A'}</span>`;
                    }
                    return data;
                }
            },
            {
                data: 'action',
                name: 'actions',
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-link text-secondary mb-0" 
                                onclick="showLogDetails(${row.id})" 
                                data-bs-toggle="tooltip" 
                                data-bs-placement="top" 
                                title="View Details">
                                <i class="fas fa-eye text-xs"></i>
                            </button>`;
                }
            }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="text-center"><span class="text-primary fw-bold">Loading logs...</span></div>',
            emptyTable: 'No system logs found',
            zeroRecords: 'No logs match your search criteria'
        },
        drawCallback: function() {
            // Re-initialize tooltips after each draw
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
});

function refreshLogs() {
    $('#logs-table').DataTable().ajax.reload();
}

function showLogDetails(logId) {
    $('#logDetailsModal').modal('show');
    $('#logDetailsContent').html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
    
    $.get('/logs/' + logId, function(data) {
        $('#logDetailsContent').html(data);
    }).fail(function() {
        $('#logDetailsContent').html('<div class="alert alert-danger">Error loading log details. Please try again.</div>');
    });
}

function confirmClearOldLogs() {
    if (confirm('Are you sure you want to clear logs older than 90 days? This action cannot be undone.')) {
        $.post('/logs/clear-old', {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                alert('Old logs have been cleared successfully.');
                refreshLogs();
            } else {
                alert('Error clearing old logs: ' + response.message);
            }
        }).fail(function() {
            alert('Error clearing old logs. Please try again.');
        });
    }
}
</script>
@endpush