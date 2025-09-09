@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-tags position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Asset Types</h5>
                    <p class="mb-0 text-sm">Manage categories of assets in the inventory system</p>
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
                            <h5 class="mb-0">All Asset Types</h5>
                            <p class="text-sm mb-0">Manage all asset types in the system</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="dropdown">
                                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download me-1"></i> Export/Import
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('export.assets', ['module' => 'asset_types']) }}">
                                        <i class="fas fa-file-excel me-2"></i>Export to Excel
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('export.template', ['module' => 'asset_types']) }}">
                                        <i class="fas fa-download me-2"></i>Download Template
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('import.form', ['module' => 'asset_types']) }}">
                                        <i class="fas fa-upload me-2"></i>Import Asset Types
                                    </a></li>
                                </ul>
                            </div>
                            @can('create_assets')
                            <a href="{{ route('asset-types.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Asset Type
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
                        <table class="table align-items-center mb-0" id="asset-types-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Description
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Assets Count
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Created At
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
    $('#asset-types-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('asset-types.index') }}',
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'description', name: 'description'},
            {data: 'assets_count', name: 'assets_count', orderable: false, searchable: false},
            {data: 'created_at', name: 'created_at'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[1, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><span class="text-primary fw-bold">Loading asset types...</span></div>',
            search: "Search asset types:",
            searchPlaceholder: "Type name, description...",
            lengthMenu: "Display _MENU_ asset types per page",
            info: "Showing _START_ to _END_ of _TOTAL_ total asset types",
            infoEmpty: "No asset types available",
            infoFiltered: "(filtered from _MAX_ total asset types)",
            zeroRecords: "<div class='text-center py-4'><i class='fas fa-search fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No asset types match your search criteria</p><small class='text-muted'>Try adjusting your search terms</small></div>",
            emptyTable: "<div class='text-center py-4'><i class='fas fa-tags fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No asset types have been created yet</p></div>",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
});

function deleteAssetType(assetTypeId) {
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
                url: '/asset-types/' + assetTypeId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Asset type has been deleted.',
                        'success'
                    );
                    $('#asset-types-table').DataTable().ajax.reload();
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
 
@endsection