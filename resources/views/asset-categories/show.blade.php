@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-tags position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">{{ $category->name }}</h5>
                    <p class="mb-0 text-sm">Asset category details and information</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Category Information</h5>
                            <p class="text-sm mb-0">Details about {{ $category->name }}</p>
                        </div>
                        <div>
                            <a href="{{ route('asset-categories.index') }}" class="btn bg-gradient-secondary btn-sm mb-0 me-2">
                                <i class="fas fa-arrow-left me-2"></i>Back to Categories
                            </a>
                            @can('manage_categories')
                            <a href="{{ route('asset-categories.edit', $category) }}" class="btn bg-gradient-info btn-sm mb-0">
                                <i class="fas fa-edit me-2"></i>Edit Category
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Category Name</label>
                                <div class="form-control-static">
                                    <h6 class="mb-0">{{ $category->name }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Created Date</label>
                                <div class="form-control-static">
                                    <h6 class="mb-0">{{ $category->created_at->format('M d, Y H:i A') }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="form-control-label">Description</label>
                                <div class="form-control-static">
                                    <p class="mb-0">{{ $category->description ?: 'No description provided' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Last Updated</label>
                                <div class="form-control-static">
                                    <h6 class="mb-0">{{ $category->updated_at->format('M d, Y H:i A') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label">Total Assets</label>
                                <div class="form-control-static">
                                    <span class="badge badge-lg bg-gradient-info">{{ $category->assets->count() }} Assets</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0">Quick Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div class="text-center">
                                <h3 class="font-weight-bolder text-info">{{ $category->assets->count() }}</h3>
                                <p class="mb-0 text-sm">Total Assets</p>
                            </div>
                        </div>
                        <div class="col-12 mb-3">
                            <div class="text-center">
                                <h3 class="font-weight-bolder text-success">{{ $category->logs->count() }}</h3>
                                <p class="mb-0 text-sm">Activity Logs</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="text-center">
                                <h3 class="font-weight-bolder text-secondary">{{ $category->created_at->diffForHumans() }}</h3>
                                <p class="mb-0 text-sm">Created</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @can('delete-asset-category')
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <h6 class="mb-0 text-danger">Danger Zone</h6>
                </div>
                <div class="card-body">
                    <p class="text-sm mb-3">Deleting this category will remove it permanently. This action cannot be undone.</p>
                    @if($category->assets->count() > 0)
                        <div class="alert alert-warning" role="alert">
                            <small><i class="fas fa-exclamation-triangle me-2"></i>This category has {{ $category->assets->count() }} associated assets and cannot be deleted.</small>
                        </div>
                    @else
                        <form action="{{ route('asset-categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this category? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="fas fa-trash me-2"></i>Delete Category
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @endcan
        </div>
    </div>

    @if($category->assets->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Associated Assets</h5>
                            <p class="text-sm mb-0">Assets belonging to this category</p>
                        </div>
                        <div>
                            <a href="{{ route('assets.index', ['asset_category_id' => $category->id]) }}" class="btn bg-gradient-primary btn-sm mb-0">
                                <i class="fas fa-external-link-alt me-2"></i>View All Assets
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Asset Tag</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Assigned To</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($category->assets->take(10) as $asset)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $asset->asset_tag }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs text-secondary mb-0">{{ $asset->name }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $asset->status === 'Available' ? 'success' : ($asset->status === 'Assigned' ? 'info' : ($asset->status === 'Maintenance' ? 'warning' : 'secondary')) }}">{{ $asset->status }}</span>
                                    </td>
                                    <td>
                                        <span class="text-secondary text-xs font-weight-bold">{{ $asset->user ? $asset->user->name : 'Unassigned' }}</span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('assets.show', $asset) }}" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="View Asset">
                                            <i class="fas fa-eye text-dark me-2" aria-hidden="true"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($category->assets->count() > 10)
                    <div class="text-center mt-3">
                        <a href="{{ route('assets.index', ['asset_category_id' => $category->id]) }}" class="btn btn-link text-primary">
                            View all {{ $category->assets->count() }} assets <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection