<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getPermissionsDataTable();
        }
        
        return view('permissions.index');
    }

    /**
     * Get permissions data for DataTables
     *
     * @return mixed
     */
    private function getPermissionsDataTable()
    {
        $permissions = Permission::select('permissions.*');

        return DataTables::of($permissions)
            ->addColumn('roles_count', function ($permission) {
                return $permission->roles->count();
            })
            ->addColumn('actions', function ($permission) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                
                if (auth()->user()->can('view-permissions')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('permissions.show', $permission->id) . '"><i class="fas fa-eye me-2"></i>View</a></li>';
                }
                
                if (auth()->user()->can('edit-permission')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('permissions.edit', $permission->id) . '"><i class="fas fa-edit me-2"></i>Edit</a></li>';
                }
                
                if (auth()->user()->can('delete-permission')) {
                    $actions .= '<li><hr class="dropdown-divider"></li>';
                    $actions .= '<li><a class="dropdown-item text-danger" href="#" onclick="deletePermission(' . $permission->id . ')"><i class="fas fa-trash me-2"></i>Delete</a></li>';
                }
                
                $actions .= '</ul></div>';
                return $actions;
            })
            ->editColumn('created_at', function ($permission) {
                return $permission->created_at->format('M d, Y H:i');
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new permission.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->input('name')]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission created successfully',
                'redirect' => route('permissions.index')
            ]);
        }

        return redirect()->route('permissions.index')
            ->with('success', 'Permission created successfully');
    }

    /**
     * Display the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return redirect()->route('permissions.index')
                ->with('error', 'Permission not found');
        }
        
        // Get roles that have this permission
        $roles = $permission->roles;
    
        return view('permissions.show', compact('permission', 'roles'));
    }

    /**
     * Show the form for editing the specified permission.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return redirect()->route('permissions.index')
                ->with('error', 'Permission not found');
        }
    
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $permission = Permission::find($id);
        if (!$permission) {
            return redirect()->route('permissions.index')
                ->with('error', 'Permission not found');
        }
        
        $permission->name = $request->input('name');
        $permission->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission updated successfully',
                'redirect' => route('permissions.index')
            ]);
        }

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Permission not found'
                ], 404);
            }
            return redirect()->route('permissions.index')
                ->with('error', 'Permission not found');
        }
        
        // Check if this is a critical permission
        $criticalPermissions = ['create-user', 'edit-user', 'delete-user', 'create-role', 'edit-role', 'delete-role'];
        if (in_array($permission->name, $criticalPermissions)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete critical system permission'
                ], 422);
            }
            return redirect()->route('permissions.index')
                ->with('error', 'Cannot delete critical system permission');
        }
        
        $permission->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Permission deleted successfully',
                'redirect' => route('permissions.index')
            ]);
        }
        
        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}