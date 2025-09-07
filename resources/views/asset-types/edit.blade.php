@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-tag position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Edit Asset Type</h5>
                    <p class="mb-0 text-sm">Update asset type information</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('asset-types.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Asset Type Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('asset-types.update', $assetType) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT')
                    @if($errors->any())
                        <div class="alert alert-danger text-white" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Name <span class="text-danger">*</span></label>
                                <div class="@error('name') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Laptop, Desktop, etc." id="name" name="name" value="{{ old('name', $assetType->name) }}" required>
                                </div>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="description" class="form-control-label">Description</label>
                                <div class="@error('description') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Detailed description of this asset type">{{ old('description', $assetType->description) }}</textarea>
                                </div>
                                @error('description')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn bg-gradient-primary btn-md">Update Asset Type</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection