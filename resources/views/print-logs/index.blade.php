@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Print Logs</h6>
                        @can('create-print-log')
                        <a href="{{ route('print-logs.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                            <i class="fas fa-plus"></i>&nbsp;&nbsp;Add Print Log
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table id="print-logs-table" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Asset Tag</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Asset Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Print Format</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Copies</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Destination Printer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Printed At</th>
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
    $('#print-logs-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('print-logs.index') }}',
        columns: [
            { data: 'id', name: 'id' },
            { data: 'asset_tag', name: 'asset_tag', orderable: false, searchable: false },
            { data: 'asset_name', name: 'asset_name', orderable: false, searchable: false },
            { data: 'print_format', name: 'print_format' },
            { data: 'copies', name: 'copies' },
            { data: 'destination_printer', name: 'destination_printer' },
            { data: 'printed_by_name', name: 'printed_by_name', orderable: false, searchable: false },
            { data: 'printed_at', name: 'printed_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']],
        pageLength: 10,
        responsive: true,
        language: {
            processing: '<div class="d-flex justify-content-center align-items-center"><div class="spinner-border text-primary me-2" role="status"></div><span class="text-primary fw-bold">Loading print logs...</span></div>',
            search: 'Search print logs:',
            searchPlaceholder: 'Asset, user, date...',
            lengthMenu: 'Display _MENU_ print logs per page',
            info: 'Showing _START_ to _END_ of _TOTAL_ total print logs',
            infoEmpty: 'No print logs available',
            infoFiltered: '(filtered from _MAX_ total print logs)',
            zeroRecords: "<div class='text-center py-4'><i class='fas fa-search fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No print logs match your search criteria</p><small class='text-muted'>Try adjusting your search terms</small></div>",
            emptyTable: "<div class='text-center py-4'><i class='fas fa-print fa-2x text-muted mb-3'></i><p class='text-muted mb-0'>No print logs have been recorded yet</p></div>",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        }
    });
});
</script>
@endpush