@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Edit Asset Transfer</h6>
                        <a href="{{ route('asset-transfers.show', $assetTransfer->id) }}" class="btn btn-outline-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to Transfer
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($assetTransfer->status !== 'pending')
                        <div class="alert alert-warning" role="alert">
                            <strong>Warning:</strong> Only pending transfers can be edited. This transfer is currently {{ $assetTransfer->status }}.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('asset-transfers.update', $assetTransfer->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_id" class="form-control-label">Asset <span class="text-danger">*</span></label>
                                    <select class="form-control @error('asset_id') is-invalid @enderror" id="asset_id" name="asset_id" required {{ $assetTransfer->status !== 'pending' ? 'disabled' : '' }}>
                                        <option value="">Select Asset</option>
                                        @foreach($assets as $asset)
                                            <option value="{{ $asset->id }}" 
                                                {{ (old('asset_id', $assetTransfer->asset_id) == $asset->id) ? 'selected' : '' }}>
                                                {{ $asset->asset_tag }} - {{ $asset->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('asset_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transfer_date" class="form-control-label">Transfer Date <span class="text-danger">*</span></label>
                                    <input class="form-control @error('transfer_date') is-invalid @enderror" 
                                           type="date" 
                                           id="transfer_date" 
                                           name="transfer_date" 
                                           value="{{ old('transfer_date', $assetTransfer->transfer_date->format('Y-m-d')) }}" 
                                           required
                                           {{ $assetTransfer->status !== 'pending' ? 'readonly' : '' }}>
                                    @error('transfer_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_user_id" class="form-control-label">From User</label>
                                    <select class="form-control @error('from_user_id') is-invalid @enderror" id="from_user_id" name="from_user_id" {{ $assetTransfer->status !== 'pending' ? 'disabled' : '' }}>
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ (old('from_user_id', $assetTransfer->from_user_id) == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('from_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_user_id" class="form-control-label">To User</label>
                                    <select class="form-control @error('to_user_id') is-invalid @enderror" id="to_user_id" name="to_user_id" {{ $assetTransfer->status !== 'pending' ? 'disabled' : '' }}>
                                        <option value="">Select User (Optional)</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" 
                                                {{ (old('to_user_id', $assetTransfer->to_user_id) == $user->id) ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('to_user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="from_location" class="form-control-label">From Location</label>
                                    <input class="form-control @error('from_location') is-invalid @enderror" 
                                           type="text" 
                                           id="from_location" 
                                           name="from_location" 
                                           value="{{ old('from_location', $assetTransfer->from_location) }}" 
                                           placeholder="e.g., IT Storage Room"
                                           {{ $assetTransfer->status !== 'pending' ? 'readonly' : '' }}>
                                    @error('from_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="to_location" class="form-control-label">To Location</label>
                                    <input class="form-control @error('to_location') is-invalid @enderror" 
                                           type="text" 
                                           id="to_location" 
                                           name="to_location" 
                                           value="{{ old('to_location', $assetTransfer->to_location) }}" 
                                           placeholder="e.g., Finance Department"
                                           {{ $assetTransfer->status !== 'pending' ? 'readonly' : '' }}>
                                    @error('to_location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required {{ $assetTransfer->status !== 'pending' ? 'disabled' : '' }}>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status }}" 
                                                {{ (old('status', $assetTransfer->status) == $status) ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="transfer_reason" class="form-control-label">Transfer Reason</label>
                                    <select class="form-control @error('transfer_reason') is-invalid @enderror" id="transfer_reason" name="transfer_reason" {{ $assetTransfer->status !== 'pending' ? 'disabled' : '' }}>
                                        <option value="">Select Reason (Optional)</option>
                                        <option value="assignment" {{ old('transfer_reason', $assetTransfer->transfer_reason) == 'assignment' ? 'selected' : '' }}>Assignment</option>
                                        <option value="relocation" {{ old('transfer_reason', $assetTransfer->transfer_reason) == 'relocation' ? 'selected' : '' }}>Relocation</option>
                                        <option value="maintenance" {{ old('transfer_reason', $assetTransfer->transfer_reason) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="upgrade" {{ old('transfer_reason', $assetTransfer->transfer_reason) == 'upgrade' ? 'selected' : '' }}>Upgrade</option>
                                        <option value="disposal" {{ old('transfer_reason', $assetTransfer->transfer_reason) == 'disposal' ? 'selected' : '' }}>Disposal</option>
                                        <option value="other" {{ old('transfer_reason', $assetTransfer->transfer_reason) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('transfer_reason')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notes" class="form-control-label">Notes</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="3" 
                                              placeholder="Additional notes about this transfer..."
                                              {{ $assetTransfer->status !== 'pending' ? 'readonly' : '' }}>{{ old('notes', $assetTransfer->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($assetTransfer->status === 'pending')
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-outline-secondary me-2" onclick="ModalHandler.showCancelModal()">Cancel</button>
                                    <button type="button" class="btn bg-gradient-primary" onclick="ModalHandler.showFormConfirmModal(this.closest('form'), 'Update Transfer', 'Are you sure you want to update this asset transfer?')">
                                        <i class="fas fa-save"></i>&nbsp;&nbsp;Update Transfer
                                    </button>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-info" role="alert">
                                    <strong>Info:</strong> This transfer cannot be edited because it is {{ $assetTransfer->status }}.
                                </div>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection