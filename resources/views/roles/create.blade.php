@extends('layouts.user_type.auth')

@section('content')

<div>
    <div class="card card-body mx-4 mb-4">
        <div class="row gx-4">
            <div class="col-auto">
                <div class="avatar avatar-xl position-relative" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 1rem; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); display: flex; align-items: center; justify-content: center; width: 4rem; height: 4rem;">
                    <i class="fas fa-user-shield text-white" style="font-size: 1.5rem;"></i>
                </div>
            </div>
            <div class="col-auto my-auto">
                <div class="h-100">
                    <h5 class="mb-1">Create New Role</h5>
                    <p class="mb-0 text-sm">Create a new role with specific permissions</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">Role Information</h6>
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

                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name" class="form-control-label">Role Name</label>
                                <div class="@error('name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="Role Name" id="name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-control-label mb-0">Permissions</label>
                                <div>
                                    <button type="button" class="btn btn-sm btn-outline-primary me-2" id="selectAllBtn">
                                        <i class="fas fa-check-square me-1"></i>Select All
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="removeAllBtn">
                                        <i class="fas fa-square me-1"></i>Remove All
                                    </button>
                                </div>
                            </div>
                            
                            @php
                                $groupedPermissions = [
                                    'User Management' => $permissions->filter(fn($p) => str_contains($p->name, 'user')),
                                    'Role Management' => $permissions->filter(fn($p) => str_contains($p->name, 'role')),
                                    'Permission Management' => $permissions->filter(fn($p) => str_contains($p->name, 'permission')),
                                    'Asset Management' => $permissions->filter(fn($p) => str_contains($p->name, 'asset') && !str_contains($p->name, 'transfer')),
                                    'Asset Type Management' => $permissions->filter(fn($p) => str_contains($p->name, 'asset-type')),
                                    'Peripheral Management' => $permissions->filter(fn($p) => str_contains($p->name, 'peripheral')),
                                    'Asset Transfer Management' => $permissions->filter(fn($p) => str_contains($p->name, 'transfer')),
                                    'Print & Audit' => $permissions->filter(fn($p) => str_contains($p->name, 'print') || str_contains($p->name, 'audit')),
                                    'Legacy Inventory' => $permissions->filter(fn($p) => str_contains($p->name, 'product') || str_contains($p->name, 'category') || str_contains($p->name, 'supplier') || str_contains($p->name, 'order') || str_contains($p->name, 'report')),
                                ];
                            @endphp
                            
                            @foreach($groupedPermissions as $groupName => $groupPermissions)
                                @if($groupPermissions->count() > 0)
                                <div class="card mb-3">
                                    <div class="card-header pb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">{{ $groupName }}</h6>
                                            <div>
                                                <button type="button" class="btn btn-xs btn-outline-info me-1 select-group-btn" data-group="{{ strtolower(str_replace(' ', '-', $groupName)) }}">
                                                    <i class="fas fa-check-square me-1"></i>Select All
                                                </button>
                                                <button type="button" class="btn btn-xs btn-outline-secondary remove-group-btn" data-group="{{ strtolower(str_replace(' ', '-', $groupName)) }}">
                                                    <i class="fas fa-square me-1"></i>Remove All
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body pt-2">
                                        <div class="row">
                                            @foreach($groupPermissions as $permission)
                                            <div class="col-md-6 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input permission-checkbox group-{{ strtolower(str_replace(' ', '-', $groupName)) }}" type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="permission_{{ $permission->id }}">
                                                    <label class="custom-control-label" for="permission_{{ $permission->id }}">{{ ucwords(str_replace('-', ' ', $permission->name)) }}</label>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-light m-0" onclick="ModalHandler.showCancelModal('{{ route('roles.index') }}')">Cancel</button>
                        <button type="button" class="btn bg-gradient-primary m-0 ms-2" onclick="ModalHandler.showFormConfirmModal('Create Role', 'Are you sure you want to create this role?', this.form)">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllBtn = document.getElementById('selectAllBtn');
        const removeAllBtn = document.getElementById('removeAllBtn');
        const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
        const selectGroupBtns = document.querySelectorAll('.select-group-btn');
        const removeGroupBtns = document.querySelectorAll('.remove-group-btn');

        // Global Select All functionality
        if (selectAllBtn) {
            selectAllBtn.addEventListener('click', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
        }

        // Global Remove All functionality
        if (removeAllBtn) {
            removeAllBtn.addEventListener('click', function() {
                permissionCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        }

        // Group Select All functionality
        selectGroupBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.getAttribute('data-group');
                const groupCheckboxes = document.querySelectorAll('.group-' + group);
                groupCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
            });
        });

        // Group Remove All functionality
        removeGroupBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const group = this.getAttribute('data-group');
                const groupCheckboxes = document.querySelectorAll('.group-' + group);
                groupCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        });
    });
</script>
@endpush