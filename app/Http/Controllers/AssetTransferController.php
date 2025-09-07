<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetTransfer;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssetTransferController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('permission:view-asset-transfers', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-asset-transfer', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-asset-transfer', ['only' => ['edit', 'update']]);
    }

    /**
     * Display a listing of the asset transfers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status', '');
        $assetId = $request->input('asset_id', '');
        
        $transfers = AssetTransfer::with(['asset', 'fromUser', 'toUser', 'processedBy'])
            ->when($search, function ($query, $search) {
                return $query->whereHas('asset', function($q) use ($search) {
                    $q->where('asset_tag', 'like', "%{$search}%");
                })->orWhere('from_location', 'like', "%{$search}%")
                  ->orWhere('to_location', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                return $query->where('status', $status);
            })
            ->when($assetId, function ($query, $assetId) {
                return $query->where('asset_id', $assetId);
            })
            ->orderBy('transfer_date', 'desc')
            ->paginate(10);

        $assets = Asset::orderBy('asset_tag')->get();
        $statuses = ['pending', 'completed', 'cancelled'];
        
        return view('asset-transfers.index', compact('transfers', 'assets', 'statuses', 'search', 'status', 'assetId'));
    }

    /**
     * Show the form for creating a new asset transfer.
     */
    public function create(Request $request)
    {
        $assetId = $request->input('asset_id');
        $assets = Asset::orderBy('asset_tag')->get();
        $users = User::orderBy('name')->get();
        $statuses = ['pending', 'completed'];
        
        // If asset_id is provided, get the current asset details
        $selectedAsset = null;
        if ($assetId) {
            $selectedAsset = Asset::with('assignedTo')->find($assetId);
        }
        
        return view('asset-transfers.create', compact('assets', 'users', 'statuses', 'assetId', 'selectedAsset'));
    }

    /**
     * Store a newly created asset transfer in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'required|exists:assets,id',
            'from_location' => 'nullable|string|max:200',
            'to_location' => 'nullable|string|max:200',
            'from_user_id' => 'nullable|exists:users,id',
            'to_user_id' => 'nullable|exists:users,id',
            'transfer_reason' => 'nullable|string',
            'status' => 'required|in:pending,completed',
        ]);

        // Get the asset
        $asset = Asset::findOrFail($validated['asset_id']);

        // Set default locations if not provided
        if (empty($validated['from_location'])) {
            $validated['from_location'] = $asset->location;
        }

        // Set processed_by to current user
        $validated['processed_by'] = Auth::id();

        DB::beginTransaction();
        try {
            $transfer = AssetTransfer::create($validated);
            
            // If the transfer is completed, update the asset
            if ($validated['status'] === 'completed') {
                $this->completeTransfer($transfer);
            }
            
            $this->auditService->logCreated($transfer);
            $this->auditService->logTransferred($asset, [
                'transfer_id' => $transfer->id,
                'from_user_id' => $validated['from_user_id'],
                'to_user_id' => $validated['to_user_id'],
                'from_location' => $validated['from_location'],
                'to_location' => $validated['to_location'],
            ]);
            
            DB::commit();
            return redirect()->route('asset-transfers.index')
                ->with('success', 'Asset transfer created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create asset transfer: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified asset transfer.
     */
    public function show(AssetTransfer $assetTransfer)
    {
        $assetTransfer->load(['asset', 'fromUser', 'toUser', 'processedBy']);
        return view('asset-transfers.show', compact('assetTransfer'));
    }

    /**
     * Show the form for editing the specified asset transfer.
     */
    public function edit(AssetTransfer $assetTransfer)
    {
        // Only allow editing of pending transfers
        if ($assetTransfer->status !== 'pending') {
            return redirect()->route('asset-transfers.show', $assetTransfer)
                ->with('error', 'Only pending transfers can be edited.');
        }
        
        $assets = Asset::orderBy('asset_tag')->get();
        $users = User::orderBy('name')->get();
        $statuses = ['pending', 'completed', 'cancelled'];
        
        return view('asset-transfers.edit', compact('assetTransfer', 'assets', 'users', 'statuses'));
    }

    /**
     * Update the specified asset transfer in storage.
     */
    public function update(Request $request, AssetTransfer $assetTransfer)
    {
        // Only allow updating of pending transfers
        if ($assetTransfer->status !== 'pending') {
            return redirect()->route('asset-transfers.show', $assetTransfer)
                ->with('error', 'Only pending transfers can be updated.');
        }
        
        $validated = $request->validate([
            'from_location' => 'nullable|string|max:200',
            'to_location' => 'nullable|string|max:200',
            'from_user_id' => 'nullable|exists:users,id',
            'to_user_id' => 'nullable|exists:users,id',
            'transfer_reason' => 'nullable|string',
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        DB::beginTransaction();
        try {
            $oldAttributes = $assetTransfer->getAttributes();
            $oldStatus = $assetTransfer->status;
            
            $assetTransfer->update($validated);
            $this->auditService->logUpdated($assetTransfer, $oldAttributes);
            
            // If the status changed to completed, update the asset
            if ($oldStatus !== 'completed' && $validated['status'] === 'completed') {
                $this->completeTransfer($assetTransfer);
            }
            
            DB::commit();
            return redirect()->route('asset-transfers.index')
                ->with('success', 'Asset transfer updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update asset transfer: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Complete a transfer by updating the asset's location and assigned user.
     */
    private function completeTransfer(AssetTransfer $transfer)
    {
        $asset = $transfer->asset;
        
        // Update asset location if to_location is provided
        if ($transfer->to_location) {
            $asset->location = $transfer->to_location;
        }
        
        // Update assigned user if to_user_id is provided
        if ($transfer->to_user_id) {
            $asset->assigned_to_user_id = $transfer->to_user_id;
            $asset->status = 'assigned';
        } elseif ($asset->assigned_to_user_id == $transfer->from_user_id) {
            // If the asset was assigned to from_user and now it's not assigned to anyone
            $asset->assigned_to_user_id = null;
            $asset->status = 'available';
        }
        
        $asset->save();
        
        return $asset;
    }
}