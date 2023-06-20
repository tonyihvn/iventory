<?php

namespace App\Http\Controllers;

use App\supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $suppliers = supplier::all();
        return view('suppliers',compact('suppliers'));
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
        supplier::create([
            'supplier_name'=>$request->supplier_name,
            'company_name'=>$request->company_name,
            'phone_number'=>$request->phone_number,
            'email'=>$request->email,
            'items'=>$request->items,
            'category'=>$request->category
        ]);
        session()->flash('message','The new supplier has been added to database! <br>');

        return view('suppliers');

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
    public function destroy(Request $request, $id)
    {
        supplier::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted Supplier Transaction ",
            'description'=>'A supplier was deleted',
            'doneby'=>Auth::user()->id
        ]);

        session()->flash('message','The the selected item has been successfully deleted.');

        return redirect()->back();
    }
}
