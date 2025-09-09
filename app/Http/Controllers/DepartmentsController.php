<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepartmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $departments = Department::with(['parent'])
                ->withCount(['projects', 'logs', 'children'])
                ->select('departments.*');

            return DataTables::of($departments)
                ->addColumn('parent_name', function ($department) {
                    return $department->parent ? $department->parent->name : '-';
                })
                ->addColumn('projects_count', function ($department) {
                    return $department->projects_count;
                })
                ->addColumn('logs_count', function ($department) {
                    return $department->logs_count;
                })
                ->addColumn('children_count', function ($department) {
                    return $department->children_count;
                })
                ->addColumn('action', function ($department) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('departments.show', $department->id) . '" class="btn btn-info btn-sm" title="View"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('departments.edit', $department->id) . '" class="btn btn-warning btn-sm" title="Edit"><i class="fas fa-edit"></i></a>';
                    $actions .= '<button type="button" class="btn btn-danger btn-sm" onclick="deleteDepartment(' . $department->id . ')" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('departments.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentDepartments = Department::whereNull('parent_id')->get();
        return view('departments.create', compact('parentDepartments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments',
            'parent_id' => 'nullable|exists:departments,id',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Department::create($request->only(['name', 'parent_id', 'description']));

        return redirect()->route('departments.index')
            ->with('success', 'Department created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Department $department)
    {
        $department->load(['parent', 'children', 'projects', 'logs']);
        return view('departments.show', compact('department'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        $parentDepartments = Department::whereNull('parent_id')
            ->where('id', '!=', $department->id)
            ->get();
        return view('departments.edit', compact('department', 'parentDepartments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Department $department)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
            'parent_id' => 'nullable|exists:departments,id|not_in:' . $department->id,
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $department->update($request->only(['name', 'parent_id', 'description']));

        return redirect()->route('departments.index')
            ->with('success', 'Department updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        if ($department->children()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete department with child departments.'
                ], 400);
            }
            return redirect()->route('departments.index')
                ->with('error', 'Cannot delete department with child departments.');
        }

        if ($department->projects()->count() > 0) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete department with associated projects.'
                ], 400);
            }
            return redirect()->route('departments.index')
                ->with('error', 'Cannot delete department with associated projects.');
        }

        $department->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Department deleted successfully.'
            ]);
        }

        return redirect()->route('departments.index')
            ->with('success', 'Department deleted successfully.');
    }
}
