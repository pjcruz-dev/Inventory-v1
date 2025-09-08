<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    /**
     * Display a listing of the locations.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getLocationsDataTable();
        }

        return view('locations.index');
    }

    /**
     * Get locations data for DataTables.
     */
    public function getLocationsDataTable()
    {
        $locations = Location::withCount('assets')->select('locations.*');

        return DataTables::of($locations)
            ->addColumn('assets_count', function ($location) {
                return $location->assets_count;
            })
            ->addColumn('address_display', function ($location) {
                return $location->address ?: 'N/A';
            })
            ->addColumn('created_at_formatted', function ($location) {
                return $location->created_at->format('d M Y');
            })
            ->addColumn('actions', function ($location) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                $actions .= '<li><a class="dropdown-item" href="' . route('locations.show', $location->id) . '"><i class="fas fa-eye me-2"></i> View</a></li>';
                $actions .= '<li><a class="dropdown-item" href="' . route('locations.edit', $location->id) . '"><i class="fas fa-edit me-2"></i> Edit</a></li>';
                $actions .= '<li><button type="button" class="dropdown-item" onclick="deleteLocation(' . $location->id . ')"><i class="fas fa-trash me-2"></i> Delete</button></li>';
                
                $actions .= '</ul>';
                $actions .= '</div>';
                
                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new location.
     */
    public function create()
    {
        return view('locations.create');
    }

    /**
     * Store a newly created location in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations',
            'address' => 'nullable|string|max:500',
        ]);

        Location::create($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Location created successfully.');
    }

    /**
     * Display the specified location.
     */
    public function show(Location $location)
    {
        $location->load('assets');
        return view('locations.show', compact('location'));
    }

    /**
     * Show the form for editing the specified location.
     */
    public function edit(Location $location)
    {
        return view('locations.edit', compact('location'));
    }

    /**
     * Update the specified location in storage.
     */
    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:locations,name,' . $location->id,
            'address' => 'nullable|string|max:500',
        ]);

        $location->update($validated);

        return redirect()->route('locations.index')
            ->with('success', 'Location updated successfully.');
    }

    /**
     * Remove the specified location from storage.
     */
    public function destroy(Location $location)
    {
        // Check if location has assets
        if ($location->assets()->count() > 0) {
            return redirect()->route('locations.index')
                ->with('error', 'Cannot delete location that has assets assigned to it.');
        }

        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Location deleted successfully.');
    }
}