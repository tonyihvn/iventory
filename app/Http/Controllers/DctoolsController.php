<?php

namespace App\Http\Controllers;

use App\dctools;
use App\dcstocks;
use App\dcsupplies;
use App\facilities;
use App\dcdistributions;
use App\dctoolutilizations;
use Illuminate\Http\Request;

class DctoolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dctools = dctools::all();
        return view('dctools',compact('dctools'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('new_dctool');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item_id = dctools::create([
            'tool_name'=>$request->tool_name,
            'description'=>$request->description,
            'category'=>$request->category
        ])->id;

        stocks::create([
            'item_id'=>$item_id,
            'quantity_remaining'=>0
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\dctools  $dctools
     * @return \Illuminate\Http\Response
     */
    public function show(dctools $dctools)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\dctools  $dctools
     * @return \Illuminate\Http\Response
     */
    public function edit(dctools $dctools)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\dctools  $dctools
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, dctools $dctools)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\dctools  $dctools
     * @return \Illuminate\Http\Response
     */
    public function destroy(dctools $dctools)
    {
        //
    }

    public function addDCTStock($dcid){
        $item = dctools::select('id','tool_name','category')->where('id',$dcid)->first();
        return view('new_dctstock',compact('item'));
    }

    public function dcDistribution($dcid){
        $dctool = dctools::select('id','tool_name','category')->where('id',$dcid)->first();
        $facilities = facilities::select('id','facility_name')->get();

        return view('new_dcdistribution',compact('dctool','facilities'));
    }

    public function newDCTSupply(Request $request){
        $this->validate($request, [
            'item'=>'required'
        ]);

        dcsupplies::create([
            'item_id'=>$request->item,
            'quantity_supplied'=>$request->quantity_supplied,
            'date_supplied'=>$request->date_supplied,
            'supplied_to'=>$request->supplied_to,
            'supplier'=>$request->supplier,
            'batchno'=>$request->batchno,
            'remarks'=>$request->remarks
        ]);

        $itemstock = dcstocks::where('item_id',$request->item)->first();
        if(isset($itemstock)){
            $itemstock->increment('quantity_remaining',$request->quantity_supplied);
        }else{
            dcstocks::create([
                'item_id'=>$request->item,
                'quantity_remaining'=>$request->quantity_supplied
            ]);
        }

        session()->flash('message','The item supplied has been added to stock! <br>');
        return redirect()->back();

    }

    public function savedcDistribution(Request $request){

        $documentname = '';
        if($request->hasFile('documents'))
        {
            $file = $request->file('documents');
            $documentname = $file->getClientOriginalName();
            $file->move('uploads/', $documentname);
        }

        $state = facilities::where('id',$request->sent_to)->first()->state;
        $fromstate = facilities::where('id',$request->sent_from)->first()->state;

        dcdistributions::create([
            'item_id'=>$request->item,
            'quantity_sent'=>$request->quantity_sent,
            'date_sent'=>$request->date_sent,
            'sent_from'=>$request->sent_from,
            'sent_to'=>$request->sent_to,
            'documents'=>$documentname,
            'remarks'=>$request->remarks,
            'sent_by'=>$request->sent_by,
            'received_by'=>$request->received_by,
            'sentto_state'=>$state,
            'sentfrom_state'=>$fromstate
        ]);

        if($fromstate!="WAREHOUSE"){
        dcstocks::where('item_id',$request->item)->decrement('quantity_remaining',$request->quantity_sent);
        }
        session()->flash('message','The item was distributed successfully! <br>');
        return redirect()->back();

    }

    public function dcReport($dcid){
        $item = dctools::where('id',$dcid)->first();
        $itemsupplies = dcsupplies::where('item_id',$dcid)->get();
        $itemdist = dcdistributions::where('item_id',$dcid)->get();
        return view('dct_item',compact('item','itemsupplies','itemdist'));
    }

    public function dcUtilization($dcid){
        $dctool = dctools::where('id',$dcid)->first();
        if(Auth()->user()->role=="DCTManager"){
            $facilities = facilities::select('id','facility_name','state')->where('state',Auth()->user()->state)->get();

        }if(Auth()->user()->role=="DCTUser"){
            $facilities = facilities::select('id','facility_name')->where('id',Auth()->user()->facility)->get();
        }
        else{
            $facilities = facilities::select('id','facility_name')->get();
        }

        return view('dctool_utilization',compact('dctool','facilities'));

    }

    public function savedcUtilization(Request $request){

        foreach ($request->facility_id as $key => $facility) {
            $allrec = dcdistributions::where('sent_to',$facility)->sum('quantity_sent');
            $allused = dctoolutilizations::where('facility_id',$facility)->sum('quantity_used');
            $benchmark = $allrec-$allused;

            if($request->quantity_used[$key]!=""){
                dctoolutilizations::create([
                    'item_id'=>$request->item,
                    'facility_id'=>$facility,
                    'dated_from'=>$request->date_from,
                    'dated_to'=>$request->date_to,
                    'quantity_used'=>$request->quantity_used[$key],
                    'benchmark'=>$benchmark,
                    'remarks'=>$request->remarks
                ]);
            }
        }



        session()->flash('message','The DCTool Utilization Saved Successfully! <br>');
        return redirect()->back();

    }

    public function fdcUtilization($dcid){
        $distribution = dcdistributions::where('sent_to',$dcid)->get();
        $utilization = dctoolutilizations::where('facility_id',$dcid)->get();

        return view('fdcutilization',compact('distribution','utilization'));

    }

}
