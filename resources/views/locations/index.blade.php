@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">All Locations</h5>
                        </div>
                        <div class="ms-auto">
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Type here...">
                            </div>
                        </div>
                        @can('create', App\Models\Location::class)
                        <a href="{{ route('locations.create') }}" class="btn bg-gradient-primary btn-sm mb-0 ms-3" type="button">+&nbsp; New Location</a>
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
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        ID
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Address
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Assets Count
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Created At
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locations as $location)
                                <tr class="searchable-item">
                                    <td class="ps-4">
                                        <p class="text-xs font-weight-bold mb-0">{{ $location->id }}</p>
                                    </td>
                                    <td>
                                        <div>
                                            <p class="text-xs font-weight-bold mb-0">{{ $location->name }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $location->address ?: 'N/A' }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $location->assets_count }}</p>
                                    </td>
                                    <td>
                                        <p class="text-xs font-weight-bold mb-0">{{ $location->created_at->format('d M Y') }}</p>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="fa fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('locations.show', $location->id) }}">
                                                        <i class="fas fa-eye me-2"></i> View
                                                    </a>
                                                </li>
                                                @can('update', $location)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('locations.edit', $location->id) }}">
                                                        <i class="fas fa-edit me-2"></i> Edit
                                                    </a>
                                                </li>
                                                @endcan
                                                @can('delete', $location)
                                                <li>
                                                    <form action="{{ route('locations.destroy', $location->id) }}" method="POST" id="delete-form-{{ $location->id }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item" onclick="confirmDelete({{ $location->id }})">
                                                            <i class="fas fa-trash me-2"></i> Delete
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
                        {{ $locations->links() }}
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
        const searchableItems = document.querySelectorAll('.searchable-item');

        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();

            searchableItems.forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    function confirmDelete(id) {
        if (confirm('Are you sure you want to delete this location?')) {
            document.getElementById('delete-form-' + id).submit();
        }
    }
</script>

@endsection