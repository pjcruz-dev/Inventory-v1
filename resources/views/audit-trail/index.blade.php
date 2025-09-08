@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Audit Trail</h6>
                        <div class="text-sm text-secondary">
                            <i class="fas fa-info-circle"></i> System activity log
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Search and Filter Form -->
                    <div class="row px-3 mb-3">
                        <div class="col-12">
                            <form method="GET" action="{{ route('audit-trail.index') }}">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="search" 
                                                   placeholder="Search..." 
                                                   value="{{ $search }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" name="entity_type">
                                                <option value="">All Entity Types</option>
                                                @foreach($entityTypes as $type)
                                                    <option value="{{ $type }}" {{ $entityType == $type ? 'selected' : '' }}>
                                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" name="action">
                                                <option value="">All Actions</option>
                                                @foreach($actions as $act)
                                                    <option value="{{ $act }}" {{ $action == $act ? 'selected' : '' }}>
                                                        {{ $act }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <select class="form-control" name="performed_by">
                                                <option value="">All Users</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}" {{ $performedBy == $user->id ? 'selected' : '' }}>
                                                        {{ $user->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="date_from" 
                                                   placeholder="From Date" 
                                                   value="{{ $dateFrom }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <input type="date" class="form-control" name="date_to" 
                                                   placeholder="To Date" 
                                                   value="{{ $dateTo }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-outline-primary btn-sm mb-0">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        <a href="{{ route('audit-trail.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                                            <i class="fas fa-times"></i> Clear
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if($auditTrails->count() > 0)
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Entity</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Action</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Performed By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
                                    <th class="text-secondary opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($auditTrails as $auditTrail)
                                <tr>
                                    <td>
                                        <div class="d-flex px-2 py-1">
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ ucfirst(str_replace('_', ' ', $auditTrail->entity_type)) }}</h6>
                                                <p class="text-xs text-secondary mb-0">ID: {{ $auditTrail->entity_id }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $actionColors = [
                                                'CREATED' => 'success',
                                                'UPDATED' => 'info',
                                                'DELETED' => 'danger',
                                                'TRANSFERRED' => 'warning',
                                                'PRINTED' => 'secondary'
                                            ];
                                            $actionColor = $actionColors[$auditTrail->action] ?? 'primary';
                                        @endphp
                                        <span class="badge badge-sm bg-gradient-{{ $actionColor }}">{{ $auditTrail->action }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $auditTrail->performer ? $auditTrail->performer->name : 'System' }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="text-secondary text-xs font-weight-bold">
                                            {{ $auditTrail->performed_at->format('M d, Y H:i:s') }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <a href="{{ route('audit-trail.show', $auditTrail->id) }}" 
                                           class="text-secondary font-weight-bold text-xs" 
                                           data-toggle="tooltip" 
                                           data-original-title="View audit details">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-3">
                        {{ $auditTrails->appends(request()->query())->links() }}
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-history fa-3x text-secondary mb-3"></i>
                        <p class="text-secondary mb-0">No audit trail records found.</p>
                        <p class="text-xs text-secondary">System activities will appear here as they occur.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection