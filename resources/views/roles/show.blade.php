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
                    <h5 class="mb-1">Role Details</h5>
                    <p class="mb-0 text-sm">View role information and permissions</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-end">
                <a href="{{ route('roles.edit', $role->id) }}" class="btn bg-gradient-warning">
                    <i class="fas fa-edit"></i>&nbsp;&nbsp;Edit Role
                </a>
                <a href="{{ route('roles.index') }}" class="btn bg-gradient-dark">
                    <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Role Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="alert-text">{{ $message }}</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Role Name</label>
                            <p class="form-control-static">{{ $role->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label">Created At</label>
                            <p class="form-control-static">{{ $role->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <label class="form-control-label">Permissions</label>
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    @if(!empty($rolePermissions))
                                        @foreach($rolePermissions as $permission)
                                            <div class="col-md-3 mb-2">
                                                <span class="badge bg-gradient-info">{{ $permission->name }}</span>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12">
                                            <p class="text-muted">No permissions assigned to this role.</p>
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