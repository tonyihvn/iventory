<?php

namespace App\Http\Controllers;

use App\facilities;
use App\audit;
use Auth;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(auth()->user()->role=="Admin"){
            $facilities = facilities::orderBy('facility_name', 'asc')->get();
        }else{
            $facilities = facilities::orderBy('facility_name', 'asc')->where('state',auth()->user()->state)->get();
        }
        return view('facilities',compact('facilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new_facility');
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
            'facility_name' => 'required|min:3'
        ]);

        facilities::create([
            'facility_name'=>$request->facility_name,
            'facility_no'=>$request->facility_no,
            'state'=>$request->state,
            'lga'=>$request->lga,
            'town'=>$request->town,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'contact_person'=>$request->contact_person
        ]);

        audit::create([
            'action'=>"Created New Facility ".$request->facility_name,
            'description'=>'A new user facility was created',
            'doneby'=>"Admin" // Auth::user()->id
        ]);
        session()->flash('message','The New Facility: '.$request->facility_name.' has been added successfully!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\facilities  $facilities
     * @return \Illuminate\Http\Response
     */
    public function show(facilities $facilities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\facilities  $facilities
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $facility = facilities::where('id','=',$id)->first();

        return view('facility', ['facility'=>$facility]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\facilities  $facilities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, facilities $facilities)
    {

        $facility = facilities::where('id','=', $request->id);
        $facility->update([
            'facility_name'=>$request->facility_name,
            'facility_no'=>$request->facility_no,
            'state'=>$request->state,
            'lga'=>$request->lga,
            'town'=>$request->town,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,
            'contact_person'=>$request->contact_person

        ]);

        audit::create([
            'action'=>"Updated Facility ".$request->facility_name,
            'description'=>'A facility was updated',
            'doneby'=>"Admin" // Auth::user()->id
        ]);

        session()->flash('message','The Facility: '.$request->facility_name.' has been updated successfully!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\facilities  $facilities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        facilities::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted Facility ".$request->id,
            'description'=>'A facility was deleted',
            'doneby'=>"Admin" // Auth::user()->id
        ]);

        session()->flash('message','The the selected facility has been successfully deleted.');

        return redirect()->back();
    }
}
