<?php

namespace App\Http\Controllers;

use App\Models\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class AssetCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = AssetCategory::withCount('assets')
                ->select('asset_categories.*');

            return DataTables::of($categories)
                ->addColumn('assets_count', function ($category) {
                    return $category->assets_count;
                })
                ->addColumn('action', function ($category) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('asset-categories.show', $category->id) . '" class="btn btn-info btn-sm" title="View"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('asset-categories.edit', $category->id) . '" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-danger btn-sm" onclick="deleteAssetCategory(' . $category->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('asset-categories.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('asset-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:asset_categories',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        AssetCategory::create($request->only(['name', 'description']));

        return redirect()->route('asset-categories.index')
            ->with('success', 'Asset category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AssetCategory $assetCategory)
    {
        $assetCategory->load(['assets', 'logs']);
        return view('asset-categories.show', compact('assetCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AssetCategory $assetCategory)
    {
        return view('asset-categories.edit', compact('assetCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AssetCategory $assetCategory)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $assetCategory->id,
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $assetCategory->update($request->only(['name', 'description']));

        return redirect()->route('asset-categories.index')
            ->with('success', 'Asset category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AssetCategory $assetCategory)
    {
        if ($assetCategory->assets()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete category with associated assets.'
                ], 400);
            }
            return redirect()->route('asset-categories.index')
                ->with('error', 'Cannot delete category with associated assets.');
        }

        $assetCategory->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Asset category deleted successfully.'
            ]);
        }

        return redirect()->route('asset-categories.index')
            ->with('success', 'Asset category deleted successfully.');
    }
}
