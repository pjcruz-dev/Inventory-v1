@extends('layouts.user_type.auth')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Audit Trail Details</h6>
                        <a href="{{ route('audit-trail.index') }}" class="btn btn-outline-secondary btn-sm mb-0">
                            <i class="fas fa-arrow-left"></i>&nbsp;&nbsp;Back to Audit Trail
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Action Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Entity Type:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ ucfirst(str_replace('_', ' ', $auditTrail->entity_type)) }}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Entity ID:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $auditTrail->entity_id }}</p>
                                        </div>
                                    </div>
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Action:</strong></p>
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
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="text-sm mb-2"><strong>Performed At:</strong></p>
                                            <p class="text-sm font-weight-bold">{{ $auditTrail->performed_at->format('M d, Y H:i:s') }}</p>
                                        </div>
                                    </div>
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-sm mb-2"><strong>Performed By:</strong></p>
                                            <p class="text-sm font-weight-bold">
                                                {{ $auditTrail->performer ? $auditTrail->performer->name : 'System' }}
                                                @if($auditTrail->performer)
                                                    <span class="text-xs text-secondary">({{ $auditTrail->performer->email }})</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @if($auditTrail->note)
                                    <hr class="horizontal dark">
                                    <div class="row">
                                        <div class="col-12">
                                            <p class="text-sm mb-2"><strong>Note:</strong></p>
                                            <p class="text-sm">{{ $auditTrail->note }}</p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($relatedEntity)
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Related Entity</h6>
                                </div>
                                <div class="card-body">
                                    @if($auditTrail->entity_type === 'Asset')
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="text-sm mb-2"><strong>Asset Tag:</strong></p>
                                                <p class="text-sm font-weight-bold">{{ $relatedEntity->asset_tag }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="text-sm mb-2"><strong>Name:</strong></p>
                                                <p class="text-sm font-weight-bold">{{ $relatedEntity->name }}</p>
                                            </div>
                                        </div>
                                        <hr class="horizontal dark">
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="{{ route('assets.show', $relatedEntity->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>&nbsp;&nbsp;View Asset
                                                </a>
                                            </div>
                                        </div>
                                    @elseif($auditTrail->entity_type === 'AssetTransfer')
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="text-sm mb-2"><strong>Asset:</strong></p>
                                                <p class="text-sm font-weight-bold">{{ $relatedEntity->asset->asset_tag ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="text-sm mb-2"><strong>Status:</strong></p>
                                                <p class="text-sm font-weight-bold">{{ ucfirst($relatedEntity->status) }}</p>
                                            </div>
                                        </div>
                                        <hr class="horizontal dark">
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="{{ route('asset-transfers.show', $relatedEntity->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>&nbsp;&nbsp;View Transfer
                                                </a>
                                            </div>
                                        </div>
                                    @elseif($auditTrail->entity_type === 'PrintLog')
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="text-sm mb-2"><strong>Asset:</strong></p>
                                                <p class="text-sm font-weight-bold">{{ $relatedEntity->asset->asset_tag ?? 'N/A' }}</p>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="text-sm mb-2"><strong>Print Format:</strong></p>
                                                <p class="text-sm font-weight-bold">{{ ucfirst(str_replace('_', ' ', $relatedEntity->print_format)) }}</p>
                                            </div>
                                        </div>
                                        <hr class="horizontal dark">
                                        <div class="row">
                                            <div class="col-12">
                                                <a href="{{ route('print-logs.show', $relatedEntity->id) }}" class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i>&nbsp;&nbsp;View Print Log
                                                </a>
                                            </div>
                                        </div>
                                    @else
                                        <div class="row">
                                            <div class="col-12">
                                                <p class="text-sm mb-2"><strong>Entity Details:</strong></p>
                                                <p class="text-sm">{{ $auditTrail->entity_type }} with ID {{ $auditTrail->entity_id }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @else
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Related Entity</h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center py-3">
                                        <i class="fas fa-exclamation-triangle fa-2x text-warning mb-2"></i>
                                        <p class="text-sm mb-0">Related entity not found</p>
                                        <p class="text-xs text-secondary">The {{ $auditTrail->entity_type }} with ID {{ $auditTrail->entity_id }} may have been deleted.</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    @if($auditTrail->changes)
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <h6 class="mb-0">Changes Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Field</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Old Value</th>
                                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">New Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(isset($auditTrail->changes['old']) && isset($auditTrail->changes['new']))
                                                    @foreach($auditTrail->changes['old'] as $field => $oldValue)
                                                        @if(isset($auditTrail->changes['new'][$field]) && $oldValue != $auditTrail->changes['new'][$field])
                                                        <tr>
                                                            <td class="text-sm font-weight-bold">{{ ucfirst(str_replace('_', ' ', $field)) }}</td>
                                                            <td class="text-sm">
                                                                <span class="badge badge-sm bg-gradient-danger">{{ $oldValue ?: 'NULL' }}</span>
                                                            </td>
                                                            <td class="text-sm">
                                                                <span class="badge badge-sm bg-gradient-success">{{ $auditTrail->changes['new'][$field] ?: 'NULL' }}</span>
                                                            </td>
                                                        </tr>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach($auditTrail->changes as $key => $value)
                                                    <tr>
                                                        <td class="text-sm font-weight-bold">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                                                        <td class="text-sm" colspan="2">
                                                            @if(is_array($value))
                                                                <pre class="text-xs">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                                            @else
                                                                {{ $value }}
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection