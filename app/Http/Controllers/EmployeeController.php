<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;
use App\Departement;
use App\Division;
use App\Position;
use App\User;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee = Employee::all();
        return view('employee.index', compact('employee'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::all();
        $departements = Departement::all();
        $divisions = Division::all();
        $positions = Position::all();
        return view('employee.create', compact('user', 'departements', 'divisions', 'positions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'nip'=>'required|string|max:25|unique:employees',
            'name'=>'required|string|max:100',
            'user_id'=>'required|exists:users,user_id',
            'email'=>'required|email|unique:employees,email',
            'phone'=>'required|string|max:20',
            'departement_id'=>'required|exists:departements,departement_id',
            'position_id'=>'required|exists:positions,position_id',
            'division_id'=>'required|exists:divisions,division_id',
            'address'=>'required|string',
            'date_of_birth'=>'required|date',
            'gender'=>'required|in:Male,Female'
        ]);

        Employee::create([
            'nip'=>$request->nip,
            'name'=>$request->name,
            'user_id'=>$request->user_id,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'departement_id'=>$request->departement_id,
            'position_id'=>$request->position_id,
            'division_id'=>$request->division_id,
            'address'=>$request->address,
            'date_of_birth'=>$request->date_of_birth,
            'gender'=>$request->gender
        ]);

        return redirect()->route('employee.index')->with('success', 'Data Karyawan berhasil ditambahkan');
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
    public function edit($nip)
    {
        $employee = Employee::where('nip', $nip)->firstOrFail();
        $user = User::all();
        $departements = Departement::all();
        $divisions = Division::all();
        $positions = Position::all();
        return view('employee.edit', compact('employee', 'user', 'departements', 'divisions', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nip)
    {
        $this->validate($request, [
            'nip' => 'required|string|max:25|unique:employees,nip,' . $nip . ',nip',
            'name' => 'required|string|max:100',
            'user_id' => 'required|exists:users,user_id',
            'email' => 'required|email|unique:employees,email,' . $nip . ',nip',
            'phone' => 'required|string|max:20',
            'departement_id' => 'required|exists:departements,departement_id',
            'position_id' => 'required|exists:positions,position_id',
            'division_id' => 'required|exists:divisions,division_id',
            'address' => 'required|string',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female'
        ]);

        Employee::where('nip', $nip)->update([
            'nip' => $request->nip,
            'name' => $request->name,
            'user_id' => $request->user_id,
            'email' => $request->email,
            'phone' => $request->phone,
            'departement_id' => $request->departement_id,
            'position_id' => $request->position_id,
            'division_id' => $request->division_id,
            'address' => $request->address,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender
        ]);

        return redirect()->route('employee.index')->with('success', 'Data Karyawan berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nip)
    {
        Employee::where('nip', $nip)->delete();
        return redirect()->route('employee.index')->with('success', 'Data Karyawan berhasil dihapus');
    }

    public function getUserEmail($id)
    {
        $user = User::find($id);

        return response()->json([
            'email' => $user ? $user->email : ''
        ]);
    }
}
