<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getRolesDataTable();
        }
        
        return view('roles.index');
    }

    /**
     * Get roles data for DataTables
     *
     * @return mixed
     */
    private function getRolesDataTable()
    {
        $roles = Role::with('permissions')->select('roles.*');

        return DataTables::of($roles)
            ->addColumn('permissions_count', function ($role) {
                return $role->permissions->count();
            })
            ->addColumn('permissions_list', function ($role) {
                return $role->permissions->take(3)->pluck('name')->implode(', ') . 
                       ($role->permissions->count() > 3 ? ' (+' . ($role->permissions->count() - 3) . ' more)' : '');
            })
            ->addColumn('actions', function ($role) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                
                if (auth()->user()->can('view-roles')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('roles.show', $role->id) . '"><i class="fas fa-eye me-2"></i>View</a></li>';
                }
                
                if (auth()->user()->can('edit-role')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('roles.edit', $role->id) . '"><i class="fas fa-edit me-2"></i>Edit</a></li>';
                }
                
                if (auth()->user()->can('delete-role')) {
                    $actions .= '<li><hr class="dropdown-divider"></li>';
                    $actions .= '<li><a class="dropdown-item text-danger" href="#" onclick="deleteRole(' . $role->id . ')"><i class="fas fa-trash me-2"></i>Delete</a></li>';
                }
                
                $actions .= '</ul></div>';
                return $actions;
            })
            ->editColumn('created_at', function ($role) {
                return $role->created_at->format('M d, Y H:i');
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::get();
        return view('roles.create', compact('permissions'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        $role = Role::create(['name' => $request->input('name')]);
        
        // Convert permission IDs to integers to ensure proper data type
        $permissionIds = array_map('intval', $request->input('permissions'));
        $role->syncPermissions($permissionIds);
    
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role created successfully',
                'redirect' => route('roles.index')
            ]);
        }
    
        return redirect()->route('roles.index')
                        ->with('success', 'Role created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')
                ->with('error', 'Role not found');
        }
        
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();
    
        return view('roles.show', compact('role', 'rolePermissions'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')
                ->with('error', 'Role not found');
        }
        
        $permissions = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();
    
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
    
        $role = Role::find($id);
        if (!$role) {
            return redirect()->route('roles.index')
                ->with('error', 'Role not found');
        }
        
        $role->name = $request->input('name');
        $role->save();
    
        // Convert permission IDs to integers to ensure proper data type
        $permissionIds = array_map('intval', $request->input('permissions'));
        $role->syncPermissions($permissionIds);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role updated successfully',
                'redirect' => route('roles.index')
            ]);
        }

        return redirect()->route('roles.index')
                        ->with('success', 'Role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $role = Role::find($id);
        if (!$role) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Role not found'
                ], 404);
            }
            return redirect()->route('roles.index')
                ->with('error', 'Role not found');
        }
        
        // Check if it's the admin role
        if ($role->name === 'Admin' || $role->name === 'admin') {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete the Admin role'
                ], 422);
            }
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete the Admin role');
        }
        
        $role->delete();
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Role deleted successfully',
                'redirect' => route('roles.index')
            ]);
        }
        
        return redirect()->route('roles.index')
                        ->with('success', 'Role deleted successfully');
    }
}