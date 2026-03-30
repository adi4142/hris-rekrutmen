<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Selection;
use App\ActivityLog;

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
            'description' => 'nullable',
            'aspects' => 'nullable|array',
            'aspects.*.name' => 'required_with:aspects|string',
            'aspects.*.description' => 'nullable|string',
        ]);

        $selection = Selection::create([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($request->has('aspects') && is_array($request->aspects)) {
            foreach ($request->aspects as $aspect) {
                if (!empty($aspect['name'])) {
                    $selection->aspects()->create([
                        'name' => $aspect['name'],
                        'description' => $aspect['description'] ?? null,
                    ]);
                }
            }
        }

        ActivityLog::log('Menambah jenis seleksi baru: ' . $request->name, 'Master Data');

        return redirect()->route('selection.index')->with('success', 'Seleksi dan aspek penilaian berhasil ditambahkan');
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
            'description' => 'nullable',
            'aspects' => 'nullable|array',
            'aspects.*.name' => 'required_with:aspects|string',
            'aspects.*.description' => 'nullable|string',
        ]);

        $updateselection = Selection::findOrFail($id);
        $updateselection->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        // Hapus aspek yang lama (opsi sederhana)
        $updateselection->aspects()->delete();

        if ($request->has('aspects') && is_array($request->aspects)) {
            foreach ($request->aspects as $aspect) {
                if (!empty($aspect['name'])) {
                    $updateselection->aspects()->create([
                        'name' => $aspect['name'],
                        'description' => $aspect['description'] ?? null,
                    ]);
                }
            }
        }

        ActivityLog::log('Memperbarui jenis seleksi: ' . $updateselection->name, 'Master Data');

        return redirect()->route('selection.index')->with('success', 'Seleksi dan aspek penilaian berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $selection = Selection::findOrFail($id);
        $name = $selection->name;
        $selection->delete();

        ActivityLog::log('Menghapus jenis seleksi: ' . $name, 'Master Data');
        return redirect()->route('selection.index');
    }
}
