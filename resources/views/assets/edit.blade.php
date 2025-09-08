@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4 shadow-sm">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative bg-gradient-primary rounded-circle shadow">
                    <i class="fas fa-laptop text-white position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1 font-weight-bold">Edit Asset</h5>
                    <p class="mb-0 text-sm text-secondary">Update asset information</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('assets.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card shadow-sm">
            <div class="card-header pb-0 px-3 bg-gradient-light">
                <h6 class="mb-0 font-weight-bold">Asset Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('assets.update', $asset->id) }}" method="POST" role="form text-left">
                    @csrf
                    @method('PUT')
                    @if($errors->any())
                        <div class="alert alert-danger text-white shadow-sm" role="alert">
                            <span class="alert-icon"><i class="fas fa-exclamation-triangle"></i></span>
                            <span class="alert-text">
                                <strong>Whoops!</strong> There were some problems with your input.
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_tag" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Asset Tag <span class="text-danger">*</span></label>
                                <div class="input-group @error('asset_tag') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input class="form-control" type="text" placeholder="AST-12345" id="asset_tag" name="asset_tag" value="{{ old('asset_tag', $asset->asset_tag) }}" required>
                                </div>
                                @error('asset_tag')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Name <span class="text-danger">*</span></label>
                                <div class="input-group @error('name') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-laptop"></i></span>
                                    <input class="form-control" type="text" placeholder="Dell XPS 15" id="name" name="name" value="{{ old('name', $asset->name) }}" required>
                                </div>
                                @error('name')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_type_id" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Asset Type <span class="text-danger">*</span></label>
                                <div class="input-group @error('asset_type_id') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-list-alt"></i></span>
                                    <select class="form-control" id="asset_type_id" name="asset_type_id" required>
                                        <option value="">Select Asset Type</option>
                                        @foreach($assetTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('asset_type_id', $asset->asset_type_id) == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('asset_type_id')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Serial Number</label>
                                <div class="input-group @error('serial_number') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    <input class="form-control" type="text" placeholder="SN12345678" id="serial_number" name="serial_number" value="{{ old('serial_number', $asset->serial_number) }}">
                                </div>
                                @error('serial_number')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Status <span class="text-danger">*</span></label>
                                <div class="input-group @error('status') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="">Select Status</option>
                                        <option value="available" {{ old('status', $asset->status) == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="assigned" {{ old('status', $asset->status) == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="maintenance" {{ old('status', $asset->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="broken" {{ old('status', $asset->status) == 'broken' ? 'selected' : '' }}>Broken</option>
                                    </select>
                                </div>
                                @error('status')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_date" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Purchase Date</label>
                                <div class="input-group @error('purchase_date') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input class="form-control" type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date', $asset->purchase_date) }}">
                                </div>
                                @error('purchase_date')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_cost" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Purchase Cost</label>
                                <div class="input-group @error('purchase_cost') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                    <input class="form-control" type="number" step="0.01" placeholder="1299.99" id="purchase_cost" name="purchase_cost" value="{{ old('purchase_cost', $asset->purchase_cost) }}">
                                </div>
                                @error('purchase_cost')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="warranty_expiry" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Warranty Expiry</label>
                                <div class="input-group @error('warranty_expiry') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-shield-alt"></i></span>
                                    <input class="form-control" type="date" id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry', $asset->warranty_expiry) }}">
                                </div>
                                @error('warranty_expiry')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Location</label>
                                <div class="input-group @error('location') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input class="form-control" type="text" placeholder="Main Office" id="location" name="location" value="{{ old('location', $asset->location) }}">
                                </div>
                                @error('location')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_to" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Assigned To</label>
                                <div class="input-group @error('assigned_to') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <select class="form-control" id="assigned_to" name="assigned_to">
                                        <option value="">Not Assigned</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $asset->assigned_to) == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('assigned_to')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="form-control-label text-primary text-xs text-uppercase font-weight-bold">Notes</label>
                                <div class="input-group @error('notes') border border-danger rounded-3 @enderror">
                                    <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional information about this asset">{{ old('notes', $asset->notes) }}</textarea>
                                </div>
                                @error('notes')
                                    <p class="text-danger text-xs mt-2"><i class="fas fa-exclamation-circle me-1"></i>{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn bg-gradient-secondary btn-md me-2" onclick="ModalHandler.showCancelModal('{{ route('assets.index') }}')">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary btn-md" onclick="ModalHandler.showFormConfirmModal('Update Asset', 'Are you sure you want to update this asset?', this.form)">
                            <i class="fas fa-save me-2"></i>Update Asset
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Show/hide assigned_to field based on status
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('status');
        const assignedToGroup = document.getElementById('assigned_to').closest('.form-group');
        
        function toggleAssignedTo() {
            if (statusSelect.value === 'Assigned') {
                assignedToGroup.style.display = 'block';
                document.getElementById('assigned_to').setAttribute('required', 'required');
            } else {
                assignedToGroup.style.display = 'none';
                document.getElementById('assigned_to').removeAttribute('required');
            }
        }
        
        // Initial state
        toggleAssignedTo();
        
        // On change
        statusSelect.addEventListener('change', toggleAssignedTo);
    });
</script>

@endsection