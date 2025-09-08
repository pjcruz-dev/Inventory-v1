@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-exchange-alt position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Asset Transfer #{{ $assetTransfer->id }}</h5>
                    <p class="mb-0 text-sm">{{ $assetTransfer->asset->name }} ({{ $assetTransfer->asset->asset_tag }})</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('asset-transfers.index') }}" class="btn btn-sm btn-dark">
                        <i class="fas fa-arrow-left me-1"></i> Back to List
                    </a>
                    @if($assetTransfer->status == 'Pending')
                        @can('update-asset-transfer')
                        <a href="{{ route('asset-transfers.edit', $assetTransfer->id) }}" class="btn btn-sm btn-info">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        @endcan
                        @can('complete-asset-transfer')
                        <a href="{{ route('asset-transfers.complete', $assetTransfer->id) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-check me-1"></i> Complete
                        </a>
                        @endcan
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0">Transfer Information</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Asset</h6>
                                <p class="text-dark text-sm">
                                    <a href="{{ route('assets.show', $assetTransfer->asset->id) }}">
                                        {{ $assetTransfer->asset->name }} ({{ $assetTransfer->asset->asset_tag }})
                                    </a>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Status</h6>
                                <p>
                                    @if($assetTransfer->status == 'Pending')
                                        <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                    @elseif($assetTransfer->status == 'Completed')
                                        <span class="badge badge-sm bg-gradient-success">Completed</span>
                                    @elseif($assetTransfer->status == 'Cancelled')
                                        <span class="badge badge-sm bg-gradient-danger">Cancelled</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Transfer Date</h6>
                                <p class="text-dark text-sm">{{ date('M d, Y', strtotime($assetTransfer->transfer_date)) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Completion Date</h6>
                                <p class="text-dark text-sm">
                                    {{ $assetTransfer->completion_date ? date('M d, Y', strtotime($assetTransfer->completion_date)) : 'Not completed yet' }}
                                </p>
                            </div>
                            <div class="col-md-12">
                                <hr class="horizontal dark">
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">From User</h6>
                                <p class="text-dark text-sm">
                                    @if($assetTransfer->fromUser)
                                        <a href="{{ route('users.show', $assetTransfer->fromUser->id) }}">
                                            {{ $assetTransfer->fromUser->name }}
                                        </a>
                                    @else
                                        Inventory
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">To User</h6>
                                <p class="text-dark text-sm">
                                    @if($assetTransfer->toUser)
                                        <a href="{{ route('users.show', $assetTransfer->toUser->id) }}">
                                            {{ $assetTransfer->toUser->name }}
                                        </a>
                                    @else
                                        Inventory
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">From Location</h6>
                                <p class="text-dark text-sm">{{ $assetTransfer->from_location ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">To Location</h6>
                                <p class="text-dark text-sm">{{ $assetTransfer->to_location ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-12">
                                <hr class="horizontal dark">
                            </div>
                            <div class="col-md-12 mb-3">
                                <h6 class="text-sm">Notes</h6>
                                <p class="text-dark text-sm">{{ $assetTransfer->notes ?? 'No notes available' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Created By</h6>
                                <p class="text-dark text-sm">
                                    @if($assetTransfer->createdBy)
                                        {{ $assetTransfer->createdBy->name }}
                                    @else
                                        System
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Completed By</h6>
                                <p class="text-dark text-sm">
                                    @if($assetTransfer->completedBy)
                                        {{ $assetTransfer->completedBy->name }}
                                    @else
                                        Not completed yet
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Created At</h6>
                                <p class="text-dark text-sm">{{ date('M d, Y H:i', strtotime($assetTransfer->created_at)) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <h6 class="text-sm">Last Updated</h6>
                                <p class="text-dark text-sm">{{ date('M d, Y H:i', strtotime($assetTransfer->updated_at)) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0">Asset Status</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="timeline timeline-one-side">
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fas fa-plus text-success"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Transfer Created</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ date('M d, Y H:i', strtotime($assetTransfer->created_at)) }}</p>
                                    <p class="text-sm mt-3 mb-0">
                                        Transfer initiated by {{ $assetTransfer->createdBy ? $assetTransfer->createdBy->name : 'System' }}
                                    </p>
                                </div>
                            </div>
                            
                            @if($assetTransfer->status == 'Completed')
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fas fa-check text-info"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Transfer Completed</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ date('M d, Y H:i', strtotime($assetTransfer->completion_date)) }}</p>
                                    <p class="text-sm mt-3 mb-0">
                                        Asset successfully transferred to {{ $assetTransfer->toUser ? $assetTransfer->toUser->name : 'Inventory' }}
                                    </p>
                                </div>
                            </div>
                            @elseif($assetTransfer->status == 'Cancelled')
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fas fa-times text-danger"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Transfer Cancelled</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ date('M d, Y H:i', strtotime($assetTransfer->updated_at)) }}</p>
                                    <p class="text-sm mt-3 mb-0">
                                        Transfer was cancelled
                                    </p>
                                </div>
                            </div>
                            @else
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fas fa-clock text-warning"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Awaiting Completion</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ date('M d, Y', strtotime($assetTransfer->transfer_date)) }}</p>
                                    <p class="text-sm mt-3 mb-0">
                                        Transfer is pending completion
                                    </p>
                                </div>
                            </div>
                            @endif
                            
                            <div class="timeline-block">
                                <span class="timeline-step">
                                    <i class="fas fa-laptop"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">Asset Current Status</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">Current</p>
                                    <p class="text-sm mt-3 mb-0">
                                        Status: 
                                        @if($assetTransfer->asset->status == 'Available')
                                            <span class="badge badge-sm bg-gradient-success">Available</span>
                                        @elseif($assetTransfer->asset->status == 'Assigned')
                                            <span class="badge badge-sm bg-gradient-primary">Assigned</span>
                                        @elseif($assetTransfer->asset->status == 'Maintenance')
                                            <span class="badge badge-sm bg-gradient-warning">Maintenance</span>
                                        @elseif($assetTransfer->asset->status == 'Retired')
                                            <span class="badge badge-sm bg-gradient-danger">Retired</span>
                                        @endif
                                    </p>
                                    <p class="text-sm mb-0">
                                        Location: {{ $assetTransfer->asset->location ?? 'N/A' }}
                                    </p>
                                    <p class="text-sm mb-0">
                                        Assigned to: 
                                        @if($assetTransfer->asset->assignedUser)
                                            {{ $assetTransfer->asset->assignedUser->name }}
                                        @else
                                            Not Assigned
                                        @endif
                                    </p>
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