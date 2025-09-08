<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function __construct()
    {

    }

    /**
     * Display a listing of audit trail records.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $entityType = $request->input('entity_type', '');
        $action = $request->input('action', '');
        $performedBy = $request->input('performed_by', '');
        $dateFrom = $request->input('date_from', '');
        $dateTo = $request->input('date_to', '');
        
        $auditTrails = AuditTrail::with('performer')
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('entity_type', 'like', "%{$search}%")
                      ->orWhere('action', 'like', "%{$search}%")
                      ->orWhere('note', 'like', "%{$search}%");
                });
            })
            ->when($entityType, function ($query, $entityType) {
                return $query->where('entity_type', $entityType);
            })
            ->when($action, function ($query, $action) {
                return $query->where('action', $action);
            })
            ->when($performedBy, function ($query, $performedBy) {
                return $query->where('performed_by', $performedBy);
            })
            ->when($dateFrom, function ($query, $dateFrom) {
                return $query->whereDate('performed_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query, $dateTo) {
                return $query->whereDate('performed_at', '<=', $dateTo);
            })
            ->orderBy('performed_at', 'desc')
            ->paginate(15);

        // Get unique entity types and actions for filtering
        $entityTypes = AuditTrail::distinct()->pluck('entity_type');
        $actions = AuditTrail::distinct()->pluck('action');
        $users = User::orderBy('name')->get();
        
        return view('audit-trail.index', compact(
            'auditTrails', 
            'entityTypes', 
            'actions', 
            'users', 
            'search', 
            'entityType', 
            'action', 
            'performedBy',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Display the specified audit trail record.
     */
    public function show(AuditTrail $auditTrail)
    {
        $auditTrail->load('performer');
        
        // Get the related entity if possible
        $relatedEntity = null;
        $entityClass = $this->getEntityClass($auditTrail->entity_type);
        
        if ($entityClass && class_exists($entityClass)) {
            $relatedEntity = $entityClass::find($auditTrail->entity_id);
        }
        
        return view('audit-trail.show', compact('auditTrail', 'relatedEntity'));
    }
    
    /**
     * Get the fully qualified class name for an entity type.
     */
    private function getEntityClass($entityType)
    {
        $map = [
            'asset' => '\\App\\Models\\Asset',
            'asset_type' => '\\App\\Models\\AssetType',
            'peripheral' => '\\App\\Models\\Peripheral',
            'asset_transfer' => '\\App\\Models\\AssetTransfer',
            'print_log' => '\\App\\Models\\PrintLog',
        ];
        
        return $map[strtolower($entityType)] ?? null;
    }
}