<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Departement;
use App\ActivityLog;

class DepartementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Departement::query();

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $departement = $query->paginate(20);
        return view('departement.index', compact('departement'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('departement.create');
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
        'name'=>'required|string|max:255|unique:departements',
        'description'=>'nullable|string',]);

        Departement::create([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Menambah departemen baru: ' . $request->name, 'Master Data');

        return redirect()->route('departement.index');
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
        $editdepartement = Departement::findOrFail($id);
        return view('departement.edit', compact('editdepartement'));
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
        'name'=>'required|string|max:255|unique:departements,name,'.$id.',departement_id',
        'description'=>'nullable|string',]);

        $departement = Departement::findOrFail($id);
        $departement->update([
        'name'=>$request->name,
        'description'=>$request->description]);

        ActivityLog::log('Memperbarui departemen: ' . $departement->name, 'Master Data');

        return redirect()->route('departement.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $departement = Departement::findOrFail($id);
        $name = $departement->name;
        $departement->delete();

        ActivityLog::log('Menghapus departemen: ' . $name, 'Master Data');
        return redirect()->route('departement.index');
    }
}
