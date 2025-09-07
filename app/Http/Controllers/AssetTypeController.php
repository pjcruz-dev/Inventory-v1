<?php

namespace App\Http\Controllers;

use App\Models\AssetType;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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
        $search = $request->input('search', '');
        
        $assetTypes = AssetType::when($search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->orderBy('name')
            ->paginate(10);

        return view('asset-types.index', compact('assetTypes', 'search'));
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
            return redirect()->route('asset-types.index')
                ->with('success', 'Asset type created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
            return redirect()->route('asset-types.index')
                ->with('success', 'Asset type updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
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
            return back()->with('error', 'Cannot delete asset type because it has associated assets.');
        }

        DB::beginTransaction();
        try {
            $this->auditService->logDeleted($assetType);
            $assetType->delete();
            
            DB::commit();
            return redirect()->route('asset-types.index')
                ->with('success', 'Asset type deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete asset type: ' . $e->getMessage());
        }
    }
}