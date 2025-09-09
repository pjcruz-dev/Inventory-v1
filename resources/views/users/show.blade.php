@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-user position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">{{ $user->name }}</h5>
                    <p class="mb-0 text-sm">User Details</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-end">
                <div class="nav-wrapper position-relative end-0">
                    <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                        @can('edit_users')
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1 active" href="{{ route('users.edit', $user->id) }}">
                                <i class="fas fa-user-edit text-sm me-2"></i> Edit
                            </a>
                        </li>
                        @endcan
                        <li class="nav-item">
                            <a class="nav-link mb-0 px-0 py-1" href="{{ route('users.index') }}">
                                <i class="fas fa-arrow-left text-sm me-2"></i> Back
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">User Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                        <span class="alert-text">Success! {{ session('success') }}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">Full Name</label>
                            <p>{{ $user->name }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">Email Address</label>
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">Phone Number</label>
                            <p>{{ $user->phone ?: 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">Location</label>
                            <p>{{ $user->location ?: 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">About</label>
                            <p>{{ $user->about_me ?: 'No information provided.' }}</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">Created At</label>
                            <p>{{ $user->created_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-control-label fw-bold">Last Updated</label>
                            <p>{{ $user->updated_at->format('F d, Y h:i A') }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection