<?php

namespace App\Http\Controllers;

use App\inventory;
use App\facilities;
use App\department;
use App\unit;
use App\audit;
use App\User;
use App\movement;
use App\files;
use Auth;
use File;


use Illuminate\Http\Request;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $movements = movement::orderBy('date_moved', 'desc')->paginate(100);
        return view('movements', compact('movements'));
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
            'itemid' => 'required|min:1'
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

        movement::create([
            'inventories_id'=>$request->itemid,
            'from'=>$request->old,
            'to'=>$request->to_user,
            'from_user'=>$request->from_user,
            'to_user'=>$request->to_user,
            'reason'=>$request->reason,
            'user_id'=>$request->approved_by,
            'date_moved'=>$request->date_moved,
            'file'=>$singlefile
        ]);

       
        
        $itemid = $request->itemid;

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
        
        $inventory = inventory::where('id','=', $request->itemid);
        $inventory->update([
            'user_id'=>$request->to_user,
            'department_id'=>$request->department,
            'unit_id'=>$request->unit,
            'facility_id'=>$request->facility,
            'remarks'=>"Moved to .".$request->old
        ]);
        
        audit::create([
            'action'=>"A New Movement Transaction for Item: ".$request->itemid,
            'description'=>'From '.$request->old,
            'doneby'=>Auth::user()->id           
        ]);
        session()->flash('message','The Movement Transaction was successfully!');
        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show(movement $movement,$id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function edit(inventory $inventory, $id)
    {
        $item = inventory::where('id','=',$id)->first();
        
        $facilities = facilities::select('id','facility_name')->get();
        $departments = department::select('id','department_name')->get();
        $units = unit::select('id','unit_name')->get();
        $users = User::select('id','name','facility','department','unit')->get();
        
        return view('move_item',compact('item'), ['departments'=>$departments,'units'=>$units,'users'=>$users,'facilities'=>$facilities]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, movement $movement)
    {
        //    
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        movement::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted Movement Transaction ".$request->id,
            'description'=>'A movement traction was deleted',
            'doneby'=>Auth::user()->id           
        ]);
 
        session()->flash('message','The the selected item has been successfully deleted.');

        return redirect()->back();
    }
}
