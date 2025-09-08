<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getUsersDataTable();
        }
        
        return view('users.index');
    }

    /**
     * Get users data for DataTables
     *
     * @return mixed
     */
    private function getUsersDataTable()
    {
        $users = User::with('roles')->select('users.*');

        return DataTables::of($users)
            ->addColumn('roles_list', function ($user) {
                return $user->roles->pluck('name')->implode(', ');
            })
            ->addColumn('actions', function ($user) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                
                if (auth()->user()->can('view-users')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('users.show', $user->id) . '"><i class="fas fa-eye me-2"></i>View</a></li>';
                }
                
                if (auth()->user()->can('edit-users')) {
                    $actions .= '<li><a class="dropdown-item" href="' . route('users.edit', $user->id) . '"><i class="fas fa-edit me-2"></i>Edit</a></li>';
                }
                
                if (auth()->user()->can('delete-users') && $user->id !== auth()->id()) {
                    $actions .= '<li><hr class="dropdown-divider"></li>';
                    $actions .= '<li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(' . $user->id . ')"><i class="fas fa-trash me-2"></i>Delete</a></li>';
                }
                
                $actions .= '</ul></div>';
                return $actions;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('M d, Y H:i');
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    /**
     * Show the form for creating a new user
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created user in storage
     * Only admins can create users and assign roles
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Ensure only admins can create users
        if (!auth()->user()->hasPermissionTo('manage-users')) {
            abort(403, 'Unauthorized. Only administrators can create user accounts.');
        }

        $attributes = $request->validate(User::validationRules());
        $request->validate([
            'roles' => ['required', 'array'],
        ]);

        $attributes['password'] = Hash::make($attributes['password']);
        
        $user = User::create($attributes);
        
        // Only admins can assign roles
        if ($request->has('roles') && is_array($request->roles)) {
            // Convert role IDs to role names for syncRoles method
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User account created successfully by administrator',
                'redirect' => route('users.index')
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User account created successfully by administrator');
    }

    /**
     * Display the specified user
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     * Only admins can edit users and assign roles
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user)
    {
        // Ensure only admins can edit users
        if (!auth()->user()->hasPermissionTo('manage-users')) {
            abort(403, 'Unauthorized. Only administrators can edit user accounts.');
        }

        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();
        return view('users.edit', compact('user', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user in storage
     * Only admins can update users and assign roles
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        // Ensure only admins can update users
        if (!auth()->user()->hasPermissionTo('manage-users')) {
            abort(403, 'Unauthorized. Only administrators can update user accounts.');
        }

        $attributes = $request->validate(User::validationRules($user->id));
        $request->validate([
            'roles' => ['required', 'array'],
        ]);
        
        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['min:5', 'max:20'],
            ]);
            $attributes['password'] = Hash::make($request->password);
        }

        $user->update($attributes);
        
        // Only admins can assign/change roles
        if ($request->has('roles') && is_array($request->roles)) {
            // Convert role IDs to role names for syncRoles method
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully by administrator',
                'redirect' => route('users.index')
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully by administrator');
    }

    /**
     * Remove the specified user from storage
     * Only admins can delete users
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        // Ensure only admins can delete users
        if (!auth()->user()->hasPermissionTo('manage-users')) {
            abort(403, 'Unauthorized. Only administrators can delete user accounts.');
        }

        // Prevent deleting your own account
        if ($user->id === Auth::id()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot delete your own account'
                ], 422);
            }
            return redirect()->route('users.index')
                ->with('error', 'You cannot delete your own account');
        }

        $user->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully by administrator',
                'redirect' => route('users.index')
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully by administrator');
    }

    /**
     * Show form for resetting user password
     * 
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function resetPassword(User $user)
    {
        return view('users.reset-password', compact('user'));
    }

    /**
     * Update user password
     * 
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request, User $user)
    {
        $request->validate([
            'password' => ['required', 'min:5', 'max:20', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('users.index')
            ->with('success', 'User password reset successfully');
    }
}