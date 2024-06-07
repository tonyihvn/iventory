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
use App\supplies;
use App\stocks;
use App\requests;
use App\dctools;
use App\items;
use App\multifacilities;

use Auth;
use File;
use DB;
use Illuminate\Support\Facades\Hash;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;

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
        $items = items::select('id','item_name')->get();

        if(auth()->user()->role=="Admin" || auth()->user()->role=='Observer'){
            $usrs = User::select('id','name')->get();
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Facility"){

            // Step 1: Get the list of facility IDs
            $facilityIds = multifacilities::pluck('facility_id')->toArray();

            // Step 2: Add a custom facility ID to the list
            $customFacilityId = auth()->user()->facility; // Replace with your custom facility ID
            $facilityIds[] = $customFacilityId;

            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->whereIn('facility_id',$facilityIds)->orderBy('item_name', 'asc')->get();
        }else{
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);
    }

    public function dataQuality()
    {

        $categories = category::select('id','category_name')->get();
        $items = items::select('id','item_name')->get();

        if(auth()->user()->role=="Admin"){
            $usrs = User::select('id','name')->get();
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Facility"){
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->get();
        }else{
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('data-quality', compact('inventories'), ['facilities'=>$facilities,'usrs'=>$usrs,'items'=>$items]);
    }

    public function categoryInventory($category)
    {
        $categories = category::select('id','category_name')->get();
        $items = items::select('id','item_name')->get();
        if(auth()->user()->role=="Admin" || auth()->user()->role=='Observer'){
            $usrs = User::select('id','name')->get();
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::where('category',$category)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::where('category',$category)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Facility"){
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::where('category',$category)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->get();
        }else{
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::where('category',$category)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);
    }

    public function userItems($userid)
    {
        $categories = category::select('id','category_name')->get();
        $items = items::select('id','item_name')->get();

        $theuser = User::select('name','id')->where('id',$userid)->first();
        $uname = trim($theuser->name);
            $usrs = User::select('id','name')->get();
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',$userid)

            ->orWhere('assigned_to','like','%' . $uname . '%')->orderBy('item_name', 'asc')->get();

            // ->orWhere(function ($query) use ($uname) {
            //     $query->where('assigned_to', '=', $uname);
            // })
            // ->get();

            session()->flash('message',$uname."'s Items");
        return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);
    }

    public function facilityItems($fid)
    {

        $categories = category::select('id','category_name')->get();
        $items = items::select('id','item_name')->get();
        $catg = facilities::select('facility_name')->where('id',$fid)->first()->facility_name;
        $category = $catg." Inventory";

        if(auth()->user()->role=="Admin" || auth()->user()->role=='Observer'){
            $usrs = User::select('id','name')->get();
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::where('facility_id',$fid)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Manager"){
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::where('facility_id',$fid)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->get();
        }else if(auth()->user()->role=="Facility"){
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::where('facility_id',$fid)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->get();
        }else{
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::where('facility_id',$fid)->select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
        }
        return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'category'=>$category,'usrs'=>$usrs,'items'=>$items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $items = items::select('id','item_name')->get();
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

        return view('new_item',compact('facilities'), ['departments'=>$departments,'units'=>$units,'users'=>$users,'categories'=>$categories,'items'=>$items]);
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
        $new_user = explode(',', $request->new_user);
        $userid = array();

        if($request->user[0]==0){
            foreach($new_user as $key=>$newuser){
                $user = User::create([
                    'name' => $newuser,
                    'email'=> strtolower(str_replace(' ','',$newuser)."@ihvnigeria.org"),
                    'password' => Hash::make('nopassword'),
                    'state'=>$request->state,
                    'department'=>$request->department,
                    'unit'=>$request->unit,
                    'facility'=>$request->facility,
                    'role' => 'User'
                ])->id;

                array_push($userid,$user);
            }
        }else{

            foreach($request->user as $key=>$newuser){

                array_push($userid,$newuser);
            }

        }



        if($request->quantity_added>1){

            $serialno = ''; $tagno='';
            if($request->ihvn_no==""){
                $ihvnno_arr = array_fill(0, $request->quantity_added, 'NA');
            }else{
               $ihvnno_arr = explode(',', $request->ihvn_no);
            }

            $serial_no_arr = explode(',', $request->serial_no);
            $tag_no_arr = explode(',', $request->tag_no);


            foreach($ihvnno_arr as $key=>$ihvnno){
                if(isset($serial_no_arr[$key])){
                    $serialno=$serial_no_arr[$key];
                }
                if(isset($tag_no_arr[$key])){
                    $tagno=$tag_no_arr[$key];
                }

                if(count($userid)>1){
                    $user_id=$userid[$key];
                }else{
                    $user_id=$userid[0];
                }

                $itemid = inventory::create([
                    'item_id'=>$request->item_id,
                    'item_name'=>$request->item_name,
                    'serial_no'=>$serialno,
                    'ihvn_no'=>$ihvnno,
                    'tag_no'=>$tagno,
                    'description'=>$request->description,
                    'category'=>$request->category,
                    'type'=>$request->type,
                    'date_purchased'=>$request->date_purchased,
                    'supplier'=>$request->supplier,
                    'user_id'=>$user_id,
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
                'item_id'=>$request->item_id,
                'item_name'=>$request->item_name,
                'serial_no'=>$request->serial_no,
                'ihvn_no'=>$request->ihvn_no,
                'tag_no'=>$request->tag_no,
                'description'=>$request->description,
                'category'=>$request->category,
                'type'=>$request->type,
                'date_purchased'=>$request->date_purchased,
                'supplier'=>$request->supplier,
                'user_id'=>$userid[0],
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
            'action'=>"Added new inventory item". $itemid,
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
        $items = items::select('id','item_name')->get();
        $facilities = facilities::select('id','facility_name')->get();
        $departments = department::select('id','department_name')->get();
        $units = unit::select('id','unit_name')->get();
        $users = User::select('id','name','facility','department','unit')->get();
        $categories = category::select('id','category_name')->get();


        return view('item',compact('item'), ['departments'=>$departments,'units'=>$units,'users'=>$users,'categories'=>$categories,'facilities'=>$facilities,'items'=>$items]);
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

        if($request->user==0){
            $userid = User::create([
                'name' => $request->new_user,
                'email'=> str_replace(' ','',$request->new_user)."@ihvnigeria.org",
                'password' => Hash::make('nopassword'),
                'state'=>$request->state,
                'department'=>$request->department,
                'unit'=>$request->unit,
                'facility'=>$request->facility,
                'role' => 'User'
            ])->id;
        }else{
            $userid = $request->user;
        }

        $inventory->update([
            'item_id'=>$request->item_id,
            'item_name'=>$request->item_name,
            'serial_no'=>$request->serial_no,
            'ihvn_no'=>$request->ihvn_no,
            'tag_no'=>$request->tag_no,
            'category'=>$request->category,
            'type'=>$request->type,
            'date_purchased'=>$request->date_purchased,
            'supplier'=>$request->supplier,
            'user_id'=>$userid,
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

        if(isset($request->item_id)){
            $i = 0;
            $item = items::where('id',$request->item_id)->first();

            foreach ($request->tid as $itemid){
                $it = inventory::where('id',$itemid)->first();
                // RECORD SALES
                $it->update([
                    'item_name'=>$item->item_name,
                    'item_id'=>$request->item_id
                    ]);
                $i++;
            }
        }

        if(isset($request->delete_items)){
            inventory::whereIn('id',$request->tid)->delete();
        }

        audit::create([
            'action'=>"Updated Items ".var_dump($request->tid),
            'description'=>'An item was updated in the inventory',
            'doneby'=>Auth()->user()->id
        ]);

        session()->flash('message','The Item(s) has been updated successfully!');

        return redirect()->back();
    }

    public function uItems(){
        $items = items::all();
        return view('new_uitem',compact('items'));
    }

    public function editUItems($uid){
        $item = items::where('id',$uid)->first();
        $items = items::all();
        return view('edit_uitem',compact('items','item'));
    }

    public function deleteuitem($uid){
        items::where('id',$uid)->delete();
        return redirect()->back()->with(['message'=>'Item deleted successfully']);
    }


    public function newuItem(Request $request){
        items::updateOrCreate([

            'id'   => $request->id
        ],[
            'item_name'=>$request->item_name,
            'specifications'=>$request->specifications
        ]);

        return redirect()->back()->with(['message'=>'Item saved successfully']);
    }

    public function updateInventory(Request $request){

        $changedIHVNNo = '';

        foreach ($request->tid as $key => $item) {


            $inv = inventory::where('id',$item)->first();

            if($inv->ihvn_no!=$request->sihvn_no[$key] && $request->sihvn_no[$key]!=''){
                $changedIHVNNo = ' ::: IHVN Tag Changed from '. $request->sihvn_no[$key].' ::: ';
            }

            $inv->update([
                'ihvn_no' => $request->sihvn_no[$key],
                'tag_no' => $request->stag_no[$key],
                'serial_no'=>$request->sserial_no[$key],
                'facility_id'=>$request->sfacility_id[$key],
                'user_id'=>$request->suser_id[$key],
                'status'=>$request->sstatus[$key],
                'remarks' => DB::raw("CONCAT(`remarks`,'$changedIHVNNo')")
             ]);

        }

        session()->flash('message','The selected items have been updated and saved!');
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
            'doneby'=>Auth()->user()->id
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

    public function updateTagnumbers()
    {
        return view('update_tag');
    }

    public function updateTags(Request $request)
    {
        if(isset($request->oldtag)){
            $i = 0;

            foreach ($request->oldtag as $pp){
                // RECORD SALES
                inventory::where('ihvn_no',$pp)->update([
                    'ihvn_no'=>$request->newtag[$i],
                    'remarks' => DB::raw("CONCAT('Old Tag Changed: $pp', `remarks`)")
                    ]);
                $i++;
            }
        }

        $message = "New Tags updated successfully";

        return redirect()->back()->with(['message'=>$message]);
    }


    public function requests()
    {
        $facilities = facilities::select('id','facility_name')->get();
        $dctools = dctools::select('tool_name')->get();
        if(Auth::user()->role=="Admin"){
            $requests = requests::with('user')->paginate(50);
        }elseif(Auth::user()->role=="DCTAdmin"){
            $requests = requests::where('type','DCT Tools')->with('user')->paginate(50);
        }elseif(Auth::user()->role=="Manager"){
            $requests = requests::with('user')->where('state',auth()->user()->state)->paginate(50);
        }else{
            $requests = requests::where('user_id', auth()->user()->id)->with('user')->paginate(50);
        }
        $users = User::select('id','name','facility','department','unit')->get();
        return view('requests',compact('requests','dctools'))->with(['users'=>$users,'facilities'=>$facilities]);
    }

    public function request($id)
    {

        $item = requests::where('id', $id)->first();
        return view('edit_request',compact('item'));
    }

    public function new_request(Request $request){

        $item = "";
        $quantity = "";

        if($request->type=="Gadgets"){
            $item = $request->quantity_requested;
            $quantity = $request->quantity_requested;
        }else{
            foreach($request->item as $key=>$dctool){
                $item.=$dctool." Qty: ".$request->quantity[$key].", ::: ";
            }
        }

        requests::create([
            'item_name'=>$item,
            'quantity_requested'=>$quantity,
            'user_id'=>Auth()->user()->id,
            'location'=>$request->location,
            'state'=>Auth()->user()->state,
            'request_status'=>'Sent',
            'request_reason'=>$request->request_reason,
            'comments'=>$request->comments,
            'remarks'=>'',
            'type'=>$request->type
        ]);

        audit::create([
            'action'=>$request->item_name." Request From ".$request->state,
            'description'=>'A Request Item was deleted',
            'doneby'=>Auth()->user()->id
        ]);

        try{
        // SEND E-MAILS
        $reqState = Auth()->user()->state;
        $stateInvPOCEmails = User::select('email')->where('role','DCTManager')->where('state',$reqState)->get()->toArray();

            $AdminEmails = ['sdabban@ihvnigeria.org', 'mokposo@ihvnigeria.org', 'anwokoma@ihvnigeria.org'];
            $emails = array_merge($stateInvPOCEmails,$AdminEmails);

            foreach ($emails as $email) {
                Mail::to($email)->send(new SendEmail(Auth()->user()->name,$request->request_reason));
            }

        }//catch exception
        catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }

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

    public function item_search(Request $request){
        $items = items::select('id','item_name')->get();

        $categories = category::select('id','category_name')->get();

        if(auth()->user()->role=="Admin" || auth()->user()->role=='Observer'){
            $usrs = User::select('id','name')->get();
            $facilities = facilities::select('id','facility_name')->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->orderBy('item_name', 'asc')->where('ihvn_no', 'like', '%' . $request->keyword . '%')->orWhere('item_name', 'like', '%' . $request->keyword . '%')->orWhere('serial_no', 'like', '%' . $request->keyword . '%')->with('currentUser')->get();
        }else if(auth()->user()->role=="Manager"){
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get();

            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('state',auth()->user()->state)->orderBy('item_name', 'asc')->where('ihvn_no', 'like', '%' . $request->keyword . '%')->orWhere('item_name', 'like', '%' . $request->keyword . '%')->orWhere('serial_no', 'like', '%' . $request->keyword . '%')->get();
        }else if(auth()->user()->role=="Facility"){
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();

            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->where('ihvn_no', 'like', '%' . $request->keyword . '%')->orWhere('item_name', 'like', '%' . $request->keyword . '%')->orWhere('serial_no', 'like', '%' . $request->keyword . '%')->get();
        }else{
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();

            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->where('ihvn_no', 'like', '%' . $request->keyword . '%')->orWhere('item_name', 'like', '%' . $request->keyword . '%')->orWhere('serial_no', 'like', '%' . $request->keyword . '%')->get();
        }
        return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);

    }

    public function addStock()
    {
        $items = items::select('id','item_name')->get();
        return view('new_stock',compact('items'));
    }


    public function newSupply(Request $request){
        $this->validate($request, [
            'item'=>'required'
        ]);

        supplies::create([
            'item_id'=>$request->item,
            'quantity_supplied'=>$request->quantity_supplied,
            'date_supplied'=>$request->date_supplied,
            'supplied_to'=>$request->supplied_to,
            'supplier'=>$request->supplier,
            'batchno'=>$request->batchno,
            'remarks'=>$request->remarks
        ]);

        $itemstock = stocks::where('item_id',$request->item)->first();
        if(isset($itemstock)){
            $itemstock->increment('quantity_remaining',$request->quantity_supplied);
        }else{
            stocks::create([
                'item_id'=>$request->item,
                'quantity_remaining'=>$request->quantity_supplied
            ]);
        }

        session()->flash('message','The item supplied has been added to stock!');
        return redirect()->back();

    }

    public function addMoreFacilities(Request $request){

        foreach($request->facilities as $facility){
            if($facility!=""){
                $requested = multifacilities::create([
                    'user_id'=>$request->user_id,
                    'facility_id'=>$facility
                ]);
            }
        }


        session()->flash('message','You have successfull added more facilities to the selected user!');
        return redirect()->back();

    }



}
