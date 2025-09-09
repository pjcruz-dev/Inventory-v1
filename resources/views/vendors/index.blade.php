@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-building position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Vendors</h5>
                    <p class="mb-0 text-sm">Manage vendors and suppliers in the inventory system</p>
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
                            <h5 class="mb-0">All Vendors</h5>
                            <p class="text-sm mb-0">Manage all vendors and suppliers in the system</p>
                        </div>
                        <div class="d-flex">
                            @can('manage_vendors')
                            <a href="{{ route('vendors.create') }}" class="btn bg-gradient-dark btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Vendor
                            </a>
                            @endcan
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
                        <table id="vendors-table" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Vendor Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Contact Info
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Assets Count
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Created Date
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Actions
                                    </th>
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

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#vendors-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route("vendors.index") }}',
        columns: [
            {
                data: 'name',
                name: 'name',
                render: function(data, type, row) {
                    var html = '<div class="d-flex px-2 py-1"><div class="d-flex flex-column justify-content-center"><h6 class="mb-0 text-sm">' + data + '</h6>';
                    if (row.website) {
                        var hostname = new URL(row.website).hostname;
                        html += '<p class="text-xs text-secondary mb-0"><a href="' + row.website + '" target="_blank" class="text-info"><i class="fas fa-external-link-alt me-1"></i>' + hostname + '</a></p>';
                    }
                    html += '</div></div>';
                    return html;
                }
            },
            {
                data: 'contact_info',
                name: 'contact_info',
                render: function(data, type, row) {
                    return '<p class="text-xs text-secondary mb-0">' + (data || 'N/A') + '</p>';
                }
            },
            {
                data: 'assets_count',
                name: 'assets_count',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="badge badge-sm bg-gradient-info">' + (data || 0) + '</span>';
                }
            },
            {
                data: 'created_at',
                name: 'created_at',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="text-secondary text-xs font-weight-bold">' + data + '</span>';
                }
            },
            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            }
        ],
        order: [[3, 'desc']],
        language: {
            processing: '<div class="text-center"><span class="text-primary fw-bold">Loading vendors...</span></div>',
            emptyTable: '<div class="text-center py-4"><i class="fas fa-building fa-3x text-muted mb-3"></i><h5 class="text-muted">No vendors found</h5><p class="text-muted">Start by adding your first vendor.</p></div>'
        }
    });
});

function deleteVendor(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/vendors/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire(
                            'Deleted!',
                            response.message,
                            'success'
                        );
                        $('#vendors-table').DataTable().ajax.reload();
                    } else {
                        Swal.fire(
                            'Error!',
                            response.message,
                            'error'
                        );
                    }
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong!',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
@endpush