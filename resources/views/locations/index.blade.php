@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Locations</h6>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('locations.create') }}" class="btn btn-primary btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Location
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="locationsTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Address</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assets</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created At</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- DataTable will populate this -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('#locationsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('locations.index') }}',
            type: 'GET'
        },
        columns: [
            { data: 'id', name: 'id', className: 'text-center' },
            { data: 'name', name: 'name' },
            { data: 'address_display', name: 'address', className: 'text-center' },
            { data: 'assets_count', name: 'assets_count', className: 'text-center' },
            { data: 'created_at_formatted', name: 'created_at', className: 'text-center' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'text-center' }
        ],
        order: [[0, 'desc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>Loading locations...</div>',
            search: '',
            searchPlaceholder: 'Search locations...',
            lengthMenu: 'Show _MENU_ locations per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ locations',
            infoEmpty: 'Showing 0 to 0 of 0 locations',
            infoFiltered: '(filtered from _MAX_ total locations)',
            zeroRecords: '<div class="text-center py-4"><i class="fas fa-map-marker-alt fa-3x text-secondary mb-3"></i><br><strong>No locations found</strong><br><small class="text-muted">Try adjusting your search criteria</small></div>',
            emptyTable: '<div class="text-center py-4"><i class="fas fa-map-marker-alt fa-3x text-secondary mb-3"></i><br><strong>No locations available</strong><br><small class="text-muted">Start by creating your first location</small></div>',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rtip',
        drawCallback: function() {
            // Re-initialize Bootstrap tooltips if any
            $('[data-bs-toggle="tooltip"]').tooltip();
        }
    });
});

function deleteLocation(locationId) {
    if (confirm('Are you sure you want to delete this location?')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/locations/${locationId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = '{{ csrf_token() }}';
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection