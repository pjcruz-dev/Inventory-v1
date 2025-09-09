<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class VendorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $vendors = Vendor::withCount('assets')->select('vendors.*');
            
            return DataTables::of($vendors)
                ->addColumn('assets_count', function ($vendor) {
                    return $vendor->assets_count;
                })
                ->addColumn('contact_info', function ($vendor) {
                    $info = [];
                    if ($vendor->email) $info[] = $vendor->email;
                    if ($vendor->phone) $info[] = $vendor->phone;
                    return implode(' | ', $info);
                })
                ->addColumn('action', function ($vendor) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('vendors.show', $vendor->id) . '" class="btn btn-sm btn-outline-info" title="View"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('vendors.edit', $vendor->id) . '" class="btn btn-sm btn-outline-primary" title="Edit"><i class="fas fa-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteVendor(' . $vendor->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->editColumn('created_at', function ($vendor) {
                    return $vendor->created_at->format('M d, Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('vendors.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vendors',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Vendor::create($request->only([
            'name', 'contact_person', 'email', 'phone', 
            'address', 'website', 'description'
        ]));

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        $vendor->load(['assets', 'logs']);
        return view('vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:vendors,name,' . $vendor->id,
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vendor->update($request->only([
            'name', 'contact_person', 'email', 'phone', 
            'address', 'website', 'description'
        ]));

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        if (request()->ajax()) {
            if ($vendor->assets()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete vendor with associated assets.'
                ]);
            }

            $vendor->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully.'
            ]);
        }
        
        if ($vendor->assets()->count() > 0) {
            return redirect()->route('vendors.index')
                ->with('error', 'Cannot delete vendor with associated assets.');
        }

        $vendor->delete();

        return redirect()->route('vendors.index')
            ->with('success', 'Vendor deleted successfully.');
    }
}
