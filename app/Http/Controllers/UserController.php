<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

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
        $users = User::with('role')->select('users.*');

        return DataTables::of($users)
            ->addColumn('full_name', function ($user) {
                return $user->full_name;
            })
            ->addColumn('role', function ($user) {
                return $user->role ? $user->role->name : 'No Role';
            })
            ->addColumn('actions', function ($user) {
                $actions = '<div class="dropdown">';
                $actions .= '<button class="btn btn-sm btn-icon-only text-dark mb-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">';
                $actions .= '<i class="fa fa-ellipsis-v"></i>';
                $actions .= '</button>';
                $actions .= '<ul class="dropdown-menu dropdown-menu-end">';
                
                $actions .= '<li><a class="dropdown-item" href="' . route('users.show', $user->id) . '"><i class="fas fa-eye me-2"></i>View</a></li>';
                $actions .= '<li><a class="dropdown-item" href="' . route('users.edit', $user->id) . '"><i class="fas fa-edit me-2"></i>Edit</a></li>';
                
                if ($user->id !== auth()->id()) {
                    $actions .= '<li><hr class="dropdown-divider"></li>';
                    $actions .= '<li><a class="dropdown-item text-danger" href="#" onclick="deleteUser(' . $user->id . ')"><i class="fas fa-trash me-2"></i>Delete</a></li>';
                }
                
                $actions .= '</ul></div>';
                return $actions;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at->format('M d, Y H:i');
            })
            ->editColumn('status', function ($user) {
                $statusClass = $user->status === 'Active' ? 'success' : ($user->status === 'Inactive' ? 'warning' : 'danger');
                return '<span class="badge bg-gradient-' . $statusClass . '">' . $user->status . '</span>';
            })
            ->rawColumns(['actions', 'status'])
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
        $attributes = $request->validate(User::validationRules());

        $attributes['password'] = Hash::make($attributes['password']);
        
        $user = User::create($attributes);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User account created successfully',
                'redirect' => route('users.index')
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User account created successfully');
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
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
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
        $attributes = $request->validate(User::validationRules($user->id));
        
        // Only update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['min:8', 'max:20', 'confirmed'],
            ]);
            $attributes['password'] = Hash::make($request->password);
        } else {
            // Remove password from attributes if not provided
            unset($attributes['password']);
        }

        $user->update($attributes);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully',
                'redirect' => route('users.index')
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
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
                'message' => 'User deleted successfully',
                'redirect' => route('users.index')
            ]);
        }

        return redirect()->route('users.index')
            ->with('success', 'User deleted successfully');
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