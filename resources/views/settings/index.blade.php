@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">System Settings</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Roles Management -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Roles Management</h5>
                                    <a href="{{ route('settings.roles') }}" class="btn btn-primary btn-sm float-right">Manage Roles</a>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Configure user roles and their permissions</p>
                                    <div class="row">
                                        @foreach($roles as $role)
                                        <div class="col-12 mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span><strong>{{ $role->name }}</strong> ({{ $role->users->count() }} users)</span>
                                                <span class="badge badge-info">{{ $role->permissions->count() }} permissions</span>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Users Management -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Users Management</h5>
                                    <a href="{{ route('settings.users') }}" class="btn btn-primary btn-sm float-right">Manage Users</a>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Assign roles to users and manage access</p>
                                    <div class="row">
                                        <div class="col-12">
                                            <p><strong>Total Users:</strong> {{ $users->count() }}</p>
                                            @foreach($roles as $role)
                                            <div class="d-flex justify-content-between">
                                                <span>{{ $role->name }}:</span>
                                                <span>{{ $users->where('role_id', $role->id)->count() }} users</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Overview -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title">Permissions Overview</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @php
                                            $permissionGroups = $permissions->groupBy(function($permission) {
                                                return explode('.', $permission->name)[0];
                                            });
                                        @endphp
                                        @foreach($permissionGroups as $group => $groupPermissions)
                                        <div class="col-md-3 mb-3">
                                            <h6 class="text-uppercase text-muted">{{ ucfirst(str_replace('_', ' ', $group)) }}</h6>
                                            <ul class="list-unstyled">
                                                @foreach($groupPermissions as $permission)
                                                <li class="small">{{ $permission->name }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Add any JavaScript for settings page
});
</script>
@endpush