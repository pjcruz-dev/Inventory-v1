@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-laptop position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Assets</h5>
                    <p class="mb-0 text-sm">Manage all assets in the inventory system</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Assets</h5>
                            <p class="text-sm mb-0">Manage all assets in the system</p>
                        </div>
                        <div class="d-flex">
                            <div class="input-group me-3" style="width: 250px;">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search assets...">
                            </div>
                            <div class="dropdown me-2">
                                <button class="btn bg-gradient-secondary dropdown-toggle btn-sm mb-0" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-2"></i>Filter
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                                    <li><h6 class="dropdown-header">Asset Type</h6></li>
                                    @foreach($assetTypes as $type)
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['asset_type_id' => $type->id]) }}">{{ $type->name }}</a></li>
                                    @endforeach
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Status</h6></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Available']) }}">Available</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Assigned']) }}">Assigned</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Maintenance']) }}">Maintenance</a></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index', ['status' => 'Retired']) }}">Retired</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="{{ route('assets.index') }}">Clear Filters</a></li>
                                </ul>
                            </div>
                            <div class="dropdown me-2">
                                <button class="btn bg-gradient-info dropdown-toggle btn-sm mb-0" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-file-export me-2"></i>Export/Import
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                                    @can('export-assets')
                                    <li><a class="dropdown-item" href="{{ route('export.assets') }}"><i class="fas fa-file-excel me-2"></i>Export to Excel</a></li>
                                    @endcan
                                    @can('import-assets')
                                    <li><a class="dropdown-item" href="{{ route('export.template') }}"><i class="fas fa-download me-2"></i>Download Template</a></li>
                                    <li><a class="dropdown-item" href="{{ route('import.form') }}"><i class="fas fa-file-upload me-2"></i>Import Assets</a></li>
                                    @endcan
                                </ul>
                            </div>
                            @can('create-asset')
                            <a href="{{ route('assets.create') }}" class="btn bg-gradient-primary btn-sm mb-0">
                                <i class="fas fa-plus me-2"></i>New Asset
                            </a>
                            @endcan
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
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
                    
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Asset Tag
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Asset
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Type
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Status
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Location
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Assigned To
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="assetsTable">
                                @foreach($assets as $asset)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $asset->asset_tag }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $asset->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $asset->serial_number }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $asset->assetType->name }}</p>
                                    </td>
                                    <td>
                                        <span class="badge badge-sm bg-gradient-{{ $asset->status == 'Available' ? 'success' : ($asset->status == 'Assigned' ? 'info' : ($asset->status == 'Maintenance' ? 'warning' : 'secondary')) }}">{{ $asset->status }}</span>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $asset->location ?? 'Not specified' }}</p>
                                    </td>
                                    <td>
                                        @if($asset->assignedTo)
                                            <div class="d-flex px-2 py-1">
                                                <div>
                                                    <img src="/assets/img/team-{{ rand(1, 4) }}.jpg" class="avatar avatar-xs me-2">
                                                </div>
                                                <div class="d-flex flex-column justify-content-center">
                                                    <h6 class="mb-0 text-xs">{{ $asset->assignedTo->name }}</h6>
                                                </div>
                                            </div>
                                        @else
                                            <p class="text-xs text-secondary mb-0">Not assigned</p>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                @can('view-assets')
                                                <li><a class="dropdown-item" href="{{ route('assets.show', $asset) }}"><i class="fas fa-eye me-2"></i>View</a></li>
                                                @endcan
                                                @can('edit-asset')
                                                <li><a class="dropdown-item" href="{{ route('assets.edit', $asset) }}"><i class="fas fa-edit me-2"></i>Edit</a></li>
                                                @endcan
                                                @can('print-asset')
                                                <li><a class="dropdown-item" href="{{ route('assets.print', $asset) }}"><i class="fas fa-print me-2"></i>Print</a></li>
                                                @endcan
                                                @can('create-asset-transfer')
                                                <li><a class="dropdown-item" href="{{ route('asset-transfers.create', ['asset_id' => $asset->id]) }}"><i class="fas fa-exchange-alt me-2"></i>Transfer</a></li>
                                                @endcan
                                                @can('delete-asset')
                                                <li>
                                                    <form action="{{ route('assets.destroy', $asset) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to delete this asset?')">
                                                            <i class="fas fa-trash me-2"></i>Delete
                                                        </button>
                                                    </form>
                                                </li>
                                                @endcan
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $assets->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const assetsTable = document.getElementById('assetsTable');
        const rows = assetsTable.getElementsByTagName('tr');
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = searchInput.value.toLowerCase();
            
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchTerm)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    });
</script>
 
@endsection