<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class ProjectsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $projects = Project::with('department')
                ->withCount('logs')
                ->select('projects.*');

            return DataTables::of($projects)
                ->addColumn('department_name', function ($project) {
                    return $project->department ? $project->department->name : 'No Department';
                })
                ->addColumn('action', function ($project) {
                    $actions = '<div class="d-flex">';
                    $actions .= '<a href="' . route('projects.show', $project) . '" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="View Project"><i class="fas fa-eye text-dark me-2"></i></a>';
                    $actions .= '<a href="' . route('projects.edit', $project) . '" class="btn btn-link text-dark px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit Project"><i class="fas fa-pencil-alt text-dark me-2"></i></a>';
                    $actions .= '<button onclick="deleteProject(' . $project->id . ')" class="btn btn-link text-danger px-2 mb-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete Project"><i class="fas fa-trash text-danger"></i></button>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->editColumn('created_at', function ($project) {
                    return $project->created_at->format('M d, Y');
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('projects.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all();
        return view('projects.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:projects',
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Project::create($request->only(['name', 'department_id', 'description']));

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        $project->load(['department', 'logs']);
        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        $departments = Department::all();
        return view('projects.edit', compact('project', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:projects,name,' . $project->id,
            'department_id' => 'required|exists:departments,id',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $project->update($request->only(['name', 'department_id', 'description']));

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (request()->ajax()) {
            if ($project->logs()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete project with associated logs.'
                ]);
            }

            $project->delete();

            return response()->json([
                'success' => true,
                'message' => 'Project deleted successfully.'
            ]);
        }

        if ($project->logs()->count() > 0) {
            return redirect()->route('projects.index')
                ->with('error', 'Cannot delete project with associated logs.');
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
