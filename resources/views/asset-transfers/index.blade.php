@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Asset Transfers</h5>
                        </div>
                        <div class="ms-auto">
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Type here...">
                            </div>
                        </div>
                        @can('create-asset-transfer')
                        <a href="{{ route('asset-transfers.create') }}" class="btn bg-gradient-primary btn-sm mb-0 ms-3" type="button">+&nbsp; New Transfer</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show mx-4" role="alert">
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mx-4" role="alert">
                            <span class="alert-text">{{ session('error') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Asset</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">From</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">To</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Created At</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transfers as $transfer)
                                <tr>
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $transfer->id }}</p>
                                    </td>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <i class="fas fa-laptop me-3 text-sm"></i>
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $transfer->asset->name }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $transfer->asset->asset_tag }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $transfer->fromUser ? $transfer->fromUser->name : 'Inventory' }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0">{{ $transfer->from_location }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">
                                            {{ $transfer->toUser ? $transfer->toUser->name : 'Inventory' }}
                                        </p>
                                        <p class="text-xs text-secondary mb-0">{{ $transfer->to_location }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ date('M d, Y', strtotime($transfer->transfer_date)) }}</p>
                                    </td>
                                    <td>
                                        @if($transfer->status == 'Pending')
                                            <span class="badge badge-sm bg-gradient-warning">Pending</span>
                                        @elseif($transfer->status == 'Completed')
                                            <span class="badge badge-sm bg-gradient-success">Completed</span>
                                        @elseif($transfer->status == 'Cancelled')
                                            <span class="badge badge-sm bg-gradient-danger">Cancelled</span>
                                        @endif
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ date('M d, Y', strtotime($transfer->created_at)) }}</p>
                                    </td>
                                    <td class="text-center">
                                        <div class="dropdown">
                                            <a href="#" class="btn bg-gradient-dark dropdown-toggle btn-sm" data-bs-toggle="dropdown" id="navbarDropdownMenuLink2">
                                                Actions
                                            </a>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink2">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('asset-transfers.show', $transfer->id) }}">
                                                        <i class="fas fa-eye me-2"></i>View
                                                    </a>
                                                </li>
                                                @if($transfer->status == 'Pending')
                                                    @can('update-asset-transfer')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('asset-transfers.edit', $transfer->id) }}">
                                                            <i class="fas fa-edit me-2"></i>Edit
                                                        </a>
                                                    </li>
                                                    @endcan
                                                    @can('complete-asset-transfer')
                                                    <li>
                                                        <a class="dropdown-item" href="{{ route('asset-transfers.complete', $transfer->id) }}">
                                                            <i class="fas fa-check-circle me-2"></i>Complete Transfer
                                                        </a>
                                                    </li>
                                                    @endcan
                                                    @can('delete-asset-transfer')
                                                    <li>
                                                        <form action="{{ route('asset-transfers.destroy', $transfer->id) }}" method="post">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="dropdown-item text-danger" type="submit" onclick="return confirm('Are you sure you want to cancel this transfer?')">
                                                                <i class="fas fa-times-circle me-2"></i>Cancel Transfer
                                                            </button>
                                                        </form>
                                                    </li>
                                                    @endcan
                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center mt-3">
                        {{ $transfers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple search functionality
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const tableRows = document.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('keyup', function () {
            const searchTerm = searchInput.value.toLowerCase();
            
            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>

@endsection