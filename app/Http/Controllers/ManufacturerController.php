<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class ManufacturerController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $manufacturers = Manufacturer::select(['id', 'name', 'description', 'website', 'contact_email', 'is_active', 'created_at']);

            return DataTables::of($manufacturers)
                ->addColumn('actions', function ($manufacturer) {
                    $actions = '<div class="dropdown">';
                    $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                    $actions .= '<i class="fas fa-ellipsis-v"></i>';
                    $actions .= '</button>';
                    $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                    $actions .= '<li><a class="dropdown-item" href="' . route('manufacturers.show', $manufacturer->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
                    $actions .= '<li><a class="dropdown-item" href="' . route('manufacturers.edit', $manufacturer->id) . '"><i class="fas fa-edit me-2"></i> Edit</a></li>';
                    $actions .= '<li><button type="button" class="dropdown-item" onclick="deleteManufacturer(' . $manufacturer->id . ')"><i class="fas fa-trash me-2"></i> Delete</button></li>';
                    $actions .= '</ul>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->addColumn('status', function ($manufacturer) {
                    return $manufacturer->is_active ? 
                        '<span class="badge badge-success">Active</span>' : 
                        '<span class="badge badge-danger">Inactive</span>';
                })
                ->editColumn('created_at', function ($manufacturer) {
                    return $manufacturer->created_at->format('Y-m-d H:i:s');
                })
                ->rawColumns(['actions', 'status'])
                ->make(true);
        }

        return view('manufacturers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manufacturers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:manufacturers,name',
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active');

        try {
            $manufacturer = Manufacturer::create($validatedData);

            // Log the creation
            $this->auditService->logCreated($manufacturer);

            return redirect()->route('manufacturers.index')
                ->with('success', 'Manufacturer created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error creating manufacturer: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer)
    {
        $manufacturer->load('assets');
        return view('manufacturers.show', compact('manufacturer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manufacturer $manufacturer)
    {
        return view('manufacturers.edit', compact('manufacturer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:manufacturers,name,' . $manufacturer->id,
            'description' => 'nullable|string',
            'website' => 'nullable|url|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        $validatedData['is_active'] = $request->has('is_active');
        $oldData = $manufacturer->toArray();

        try {
            $manufacturer->update($validatedData);

            // Log the update
            $this->auditService->logUpdated($manufacturer, $oldData);

            return redirect()->route('manufacturers.index')
                ->with('success', 'Manufacturer updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Error updating manufacturer: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer)
    {
        try {
            // Check if manufacturer has assets
            if ($manufacturer->assets()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete manufacturer. It has associated assets.'
                ], 400);
            }

            $oldData = $manufacturer->toArray();
            $manufacturer->delete();

            // Log the deletion
            $this->auditService->logDeleted($manufacturer);

            return response()->json([
                'success' => true,
                'message' => 'Manufacturer deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting manufacturer: ' . $e->getMessage()
            ], 500);
        }
    }
}
