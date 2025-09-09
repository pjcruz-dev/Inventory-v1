@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User Role Management</h3>
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

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Current Role</th>
                                    <th>Assign Role</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-sm rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mr-2">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <strong>{{ $user->name }}</strong>
                                                <br>
                                                <small class="text-muted">ID: {{ $user->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if($user->role)
                                            <span class="badge badge-{{ $user->role->name === 'Admin' ? 'danger' : ($user->role->name === 'IT Staff' ? 'warning' : 'info') }}">
                                                {{ $user->role->name }}
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">No Role</span>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('settings.users.role', $user) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group input-group-sm">
                                                <select name="role_id" class="form-control">
                                                    <option value="">No Role</option>
                                                    @foreach($roles as $role)
                                                        <option value="{{ $role->id }}" 
                                                                {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                            {{ $role->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary btn-sm">
                                                        <i class="fas fa-save"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" 
                                                data-toggle="modal" 
                                                data-target="#userPermissionsModal"
                                                onclick="showUserPermissions({{ $user->id }}, '{{ $user->name }}')">
                                            <i class="fas fa-eye"></i> View Permissions
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Permissions Modal -->
<div class="modal fade" id="userPermissionsModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">User Permissions</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="userPermissionsContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 16px;
    font-weight: bold;
}

.table td {
    vertical-align: middle;
}

.permission-group {
    margin-bottom: 1rem;
}

.permission-item {
    padding: 0.25rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.permission-item:last-child {
    border-bottom: none;
}
</style>
@endpush

@push('scripts')
<script>
function showUserPermissions(userId, userName) {
    const modal = $('#userPermissionsModal');
    const content = $('#userPermissionsContent');
    
    // Update modal title
    modal.find('.modal-title').text(`Permissions for ${userName}`);
    
    // Show loading
    content.html(`
        <div class="text-center">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    `);
    
    // Fetch user permissions
    fetch(`/api/users/${userId}/permissions`)
        .then(response => response.json())
        .then(data => {
            let html = '';
            
            if (data.role) {
                html += `
                    <div class="alert alert-info">
                        <strong>Role:</strong> ${data.role.name}
                        <br>
                        <small>${data.role.description || 'No description'}</small>
                    </div>
                `;
            } else {
                html += `
                    <div class="alert alert-warning">
                        <strong>No role assigned</strong>
                    </div>
                `;
            }
            
            if (data.permissions && data.permissions.length > 0) {
                // Group permissions
                const groupedPermissions = {};
                data.permissions.forEach(permission => {
                    const group = permission.name.split('.')[0];
                    if (!groupedPermissions[group]) {
                        groupedPermissions[group] = [];
                    }
                    groupedPermissions[group].push(permission);
                });
                
                html += '<h6>Permissions:</h6>';
                
                Object.keys(groupedPermissions).forEach(group => {
                    html += `
                        <div class="permission-group">
                            <h6 class="text-uppercase text-muted border-bottom pb-1">
                                ${group.replace('_', ' ')}
                            </h6>
                    `;
                    
                    groupedPermissions[group].forEach(permission => {
                        html += `
                            <div class="permission-item">
                                <i class="fas fa-check text-success mr-2"></i>
                                ${permission.name.replace(/[_.]/g, ' ')}
                            </div>
                        `;
                    });
                    
                    html += '</div>';
                });
            } else {
                html += `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        No permissions assigned
                    </div>
                `;
            }
            
            content.html(html);
        })
        .catch(error => {
            console.error('Error:', error);
            content.html(`
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    Error loading user permissions
                </div>
            `);
        });
}
</script>
@endpush