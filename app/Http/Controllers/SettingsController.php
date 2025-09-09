<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    /**
     * Display the settings dashboard.
     */
    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        $users = User::with('role')->get();
        
        return view('settings.index', compact('roles', 'permissions', 'users'));
    }

    /**
     * Display role management page.
     */
    public function roles()
    {
        $roles = Role::with('permissions', 'users')->get();
        $permissions = Permission::all();
        
        return view('settings.roles', compact('roles', 'permissions'));
    }

    /**
     * Store a new role.
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:roles',
            'description' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'description' => $request->description
        ]);

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('settings.roles')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Update role permissions.
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('settings.roles')
            ->with('success', 'Role permissions updated successfully.');
    }

    /**
     * Delete a role.
     */
    public function destroyRole(Role $role)
    {
        // Check if role is assigned to any users
        if ($role->users()->count() > 0) {
            return redirect()->route('settings.roles')
                ->with('error', 'Cannot delete role that is assigned to users.');
        }

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('settings.roles')
            ->with('success', 'Role deleted successfully.');
    }

    /**
     * Display user management page.
     */
    public function users()
    {
        $users = User::with('role')->paginate(20);
        $roles = Role::all();
        
        return view('settings.users', compact('users', 'roles'));
    }

    /**
     * Update user role.
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id'
        ]);

        $user->update([
            'role_id' => $request->role_id
        ]);

        return redirect()->route('settings.users')
            ->with('success', 'User role updated successfully.');
    }
}
