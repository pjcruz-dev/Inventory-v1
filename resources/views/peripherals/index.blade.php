@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Peripherals</h5>
                        </div>
                        @can('create-peripheral')
                        <a href="{{ route('peripherals.create') }}" class="btn bg-gradient-primary btn-sm mb-0" type="button">+&nbsp; New Peripheral</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                            <span class="alert-text">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive p-0">
                        <table id="peripherals-table" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Details</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Serial No</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Asset Tag</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Asset Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Created At</th>
                                    <th class="text-secondary opacity-7">Actions</th>
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
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#peripherals-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('peripherals.index') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'type', name: 'type' },
            { data: 'details', name: 'details' },
            { data: 'serial_no', name: 'serial_no' },
            { data: 'asset_tag', name: 'asset_tag', orderable: false, searchable: false },
            { data: 'asset_name', name: 'asset_name', orderable: false, searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border text-primary me-2" role="status"></div><span class="text-primary fw-bold">Loading peripherals...</span></div>',
            search: 'Search peripherals:',
            searchPlaceholder: 'Type, details, serial number...',
            lengthMenu: 'Display _MENU_ peripherals per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ total peripherals',
            infoEmpty: 'No peripherals available',
            infoFiltered: '(filtered from _MAX_ total peripherals)',
            zeroRecords: "<div class='text-center py-4'><i class='fas fa-search fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No peripherals match your search criteria</p><small class='text-muted'>Try adjusting your search terms</small></div>",
            emptyTable: "<div class='text-center py-4'><i class='fas fa-mouse fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No peripherals have been added yet</p></div>",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
});

function deletePeripheral(id) {
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
                url: '/peripherals/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'Deleted!',
                        'Peripheral has been deleted.',
                        'success'
                    );
                    $('#peripherals-table').DataTable().ajax.reload();
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