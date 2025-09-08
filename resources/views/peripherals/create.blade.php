@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-keyboard position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create Peripheral</h5>
                    <p class="mb-0 text-sm">Add a new peripheral to the inventory system</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('peripherals.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Peripheral Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('peripherals.store') }}" method="POST" role="form text-left">
                    @csrf
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
                                <label for="details" class="form-control-label">Details</label>
                                <div class="@error('details') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Logitech MX Keys" id="details" name="details" value="{{ old('details') }}">
                                </div>
                                @error('details')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="form-control-label">Type <span class="text-danger">*</span></label>
                                <div class="@error('type') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="Keyboard" {{ old('type') == 'Keyboard' ? 'selected' : '' }}>Keyboard</option>
                                        <option value="Mouse" {{ old('type') == 'Mouse' ? 'selected' : '' }}>Mouse</option>
                                        <option value="Monitor" {{ old('type') == 'Monitor' ? 'selected' : '' }}>Monitor</option>
                                        <option value="Printer" {{ old('type') == 'Printer' ? 'selected' : '' }}>Printer</option>
                                        <option value="Scanner" {{ old('type') == 'Scanner' ? 'selected' : '' }}>Scanner</option>
                                        <option value="Docking Station" {{ old('type') == 'Docking Station' ? 'selected' : '' }}>Docking Station</option>
                                        <option value="External Drive" {{ old('type') == 'External Drive' ? 'selected' : '' }}>External Drive</option>
                                        <option value="Webcam" {{ old('type') == 'Webcam' ? 'selected' : '' }}>Webcam</option>
                                        <option value="Headset" {{ old('type') == 'Headset' ? 'selected' : '' }}>Headset</option>
                                        <option value="Other" {{ old('type') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                @error('type')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_no" class="form-control-label">Serial Number</label>
                                <div class="@error('serial_no') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="SN12345678" id="serial_no" name="serial_no" value="{{ old('serial_no') }}">
                                </div>
                                @error('serial_no')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_id" class="form-control-label">Assigned Asset</label>
                                <div class="@error('asset_id') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="asset_id" name="asset_id">
                                        <option value="">Not Assigned</option>
                                        @foreach($assets as $asset)
                                            <option value="{{ $asset->id }}" {{ (old('asset_id') == $asset->id || (isset($assetId) && $assetId == $asset->id)) ? 'selected' : '' }}>
                                                {{ $asset->asset_tag }} - {{ $asset->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('asset_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-secondary me-2" onclick="ModalHandler.showCancelModal()">
                            <i class="fas fa-times me-1"></i>Cancel
                        </button>
                        <button type="button" class="btn bg-gradient-primary btn-md" onclick="ModalHandler.showFormConfirmModal('create', 'peripheral', this.form)">
                            <i class="fas fa-plus me-1"></i>Create Peripheral
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection