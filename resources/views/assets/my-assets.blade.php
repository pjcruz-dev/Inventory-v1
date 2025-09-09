@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">My Assets</h6>
                        <div class="d-flex gap-2">
                            <a href="{{ route('assets.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-list me-1"></i>All Assets
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-3" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-3" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="myAssetsTable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asset Tag</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Manufacturer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Model</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Purchase Info</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via DataTables -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
$(document).ready(function() {
    $('#myAssetsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route('assets.my-assets') }}',
            type: 'GET',
            data: function(d) {
                d.ajax = 'true';
            }
        },
        columns: [
            { data: 'asset_tag', name: 'asset_tag' },
            { data: 'asset_type', name: 'asset_type', orderable: false },
            { data: 'manufacturer_name', name: 'manufacturer_name', orderable: false },
            { data: 'model', name: 'model' },
            { data: 'status_badge', name: 'status', orderable: false },
            { data: 'purchase_info', name: 'purchase_info', orderable: false },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        pageLength: 25,
        responsive: true,
        language: {
            processing: '<div class="text-center"><span class="text-primary fw-bold">Loading my assets...</span></div>',
            emptyTable: 'No assets assigned to you',
            zeroRecords: 'No matching assets found'
        }
    });
});
</script>
@endpush
@endsection