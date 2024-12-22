<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::withCount('users')->paginate(3);
        return view('permissions.index', compact('permissions'));
    }

    /**
     * Show the form for creating a new permission
     */
    public function create()
    {
        return view('permissions.create');
    }

    /**
     * Store a newly created permission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:permissions'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        Permission::create($validated);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission created successfully.');
    }

    /**
     * Show permission details
     */
    public function show(Permission $permission)
    {
        $permission->load('users');
        return view('permissions.show', compact('permission'));
    }

    /**
     * Show the form for editing a permission
     */
    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    /**
     * Update the specified permission
     */
    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('permissions')->ignore($permission->id)],
            'description' => ['required', 'string', 'max:255'],
        ]);

        $permission->update($validated);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission updated successfully.');
    }

    /**
     * Remove the specified permission
     */
    public function destroy(Permission $permission)
    {
        // Prevent deletion of critical permissions
        if ($permission->name === 'user-management') {
            return redirect()
                ->route('permissions.index')
                ->with('error', 'Cannot delete the user-management permission as it is required by the system.');
        }

        $permission->delete();

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permission deleted successfully.');
    }

    /**
     * Show the form for assigning permissions to users
     */
    public function assignForm()
    {
        $users = User::all();
        $permissions = Permission::all();
        
        return view('permissions.assign', compact('users', 'permissions'));
    }

    /**
     * Assign permissions to a user
     */
    public function assign(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'permissions' => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id']
        ]);

        $user = User::findOrFail($validated['user_id']);
        $user->permissions()->sync($validated['permissions']);

        return redirect()
            ->route('permissions.index')
            ->with('success', 'Permissions assigned successfully.');
    }

    /**
     * Show users with specific permission
     */
    public function showUsers(Permission $permission)
    {
        $users = $permission->users()->paginate(10);
        return view('permissions.users', compact('permission', 'users'));
    }

    public function userPermissions(Request $request)
    {
        $user = User::find($request->user_id);
        $permissions = $user->permissions()->get(['permissions.id as permission_id', 'permissions.name', 'permissions.description']);
        return response()->json($permissions);
    }

    /**
     * Revoke a permission from a user
     */
    public function revokeFromUser(Request $request, Permission $permission, User $user)
    {
        // Prevent revoking user-management from last user with that permission
        if ($permission->name === 'user-management' && 
            $permission->users()->count() === 1 && 
            $user->hasPermission('user-management')) {
            return redirect()
                ->back()
                ->with('error', 'Cannot revoke user-management permission from the last user who has it.');
        }

        $user->permissions()->detach($permission);

        return redirect()
            ->back()
            ->with('success', 'Permission revoked successfully.');
    }
}
