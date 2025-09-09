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
                    <h5 class="mb-1">Departments</h5>
                    <p class="mb-0 text-sm">Manage organizational departments and their hierarchy</p>
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
                            <h5 class="mb-0">All Departments</h5>
                            <p class="text-sm mb-0">Hierarchical view of organizational departments</p>
                        </div>
                        <div>
                            <a href="{{ route('departments.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Department
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
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

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="departments-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Department</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Parent</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Projects</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Logs</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Children</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created</th>
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

    <!-- Department Statistics -->
    @if($departments->count() > 0)
        <div class="row mx-4">
            <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                <div class="card">
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-8">
                                <div class="numbers">
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Departments</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $departments->total() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                    <i class="fas fa-sitemap text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Root Departments</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $departments->where('parent_id', null)->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                    <i class="fas fa-building text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Sub-Departments</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $departments->where('parent_id', '!=', null)->count() }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                    <i class="fas fa-layer-group text-lg opacity-10" aria-hidden="true"></i>
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
                                    <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Projects</p>
                                    <h5 class="font-weight-bolder mb-0">{{ $departments->sum(function($dept) { return $dept->projects->count(); }) }}</h5>
                                </div>
                            </div>
                            <div class="col-4 text-end">
                                <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                    <i class="fas fa-project-diagram text-lg opacity-10" aria-hidden="true"></i>
                                </div>
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
    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
    
    // Initialize DataTable with server-side processing
    $('#departments-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route('departments.index') }}',
            type: 'GET'
        },
        columns: [
            {
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    let html = '<div class="d-flex px-2 py-1">';
                    html += '<div class="d-flex flex-column justify-content-center">';
                    html += '<h6 class="mb-0 text-sm">' + data + '</h6>';
                    if (row.description) {
                        html += '<p class="text-xs text-secondary mb-0">' + (row.description.length > 50 ? row.description.substring(0, 50) + '...' : row.description) + '</p>';
                    }
                    html += '</div></div>';
                    return html;
                }
            },
            {
                data: 'parent_name',
                name: 'parent.name',
                render: function(data, type, row) {
                    return data === '-' ? '<span class="badge badge-sm bg-gradient-primary">Root Department</span>' : '<span class="text-xs font-weight-bold">' + data + '</span>';
                }
            },
            {
                data: 'projects_count',
                name: 'projects_count',
                className: 'text-center',
                render: function(data, type, row) {
                    return data > 0 ? '<span class="badge badge-sm bg-gradient-success">' + data + '</span>' : '<span class="text-secondary text-xs">None</span>';
                }
            },
            {
                data: 'logs_count',
                name: 'logs_count',
                className: 'text-center',
                render: function(data, type, row) {
                    return data > 0 ? '<span class="badge badge-sm bg-gradient-secondary">' + data + '</span>' : '<span class="text-secondary text-xs">None</span>';
                }
            },
            {
                data: 'children_count',
                name: 'children_count',
                className: 'text-center',
                render: function(data, type, row) {
                    return data > 0 ? '<span class="badge badge-sm bg-gradient-info">' + data + '</span>' : '<span class="text-secondary text-xs">None</span>';
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="text-secondary text-xs font-weight-bold">' + new Date(data).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) + '</span>';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false,
                className: 'text-center'
            }
        ],
        language: {
            processing: '<div class="text-center"><span class="text-primary fw-bold">Loading departments...</span></div>',
            emptyTable: '<div class="text-center py-4"><i class="fas fa-building fa-3x text-muted mb-3"></i><h5 class="text-muted">No departments found</h5><p class="text-muted">Start by creating your first department.</p></div>',
            zeroRecords: '<div class="text-center py-4"><i class="fas fa-search fa-2x text-muted mb-3"></i><h5 class="text-muted">No matching departments found</h5><p class="text-muted">Try adjusting your search criteria.</p></div>'
        },
        order: [[0, 'asc']]
    });
});

function deleteDepartment(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/departments/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        response.message,
                        'success'
                    );
                    $('#departments-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    let message = 'An error occurred while deleting the department.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire(
                        'Error!',
                        message,
                        'error'
                    );
                }
            });
        }
    });
}
</script>
@endpush