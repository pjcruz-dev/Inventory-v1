@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-user-plus position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create New User</h5>
                    <p class="mb-0 text-sm">Add a new user to the system</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">User Information</h6>
                <p class="text-sm mb-0">Enter the details for the new user</p>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('users.store') }}" method="POST" role="form text-left">
                    @csrf
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                            <span class="alert-text">Error! Please check the form for errors.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="employee_no" class="form-control-label">Employee Number</label>
                                <div class="@error('employee_no')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter employee number" id="employee_no" name="employee_no" value="{{ old('employee_no') }}">
                                    </div>
                                    @error('employee_no')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="first_name" class="form-control-label">First Name</label>
                                <div class="@error('first_name')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter first name" id="first_name" name="first_name" value="{{ old('first_name') }}">
                                    </div>
                                    @error('first_name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="last_name" class="form-control-label">Last Name</label>
                                <div class="@error('last_name')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter last name" id="last_name" name="last_name" value="{{ old('last_name') }}">
                                    </div>
                                    @error('last_name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email" class="form-control-label">Email Address</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input class="form-control" type="email" placeholder="name@example.com" id="email" name="email" value="{{ old('email') }}">
                                    </div>
                                    @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="role_id" class="form-control-label">Role</label>
                                <div class="@error('role_id')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        <select class="form-control" id="role_id" name="role_id">
                                            <option value="">Select a role</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                    {{ $role->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('role_id')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password" class="form-control-label">Password</label>
                                <div class="@error('password')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input class="form-control" type="password" placeholder="Enter password" id="password" name="password">
                                    </div>
                                    @error('password')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="password_confirmation" class="form-control-label">Confirm Password</label>
                                <div>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        <input class="form-control" type="password" placeholder="Confirm password" id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department" class="form-control-label">Department</label>
                                <div class="@error('department')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter department" id="department" name="department" value="{{ old('department') }}">
                                    </div>
                                    @error('department')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="position" class="form-control-label">Position</label>
                                <div class="@error('position')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter position" id="position" name="position" value="{{ old('position') }}">
                                    </div>
                                    @error('position')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label">Status</label>
                                <div class="@error('status')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                        <select class="form-control" id="status" name="status">
                                            <option value="">Select status</option>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                            <option value="Resigned" {{ old('status') == 'Resigned' ? 'selected' : '' }}>Resigned</option>
                                        </select>
                                    </div>
                                    @error('status')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-light me-3" onclick="ModalHandler.showCancelModal('{{ route('users.index') }}')">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary" onclick="ModalHandler.showFormConfirmModal('Create User', 'Are you sure you want to create this user?', this.form)">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection