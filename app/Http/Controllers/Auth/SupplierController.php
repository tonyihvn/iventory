<?php

namespace App\Http\Controllers;

use App\supplier;
use Illuminate\Http\Request;
use App\audit;
use Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = supplier::all();
        return view('suppliers')->with(['clients'=>$clients]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('add_supplier');
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
            'supplier_name' => 'required|min:3'
        ]);

        supplier::create([
            'supplier_name'=>$request->supplier_name,
            'company_name'=>$request->company_name,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'items'=>$request->items,                
            'category'=>$request->category
            ]);
        
        audit::create([
            'action'=>"New Client Added by ". Auth::user()->name,
            'description'=>'New Client',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
        session()->flash('message','The New Client has been saved successfully!');
        
        return redirect()->back();
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function show(supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function edit(supplier $supplier)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, supplier $supplier)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\supplier  $supplier
     * @return \Illuminate\Http\Response
     */
    public function destroy(supplier $supplier)
    {
        //
    }
}
