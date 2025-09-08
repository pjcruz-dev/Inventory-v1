@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Print Log Details</h6>
                        <a href="{{ route('print-logs.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to Print Logs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Print Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Print Format:</strong></p>
                                            <span class="badge badge-sm bg-gradient-info">{{ ucfirst(str_replace('_', ' ', $printLog->print_format)) }}</span>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Copies:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->copies }}</p>
                                        </div>
                                    </div>
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Printed By:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->printedBy->name }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Printed At:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->printed_at->format('M d, Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                    @if($printLog->destination_printer)
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-sm mb-2"><strong>Destination Printer:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->destination_printer }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    @if($printLog->note)
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-sm mb-2"><strong>Note:</strong></p>
                                            <p class="text-sm">{{ $printLog->note }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Asset Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Asset Tag:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->asset->asset_tag }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Asset Name:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->asset->name }}</p>
                                        </div>
                                    </div>
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Asset Type:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->asset->assetType->name }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Status:</strong></p>
                                            @php
                                                $statusColors = [
                                                    'available' => 'success',
                                                    'assigned' => 'info',
                                                    'in_repair' => 'warning',
                                                    'disposed' => 'danger',
                                                    'reserved' => 'secondary'
                                                ];
                                                $statusColor = $statusColors[$printLog->asset->status] ?? 'secondary';
                                            @endphp
                                            <span class="badge badge-sm bg-gradient-{{ $statusColor }}">{{ ucfirst(str_replace('_', ' ', $printLog->asset->status)) }}</span>
                                        </div>
                                    </div>
                                    @if($printLog->asset->assignedTo)
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-sm mb-2"><strong>Assigned To:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $printLog->asset->assignedTo->name }}</p>
                                        </div>
                                    </div>
                                    @endif
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-12">
                                            <a href="{{ route('assets.show', $printLog->asset->id) }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>&nbsp;&nbsp;View Asset Details
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection