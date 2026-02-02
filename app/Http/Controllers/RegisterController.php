<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'roles_id' => $request->roles_id,
        ]);

        Auth::login($user);

        return redirect()->route('employee.index');
    }
}
