@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-map-marker-alt position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Edit Location</h5>
                    <p class="mb-0 text-sm">Update location information</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('locations.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Location Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('locations.update', $location->id) }}" method="POST" role="form text-left">
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
                                    <input class="form-control" type="text" placeholder="Headquarters" id="name" name="name" value="{{ old('name', $location->name) }}" required>
                                </div>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="address" class="form-control-label">Address</label>
                                <div class="@error('address') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="123 Main St, City, Country" id="address" name="address" value="{{ old('address', $location->address) }}">
                                </div>
                                @error('address')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city" class="form-control-label">City</label>
                                <div class="@error('city') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="New York" id="city" name="city" value="{{ old('city', $location->city) }}">
                                </div>
                                @error('city')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="state" class="form-control-label">State/Province</label>
                                <div class="@error('state') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="NY" id="state" name="state" value="{{ old('state', $location->state) }}">
                                </div>
                                @error('state')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country" class="form-control-label">Country</label>
                                <div class="@error('country') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="USA" id="country" name="country" value="{{ old('country', $location->country) }}">
                                </div>
                                @error('country')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="zip" class="form-control-label">Zip/Postal Code</label>
                                <div class="@error('zip') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="10001" id="zip" name="zip" value="{{ old('zip', $location->zip) }}">
                                </div>
                                @error('zip')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="form-control-label">Notes</label>
                                <div class="@error('notes') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional information about this location">{{ old('notes', $location->notes) }}</textarea>
                                </div>
                                @error('notes')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="submit" class="btn bg-gradient-primary btn-md">Update Location</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection