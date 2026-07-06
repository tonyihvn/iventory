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
use Illuminate\Support\Facades\Hash;


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

        // Allow an Admin to create a brand new user for the "From" / "To" fields
        // when they pick "Not Listed - Add New User" (value 0) in the dropdowns.
        $fromUser = $this->resolveMovementUser($request->from_user, $request->new_from_user, $request) ?? $request->from_user;
        $toUser = $this->resolveMovementUser($request->to_user, $request->new_to_user, $request) ?? $request->to_user;

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
            'to'=>$toUser,
            'from_user'=>$fromUser,
            'to_user'=>$toUser,
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
            'user_id'=>$toUser,
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
     * Create a new user on the fly (Admin only) when "Not Listed - Add New User"
     * is selected for the From/To user. Returns the new user id, the existing
     * selected id, or null when nothing usable was supplied.
     */
    private function resolveMovementUser($selectedId, $newName, Request $request)
    {
        // A real user was picked from the dropdown.
        if ($selectedId !== null && $selectedId !== '' && (string) $selectedId !== '0') {
            return $selectedId;
        }

        // "Not Listed" was chosen - only an Admin may create a new user.
        $newName = trim((string) $newName);
        if ($selectedId !== null && (string) $selectedId === '0' && $newName !== ''
            && Auth::check() && Auth::user()->role === 'Admin') {

            return User::create([
                'name'       => $newName,
                'email'      => strtolower(str_replace(' ', '', $newName)) . uniqid() . '@ihvnigeria.org',
                'password'   => Hash::make('nopassword'),
                'state'      => Auth::user()->state,
                'facility'   => $request->facility,
                'department' => $request->department,
                'unit'       => $request->unit,
                'role'       => 'User',
            ])->id;
        }

        return null;
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
