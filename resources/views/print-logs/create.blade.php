@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Create Print Log</h6>
                        <a href="{{ route('print-logs.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to Print Logs
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('print-logs.store') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="asset_id" class="form-control-label">Asset <span class="text-danger">*</span></label>
                                    <select class="form-control @error('asset_id') is-invalid @enderror" id="asset_id" name="asset_id" required>
                                        <option value="">Select Asset</option>
                                        @foreach($assets as $asset)
                                            <option value="{{ $asset->id }}" 
                                                {{ (old('asset_id', $assetId) == $asset->id) ? 'selected' : '' }}
                                                data-asset-name="{{ $asset->name }}"
                                                data-asset-type="{{ $asset->assetType->name }}">
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
                                    <label for="print_format" class="form-control-label">Print Format <span class="text-danger">*</span></label>
                                    <select class="form-control @error('print_format') is-invalid @enderror" id="print_format" name="print_format" required>
                                        <option value="">Select Print Format</option>
                                        @foreach($printFormats as $format)
                                            <option value="{{ $format }}" {{ old('print_format') == $format ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $format)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('print_format')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="copies" class="form-control-label">Number of Copies <span class="text-danger">*</span></label>
                                    <input class="form-control @error('copies') is-invalid @enderror" 
                                           type="number" 
                                           id="copies" 
                                           name="copies" 
                                           value="{{ old('copies', 1) }}" 
                                           min="1" 
                                           max="100" 
                                           required>
                                    @error('copies')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="destination_printer" class="form-control-label">Destination Printer</label>
                                    <input class="form-control @error('destination_printer') is-invalid @enderror" 
                                           type="text" 
                                           id="destination_printer" 
                                           name="destination_printer" 
                                           value="{{ old('destination_printer') }}" 
                                           placeholder="e.g., HP LaserJet Pro - Finance">
                                    @error('destination_printer')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="note" class="form-control-label">Note</label>
                                    <textarea class="form-control @error('note') is-invalid @enderror" 
                                              id="note" 
                                              name="note" 
                                              rows="3" 
                                              placeholder="Optional note about this print job...">{{ old('note') }}</textarea>
                                    @error('note')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($selectedAsset)
                        <div class="row">
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="redirect_to_asset" name="redirect_to_asset" value="1" checked>
                                    <label class="form-check-label" for="redirect_to_asset">
                                        Return to asset details page after creating print log
                                    </label>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-secondary me-2" onclick="ModalHandler.showCancelModal()">
                                        <i class="fas fa-times me-1"></i>Cancel
                                    </button>
                                    <button type="button" class="btn bg-gradient-primary" onclick="ModalHandler.showFormConfirmModal('create', 'print log', this.form)">
                                        <i class="fas fa-plus me-1"></i>Create Print Log
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const assetSelect = document.getElementById('asset_id');
        const printFormatSelect = document.getElementById('print_format');
        
        // Auto-select label format for new assets
        assetSelect.addEventListener('change', function() {
            if (this.value && !printFormatSelect.value) {
                printFormatSelect.value = 'label';
            }
        });
    });
</script>
@endpush
@endsection