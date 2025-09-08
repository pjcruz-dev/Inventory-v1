<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AssetController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;

    }

    /**
     * Display a listing of the assets.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAssetsDataTable();
        }

        $assetTypes = AssetType::orderBy('name')->get();
        $statuses = ['available', 'assigned', 'in_repair', 'disposed'];
        
        return view('assets.index', compact('assetTypes', 'statuses'));
    }

    /**
     * Get assets data for DataTables.
     */
    public function getAssetsDataTable()
    {
        $assets = Asset::with(['assetType', 'assignedTo'])
            ->select('assets.*');

        return DataTables::of($assets)
            ->addColumn('asset_type', function ($asset) {
                return $asset->assetType ? $asset->assetType->name : 'N/A';
            })
            ->addColumn('assigned_to', function ($asset) {
                return $asset->assignedTo ? $asset->assignedTo->name : 'Unassigned';
            })
            ->addColumn('status_badge', function ($asset) {
                $badgeClass = [
                    'available' => 'bg-success',
                    'assigned' => 'bg-primary',
                    'in_repair' => 'bg-warning',
                    'disposed' => 'bg-danger'
                ];
                $class = $badgeClass[$asset->status] ?? 'bg-secondary';
                return '<span class="badge ' . $class . '">' . ucfirst($asset->status) . '</span>';
            })
            ->addColumn('actions', function ($asset) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                $actions .= '<li><a class="dropdown-item" href="' . route('assets.show', $asset->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
                $actions .= '<li><a class="dropdown-item" href="' . route('assets.edit', $asset->id) . '"><i class="fas fa-edit me-2"></i> Edit</a></li>';
                $actions .= '<li><button type="button" class="dropdown-item" onclick="deleteAsset(' . $asset->id . ')"><i class="fas fa-trash me-2"></i> Delete</button></li>';
                $actions .= '</ul>';
                $actions .= '</div>';
                
                return $actions;
            })
            ->rawColumns(['status_badge', 'actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new asset.
     */
    public function create()
    {
        $assetTypes = AssetType::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $statuses = ['available', 'assigned', 'in_repair', 'disposed'];
        
        return view('assets.create', compact('assetTypes', 'users', 'statuses'));
    }

    /**
     * Store a newly created asset in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(Asset::validationRules());

        // If status is not 'assigned', remove assigned_to_user_id
        if ($validated['status'] !== 'assigned') {
            $validated['assigned_to_user_id'] = null;
        }

        // Add created_by field
        $validated['created_by'] = Auth::id();

        DB::beginTransaction();
        try {
            $asset = Asset::create($validated);
            $this->auditService->logCreated($asset);
            
            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create asset: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified asset.
     */
    public function show(Asset $asset)
    {
        $asset->load(['assetType', 'assignedTo', 'createdBy', 'peripherals', 'transfers' => function($query) {
            $query->orderBy('transfer_date', 'desc');
        }]);
        
        $printLogs = $asset->printLogs()->orderBy('printed_at', 'desc')->get();
        
        return view('assets.show', compact('asset', 'printLogs'));
    }

    /**
     * Show the form for editing the specified asset.
     */
    public function edit(Asset $asset)
    {
        $assetTypes = AssetType::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $statuses = ['available', 'assigned', 'in_repair', 'disposed'];
        
        return view('assets.edit', compact('asset', 'assetTypes', 'users', 'statuses'));
    }

    /**
     * Update the specified asset in storage.
     */
    public function update(Request $request, Asset $asset)
    {
        $validated = $request->validate(Asset::validationRules($asset->id));

        // If status is not 'assigned', remove assigned_to_user_id
        if ($validated['status'] !== 'assigned') {
            $validated['assigned_to_user_id'] = null;
        }

        DB::beginTransaction();
        try {
            $oldAttributes = $asset->getAttributes();
            $oldAssignedTo = $asset->assigned_to_user_id;
            $newAssignedTo = $validated['assigned_to_user_id'];
            
            $asset->update($validated);
            $this->auditService->logUpdated($asset, $oldAttributes);
            
            // If the assigned user has changed, create a transfer record
            if ($oldAssignedTo !== $newAssignedTo && ($oldAssignedTo || $newAssignedTo)) {
                $this->createTransferFromAssignment($asset, $oldAssignedTo, $newAssignedTo);
            }
            
            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update asset: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroy(Asset $asset)
    {
        // Check if the asset has any peripherals
        if ($asset->peripherals()->count() > 0) {
            return back()->with('error', 'Cannot delete asset because it has associated peripherals.');
        }

        DB::beginTransaction();
        try {
            $this->auditService->logDeleted($asset);
            $asset->delete();
            
            DB::commit();
            return redirect()->route('assets.index')
                ->with('success', 'Asset deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete asset: ' . $e->getMessage());
        }
    }

    /**
     * Create a transfer record when an asset's assignment changes.
     */
    private function createTransferFromAssignment(Asset $asset, $fromUserId, $toUserId)
    {
        $transfer = $asset->transfers()->create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'from_location' => $asset->location,
            'to_location' => $asset->location,
            'transfer_reason' => 'Asset assignment changed',
            'processed_by' => Auth::id(),
            'status' => 'completed',
        ]);

        $this->auditService->logTransferred($asset, [
            'transfer_id' => $transfer->id,
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
        ]);

        return $transfer;
    }
}