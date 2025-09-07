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
                                <label for="name" class="form-control-label">Full Name</label>
                                <div class="@error('name')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter full name" id="name" name="name" value="{{ old('name') }}">
                                    </div>
                                    @error('name')
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
                                <label for="phone" class="form-control-label">Phone Number</label>
                                <div class="@error('phone')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input class="form-control" type="text" placeholder="+1 (555) 123-4567" id="phone" name="phone" value="{{ old('phone') }}">
                                    </div>
                                    @error('phone')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location" class="form-control-label">Location</label>
                                <div class="@error('location')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        <input class="form-control" type="text" placeholder="Enter location" id="location" name="location" value="{{ old('location') }}">
                                    </div>
                                    @error('location')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="about_me">About Me</label>
                                <div class="@error('about_me')border border-danger rounded-3 @enderror">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-align-left"></i></span>
                                        <textarea class="form-control" id="about_me" rows="3" placeholder="Enter a brief description about the user" name="about_me">{{ old('about_me') }}</textarea>
                                    </div>
                                    @error('about_me')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="roles" class="form-control-label">Assign Roles</label>
                                <div class="@error('roles')border border-danger rounded-3 @enderror">
                                    <div class="row">
                                        @foreach($roles as $role)
                                        <div class="col-md-3 mb-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->id }}" id="role_{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="role_{{ $role->id }}">{{ $role->name }}</label>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('roles')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('users.index') }}" class="btn btn-light me-3">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection