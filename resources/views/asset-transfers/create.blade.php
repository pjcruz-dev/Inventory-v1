@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-exchange-alt position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create Asset Transfer</h5>
                    <p class="mb-0 text-sm">Transfer an asset to a new user or location</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('asset-transfers.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Transfer Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('asset-transfers.store') }}" method="POST" role="form text-left">
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
                                <label for="asset_id" class="form-control-label">Asset <span class="text-danger">*</span></label>
                                <div class="@error('asset_id') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="asset_id" name="asset_id" required {{ isset($asset) ? 'disabled' : '' }}>
                                        <option value="">Select Asset</option>
                                        @foreach($assets as $assetItem)
                                            <option value="{{ $assetItem->id }}" {{ (old('asset_id') == $assetItem->id || (isset($asset) && $asset->id == $assetItem->id)) ? 'selected' : '' }}>
                                                {{ $assetItem->asset_tag }} - {{ $assetItem->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(isset($asset))
                                        <input type="hidden" name="asset_id" value="{{ $asset->id }}">
                                    @endif
                                </div>
                                @error('asset_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="transfer_date" class="form-control-label">Transfer Date <span class="text-danger">*</span></label>
                                <div class="@error('transfer_date') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="date" id="transfer_date" name="transfer_date" value="{{ old('transfer_date', date('Y-m-d')) }}" required>
                                </div>
                                @error('transfer_date')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from_user_id" class="form-control-label">From User</label>
                                <div class="@error('from_user_id') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="from_user_id" name="from_user_id" {{ isset($asset) && $asset->assigned_to ? 'disabled' : '' }}>
                                        <option value="">Not Assigned</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ (old('from_user_id') == $user->id || (isset($asset) && $asset->assigned_to == $user->id)) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @if(isset($asset) && $asset->assigned_to)
                                        <input type="hidden" name="from_user_id" value="{{ $asset->assigned_to }}">
                                    @endif
                                </div>
                                @error('from_user_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_user_id" class="form-control-label">To User <span class="text-danger">*</span></label>
                                <div class="@error('to_user_id') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="to_user_id" name="to_user_id" required>
                                        <option value="">Not Assigned (Return to Inventory)</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('to_user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('to_user_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="from_location" class="form-control-label">From Location</label>
                                <div class="@error('from_location') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" id="from_location" name="from_location" value="{{ old('from_location', isset($asset) ? $asset->location : '') }}" {{ isset($asset) ? 'readonly' : '' }}>
                                </div>
                                @error('from_location')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to_location" class="form-control-label">To Location</label>
                                <div class="@error('to_location') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" id="to_location" name="to_location" value="{{ old('to_location') }}">
                                </div>
                                @error('to_location')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="form-control-label">Notes</label>
                                <div class="@error('notes') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional information about this transfer">{{ old('notes') }}</textarea>
                                </div>
                                @error('notes')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-outline-secondary me-2" onclick="ModalHandler.showCancelModal()">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary btn-md" onclick="ModalHandler.showFormConfirmModal(this.closest('form'), 'Create Transfer', 'Are you sure you want to create this asset transfer?')">Create Transfer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Asset selection change handler
        const assetSelect = document.getElementById('asset_id');
        
        assetSelect.addEventListener('change', function() {
            if (this.value) {
                // You could make an AJAX call here to get asset details
                // For now, we'll just clear the fields
                document.getElementById('from_user_id').value = '';
                document.getElementById('from_location').value = '';
            }
        });
        
        // To user selection handler
        const toUserSelect = document.getElementById('to_user_id');
        
        toUserSelect.addEventListener('change', function() {
            // If returning to inventory, clear to_location
            if (!this.value) {
                document.getElementById('to_location').value = 'Inventory';
            } else {
                document.getElementById('to_location').value = '';
            }
        });
    });
</script>

@endsection