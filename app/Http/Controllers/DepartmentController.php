<?php

namespace App\Http\Controllers;

use App\department;
use App\facilities;
use App\audit;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments = department::orderBy('department_name', 'asc')->paginate(50);
        return view('departments',compact('departments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $facilities = facilities::select('id','facility_name')->get();
        return view('new_department',compact('facilities'));
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
            'department_name' => 'required|min:2'
        ]);

        department::create([
            'department_name'=>$request->department_name,
            'facility'=>$request->facility,
            'internal_location'=>$request->internal_location,
            'description'=>$request->description
        ]);

        audit::create([
            'action'=>"Created New Department ".$request->department_name,
            'description'=>'A new department was created',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
        session()->flash('message','The New Department: '.$request->department_name.' has been added successfully!');
        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\department  $department
     * @return \Illuminate\Http\Response
     */
    public function show(department $department)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\department  $department
     * @return \Illuminate\Http\Response
     */
    public function edit(department $department)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\department  $department
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, department $department)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        department::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted Department ".$request->id,
            'description'=>'A Department was deleted',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
 
        session()->flash('message','The the selected department has been successfully deleted.');

        return redirect()->back();
    }
}
