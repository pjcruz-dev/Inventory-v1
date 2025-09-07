@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-tag position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Asset Type Details</h5>
                    <p class="mb-0 text-sm">Viewing details for {{ $assetType->name }}</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('asset-types.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
                @can('edit-asset-type')
                <a href="{{ route('asset-types.edit', $assetType) }}" class="btn btn-sm btn-info">
                    <i class="fas fa-edit me-1"></i> Edit
                </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <h6 class="mb-0">Asset Type Information</h6>
                    </div>
                    <div class="card-body pt-4 p-3">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">ID</h6>
                                <p class="text-sm mb-4">{{ $assetType->id }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Name</h6>
                                <p class="text-sm mb-4">{{ $assetType->name }}</p>
                            </div>
                            <div class="col-md-12">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Description</h6>
                                <p class="text-sm mb-4">{{ $assetType->description ?? 'No description provided' }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Created At</h6>
                                <p class="text-sm mb-4">{{ $assetType->created_at->format('d M Y, h:i A') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6 class="text-uppercase text-body text-xs font-weight-bolder">Updated At</h6>
                                <p class="text-sm mb-4">{{ $assetType->updated_at->format('d M Y, h:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header pb-0 px-3">
                        <div class="d-flex justify-content-between">
                            <h6 class="mb-0">Assets of this Type</h6>
                            <span class="badge bg-gradient-primary">{{ $assetType->assets->count() }} Assets</span>
                        </div>
                    </div>
                    <div class="card-body pt-4 p-3">
                        @if($assetType->assets->count() > 0)
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asset Tag</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($assetType->assets->take(5) as $asset)
                                        <tr>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0 ps-3">{{ $asset->asset_tag }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $asset->name }}</p>
                                            </td>
                                            <td>
                                                <span class="badge badge-sm bg-gradient-{{ $asset->status == 'Available' ? 'success' : ($asset->status == 'Assigned' ? 'info' : 'warning') }}">{{ $asset->status }}</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('assets.show', $asset) }}" class="btn btn-link text-info text-sm mb-0 px-0 ms-1">
                                                    <i class="fas fa-eye text-info text-sm me-1"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($assetType->assets->count() > 5)
                                <div class="text-center mt-3">
                                    <a href="{{ route('assets.index', ['asset_type_id' => $assetType->id]) }}" class="btn btn-sm btn-outline-primary">View All {{ $assetType->assets->count() }} Assets</a>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm mb-0">No assets found for this type</p>
                                @can('create-asset')
                                <a href="{{ route('assets.create', ['asset_type_id' => $assetType->id]) }}" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="fas fa-plus me-1"></i> Add Asset
                                </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection