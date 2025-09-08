<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class AssetTypeController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
        $this->middleware('permission:view-asset-type', ['only' => ['index', 'show']]);
        $this->middleware('permission:create-asset-type', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-asset-type', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete-asset-type', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the asset types.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getAssetTypesDataTable();
        }

        return view('asset-types.index');
    }

    /**
     * Get asset types data for DataTables.
     */
    public function getAssetTypesDataTable()
    {
        $assetTypes = AssetType::select('asset_types.*');

        return DataTables::of($assetTypes)
            ->addColumn('assets_count', function ($assetType) {
                return $assetType->assets()->count();
            })
            ->addColumn('actions', function ($assetType) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                $actions .= '<li><a class="dropdown-item" href="' . route('asset-types.show', $assetType->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
                
                if (auth()->user()->can('edit-asset-type')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('asset-types.edit', $assetType->id) . '"><i class="fas fa-edit me-2"></i> Edit</a></li>';
                }
                
                if (auth()->user()->can('delete-asset-type')) {
                    $actions .= '<li><button type="button" class="dropdown-item" onclick="deleteAssetType(' . $assetType->id . ')"><i class="fas fa-trash me-2"></i> Delete</button></li>';
                }
                
                $actions .= '</ul>';
                $actions .= '</div>';
                
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new asset type.
     */
    public function create()
    {
        return view('asset-types.create');
    }

    /**
     * Store a newly created asset type in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:asset_types',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $assetType = AssetType::create($validated);
            $this->auditService->logCreated($assetType);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asset type created successfully.',
                    'redirect' => route('asset-types.index')
                ]);
            }
            
            return redirect()->route('asset-types.index')
                ->with('success', 'Asset type created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create asset type: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Failed to create asset type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified asset type.
     */
    public function show(AssetType $assetType)
    {
        $assetType->load('assets');
        return view('asset-types.show', compact('assetType'));
    }

    /**
     * Show the form for editing the specified asset type.
     */
    public function edit(AssetType $assetType)
    {
        return view('asset-types.edit', compact('assetType'));
    }

    /**
     * Update the specified asset type in storage.
     */
    public function update(Request $request, AssetType $assetType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('asset_types')->ignore($assetType->id),
            ],
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $oldAttributes = $assetType->getAttributes();
            
            $assetType->update($validated);
            $this->auditService->logUpdated($assetType, $oldAttributes);
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asset type updated successfully.',
                    'redirect' => route('asset-types.index')
                ]);
            }
            
            return redirect()->route('asset-types.index')
                ->with('success', 'Asset type updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update asset type: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Failed to update asset type: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified asset type from storage.
     */
    public function destroy(AssetType $assetType)
    {
        // Check if the asset type has any assets
        if ($assetType->assets()->count() > 0) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete asset type because it has associated assets.'
                ], 422);
            }
            
            return back()->with('error', 'Cannot delete asset type because it has associated assets.');
        }

        DB::beginTransaction();
        try {
            $this->auditService->logDeleted($assetType);
            $assetType->delete();
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Asset type deleted successfully.',
                    'redirect' => route('asset-types.index')
                ]);
            }
            
            return redirect()->route('asset-types.index')
                ->with('success', 'Asset type deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete asset type: ' . $e->getMessage()
                ], 422);
            }
            
            return back()->with('error', 'Failed to delete asset type: ' . $e->getMessage());
        }
    }
}