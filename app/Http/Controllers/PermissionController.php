<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the permissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Permission::orderBy('id', 'DESC');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'LIKE', "%{$search}%");
        }
        
        $permissions = $query->paginate(10);
        return view('permissions.index', compact('permissions'))
            ->with('i', ($request->input('page', 1) - 1) * 10);
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

        return redirect()->route('permissions.index')
            ->with('success', 'Permission updated successfully');
    }

    /**
     * Remove the specified permission from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::find($id);
        if (!$permission) {
            return redirect()->route('permissions.index')
                ->with('error', 'Permission not found');
        }
        
        // Check if this is a critical permission
        $criticalPermissions = ['create-user', 'edit-user', 'delete-user', 'create-role', 'edit-role', 'delete-role'];
        if (in_array($permission->name, $criticalPermissions)) {
            return redirect()->route('permissions.index')
                ->with('error', 'Cannot delete critical system permission');
        }
        
        $permission->delete();
        
        return redirect()->route('permissions.index')
            ->with('success', 'Permission deleted successfully');
    }
}