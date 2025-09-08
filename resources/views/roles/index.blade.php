@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <div class="icon icon-shape icon-lg bg-gradient-primary shadow text-center border-radius-lg">
                        <i class="fas fa-user-shield text-white" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Role Management</h5>
                    <p class="mb-0 text-sm">Manage user roles and their permissions</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-end">
                <a href="{{ route('roles.create') }}" class="btn bg-gradient-primary">
                    <i class="fas fa-plus"></i>&nbsp;&nbsp;New Role
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0">Roles</h6>
                    </div>
                </div>
            </div>
            <div class="card-body pt-4 p-3">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="alert-text">Success! {{ $message }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive p-0">
                    <table id="roles-table" class="table align-items-center mb-0 table-hover">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permissions Count</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permissions</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Created At</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#roles-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('roles.index') }}',
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'permissions_count', name: 'permissions_count', orderable: false, searchable: false },
            { data: 'permissions_list', name: 'permissions_list', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border text-primary me-2" role="status"></div><span class="text-primary fw-bold">Loading roles...</span></div>',
            search: 'Search roles:',
            searchPlaceholder: 'Role name, permissions...',
            lengthMenu: 'Display _MENU_ roles per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ total roles',
            infoEmpty: 'No roles available',
            infoFiltered: '(filtered from _MAX_ total roles)',
            zeroRecords: "<div class='text-center py-4'><i class='fas fa-search fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No roles match your search criteria</p><small class='text-muted'>Try adjusting your search terms</small></div>",
            emptyTable: "<div class='text-center py-4'><i class='fas fa-user-shield fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No roles have been created yet</p></div>",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
});

function deleteRole(roleId) {
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
                url: '/roles/' + roleId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Role has been deleted.',
                        'success'
                    );
                    $('#roles-table').DataTable().ajax.reload();
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'Something went wrong.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
@endpush