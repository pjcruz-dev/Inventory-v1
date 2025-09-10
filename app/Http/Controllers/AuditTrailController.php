<?php

namespace App\Http\Controllers;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

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
        if ($request->ajax()) {
            $auditTrails = AuditTrail::with('performer')->select('audit_trails.*');
            
            return DataTables::of($auditTrails)
                ->addColumn('entity_info', function ($auditTrail) {
                    return ucfirst(str_replace('_', ' ', $auditTrail->entity_type)) . ' (ID: ' . $auditTrail->entity_id . ')';
                })
                ->addColumn('action_badge', function ($auditTrail) {
                    $actionColors = [
                        'created' => 'success',
                        'updated' => 'warning',
                        'deleted' => 'danger',
                        'transferred' => 'info',
                        'printed' => 'primary'
                    ];
                    $actionColor = $actionColors[$auditTrail->action] ?? 'primary';
                    return '<span class="badge badge-sm bg-gradient-' . $actionColor . '">' . $auditTrail->action . '</span>';
                })
                ->addColumn('performer_name', function ($auditTrail) {
                    return $auditTrail->performer ? $auditTrail->performer->name : 'System';
                })
                ->addColumn('action', function ($auditTrail) {
                    return '<a href="' . route('audit-trail.show', $auditTrail->id) . '" class="btn btn-sm btn-outline-info" title="View Details"><i class="fas fa-eye"></i></a>';
                })
                ->editColumn('performed_at', function ($auditTrail) {
                    return $auditTrail->performed_at->format('M d, Y H:i:s');
                })
                ->rawColumns(['action_badge', 'action'])
                ->make(true);
        }
        
        // Get unique entity types and actions for filtering
        $entityTypes = AuditTrail::distinct()->pluck('entity_type');
        $actions = AuditTrail::distinct()->pluck('action');
        $users = User::orderBy('name')->get();
        
        return view('audit-trail.index', compact(
            'entityTypes', 
            'actions', 
            'users'
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