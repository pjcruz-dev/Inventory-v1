@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Audit Trail</h6>
                        <div class="text-sm text-secondary">
                            <i class="fas fa-info-circle"></i> System activity log
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">

                    <div class="table-responsive p-0">
                        <table id="audit-trail-table" class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Performed By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#audit-trail-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('audit-trail.index') }}',
        columns: [
            {
                data: 'entity_info',
                name: 'entity_type',
                render: function(data, type, row) {
                    return '<div class="d-flex px-2 py-1">' +
                           '<div class="d-flex flex-column justify-content-center">' +
                           '<h6 class="mb-0 text-sm">' + data + '</h6>' +
                           '</div>' +
                           '</div>';
                }
            },
            {
                data: 'action_badge',
                name: 'action',
                orderable: true,
                searchable: true
            },
            {
                data: 'performer_name',
                name: 'performer.name',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="text-secondary text-xs font-weight-bold">' + data + '</span>';
                }
            },
            {
                data: 'performed_at',
                name: 'performed_at',
                className: 'text-center',
                render: function(data, type, row) {
                    return '<span class="text-secondary text-xs font-weight-bold">' + data + '</span>';
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
        order: [[3, 'desc']],
        responsive: true,
        language: {
            processing: '<div class="text-center"><span class="text-primary fw-bold">Loading audit trail...</span></div>',
            emptyTable: '<div class="text-center py-4"><i class="fas fa-history fa-3x text-secondary mb-3"></i><p class="text-secondary mb-0">No audit trail records found.</p><p class="text-xs text-secondary">System activities will appear here as they occur.</p></div>',
            zeroRecords: '<div class="text-center py-4"><i class="fas fa-search fa-2x text-secondary mb-3"></i><p class="text-secondary mb-0">No matching records found.</p></div>'
        }
    });
});
</script>
@endpush