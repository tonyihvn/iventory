<?php

namespace App\Http\Controllers;

use App\category;
use App\audit;
use App\facilities;
use App\department;
use App\unit;
use Illuminate\Http\Request;
use App\User;
use Auth;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = category::orderBy('category_name', 'asc')->paginate(50);
        return view('categories',compact('categories'));
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
            'category_name' => 'required|min:2'
        ]);

        category::create([
            'category_name'=>$request->category_name,
            'description'=>$request->description
        ]);

        audit::create([
            'action'=>"Created New Category ".$request->category_name,
            'description'=>'A new category was created',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
        session()->flash('message','The New category: '.$request->category_name.' has been added successfully!');
        
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        category::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted category ".$request->id,
            'description'=>'A category was deleted',
            'doneby'=>"Admin" // Auth::user()->id           
        ]);
 
        session()->flash('message','The the selected category has been successfully deleted.');

        return redirect()->back();
    }

    // some user functions

    public function editUser(User $user,$id)
    {
        $user = User::where('id','=',$id)->first();
        $facilities = facilities::select('facility_name','id')->get();
        $departments = department::select('department_name','id')->get();
        $units = unit::select('unit_name','id')->get();
        return view('edit_user',compact('facilities'), ['departments'=>$departments,'units'=>$units,'user'=>$user]);
    }

    public function deleteUser(Request $request, $id)
    {
        User::findOrFail($id)->delete();

        audit::create([
            'action'=>"Deleted User ".$request->id,
            'description'=>'A USer was deleted',
            'doneby'=>Auth::user()->id           
        ]);
 
        session()->flash('message','The the selected user has been successfully deleted.');

        return redirect()->back();
    }

    public function updateUser(Request $request)
    {
        $user = User::findOrFail($request->id);

       
        $user->fill(\Request::all());
        $user->save();

        audit::create([
            'action'=>"Updated User ".$request->id,
            'description'=>'A USer was updated',
            'doneby'=>Auth::user()->id           
        ]);
 
        session()->flash('message','The the selected user has been successfully updated');

        return redirect()->back();
    }
}
