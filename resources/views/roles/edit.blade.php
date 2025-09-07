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
                    <h5 class="mb-1">Edit Role</h5>
                    <p class="mb-0 text-sm">Modify role name and permissions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Role Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                @if (count($errors) > 0)
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                    <span class="alert-text">
                        <strong>Whoops!</strong> There were some problems with your input.<br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <form action="{{ route('roles.update', $role->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Role Name</label>
                                <div class="@error('name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Role Name" id="name" name="name" value="{{ $role->name }}">
                                    @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <label class="form-control-label">Permissions</label>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($permissions as $permission)
                                        <div class="col-md-3 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="permission[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}"
                                                    {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="permission_{{ $permission->id }}">{{ $permission->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('roles.index') }}" class="btn btn-light m-0">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary m-0 ms-2">Update Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection