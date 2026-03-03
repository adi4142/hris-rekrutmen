<?php

namespace App\Http\Controllers;

use App\Position;
use Illuminate\Http\Request;
use App\ActivityLog;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $position = Position::all();
        return view('position.index', compact('position'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('position.create');
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
        'name'=>'required|string|max:255|unique:positions',
        'description'=>'nullable|string',]);

        Position::create([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Menambah jabatan baru: ' . $request->name, 'Master Data');

        return redirect()->route('position.index');
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
        $editposition = Position::findOrFail($id);
        return view('position.edit', compact('editposition'));
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
        'name'=>'required|string|max:255|unique:positions,name,'.$id.',position_id',
        'description'=>'nullable|string',]);

        $position = Position::findOrFail($id);
        $position->update([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Memperbarui jabatan: ' . $position->name, 'Master Data');

        return redirect()->route('position.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $position = Position::findOrFail($id);
        $name = $position->name;
        $position->delete();

        ActivityLog::log('Menghapus jabatan: ' . $name, 'Master Data');
        return redirect()->route('position.index');
    }
}
