<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetType;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('permission:view-assets', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-asset', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-asset', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-asset', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the assets.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $assetTypeId = $request->input('asset_type_id', '');
        
        $assets = Asset::with(['assetType', 'assignedTo'])
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('asset_tag', 'like', "%{$search}%")
                      ->orWhere('serial_no', 'like', "%{$search}%")
                      ->orWhere('model', 'like', "%{$search}%")
                      ->orWhere('manufacturer', 'like', "%{$search}%")
                      ->orWhere('location', 'like', "%{$search}%");
                });
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($assetTypeId, function ($query, $assetTypeId) {
                return $query->where('asset_type_id', $assetTypeId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $assetTypes = AssetType::orderBy('name')->get();
        $statuses = ['available', 'assigned', 'in_repair', 'disposed'];
        
        return view('assets.index', compact('assets', 'assetTypes', 'statuses', 'search', 'status', 'assetTypeId'));
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
        $validated = $request->validate([
            'asset_tag' => 'required|string|max:64|unique:assets',
            'serial_no' => 'nullable|string|max:128',
            'asset_type_id' => 'required|exists:asset_types,id',
            'model' => 'nullable|string|max:200',
            'manufacturer' => 'nullable|string|max:200',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:available,assigned,in_repair,disposed',
            'location' => 'nullable|string|max:200',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

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
        $validated = $request->validate([
            'asset_tag' => 'required|string|max:64|unique:assets,asset_tag,' . $asset->id,
            'serial_no' => 'nullable|string|max:128',
            'asset_type_id' => 'required|exists:asset_types,id',
            'model' => 'nullable|string|max:200',
            'manufacturer' => 'nullable|string|max:200',
            'purchase_date' => 'nullable|date',
            'warranty_until' => 'nullable|date',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:available,assigned,in_repair,disposed',
            'location' => 'nullable|string|max:200',
            'assigned_to_user_id' => 'nullable|exists:users,id',
        ]);

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