<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10);
        $roles = Role::all();
        return view('superadmin.users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'roles_id' => 'required|exists:roles,roles_id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id,
            'is_role_verified' => 1, // Super admin created users are verified
        ]);

        return back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id.',user_id',
            'roles_id' => 'required|exists:roles,roles_id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'roles_id' => $request->roles_id,
        ]);

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password user berhasil direset');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->status == 'active') ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        return back()->with('success', 'Status user berhasil diubah menjadi ' . $newStatus);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}
