@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Roles & Permissions Management</h3>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#createRoleModal">
                        <i class="fas fa-plus"></i> Create New Role
                    </button>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <div class="row">
                        @foreach($roles as $role)
                        <div class="col-md-6 mb-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">{{ $role->name }}</h5>
                                    <small class="text-muted">{{ $role->description }}</small>
                                    <div class="float-right">
                                        <span class="badge badge-info">{{ $role->users->count() }} users</span>
                                        @if($role->users->count() == 0)
                                        <button type="button" class="btn btn-sm btn-danger ml-2" onclick="deleteRole({{ $role->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('settings.roles.permissions', $role) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        
                                        @php
                                            $rolePermissions = $role->permissions->pluck('id')->toArray();
                                            $permissionGroups = $permissions->groupBy(function($permission) {
                                                return explode('.', $permission->name)[0];
                                            });
                                        @endphp
                                        
                                        @foreach($permissionGroups as $group => $groupPermissions)
                                        <div class="mb-3">
                                            <h6 class="text-uppercase text-muted border-bottom pb-1">{{ ucfirst(str_replace('_', ' ', $group)) }}</h6>
                                            @foreach($groupPermissions as $permission)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="permissions[]" 
                                                       value="{{ $permission->id }}"
                                                       id="role_{{ $role->id }}_perm_{{ $permission->id }}"
                                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="role_{{ $role->id }}_perm_{{ $permission->id }}">
                                                    {{ ucfirst(str_replace(['_', '.'], ' ', $permission->name)) }}
                                                </label>
                                            </div>
                                            @endforeach
                                        </div>
                                        @endforeach
                                        
                                        <div class="text-right">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-save"></i> Update Permissions
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('settings.roles.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Create New Role</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Permissions</label>
                        @php
                            $permissionGroups = $permissions->groupBy(function($permission) {
                                return explode('.', $permission->name)[0];
                            });
                        @endphp
                        @foreach($permissionGroups as $group => $groupPermissions)
                        <div class="mb-3">
                            <h6 class="text-uppercase text-muted border-bottom pb-1">{{ ucfirst(str_replace('_', ' ', $group)) }}</h6>
                            @foreach($groupPermissions as $permission)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" 
                                       name="permissions[]" 
                                       value="{{ $permission->id }}"
                                       id="new_perm_{{ $permission->id }}">
                                <label class="form-check-label" for="new_perm_{{ $permission->id }}">
                                    {{ ucfirst(str_replace(['_', '.'], ' ', $permission->name)) }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Role Form -->
<form id="deleteRoleForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@push('scripts')
<script>
function deleteRole(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        const form = document.getElementById('deleteRoleForm');
        form.action = `/settings/roles/${roleId}`;
        form.submit();
    }
}

$(document).ready(function() {
    // Add select all functionality for permission groups
    $('.permission-group-header').click(function() {
        const groupContainer = $(this).closest('.permission-group');
        const checkboxes = groupContainer.find('input[type="checkbox"]');
        const allChecked = checkboxes.length === checkboxes.filter(':checked').length;
        
        checkboxes.prop('checked', !allChecked);
    });
});
</script>
@endpush