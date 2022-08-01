<?php

namespace App\Http\Controllers;

use App\audit;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $audits = audit::orderBy('created_at', 'desc')->paginate(50);
        return view('audits',compact('audits'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function show(audit $audit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function edit(audit $audit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, audit $audit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\audit  $audit
     * @return \Illuminate\Http\Response
     */
    public function destroy(audit $audit)
    {
        //
    }
}
