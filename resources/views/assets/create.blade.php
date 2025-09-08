@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-laptop position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create Asset</h5>
                    <p class="mb-0 text-sm">Add a new asset to the inventory system</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3 text-sm-end">
                <a href="{{ route('assets.index') }}" class="btn btn-sm btn-dark">
                    <i class="fas fa-arrow-left me-1"></i> Back to List
                </a>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Asset Information</h6>
            </div>
            <div class="card-body pt-4 p-3">
                <form action="{{ route('assets.store') }}" method="POST" role="form text-left">
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
                                <label for="asset_tag" class="form-control-label">Asset Tag <span class="text-danger">*</span></label>
                                <div class="@error('asset_tag') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="AST-12345" id="asset_tag" name="asset_tag" value="{{ old('asset_tag') }}" required>
                                </div>
                                @error('asset_tag')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Name <span class="text-danger">*</span></label>
                                <div class="@error('name') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Dell XPS 15" id="name" name="name" value="{{ old('name') }}" required>
                                </div>
                                @error('name')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="asset_type_id" class="form-control-label">Asset Type <span class="text-danger">*</span></label>
                                <div class="@error('asset_type_id') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="asset_type_id" name="asset_type_id" required>
                                        <option value="">Select Asset Type</option>
                                        @foreach($assetTypes as $type)
                                            <option value="{{ $type->id }}" {{ old('asset_type_id') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('asset_type_id')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="serial_number" class="form-control-label">Serial Number</label>
                                <div class="@error('serial_number') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="SN12345678" id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
                                </div>
                                @error('serial_number')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status" class="form-control-label">Status <span class="text-danger">*</span></label>
                                <div class="@error('status') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="status" name="status" required>
                                        <option value="Available" {{ old('status') == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Assigned" {{ old('status') == 'Assigned' ? 'selected' : '' }}>Assigned</option>
                                        <option value="Maintenance" {{ old('status') == 'Maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="Retired" {{ old('status') == 'Retired' ? 'selected' : '' }}>Retired</option>
                                    </select>
                                </div>
                                @error('status')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_date" class="form-control-label">Purchase Date</label>
                                <div class="@error('purchase_date') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="date" id="purchase_date" name="purchase_date" value="{{ old('purchase_date') }}">
                                </div>
                                @error('purchase_date')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="purchase_cost" class="form-control-label">Purchase Cost</label>
                                <div class="@error('purchase_cost') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="number" step="0.01" placeholder="1299.99" id="purchase_cost" name="purchase_cost" value="{{ old('purchase_cost') }}">
                                </div>
                                @error('purchase_cost')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="warranty_expiry" class="form-control-label">Warranty Expiry</label>
                                <div class="@error('warranty_expiry') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="date" id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry') }}">
                                </div>
                                @error('warranty_expiry')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="location" class="form-control-label">Location</label>
                                <div class="@error('location') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Main Office" id="location" name="location" value="{{ old('location') }}">
                                </div>
                                @error('location')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="assigned_to" class="form-control-label">Assigned To</label>
                                <div class="@error('assigned_to') border border-danger rounded-3 @enderror">
                                    <select class="form-control" id="assigned_to" name="assigned_to">
                                        <option value="">Not Assigned</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('assigned_to')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="notes" class="form-control-label">Notes</label>
                                <div class="@error('notes') border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Additional information about this asset">{{ old('notes') }}</textarea>
                                </div>
                                @error('notes')
                                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn bg-gradient-secondary btn-md me-2" onclick="ModalHandler.showCancelModal('{{ route('assets.index') }}')">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary btn-md" onclick="ModalHandler.showFormConfirmModal('Create Asset', 'Are you sure you want to create this asset?', this.form)">Create Asset</button>
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