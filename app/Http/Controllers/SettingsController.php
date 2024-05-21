<?php

namespace App\Http\Controllers;

use App\settings;
use App\audit;
use Auth;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $settings = settings::first();
        return view('settings')->with(['settings'=>$settings]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'organization_name' => 'required|min:3'
        ]);
        
        if ($request->hasFile('logo')) {
            $logoname = $request->file('logo')->getClientOriginalName();
            $file = request()->file('logo');
            $file->move('uploads', $logoname);
            // $file->store($logoname, ['disk' => 'uploads']);
        } else {
            $logoname = "";
        }

        settings::create([
            'organization_name'=>$request->organization_name,
            'description'=>$request->description,
            'logo'=>$logoname,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,                
            'copyright'=>$request->copyright
            ]);
        
        audit::create([
            'action'=>"System Settings By ", //. Auth::user()->name,
            'description'=>'System settings',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
        session()->flash('message','The New Settings has been saved successfully!');
        
        return redirect()->back();
            
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function show(settings $settings)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function edit(settings $settings,$id)
    {
        $settings = settings::where('id',$id)->first();
        return view('edit_settings')->with(['settings'=>$settings]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, settings $settings)
    {
        $this->validate($request, [
            'organization_name' => 'required|min:3'
        ]);
        
        if ($request->hasFile('logo')) {
            $logoname = $request->file('logo')->getClientOriginalName();
            $file = request()->file('logo');
            $file->move('uploads', $logoname);
            // $file->store($logoname, ['disk' => 'uploads']);
        } else {
            $logoname = $request->oldlogo;
        }

        $settings = settings::where('id','=', $request->id);
        $settings->update([
            'organization_name'=>$request->organization_name,
            'description'=>$request->description,
            'logo'=>$logoname,
            'address'=>$request->address,
            'phone_number'=>$request->phone_number,                
            'copyright'=>$request->copyright
            ]);
        
        audit::create([
            'action'=>"System Settings Updated By ", //.Auth::user()->name,
            'description'=>'System settings was modified',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
        session()->flash('message','The New Settings has been saved successfully!');
        
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\settings  $settings
     * @return \Illuminate\Http\Response
     */
    public function destroy(settings $settings)
    {
        //
    }
}
