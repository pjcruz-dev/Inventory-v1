@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-map-marker-alt position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">{{ $location->name }}</h5>
                    <p class="mb-0 text-sm">{{ $location->address ?: 'No address specified' }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('locations.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                @can('update', $location)
                <a href="{{ route('locations.edit', $location->id) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header pb-0">
                        <div class="d-flex align-items-center">
                            <h6 class="mb-0">Location Information</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id" class="form-control-label">ID</label>
                                    <p class="form-control-static">{{ $location->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Name</label>
                                    <p class="form-control-static">{{ $location->name }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="form-control-label">Address</label>
                                    <p class="form-control-static">{{ $location->address ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="city" class="form-control-label">City</label>
                                    <p class="form-control-static">{{ $location->city ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="state" class="form-control-label">State/Province</label>
                                    <p class="form-control-static">{{ $location->state ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country" class="form-control-label">Country</label>
                                    <p class="form-control-static">{{ $location->country ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="zip" class="form-control-label">Zip/Postal Code</label>
                                    <p class="form-control-static">{{ $location->zip ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes" class="form-control-label">Notes</label>
                                    <p class="form-control-static">{{ $location->notes ?: 'No notes available' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Timestamps</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">Created At</label>
                            <p class="form-control-static">{{ $location->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Updated At</label>
                            <p class="form-control-static">{{ $location->updated_at->format('M d, Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Assets at this Location</h6>
                    </div>
                    <div class="card-body p-3">
                        @if($location->assets_count > 0)
                            <ul class="list-group">
                                @foreach($location->assets()->take(5)->get() as $asset)
                                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark text-sm">{{ $asset->name }}</h6>
                                            <span class="text-xs">{{ $asset->asset_tag }}</span>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center text-sm">
                                        <a href="{{ route('assets.show', $asset->id) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4">
                                            <i class="fas fa-eye text-lg me-1"></i> View
                                        </a>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @if($location->assets_count > 5)
                            <div class="text-center mt-3">
                                <a href="{{ route('assets.index', ['location_id' => $location->id]) }}" class="text-primary text-sm font-weight-bold">View all {{ $location->assets_count }} assets</a>
                            </div>
                            @endif
                        @else
                            <div class="text-center py-3">
                                <p class="mb-0 text-sm">No assets assigned to this location</p>
                            </div>
                        @endif
                    </div>
                </div>
                
                @if(isset($auditLogs) && count($auditLogs) > 0)
                <div class="card mt-4">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Audit History</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="timeline timeline-one-side">
                            @foreach($auditLogs->take(5) as $log)
                            <div class="timeline-block mb-3">
                                <span class="timeline-step">
                                    <i class="fas fa-history text-warning"></i>
                                </span>
                                <div class="timeline-content">
                                    <h6 class="text-dark text-sm font-weight-bold mb-0">{{ $log->action }}</h6>
                                    <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">{{ $log->created_at->format('M d, Y H:i:s') }}</p>
                                    <p class="text-sm mt-3 mb-2">
                                        {{ $log->details }}
                                    </p>
                                    <span class="badge badge-sm bg-gradient-info">{{ $log->user->name }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @if(count($auditLogs) > 5)
                        <div class="text-center mt-3">
                            <a href="#" class="text-sm font-weight-bold text-primary">View All History</a>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection