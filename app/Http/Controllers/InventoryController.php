<?php

namespace App\Http\Controllers;

use App\inventory;
use App\inventoryspec;
use App\facilities;
use App\department;
use App\unit;
use App\category;
use App\audit;
use App\User;
use App\files;
use App\requests;
use Auth;
use File;


use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $categories = category::select('id','category_name')->get();

        if(auth()->user()->role=="Admin"){
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','assigned_to','status')->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','assigned_to','status')->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else{
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories]);
    }

    public function categoryInventory($category)
    {
        $facilities = facilities::select('id','facility_name')->get();

        $categories = category::select('id','category_name')->get();

        if(auth()->user()->role=="Admin"){
            $inventories = inventory::where('category',$category)->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $inventories = inventory::where('category',$category)->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else{
            $inventories = inventory::where('category',$category)->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('inventories', compact('inventories'),['category'=>$category,'facilities'=>$facilities,'categories'=>$categories]);
    }

    public function facilityItems($fid)
    {
        $facilities = facilities::select('id','facility_name')->get();

        $categories = category::select('id','category_name')->get();

        $catg = facilities::select('facility_name')->where('id',$fid)->first()->facility_name;
        $category = $catg." Inventory";

        if(auth()->user()->role=="Admin"){
            $inventories = inventory::where('facility_id',$fid)->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $inventories = inventory::where('facility_id',$fid)->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else{
            $inventories = inventory::where('facility_id',$fid)->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('inventories', compact('inventories'),['category'=>$category,'facilities'=>$facilities,'categories'=>$categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $ihvn_no = "IHVN".substr(md5(uniqid(mt_rand(), true).microtime(true)),0, 8);
        if(Auth()->user()->role=="Admin"){
            $facilities = facilities::select('id','facility_name')->get();
            $users = User::select('id','name','facility','department','unit')->get();
        }else{
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $users = User::select('id','name','facility','department','unit')->where('state',auth()->user()->state)->get();
        }

        $departments = department::select('id','department_name')->get();
        $units = unit::select('id','unit_name')->get();
        $categories = category::select('id','category_name')->get();

        return view('new_item',compact('facilities'), ['departments'=>$departments,'units'=>$units,'users'=>$users,'categories'=>$categories]);
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
            'item_name' => 'required|min:3'
        ]);


        if($request->hasFile('file'))
        {
            $files = $request->file('file');

            if(count($request->file('file'))>1){
                $singlefile = "Multiple Files";
            }else{
                $singlefile = $files[0]->getClientOriginalName();
            }
        }else{
            $singlefile = "";
        }

        if($request->quantity_added>1){

            $serialno = ''; $tagno='';

            $ihvnno_arr = explode(',', $request->ihvn_no);
            $serial_no_arr = explode(',', $request->serial_no);
            $tag_no_arr = explode(',', $request->tag_no);

            foreach($ihvnno_arr as $key=>$ihvnno){
                if(isset($serial_no_arr[$key])){
                    $serialno=$serial_no_arr[$key];
                }
                if(isset($tag_no_arr[$key])){
                    $tagno=$tag_no_arr[$key];
                }
                $itemid = inventory::create([
                    'item_name'=>$request->item_name,
                    'serial_no'=>$serialno,
                    'ihvn_no'=>$ihvnno,
                    'tag_no'=>$tagno,
                    'description'=>$request->description,
                    'category'=>$request->category,
                    'type'=>$request->type,
                    'date_purchased'=>$request->date_purchased,
                    'supplier'=>$request->supplier,
                    'user_id'=>$request->user,
                    'quantity'=>$request->quantity,
                    'status'=>$request->status,
                    'state'=>$request->state,
                    'department_id'=>$request->department,
                    'unit_id'=>$request->unit,
                    'added_by'=>$request->added_by,
                    'facility_id'=>$request->facility,
                    'internal_location'=>$request->internal_location,
                    'remarks'=>$request->remarks,
                    'file'=>$singlefile
                ])->id;
            }
        }else{
            $itemid = inventory::create([
                'item_name'=>$request->item_name,
                'serial_no'=>$request->serial_no,
                'ihvn_no'=>$request->ihvn_no,
                'tag_no'=>$request->tag_no,
                'description'=>$request->description,
                'category'=>$request->category,
                'type'=>$request->type,
                'date_purchased'=>$request->date_purchased,
                'supplier'=>$request->supplier,
                'user_id'=>$request->user,
                'quantity'=>$request->quantity,
                'status'=>$request->status,
                'state'=>$request->state,
                'department_id'=>$request->department,
                'unit_id'=>$request->unit,
                'added_by'=>$request->added_by,
                'facility_id'=>$request->facility,
                'internal_location'=>$request->internal_location,
                'remarks'=>$request->remarks,
                'file'=>$singlefile
            ])->id;
        }

        if($request->hasFile('file'))
        {
            $files = $request->file('file');

            if(count($request->file('file'))>1){
                foreach ($files as $key => $file) {
                    $filename = $files[$key]->getClientOriginalName();
                   //  $file->store('users/' . $this->user->id . '/messages');

                   $file->move('uploads/'.$itemid, $filename);

                   files::create([
                    'filename'=>$filename,
                    'itemid'=>$itemid
                    ]);
                }
            }else{

                foreach ($files as $key => $file) {
                    $filename = $files[$key]->getClientOriginalName();
                   //  $file->store('users/' . $this->user->id . '/messages');

                   $file->move('uploads/'.$itemid, $filename);

                   files::create([
                    'filename'=>$filename,
                    'itemid'=>$itemid
                    ]);
                }
            }


        }

        $i = 0;
        if(isset($request->property)){
            foreach ($request->property as $pp){
            // RECORD SPECS

                inventoryspec::create([
                    'property'=>$pp,
                    'value'=>$request->value[$i],
                    'itemid'=>$itemid
                    ]);
                    $i++;
            }
        }


        audit::create([
            'action'=>"Added new inventory item",
            'description'=>'A new item was created',
            'doneby'=>Auth()->user()->id
        ]);
        session()->flash('message','The New Item has been added successfully!');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function show(inventory $inventory,$id)
    {
        $item = inventory::where('id','=',$id)->with('inventoryspec')->first();
        return view('print_item')->with(['item'=>$item]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function edit(inventory $inventory, $id)
    {
        $item = inventory::where('id','=',$id)->with('inventoryspec')->first();

        $facilities = facilities::select('id','facility_name')->get();
        $departments = department::select('id','department_name')->get();
        $units = unit::select('id','unit_name')->get();
        $users = User::select('id','name','facility','department','unit')->get();
        $categories = category::select('id','category_name')->get();


        return view('item',compact('item'), ['departments'=>$departments,'units'=>$units,'users'=>$users,'categories'=>$categories,'facilities'=>$facilities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, inventory $inventory)
    {
        $inventory = inventory::where('id','=', $request->id);
        $inventory->update([
            'item_name'=>$request->item_name,
            'serial_no'=>$request->serial_no,
            'ihvn_no'=>$request->ihvn_no,
            'tag_no'=>$request->tag_no,
            'category'=>$request->category,
            'type'=>$request->type,
            'date_purchased'=>$request->date_purchased,
            'supplier'=>$request->supplier,
            'user_id'=>$request->user,
            'quantity'=>$request->quantity,
            'status'=>$request->status,
            'state'=>$request->state,
            'department_id'=>$request->department,
            'unit_id'=>$request->unit,
            'facility_id'=>$request->facility,
            'internal_location'=>$request->internal_location,
            'remarks'=>$request->remarks
        ]);

        // Remove Inventory Specs and Recreate
        inventoryspec::where('itemid',$request->id)->delete();
        if(isset($request->property)){
            $i = 0;

            foreach ($request->property as $pp){
                // RECORD SALES
                inventoryspec::create([
                    'property'=>$pp,
                    'value'=>$request->value[$i],
                    'itemid'=>$request->id
                    ]);
                $i++;
            }
        }

        audit::create([
            'action'=>"Updated Item ".$request->item_name,
            'description'=>'An item was updated in the inventory',
            'doneby'=>"Admin" // Auth::user()->id
        ]);

        session()->flash('message','The Item : '.$request->facility_name.' has been updated successfully!');

        return redirect()->back();
    }

    public function fixItems(Request $request)
    {

        if(isset($request->facility)){
            $i = 0;


            foreach ($request->tid as $itemid){

                $it = inventory::where('id',$itemid)->first();
                // RECORD SALES
                $it->update([
                    'facility_id'=>$request->facility
                    ]);
                $i++;
            }
        }

        if(isset($request->category)){
            $i = 0;

            foreach ($request->tid as $itemid){
                $it = inventory::where('id',$itemid)->first();
                // RECORD SALES
                $it->update([
                    'category'=>$request->category
                    ]);
                $i++;
            }
        }

        if(isset($request->status)){
            $i = 0;

            foreach ($request->tid as $itemid){
                $it = inventory::where('id',$itemid)->first();
                // RECORD SALES
                $it->update([
                    'status'=>$request->status
                    ]);
                $i++;
            }
        }

        audit::create([
            'action'=>"Updated Items ".var_dump($request->tid),
            'description'=>'An item was updated in the inventory',
            'doneby'=>"Admin" // Auth::user()->id
        ]);

        session()->flash('message','The Item : '.var_dump($request->tid).' has been updated successfully!');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\inventory  $inventory
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        inventory::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted Item ".$request->id,
            'description'=>'An Item was deleted',
            'doneby'=>"Admin" // Auth::user()->id
        ]);

        session()->flash('message','The the selected item has been successfully deleted.');

        return redirect()->back();
    }

    public function request_destroy(Request $request)
    {
        requests::findOrFail($request->id)->delete();

        audit::create([
            'action'=>"Deleted Request Item ".$request->id,
            'description'=>'A Request Item was deleted',
            'doneby'=>Auth::user()->id
        ]);

        session()->flash('message','The the selected request  item has been successfully deleted.');

        return redirect()->route('requests');
    }

    public function reports()
    {
        // $inventories = inventory::with(['facilities'])->orderBy('item_name', 'asc')->paginate(100);
        $inventories = inventory::orderBy('item_name', 'asc')->paginate(100);
        $all_items = inventory::select('item_name', 'serial_no', 'facility_id', 'user_id')->get();
        return view('generate_report', compact('inventories'),['all_items'=>$all_items]);
    }


    public function requests()
    {
        $facilities = facilities::select('id','facility_name')->get();

        if(Auth::user()->role=="Admin"){
            $requests = requests::with('user')->paginate(50);
        }else if(Auth::user()->role=="Manager"){
            $requests = requests::with('user')->where('state',auth()->user()->state)->paginate(50);
        }else{
            $requests = requests::where('user_id', auth()->user()->id)->paginate(50)->with('users');
        }
        $users = User::select('id','name','facility','department','unit')->get();
        return view('requests',compact('requests'))->with(['users'=>$users,'facilities'=>$facilities]);
    }

    public function request($id)
    {

        $item = requests::where('id', $id)->first();
        return view('edit_request',compact('item'));
    }

    public function new_request(Request $request){
        $this->validate($request, [
            'item_name'=>'required|min:3'
        ]);

        requests::create([
            'item_name'=>$request->item_name,
            'quantity_requested'=>$request->quantity_requested,
            'user_id'=>$request->user_id,
            'location'=>$request->location,
            'state'=>$request->state,
            'request_status'=>'Sent',
            'request_reason'=>$request->request_reason,
            'comments'=>$request->comments,
            'remarks'=>'',
        ]);

        audit::create([
            'action'=>$request->item_name." Request From ".$request->state,
            'description'=>'A Request Item was deleted',
            'doneby'=>Auth::user()->id
        ]);

        session()->flash('message','Your '.$request->item_name.' request has been sent successfully!');
        return redirect()->back();

    }

    public function update_request(Request $request){
        $this->validate($request, [
            'id'=>'required'
        ]);

        $requested = requests::where('id',$request->id)->first();

        $requested->update([

            'request_status'=>$request->request_status,
            'remarks'=>$request->remarks
        ]);

        session()->flash('message','The request has been updated successfully!');
        return redirect()->back();

    }

    public function product_search(Request $request){

        $item = inventory::where('id','=',$request->keyword)->with('inventoryspec')->first();

        $facilities = facilities::select('id','facility_name')->get();
        $departments = department::select('id','department_name')->get();
        $units = unit::select('id','unit_name')->get();
        $users = User::select('id','name','facility','department','unit')->get();
        $categories = category::select('id','category_name')->get();


        return view('item',compact('item'), ['departments'=>$departments,'units'=>$units,'users'=>$users,'categories'=>$categories,'facilities'=>$facilities]);

    }


}
