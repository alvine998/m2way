<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        return view('roles.index', compact('roles'))
            ->with('pageTitle', __('app.roles'));
    }

    public function create()
    {
        return view('roles.create')->with('pageTitle', __('app.add_role'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:50|unique:roles,slug|alpha_dash',
        ]);

        $permissions = $request->input('permissions', []);

        $role = Role::create($validated);
        foreach ($permissions as $permission) {
            $role->permissions()->create(['permission' => $permission]);
        }

        ActivityLogger::log('created', 'Peran ' . $role->name . ' berhasil dibuat.', $role);
        return redirect()->route('roles.index')->with('success', __('app.role_created'));
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        return view('roles.edit', compact('role'))
            ->with('pageTitle', __('app.edit_role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('roles', 'slug')->ignore($role->id)],
        ]);

        $role->update($validated);

        $role->permissions()->delete();
        foreach ($request->input('permissions', []) as $permission) {
            $role->permissions()->create(['permission' => $permission]);
        }

        ActivityLogger::log('updated', 'Peran ' . $role->name . ' berhasil diubah.', $role);
        return redirect()->route('roles.index')->with('success', __('app.role_updated'));
    }

    public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return back()->with('error', __('app.cannot_delete_role_in_use'));
        }

        $name = $role->name;
        $role->delete();
        ActivityLogger::log('deleted', 'Peran ' . $name . ' berhasil dihapus.');
        return redirect()->route('roles.index')->with('success', __('app.role_deleted'));
    }
}
