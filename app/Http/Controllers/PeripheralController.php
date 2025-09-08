<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\Peripheral;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

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
        if ($request->ajax()) {
            return $this->getPeripheralsDataTable();
        }

        $assets = Asset::orderBy('asset_tag')->get();
        $types = Peripheral::select('type')->distinct()->orderBy('type')->pluck('type');
        
        return view('peripherals.index', compact('assets', 'types'));
    }

    /**
     * Get peripherals data for DataTables.
     */
    private function getPeripheralsDataTable()
    {
        $peripherals = Peripheral::with('asset')
            ->select('peripherals.*');

        return DataTables::of($peripherals)
            ->addColumn('asset_tag', function ($peripheral) {
                return $peripheral->asset ? $peripheral->asset->asset_tag : 'N/A';
            })
            ->addColumn('asset_name', function ($peripheral) {
                return $peripheral->asset ? $peripheral->asset->name : 'N/A';
            })
            ->addColumn('actions', function ($peripheral) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                
                if (auth()->user()->can('view-peripherals')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('peripherals.show', $peripheral->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
                }
                
                if (auth()->user()->can('edit-peripheral')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('peripherals.edit', $peripheral->id) . '"><i class="fas fa-edit me-2"></i> Edit</a></li>';
                }
                
                if (auth()->user()->can('delete-peripheral')) {
                    $actions .= '<li><button type="button" class="dropdown-item" onclick="deletePeripheral(' . $peripheral->id . ')"><i class="fas fa-trash me-2"></i> Delete</button></li>';
                }
                
                $actions .= '</ul>';
                $actions .= '</div>';
                
                return $actions;
            })
            ->editColumn('created_at', function ($peripheral) {
                return $peripheral->created_at->format('Y-m-d H:i');
            })
            ->rawColumns(['actions'])
            ->make(true);
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
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peripheral created successfully.',
                    'redirect' => $request->input('redirect_to_asset') && $peripheral->asset_id 
                        ? route('assets.show', $peripheral->asset_id)
                        : route('peripherals.index')
                ]);
            }
            
            if ($request->input('redirect_to_asset') && $peripheral->asset_id) {
                return redirect()->route('assets.show', $peripheral->asset_id)
                    ->with('success', 'Peripheral created successfully.');
            }
            
            return redirect()->route('peripherals.index')
                ->with('success', 'Peripheral created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create peripheral: ' . $e->getMessage()
                ], 422);
            }
            
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
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peripheral updated successfully.',
                    'redirect' => $request->input('redirect_to_asset') && $peripheral->asset_id 
                        ? route('assets.show', $peripheral->asset_id)
                        : route('peripherals.index')
                ]);
            }
            
            if ($request->input('redirect_to_asset') && $peripheral->asset_id) {
                return redirect()->route('assets.show', $peripheral->asset_id)
                    ->with('success', 'Peripheral updated successfully.');
            }
            
            return redirect()->route('peripherals.index')
                ->with('success', 'Peripheral updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update peripheral: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Failed to update peripheral: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified peripheral from storage.
     */
    public function destroy(Request $request, Peripheral $peripheral)
    {
        $assetId = $peripheral->asset_id;
        $redirectToAsset = $request->input('redirect_to_asset');

        DB::beginTransaction();
        try {
            $this->auditService->logDeleted($peripheral);
            $peripheral->delete();
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Peripheral deleted successfully.',
                    'redirect' => $redirectToAsset && $assetId 
                        ? route('assets.show', $assetId)
                        : route('peripherals.index')
                ]);
            }
            
            if ($redirectToAsset && $assetId) {
                return redirect()->route('assets.show', $assetId)
                    ->with('success', 'Peripheral deleted successfully.');
            }
            
            return redirect()->route('peripherals.index')
                ->with('success', 'Peripheral deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete peripheral: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Failed to delete peripheral: ' . $e->getMessage());
        }
    }
}