@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-user-tag position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Role Management</h5>
                    <p class="mb-0 text-sm">Manage user roles and their permissions</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-end">
                <a href="{{ route('roles.create') }}" class="btn bg-gradient-primary">
                    <i class="fas fa-plus"></i>&nbsp;&nbsp;New Role
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="mb-0">Roles</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <form action="{{ route('roles.index') }}" method="GET" class="d-flex">
                            <div class="input-group">
                                <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                                <input type="text" name="search" class="form-control" placeholder="Search roles..." value="{{ request('search') }}">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body pt-4 p-3">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="alert-text">Success! {{ $message }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0">
                        <thead>
                            <tr>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Role Name</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Permissions</th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $key => $role)
                            <tr>
                                <td>
                                    <div class="d-flex px-3 py-1">
                                        <div class="d-flex flex-column justify-content-center">
                                            <p class="text-xs font-weight-bold mb-0">{{ ++$i }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">{{ $role->name }}</p>
                                </td>
                                <td>
                                    <p class="text-xs font-weight-bold mb-0">
                                        @php
                                            $permissions = $role->permissions->take(3);
                                            $remainingCount = $role->permissions->count() - 3;
                                        @endphp
                                        @foreach($permissions as $permission)
                                            <span class="badge bg-gradient-info">{{ $permission->name }}</span>
                                        @endforeach
                                        @if($remainingCount > 0)
                                            <span class="badge bg-gradient-secondary">+{{ $remainingCount }} more</span>
                                        @endif
                                    </p>
                                </td>
                                <td class="align-middle">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-icon-only text-dark" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('roles.show', $role->id) }}">
                                                    <i class="fas fa-eye text-info"></i> View
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">
                                                    <i class="fas fa-edit text-warning"></i> Edit
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('roles.destroy', $role->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item" onclick="return confirm('Are you sure you want to delete this role?')">
                                                        <i class="fas fa-trash text-danger"></i> Delete
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $roles->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection