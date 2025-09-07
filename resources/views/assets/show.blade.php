@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4 shadow-sm">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative bg-gradient-primary rounded-circle shadow">
                    <i class="fas fa-laptop text-white position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1 font-weight-bold">{{ $asset->name }}</h5>
                    <p class="mb-0 text-sm text-secondary">Asset Tag: {{ $asset->asset_tag }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <div class="btn-group" role="group">
                    <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                    @can('update-asset')
                    <a href="{{ route('assets.edit', $asset->id) }}" class="btn btn-sm btn-info">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    @endcan
                    @can('print-asset')
                    <a href="{{ route('assets.print', $asset->id) }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-print me-1"></i> Print
                    </a>
                    @endcan
                    @if($asset->status != 'Assigned')
                    @can('create-asset-transfer')
                    <a href="{{ route('asset-transfers.create', ['asset_id' => $asset->id]) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-exchange-alt me-1"></i> Transfer
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
                        <h6 class="mb-0">Asset Information</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Asset Type</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->assetType->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Status</label>
                                    <p class="mb-0">
                                        @if($asset->status == 'Available')
                                            <span class="badge badge-sm bg-gradient-success">Available</span>
                                        @elseif($asset->status == 'Assigned')
                                            <span class="badge badge-sm bg-gradient-primary">Assigned</span>
                                        @elseif($asset->status == 'Maintenance')
                                            <span class="badge badge-sm bg-gradient-warning">Maintenance</span>
                                        @elseif($asset->status == 'Retired')
                                            <span class="badge badge-sm bg-gradient-danger">Retired</span>
                                        @else
                                            <span class="badge badge-sm bg-gradient-secondary">{{ $asset->status }}</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Serial Number</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->serial_number ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Location</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->location ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Purchase Date</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->purchase_date ? date('M d, Y', strtotime($asset->purchase_date)) : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Purchase Cost</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->purchase_cost ? '$'.number_format($asset->purchase_cost, 2) : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Warranty Expiry</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->warranty_expiry ? date('M d, Y', strtotime($asset->warranty_expiry)) : 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Assigned To</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">
                                        @if($asset->assignedUser)
                                            <a href="{{ route('users.show', $asset->assignedUser->id) }}" class="text-primary">
                                                {{ $asset->assignedUser->name }}
                                            </a>
                                        @else
                                            <span class="text-secondary">Not Assigned</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Notes</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ $asset->notes ?? 'No notes available' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Created At</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ date('M d, Y H:i', strtotime($asset->created_at)) }}</p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Last Updated</label>
                                    <p class="text-dark font-weight-bold text-sm mb-0">{{ date('M d, Y H:i', strtotime($asset->updated_at)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <div class="card-header pb-0 px-3 bg-gradient-light">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="mb-0 font-weight-bold">Peripherals</h6>
                            </div>
                            <div class="col-md-6 d-flex justify-content-end">
                                @can('create-peripheral')
                                <a href="{{ route('peripherals.create', ['asset_id' => $asset->id]) }}" class="btn btn-sm btn-primary mb-0">
                                    <i class="fas fa-plus me-1"></i> Add
                                </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        @if($asset->peripherals->count() > 0)
                            <ul class="list-group">
                                @foreach($asset->peripherals->take(5) as $peripheral)
                                    <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <div class="icon icon-shape icon-sm bg-gradient-info shadow text-center me-2">
                                                <i class="fas fa-hdd text-white opacity-10"></i>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <h6 class="mb-1 text-dark text-sm font-weight-bold">{{ $peripheral->name }}</h6>
                                                <span class="text-xs text-secondary">{{ $peripheral->serial_number }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex">
                                            <a href="{{ route('peripherals.show', $peripheral->id) }}" class="btn btn-link btn-icon-only btn-rounded btn-sm text-info icon-move-right my-auto">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                            @if($asset->peripherals->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('peripherals.index', ['asset_id' => $asset->id]) }}" class="text-primary text-sm icon-move-right font-weight-bold">
                                        View All Peripherals
                                        <i class="fas fa-arrow-right text-xs ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <div class="icon icon-shape icon-xl bg-gradient-secondary shadow text-center mb-3 mx-auto">
                                    <i class="fas fa-hdd text-white opacity-10"></i>
                                </div>
                                <p class="text-sm text-secondary mb-0">No peripherals attached to this asset.</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-header pb-0 px-3 bg-gradient-light">
                        <h6 class="mb-0 font-weight-bold">Transfer History</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        @if($asset->transfers->count() > 0)
                            <div class="timeline timeline-one-side">
                                @foreach($asset->transfers->take(5) as $transfer)
                                    <div class="timeline-block mb-3">
                                        <span class="timeline-step bg-gradient-primary">
                                            <i class="fas fa-exchange-alt text-white"></i>
                                        </span>
                                        <div class="timeline-content">
                                            <h6 class="text-dark text-sm font-weight-bold mb-0">
                                                @if($transfer->from_user_id)
                                                    {{ $transfer->fromUser->name }} → 
                                                @else
                                                    New Asset → 
                                                @endif
                                                
                                                @if($transfer->to_user_id)
                                                    {{ $transfer->toUser->name }}
                                                @else
                                                    Inventory
                                                @endif
                                            </h6>
                                            <p class="text-secondary text-xs mt-1 mb-0">{{ date('M d, Y', strtotime($transfer->transfer_date)) }}</p>
                                            <div class="mt-2">
                                                <a href="{{ route('asset-transfers.show', $transfer->id) }}" class="btn btn-link btn-sm text-info p-0 mb-0">
                                                    View Details <i class="fas fa-arrow-right text-xs ms-1"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($asset->transfers->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('asset-transfers.index', ['asset_id' => $asset->id]) }}" class="text-primary text-sm icon-move-right font-weight-bold">
                                        View All Transfers
                                        <i class="fas fa-arrow-right text-xs ms-1"></i>
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <div class="icon icon-shape icon-xl bg-gradient-secondary shadow text-center mb-3 mx-auto">
                                    <i class="fas fa-exchange-alt text-white opacity-10"></i>
                                </div>
                                <p class="text-sm text-secondary mb-0">No transfer history for this asset.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection