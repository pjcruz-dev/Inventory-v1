@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-key position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create New Permission</h5>
                    <p class="mb-0 text-sm">Add a new permission to the system</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Permission Information</h6>
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

                <form action="{{ route('permissions.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Permission Name</label>
                                <div class="@error('name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Permission Name" id="name" name="name" value="{{ old('name') }}">
                                    <small class="form-text text-muted">Suggested format: action-resource (e.g., create-user, edit-role)</small>
                                    @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('permissions.index') }}" class="btn btn-light m-0">Cancel</a>
                        <button type="submit" class="btn bg-gradient-primary m-0 ms-2">Create Permission</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection