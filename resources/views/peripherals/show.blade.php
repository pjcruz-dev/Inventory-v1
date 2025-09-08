@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-keyboard position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">{{ $peripheral->details ?: 'N/A' }}</h5>
                    <p class="mb-0 text-sm">{{ $peripheral->type }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('peripherals.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                @can('update', $peripheral)
                <a href="{{ route('peripherals.edit', $peripheral->id) }}" class="btn btn-sm btn-info">
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
                            <h6 class="mb-0">Peripheral Information</h6>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="id" class="form-control-label">ID</label>
                                    <p class="form-control-static">{{ $peripheral->id }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="details" class="form-control-label">Details</label>
                                    <p class="form-control-static">{{ $peripheral->details ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type" class="form-control-label">Type</label>
                                    <p class="form-control-static">{{ $peripheral->type }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="serial_no" class="form-control-label">Serial No</label>
                                    <p class="form-control-static">{{ $peripheral->serial_no ?: 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset" class="form-control-label">Assigned Asset</label>
                                    <p class="form-control-static">
                                        @if($peripheral->asset)
                                            <a href="{{ route('assets.show', $peripheral->asset->id) }}">
                                                {{ $peripheral->asset->asset_tag }} - {{ $peripheral->asset->name }}
                                            </a>
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
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header pb-0">
                        <h6 class="mb-0">Timestamps</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label class="form-control-label">Created At</label>
                            <p class="form-control-static">{{ $peripheral->created_at->format('M d, Y H:i:s') }}</p>
                        </div>
                        <div class="form-group">
                            <label class="form-control-label">Updated At</label>
                            <p class="form-control-static">{{ $peripheral->updated_at->format('M d, Y H:i:s') }}</p>
                        </div>
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