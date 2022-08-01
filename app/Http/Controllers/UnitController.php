<?php

namespace App\Http\Controllers;

use App\unit;
use App\facilities;
use App\department;
use App\audit;
use Illuminate\Http\Request;

class unitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $units = unit::orderBy('unit_name', 'asc')->paginate(50);
        return view('units',compact('units'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facilities = facilities::select('facility_name')->get();
        $departments = department::select('department_name')->get();
        return view('new_unit',compact('facilities'), ['departments'=>$departments]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'unit_name' => 'required|min:2'
        ]);

        unit::create([
            'unit_name'=>$request->unit_name,
            'department'=>$request->department,
            'facility'=>$request->facility,
            'internal_location'=>$request->internal_location,
            'description'=>$request->description
        ]);

        audit::create([
            'action'=>"Created New unit ".$request->unit_name,
            'description'=>'A new unit was created',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
        session()->flash('message','The New unit: '.$request->unit_name.' has been added successfully!');
        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function show(unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function edit(unit $unit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, unit $unit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\unit  $unit
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        unit::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted unit ".$request->id,
            'description'=>'A unit was deleted',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
 
        session()->flash('message','The the selected unit has been successfully deleted.');

        return redirect()->back();
    }
}
