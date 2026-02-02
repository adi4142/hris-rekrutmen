<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Selection;

class SelectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selections = Selection::all();
        return view('Selection.index', compact('selections'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Selection.create');
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
            'name' => 'required',
            'description' => 'required',
            'order' => 'required',
        ]);

        Selection::create([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
        ]);

        return redirect()->route('selection.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $editselection = Selection::find($id);
        return view('Selection.edit', compact('editselection'));
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
            'name' => 'required',
            'description' => 'required',
            'order' => 'required',
        ]);

        $updateselection = Selection::findOrFail($id);
        $updateselection->update([
            'name' => $request->name,
            'description' => $request->description,
            'order' => $request->order,
        ]);

        return redirect()->route('selection.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Selection::where('selection_id', $id)->delete();
        return redirect()->route('selection.index');
    }
}
