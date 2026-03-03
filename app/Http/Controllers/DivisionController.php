<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use App\ActivityLog;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $division = Division::all();
        return view('division.index', compact('division'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('division.create');
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
        'name'=>'required|string|max:255|unique:divisions',
        'description'=>'nullable|string',]);

        Division::create([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Menambah divisi baru: ' . $request->name, 'Master Data');

        return redirect()->route('division.index');
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
        $editdivision = Division::findOrFail($id);
        return view('division.edit', compact('editdivision'));
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
        'name'=>'required|string|max:255|unique:divisions,name,'.$id.',division_id',
        'description'=>'nullable|string',]);

        $division = Division::findOrFail($id);
        $division->update([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Memperbarui divisi: ' . $division->name, 'Master Data');

        return redirect()->route('division.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $division = Division::findOrFail($id);
        $name = $division->name;
        $division->delete();

        ActivityLog::log('Menghapus divisi: ' . $name, 'Master Data');
        return redirect()->route('division.index');
    }
}
