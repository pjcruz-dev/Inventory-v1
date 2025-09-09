@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative">
                    <i class="fas fa-building position-absolute" style="font-size: 24px;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Edit Vendor</h5>
                    <p class="mb-0 text-sm">Update vendor information in the inventory system</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card mb-4 mx-4">
                <div class="card-header pb-0">
                    <div class="d-flex flex-row justify-content-between">
                        <div>
                            <h5 class="mb-0">Vendor Information</h5>
                            <p class="text-sm mb-0">Update the details for {{ $vendor->name }}</p>
                        </div>
                        <div>
                            <a href="{{ route('vendors.show', $vendor) }}" class="btn bg-gradient-info btn-sm mb-0 me-2">
                                <i class="fas fa-eye me-2"></i>View
                            </a>
                            <a href="{{ route('vendors.index') }}" class="btn bg-gradient-secondary btn-sm mb-0">
                                <i class="fas fa-arrow-left me-2"></i>Back to Vendors
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-check-circle"></i></span>
                            <span class="alert-text">{{ session('success') }}</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <span class="alert-icon"><i class="fas fa-exclamation-circle"></i></span>
                            <span class="alert-text">
                                <strong>Please fix the following errors:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Vendor Statistics -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-gradient-primary">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Total Assets</p>
                                                <h5 class="text-white font-weight-bolder mb-0">{{ $vendor->assets->count() }}</h5>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                <i class="fas fa-box text-dark text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-gradient-info">
                                <div class="card-body p-3">
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="numbers">
                                                <p class="text-white text-sm mb-0 text-capitalize font-weight-bold">Member Since</p>
                                                <h6 class="text-white font-weight-bolder mb-0">{{ $vendor->created_at->format('M Y') }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-4 text-end">
                                            <div class="icon icon-shape bg-white shadow text-center border-radius-md">
                                                <i class="fas fa-calendar text-dark text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('vendors.update', $vendor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-control-label">Vendor Name <span class="text-danger">*</span></label>
                                    <input class="form-control @error('name') is-invalid @enderror" 
                                           type="text" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $vendor->name) }}" 
                                           placeholder="Enter vendor name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_person" class="form-control-label">Contact Person</label>
                                    <input class="form-control @error('contact_person') is-invalid @enderror" 
                                           type="text" 
                                           id="contact_person" 
                                           name="contact_person" 
                                           value="{{ old('contact_person', $vendor->contact_person) }}" 
                                           placeholder="Enter contact person name">
                                    @error('contact_person')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-control-label">Email Address</label>
                                    <input class="form-control @error('email') is-invalid @enderror" 
                                           type="email" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $vendor->email) }}" 
                                           placeholder="Enter email address">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone" class="form-control-label">Phone Number</label>
                                    <input class="form-control @error('phone') is-invalid @enderror" 
                                           type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $vendor->phone) }}" 
                                           placeholder="Enter phone number">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="website" class="form-control-label">Website</label>
                                    <input class="form-control @error('website') is-invalid @enderror" 
                                           type="url" 
                                           id="website" 
                                           name="website" 
                                           value="{{ old('website', $vendor->website) }}" 
                                           placeholder="https://example.com">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address" class="form-control-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" 
                                              name="address" 
                                              rows="3" 
                                              placeholder="Enter vendor address">{{ old('address', $vendor->address) }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Vendor Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tax_id" class="form-control-label">Tax ID</label>
                                    <input class="form-control @error('tax_id') is-invalid @enderror" 
                                           type="text" 
                                           id="tax_id" 
                                           name="tax_id" 
                                           value="{{ old('tax_id', $vendor->tax_id ?? '') }}" 
                                           placeholder="Enter tax identification number">
                                    @error('tax_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_terms" class="form-control-label">Payment Terms</label>
                                    <select class="form-control @error('payment_terms') is-invalid @enderror" 
                                            id="payment_terms" 
                                            name="payment_terms">
                                        <option value="">Select payment terms</option>
                                        <option value="net_15" {{ old('payment_terms', $vendor->payment_terms ?? '') == 'net_15' ? 'selected' : '' }}>Net 15</option>
                                        <option value="net_30" {{ old('payment_terms', $vendor->payment_terms ?? '') == 'net_30' ? 'selected' : '' }}>Net 30</option>
                                        <option value="net_45" {{ old('payment_terms', $vendor->payment_terms ?? '') == 'net_45' ? 'selected' : '' }}>Net 45</option>
                                        <option value="net_60" {{ old('payment_terms', $vendor->payment_terms ?? '') == 'net_60' ? 'selected' : '' }}>Net 60</option>
                                        <option value="due_on_receipt" {{ old('payment_terms', $vendor->payment_terms ?? '') == 'due_on_receipt' ? 'selected' : '' }}>Due on Receipt</option>
                                        <option value="cash_on_delivery" {{ old('payment_terms', $vendor->payment_terms ?? '') == 'cash_on_delivery' ? 'selected' : '' }}>Cash on Delivery</option>
                                    </select>
                                    @error('payment_terms')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rating" class="form-control-label">Rating</label>
                                    <select class="form-control @error('rating') is-invalid @enderror" 
                                            id="rating" 
                                            name="rating">
                                        <option value="">Select rating</option>
                                        <option value="1" {{ old('rating', $vendor->rating ?? '') == '1' ? 'selected' : '' }}>1 - Poor</option>
                                        <option value="2" {{ old('rating', $vendor->rating ?? '') == '2' ? 'selected' : '' }}>2 - Fair</option>
                                        <option value="3" {{ old('rating', $vendor->rating ?? '') == '3' ? 'selected' : '' }}>3 - Good</option>
                                        <option value="4" {{ old('rating', $vendor->rating ?? '') == '4' ? 'selected' : '' }}>4 - Very Good</option>
                                        <option value="5" {{ old('rating', $vendor->rating ?? '') == '5' ? 'selected' : '' }}>5 - Excellent</option>
                                    </select>
                                    @error('rating')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status" class="form-control-label">Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" 
                                            name="status">
                                        <option value="active" {{ old('status', $vendor->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $vendor->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="suspended" {{ old('status', $vendor->status ?? '') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="description" class="form-control-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3" 
                                              placeholder="Enter vendor description or notes">{{ old('description', $vendor->description) }}</textarea>
                                    @error('description')
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
                                              placeholder="Additional notes or comments about this vendor">{{ old('notes', $vendor->notes ?? '') }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('vendors.show', $vendor) }}" class="btn btn-light me-2">
                                        <i class="fas fa-times me-2"></i>Cancel
                                    </a>
                                    <button type="submit" class="btn bg-gradient-dark">
                                        <i class="fas fa-save me-2"></i>Update Vendor
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

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('form').on('submit', function(e) {
        var name = $('#name').val().trim();
        var email = $('#email').val().trim();
        var website = $('#website').val().trim();
        
        // Validate required fields
        if (name === '') {
            e.preventDefault();
            $('#name').addClass('is-invalid');
            if (!$('#name').next('.invalid-feedback').length) {
                $('#name').after('<div class="invalid-feedback">Vendor name is required.</div>');
            }
            return false;
        }
        
        // Validate email format if provided
        if (email !== '' && !isValidEmail(email)) {
            e.preventDefault();
            $('#email').addClass('is-invalid');
            if (!$('#email').next('.invalid-feedback').length) {
                $('#email').after('<div class="invalid-feedback">Please enter a valid email address.</div>');
            }
            return false;
        }
        
        // Validate website format if provided
        if (website !== '' && !isValidUrl(website)) {
            e.preventDefault();
            $('#website').addClass('is-invalid');
            if (!$('#website').next('.invalid-feedback').length) {
                $('#website').after('<div class="invalid-feedback">Please enter a valid website URL.</div>');
            }
            return false;
        }
    });
    
    // Remove validation errors on input
    $('input, textarea').on('input', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').remove();
    });
    
    // Email validation function
    function isValidEmail(email) {
        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // URL validation function
    function isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (_) {
            return false;
        }
    }
});
</script>
@endpush