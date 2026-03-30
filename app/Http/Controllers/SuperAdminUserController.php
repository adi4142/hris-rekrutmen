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
    public function index(Request $request)
    {
        $query = User::with('role');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('role', function($q_role) use ($request) {
                      $q_role->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $users = $query->paginate(10);
        // Exclude pelamar & tamu — akun mereka dibuat otomatis saat mengirim lamaran
        $roles = Role::whereNotIn('name', ['pelamar', 'tamu', 'Pelamar', 'Tamu'])->get();
        return view('admin.users.index', compact('users', 'roles'));
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
            return back()->with('success', 'User berhasil ditambahkan! <b>Password Sementara: ' . $tempPassword . '</b>. (Email notifikasi berhasil dikirim).');
        } catch (\Exception $e) {
            \Log::error("Gagal mengirim email akun baru: " . $e->getMessage());
            return back()->with('success', 'User berhasil ditambahkan! <b>Password Sementara: ' . $tempPassword . '</b>. (Peringatan: Email gagal terkirim).');
        }
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


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $userName = $user->name;
        $user->delete();

        ActivityLog::log('Menghapus user: ' . $userName, 'User Management');

        return back()->with('success', 'User berhasil dihapus');
    }
}
