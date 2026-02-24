<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        $roles = Role::all();
        if ($roles->isEmpty()) {
            // Create a default role if none exists so registration doesn't fail
            Role::create(['name' => 'Employee', 'description' => 'Default Employee Role']);
            $roles = Role::all();
        }
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles_id' => ['required', 'exists:roles,roles_id'],
        ]);

        $code = strtoupper(substr(md5(rand()), 0, 6));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id,
            'verification_code' => $code,
        ]);

        // Kirim Email Verifikasi
        $role = Role::find($request->roles_id);
        $roleName = $role->name ?? 'User';

        Mail::to($user->email)->send(new \App\Mail\EmailVerificationMail(
            $user->name,
            $roleName,
            $code
        ));


        Auth::login($user);

        return redirect()->route('emails.verify.form')
            ->with('success', 'Registrasi berhasil! Silakan cek email Anda untuk kode verifikasi.');
    }
}
