<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\ActivityLog;

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
            'roles_id' => 'required|exists:roles,roles_id',
        ]);

        // Generate temporary password
        $tempPassword = Str::random(10);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($tempPassword),
            'roles_id' => $request->roles_id,
            'is_role_verified' => 1, // Super admin created users are verified
            'needs_password_change' => 1, // Force password change on first login
            'status' => 'active', // Account is active, but needs password change
            'email_verified_at' => now(), // Assume verified as created by Super Admin
        ]);

        ActivityLog::log('Menambah user baru: ' . $user->name . ' (' . $user->email . ')', 'User Management');

        // Kirim Email ke Admin/HRD baru
        try {
            Mail::to($user->email)->send(new \App\Mail\AdminAccountCreatedMail(
                $user->name,
                $user->email,
                $tempPassword
            ));
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email akun baru: " . $e->getMessage());
            // Tetap lanjut, tapi kasih info password sementara di flash message jika email gagal
            return back()->with('success', 'User berhasil ditambahkan. (Email gagal terkirim, Password sementara: ' . $tempPassword . ')');
        }

        return back()->with('success', 'User berhasil ditambahkan dan email notifikasi telah dikirim.');
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

        ActivityLog::log('Memperbarui user: ' . $user->name, 'User Management');

        return back()->with('success', 'User berhasil diperbarui');
    }

    public function resetPassword(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $newPassword = $request->new_password ?? Str::random(10);

        $user->update([
            'password' => Hash::make($newPassword),
            'needs_password_change' => 1 // Resetting password also forces change
        ]);

        ActivityLog::log('Mereset password user: ' . $user->name, 'User Management');

        return back()->with('success', 'Password user berhasil direset. (Password baru: ' . $newPassword . ')');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $newStatus = ($user->status == 'active') ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        ActivityLog::log('Mengubah status user ' . $user->name . ' menjadi ' . $newStatus, 'User Management');

        return back()->with('success', 'Status user berhasil diubah menjadi ' . $newStatus);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        ActivityLog::log('Menghapus user: ' . $userName, 'User Management');

        return back()->with('success', 'User berhasil dihapus');
    }
}
