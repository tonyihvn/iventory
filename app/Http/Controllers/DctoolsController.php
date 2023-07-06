<?php

namespace App\Http\Controllers;

use App\dctools;
use App\dcstocks;
use App\dcsupplies;
use App\facilities;
use App\dcdistributions;
use App\dctoolutilizations;
use App\supplier;
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
        $dctools = dctools::with('distributions')->get();
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
        $suppliers = supplier::select('supplier_name','company_name')->get();

        return view('new_dctstock',compact('item','suppliers'));
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

    public function newBulkDCTSupply(Request $request){

        foreach($request->items as $key=>$item){

            dcsupplies::create([
                'item_id'=>$item,
                'quantity_supplied'=>$request->quantity_supplied[$key],
                'date_supplied'=>$request->date_supplied,
                'supplied_to'=>$request->supplied_to,
                'supplier'=>$request->supplier,
                'batchno'=>$request->batchno,
                'remarks'=>$request->remarks
            ]);

            $itemstock = dcstocks::where('item_id',$item)->first();
            if(isset($itemstock)){
                $itemstock->increment('quantity_remaining',$request->quantity_supplied[$key]);
            }else{
                dcstocks::create([
                    'item_id'=>$item,
                    'quantity_remaining'=>$request->quantity_supplied[$key]
                ]);
            }
        }

        session()->flash('message','The item supplied has been added to stock! <br>');
        return redirect()->route('dctools');

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
            'batchno'=>$request->batchno,
            'sent_by'=>$request->sent_by,
            'received_by'=>$request->received_by,
            'sentto_state'=>$state,
            'sentfrom_state'=>$fromstate
        ]);

        if($fromstate=="WAREHOUSE"){
        dcstocks::where('item_id',$request->item)->decrement('quantity_remaining',$request->quantity_sent);
        }
        session()->flash('message','The item was distributed successfully! <br>');
        return redirect()->back();

    }

    public function saveBulkdcDistribution(Request $request){

        $documentname = '';

        if($request->hasFile('documents'))
        {
            $file = $request->file('documents');
            $documentname = $file->getClientOriginalName();
            $file->move('uploads/', $documentname);
        }

        $state = facilities::where('id',$request->sent_to)->first()->state;
        $fromstate = facilities::where('id',$request->sent_from)->first()->state;

        foreach ($request->items as $key => $item) {
            dcdistributions::create([
                'item_id'=>$item,
                'quantity_sent'=>$request->quantity_sent[$key],
                'date_sent'=>$request->date_sent,
                'sent_from'=>$request->sent_from,
                'sent_to'=>$request->sent_to,
                'documents'=>$documentname,
                'remarks'=>$request->remarks,
                'batchno'=>$request->batchno,
                'sent_by'=>$request->sent_by,
                'received_by'=>$request->received_by,
                'sentto_state'=>$state,
                'sentfrom_state'=>$fromstate
            ]);

            if($fromstate=="WAREHOUSE"){
            dcstocks::where('item_id',$item)->decrement('quantity_remaining',$request->quantity_sent[$key]);
            }
        }
        session()->flash('message','The item(s) was sent successfully! <br>');
        return redirect()->route('dctools');

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


    public function newDCTReport(){
        $dctools = dctools::select('id','tool_name','category')->get();
        if(Auth()->user()->role=="DCTManager"){
            $facilities = facilities::select('id','facility_name','state')->where('state',Auth()->user()->state)->get();

        }if(Auth()->user()->role=="DCTUser"){
            $facilities = facilities::select('id','facility_name')->where('id',Auth()->user()->facility)->get();
        }
        else{
            $facilities = facilities::select('id','facility_name')->get();
        }

        return view('generate_dctreport',compact('dctools','facilities'));
    }

    public function generateDCTReport(Request $request){
        $from =$request->date_from;
        $to = $request->date_to;

        if($request->items[0]== "All" && $request->facilities[0]!= "All"){
            $utilization = dctoolutilizations::whereIn('facility_id', $request->facilities)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }elseif($request->facilities[0]== "All" && $request->items[0]!= "All"){
            $utilization = dctoolutilizations::whereIn('item_id', $request->items)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }elseif($request->facilities[0]== "All" && $request->items[0]== "All"){

            $utilization = dctoolutilizations::whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }elseif($request->items[0]== "OVC"){
            $dctoolss = dctools::select('id')->where('category','OVC')->get()->toArray();
            $utilization = dctoolutilizations::whereIn('item_id', $dctoolss)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }elseif($request->items[0]== "Paediatrics"){
            $dctoolss = dctools::select('id')->where('category','Paediatrics')->get()->toArray();
            $utilization = dctoolutilizations::whereIn('item_id', $dctoolss)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }elseif($request->items[0]== "Adult Treatment"){
            $dctoolss = dctools::select('id')->where('category','Adult Treatment')->get()->toArray();
            $utilization = dctoolutilizations::whereIn('item_id', $dctoolss)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }else{
        $utilization = dctoolutilizations::whereIn('item_id', $request->items)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->orWhereIn('facility_id', $request->facilities)->whereRaw('? BETWEEN dated_from AND dated_to', [$from, $to])->get();
        }

        // dd($utilization);
        return view('dct_report',compact('utilization','from','to'));
    }

    public function bulkToolAction(Request $request){
        if(Auth()->user()->role=="DCTManager"){
            $facilities = facilities::select('id','facility_name','state')->where('state',Auth()->user()->state)->get();

        }if(Auth()->user()->role=="DCTUser"){
            $facilities = facilities::select('id','facility_name')->where('id',Auth()->user()->facility)->get();
        }
        else{
            $facilities = facilities::select('id','facility_name')->get();
        }
        $dctools = dctools::select('id','tool_name','category')->whereIn('id',$request->tool_name)->get();

        if($request->action=="Distribution"){
            return view('new_dcdistribution_bulk',compact('dctools','facilities'));

        }else{
            $suppliers = supplier::select('supplier_name','company_name')->get();
            return view('new_bulkdctstock',compact('dctools','facilities','suppliers'));
        }


    }

    public function confirmDelivery(){

        if(Auth()->user()->role=="DCTManager"){
            $distribution = dcdistributions::where('sent_to',Auth()->user()->state)->get();

        }if(Auth()->user()->role=="DCTUser"){
            $distribution = dcdistributions::where('sent_to',Auth()->user()->facility)->get();
        }
        else{
            $distribution = dcdistributions::all();
        }
        return view('confirm-dctools',compact('distribution'));

    }

    public function saveConfirmation(Request $request){
        $this->validate($request, [
            'tool_name'=>'required'
        ],[
            'tool_name.required' => 'You must select at list one tool to confirm'
        ]);

        $documentname = '';

        if($request->hasFile('documents'))
        {
            $file = $request->file('documents');
            $documentname = $file->getClientOriginalName();
            $file->move('uploads/', $documentname);
        }

        foreach ($request->tool_name as $key => $item) {
            dcdistributions::where('id',$item)->update([
                'quantity_received' => $request->qty_recieved[$key],
                'rremarks' => $request->remarks,
                'rdocuments'=>$documentname,
             ]);

        }

        session()->flash('message','Item reciept confirmation has been saved!');
        return redirect()->route('confirm-delivery');
    }

}
