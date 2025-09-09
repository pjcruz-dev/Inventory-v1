@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-laptop position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Assets</h5>
                    <p class="mb-0 text-sm">Manage all assets in the inventory system</p>
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
                            <h5 class="mb-0">All Assets</h5>
                            <p class="text-sm mb-0">Manage all assets in the system</p>
                        </div>
                        <div class="d-flex">
                            <div class="dropdown me-2">
                                <button class="btn bg-gradient-secondary dropdown-toggle btn-sm mb-0" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><h6 class="dropdown-header">Asset Type</h6></li>
                                    @foreach($assetTypes as $type)
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['asset_type_id' => $type->id]) }}">{{ $type->name }}</a></li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Status</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Available']) }}">Available</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Assigned']) }}">Assigned</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Maintenance']) }}">Maintenance</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Retired']) }}">Retired</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Assigned User</h6></li>
                                    @foreach($users as $user)
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['user_id' => $user->id]) }}">{{ $user->name }}</a></li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index') }}">Clear Filters</a></li>
                                </ul>
                            </div>
                            <div class="dropdown me-2">
                                <button class="btn bg-gradient-info dropdown-toggle btn-sm mb-0" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-file-export me-2"></i>Export/Import
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    @can('export_data')
                                    <li><a class="dropdown-item" href="{{ route('export.assets') }}"><i class="fas fa-file-excel me-2"></i>Export to Excel</a></li>
                                    @endcan
                                    @can('import_data')
                                    <li><a class="dropdown-item" href="{{ route('export.template') }}"><i class="fas fa-download me-2"></i>Download Template</a></li>
                                    <li><a class="dropdown-item" href="{{ route('import.form') }}"><i class="fas fa-file-upload me-2"></i>Import Assets</a></li>
                                    @endcan
                                </ul>
                            </div>
                            <a href="{{ route('assets.my-assets') }}" class="btn bg-gradient-primary btn-sm mb-0 me-2">
                                <i class="fas fa-user me-2"></i>My Assets
                            </a>
                            @can('create_assets')
                            <a href="{{ route('assets.create') }}" class="btn bg-gradient-dark btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Asset
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
                        <table class="table align-items-center mb-0" id="assets-table">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Asset Tag
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Site ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        QR Code
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Type
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Manufacturer
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Model
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Asset Owner
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status
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
    $('#assets-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('assets.index') }}',
            data: function(d) {
                d.user_id = '{{ request('user_id') }}';
                d.status = '{{ request('status') }}';
                d.asset_type_id = '{{ request('asset_type_id') }}';
            }
        },
        columns: [
            {data: 'asset_tag', name: 'asset_tag'},
            {data: 'site_id', name: 'site_id'},
            {data: 'qr_code_display', name: 'qr_code', orderable: false, searchable: false},
            {data: 'asset_type', name: 'asset_type', orderable: false, searchable: false},
            {data: 'manufacturer_name', name: 'manufacturer_name', orderable: false, searchable: false},
            {data: 'model', name: 'model'},
            {data: 'asset_owner', name: 'asset_owner'},
            {data: 'status_badge', name: 'status', orderable: false, searchable: false},
            {data: 'actions', name: 'actions', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><span class="text-primary fw-bold">Loading assets...</span></div>',
            search: "Search assets:",
            searchPlaceholder: "Asset tag, name, serial, model...",
            lengthMenu: "Display _MENU_ assets per page",
            info: "Showing _START_ to _END_ of _TOTAL_ total assets",
            infoEmpty: "No assets available",
            infoFiltered: "(filtered from _MAX_ total assets)",
            zeroRecords: "<div class='text-center py-4'><i class='fas fa-search fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No assets match your search criteria</p><small class='text-muted'>Try adjusting your search terms</small></div>",
            emptyTable: "<div class='text-center py-4'><i class='fas fa-laptop fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No assets have been added yet</p></div>",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
});

function deleteAsset(assetId) {
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
                url: '/assets/' + assetId,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Asset has been deleted.',
                        'success'
                    );
                    $('#assets-table').DataTable().ajax.reload();
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