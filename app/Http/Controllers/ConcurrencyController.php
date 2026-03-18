<?php

namespace App\Http\Controllers;

use App\concurrency;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel; // <- important import
use App\Exports\ConcurrenciesExport;
use App\Imports\ConcurrenciesImport;

class ConcurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
      public function index()
    {
        // You can paginate or load whatever you currently show in concurrencies.blade.php
        $concurrencies = concurrency::orderBy('id')->paginate(100);
        return view('concurrencies', compact('concurrencies'));
    }

    /**
     * Export concurrencies to Excel
     */
    public function export()
    {
        $filename = 'concurrencies_export_' . date('Y_m_d_H_i_s') . '.xlsx';
        return Excel::download(new ConcurrenciesExport, $filename);
    }

    /**
     * Import edited Excel and update concurrencies + linked inventories
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new ConcurrenciesImport, $request->file('file'));
            return redirect()->route('concurrency')->with('success', 'File imported successfully and database updated.');
        } catch (\Exception $e) {
            \Log::error('Concurrencies import error: ' . $e->getMessage());
            return redirect()->route('concurrency')->with('error', 'Import failed: ' . $e->getMessage());
        }
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
     * @param  \App\concurrency  $concurrency
     * @return \Illuminate\Http\Response
     */
    public function show(concurrency $concurrency)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\concurrency  $concurrency
     * @return \Illuminate\Http\Response
     */
    public function edit(concurrency $concurrency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\concurrency  $concurrency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, concurrency $concurrency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\concurrency  $concurrency
     * @return \Illuminate\Http\Response
     */
    public function destroy(concurrency $concurrency)
    {
        //
    }
}
