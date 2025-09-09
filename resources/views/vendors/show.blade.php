@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-building position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">{{ $vendor->name }}</h5>
                    <p class="mb-0 text-sm">Vendor Details and Information</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                <div class="nav-wrapper position-relative end-0">
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('vendors.edit', $vendor) }}" class="btn bg-gradient-primary btn-sm mb-0 me-2">
                            <i class="fas fa-edit me-2"></i>Edit
                        </a>
                        <a href="{{ route('vendors.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left me-2"></i>Back to Vendors
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
            <span class="alert-text">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
            <span class="alert-text">{{ session('error') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Quick Statistics -->
    <div class="row mx-4 mb-4">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Assets</p>
                                <h5 class="font-weight-bolder mb-0">{{ $vendor->assets->count() }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                <i class="fas fa-box text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Active Assets</p>
                                <h5 class="font-weight-bolder mb-0">{{ $vendor->assets->where('status', 'active')->count() }}</h5>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-success shadow text-center border-radius-md">
                                <i class="fas fa-check-circle text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Member Since</p>
                                <h6 class="font-weight-bolder mb-0">{{ $vendor->created_at->format('M Y') }}</h6>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-info shadow text-center border-radius-md">
                                <i class="fas fa-calendar text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-8">
                            <div class="numbers">
                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Last Updated</p>
                                <h6 class="font-weight-bolder mb-0">{{ $vendor->updated_at->diffForHumans() }}</h6>
                            </div>
                        </div>
                        <div class="col-4 text-end">
                            <div class="icon icon-shape bg-gradient-warning shadow text-center border-radius-md">
                                <i class="fas fa-clock text-lg opacity-10" aria-hidden="true"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Vendor Information -->
        <div class="col-12 col-lg-8">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6>Vendor Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Vendor Name</label>
                                <p class="form-control-static">{{ $vendor->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Contact Person</label>
                                <p class="form-control-static">{{ $vendor->contact_person ?: 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Email Address</label>
                                <p class="form-control-static">
                                    @if($vendor->email)
                                        <a href="mailto:{{ $vendor->email }}">{{ $vendor->email }}</a>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Phone Number</label>
                                <p class="form-control-static">
                                    @if($vendor->phone)
                                        <a href="tel:{{ $vendor->phone }}">{{ $vendor->phone }}</a>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Website</label>
                                <p class="form-control-static">
                                    @if($vendor->website)
                                        <a href="{{ $vendor->website }}" target="_blank">{{ $vendor->website }} <i class="fas fa-external-link-alt text-xs"></i></a>
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Address</label>
                                <p class="form-control-static">{{ $vendor->address ?: 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                    @if($vendor->description)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="form-control-label">Description</label>
                                    <p class="form-control-static">{{ $vendor->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Created At</label>
                                <p class="form-control-static">{{ $vendor->created_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Last Updated</label>
                                <p class="form-control-static">{{ $vendor->updated_at->format('F j, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-12 col-lg-4">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6>Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('vendors.edit', $vendor) }}" class="btn bg-gradient-primary">
                            <i class="fas fa-edit me-2"></i>Edit Vendor
                        </a>
                        @if($vendor->assets->count() > 0)
                            <a href="{{ route('assets.index', ['vendor' => $vendor->id]) }}" class="btn bg-gradient-info">
                                <i class="fas fa-box me-2"></i>View Assets ({{ $vendor->assets->count() }})
                            </a>
                        @endif
                        @if($vendor->email)
                            <a href="mailto:{{ $vendor->email }}" class="btn bg-gradient-success">
                                <i class="fas fa-envelope me-2"></i>Send Email
                            </a>
                        @endif
                        @if($vendor->phone)
                            <a href="tel:{{ $vendor->phone }}" class="btn bg-gradient-warning">
                                <i class="fas fa-phone me-2"></i>Call Vendor
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Associated Assets -->
    @if($vendor->assets->count() > 0)
        <div class="row">
            <div class="col-12">
                <div class="card mb-4 mx-4">
                    <div class="card-header pb-0">
                        <div class="d-flex flex-row justify-content-between">
                            <div>
                                <h6 class="mb-0">Associated Assets</h6>
                                <p class="text-sm mb-0">Assets provided by this vendor</p>
                            </div>
                            <div>
                                <a href="{{ route('assets.index', ['vendor' => $vendor->id]) }}" class="btn bg-gradient-primary btn-sm mb-0">
                                    <i class="fas fa-eye me-2"></i>View All Assets
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body px-0 pt-0 pb-2">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0">
                                <thead>
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asset</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Type</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Assigned To</th>
                                        <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Purchase Date</th>
                                        <th class="text-secondary opacity-7"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($vendor->assets->take(10) as $asset)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $asset->name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">{{ $asset->asset_tag }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $asset->assetType->name ?? 'N/A' }}</p>
                                            </td>
                                            <td class="align-middle text-center text-sm">
                                                @php
                                                    $statusClass = match($asset->status) {
                                                        'active' => 'bg-gradient-success',
                                                        'inactive' => 'bg-gradient-secondary',
                                                        'maintenance' => 'bg-gradient-warning',
                                                        'disposed' => 'bg-gradient-danger',
                                                        default => 'bg-gradient-secondary'
                                                    };
                                                @endphp
                                                <span class="badge badge-sm {{ $statusClass }}">{{ ucfirst($asset->status) }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $asset->assignedUser->name ?? 'Unassigned' }}
                                                </span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">
                                                    {{ $asset->purchase_date ? $asset->purchase_date->format('M j, Y') : 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <a href="{{ route('assets.show', $asset) }}" class="text-secondary font-weight-bold text-xs" data-toggle="tooltip" data-original-title="View asset">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($vendor->assets->count() > 10)
                            <div class="text-center mt-3">
                                <p class="text-sm text-secondary">Showing 10 of {{ $vendor->assets->count() }} assets</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Danger Zone -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6 class="text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    @if($vendor->assets->count() > 0)
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Cannot delete vendor:</strong> This vendor has {{ $vendor->assets->count() }} associated asset(s). Please reassign or remove all assets before deleting this vendor.
                        </div>
                    @else
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Delete Vendor:</strong> This action cannot be undone. All vendor information will be permanently removed.
                        </div>
                        <form action="{{ route('vendors.destroy', $vendor) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this vendor? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn bg-gradient-danger">
                                <i class="fas fa-trash me-2"></i>Delete Vendor
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize tooltips
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endpush