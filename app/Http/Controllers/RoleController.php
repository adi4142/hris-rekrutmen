<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use App\ActivityLog;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
        'name'=>'required|string|max:255|unique:roles',
        'description'=>'nullable|string',]);

        Role::create([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Menambah role baru: ' . $request->name, 'User Management');

        return redirect()->route('role.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editrole = Role::findOrFail($id);
        return view('role.edit', compact('editrole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
        'name'=>'required|string|max:255|unique:roles,name,'.$id.',roles_id',
        'description'=>'nullable|string',]);

        $update = Role::findOrFail($id);
        $update->update([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Memperbarui role: ' . $update->name, 'User Management');

        return redirect()->route('role.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $name = $role->name;
        $role->delete();

        ActivityLog::log('Menghapus role: ' . $name, 'User Management');
        return redirect()->route('role.index');
    }
}
