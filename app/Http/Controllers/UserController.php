<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = User::query();
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        $users = $query->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'))
            ->with('pageTitle', __('app.users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'))->with('pageTitle', __('app.add_user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        ActivityLogger::log('created', 'Pengguna ' . $user->name . ' berhasil dibuat.', $user);

        return redirect()->route('users.index')->with('success', __('app.user_created'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'))->with('pageTitle', __('app.edit_user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);
        ActivityLogger::log('updated', 'Pengguna ' . $user->name . ' berhasil diubah.', $user);

        return redirect()->route('users.index')->with('success', __('app.user_updated'));
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', __('app.cannot_delete_self'));
        }

        $name = $user->name;
        $user->delete();
        ActivityLogger::log('deleted', 'Pengguna ' . $name . ' berhasil dihapus.');

        return redirect()->route('users.index')->with('success', __('app.user_deleted'));
    }
}
