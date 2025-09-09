@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Manufacturer Details: {{ $manufacturer->name }}</h6>
                        <div>
                            <a href="{{ route('manufacturers.edit', $manufacturer) }}" class="btn btn-warning btn-sm me-2">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('manufacturers.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Basic Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Name:</strong></div>
                                        <div class="col-sm-8">{{ $manufacturer->name }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Status:</strong></div>
                                        <div class="col-sm-8">
                                            @if($manufacturer->is_active)
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Website:</strong></div>
                                        <div class="col-sm-8">
                                            @if($manufacturer->website)
                                                <a href="{{ $manufacturer->website }}" target="_blank" class="text-primary">
                                                    {{ $manufacturer->website }}
                                                    <i class="fas fa-external-link-alt ms-1"></i>
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Created:</strong></div>
                                        <div class="col-sm-8">{{ $manufacturer->created_at->format('M d, Y H:i') }}</div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Updated:</strong></div>
                                        <div class="col-sm-8">{{ $manufacturer->updated_at->format('M d, Y H:i') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Email:</strong></div>
                                        <div class="col-sm-8">
                                            @if($manufacturer->contact_email)
                                                <a href="mailto:{{ $manufacturer->contact_email }}" class="text-primary">
                                                    {{ $manufacturer->contact_email }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Phone:</strong></div>
                                        <div class="col-sm-8">
                                            @if($manufacturer->contact_phone)
                                                <a href="tel:{{ $manufacturer->contact_phone }}" class="text-primary">
                                                    {{ $manufacturer->contact_phone }}
                                                </a>
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-sm-4"><strong>Address:</strong></div>
                                        <div class="col-sm-8">
                                            @if($manufacturer->address)
                                                {{ $manufacturer->address }}
                                            @else
                                                <span class="text-muted">Not provided</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($manufacturer->description)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Description</h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $manufacturer->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">Associated Assets ({{ $manufacturer->assets->count() }})</h6>
                                        @if($manufacturer->assets->count() > 0)
                                            <small class="text-muted">Assets using this manufacturer</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    @if($manufacturer->assets->count() > 0)
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>Asset Tag</th>
                                                        <th>Model</th>
                                                        <th>Serial No</th>
                                                        <th>Status</th>
                                                        <th>Created</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($manufacturer->assets as $asset)
                                                    <tr>
                                                        <td>{{ $asset->asset_tag }}</td>
                                                        <td>{{ $asset->model }}</td>
                                                        <td>{{ $asset->serial_no ?? 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge badge-{{ $asset->status === 'available' ? 'success' : ($asset->status === 'assigned' ? 'info' : ($asset->status === 'in_repair' ? 'warning' : 'danger')) }}">
                                                                {{ ucfirst($asset->status) }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $asset->created_at->format('M d, Y') }}</td>
                                                        <td>
                                                            <a href="{{ route('assets.show', $asset) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div class="text-center py-4">
                                            <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">No assets are currently associated with this manufacturer.</p>
                                        </div>
                                    @endif
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