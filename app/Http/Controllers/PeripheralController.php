<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Peripheral;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeripheralController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('permission:view-peripherals', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-peripheral', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-peripheral', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-peripheral', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the peripherals.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $assetId = $request->input('asset_id', '');
        $type = $request->input('type', '');
        
        $peripherals = Peripheral::with('asset')
            ->when($search, function ($query, $search) {
                return $query->where(function($q) use ($search) {
                    $q->where('type', 'like', "%{$search}%")
                      ->orWhere('details', 'like', "%{$search}%")
                      ->orWhere('serial_no', 'like', "%{$search}%");
                });
            })
            ->when($assetId, function ($query, $assetId) {
                return $query->where('asset_id', $assetId);
            })
            ->when($type, function ($query, $type) {
                return $query->where('type', $type);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $assets = Asset::orderBy('asset_tag')->get();
        $types = Peripheral::select('type')->distinct()->orderBy('type')->pluck('type');
        
        return view('peripherals.index', compact('peripherals', 'assets', 'types', 'search', 'assetId', 'type'));
    }

    /**
     * Show the form for creating a new peripheral.
     */
    public function create(Request $request)
    {
        $assets = Asset::orderBy('asset_tag')->get();
        $assetId = $request->input('asset_id');
        $commonTypes = ['Mouse', 'Keyboard', 'RAM', 'Charger', 'Docking Station', 'Monitor', 'Headset'];
        
        return view('peripherals.create', compact('assets', 'assetId', 'commonTypes'));
    }

    /**
     * Store a newly created peripheral in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'asset_id' => 'nullable|exists:assets,id',
            'type' => 'required|string|max:100',
            'details' => 'nullable|string',
            'serial_no' => 'nullable|string|max:128',
        ]);

        DB::beginTransaction();
        try {
            $peripheral = Peripheral::create($validated);
            $this->auditService->logCreated($peripheral);
            
            DB::commit();
            
            if ($request->input('redirect_to_asset') && $peripheral->asset_id) {
                return redirect()->route('assets.show', $peripheral->asset_id)
                    ->with('success', 'Peripheral created successfully.');
            }
            
            return redirect()->route('peripherals.index')
                ->with('success', 'Peripheral created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create peripheral: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified peripheral.
     */
    public function show(Peripheral $peripheral)
    {
        $peripheral->load('asset');
        return view('peripherals.show', compact('peripheral'));
    }

    /**
     * Show the form for editing the specified peripheral.
     */
    public function edit(Peripheral $peripheral)
    {
        $assets = Asset::orderBy('asset_tag')->get();
        $commonTypes = ['Mouse', 'Keyboard', 'RAM', 'Charger', 'Docking Station', 'Monitor', 'Headset'];
        
        return view('peripherals.edit', compact('peripheral', 'assets', 'commonTypes'));
    }

    /**
     * Update the specified peripheral in storage.
     */
    public function update(Request $request, Peripheral $peripheral)
    {
        $validated = $request->validate([
            'asset_id' => 'nullable|exists:assets,id',
            'type' => 'required|string|max:100',
            'details' => 'nullable|string',
            'serial_no' => 'nullable|string|max:128',
        ]);

        DB::beginTransaction();
        try {
            $oldAttributes = $peripheral->getAttributes();
            
            $peripheral->update($validated);
            $this->auditService->logUpdated($peripheral, $oldAttributes);
            
            DB::commit();
            
            if ($request->input('redirect_to_asset') && $peripheral->asset_id) {
                return redirect()->route('assets.show', $peripheral->asset_id)
                    ->with('success', 'Peripheral updated successfully.');
            }
            
            return redirect()->route('peripherals.index')
                ->with('success', 'Peripheral updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update peripheral: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified peripheral from storage.
     */
    public function destroy(Peripheral $peripheral)
    {
        $assetId = $peripheral->asset_id;
        $redirectToAsset = request()->input('redirect_to_asset');

        DB::beginTransaction();
        try {
            $this->auditService->logDeleted($peripheral);
            $peripheral->delete();
            
            DB::commit();
            
            if ($redirectToAsset && $assetId) {
                return redirect()->route('assets.show', $assetId)
                    ->with('success', 'Peripheral deleted successfully.');
            }
            
            return redirect()->route('peripherals.index')
                ->with('success', 'Peripheral deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete peripheral: ' . $e->getMessage());
        }
    }
}