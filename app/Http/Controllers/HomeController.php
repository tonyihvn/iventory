<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\inventory;
use App\audit;
use App\User;
use App\facilities;
use App\category;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if(auth()->user()->role=='Admin'){
            $usrs = User::select('id','name')->get()->toArray();
            $allcats = inventory::select('category', \DB::raw('COUNT(id) as quantity'))
            ->groupBy('category')
            ->get();

            $audits = audit::orderBy('created_at','desc')->paginate(10);

            $laptopscount = inventory ::select(DB::raw("count(*) as count"))
            ->where('category','Laptops')
            ->groupBy(DB::raw("state"))
            ->orderBy("state")
            ->get()->toArray();

            $laptops = array_column($laptopscount, 'count');

            $phonescount = inventory ::select(DB::raw("count(*) as count"))
            ->where('category','Phones')
            ->groupBy(DB::raw("state"))
            ->orderBy("state")
            ->get()->toArray();

            $phones = array_column($phonescount, 'count');

            $biometricscount = inventory ::select(DB::raw("count(*) as count"))
            ->where('category','Biometrics')
            ->groupBy(DB::raw("state"))
            ->orderBy("state")
            ->get()->toArray();

            $biometrics = array_column($biometricscount, 'count');
            $states = "'FCT','KATSINA','NASARAWA','RIVERS'";
            // dd($laptops);

            return view('dashboard')
            ->with('Laptops',json_encode($laptops,JSON_NUMERIC_CHECK))
            ->with('Phones',json_encode($phones,JSON_NUMERIC_CHECK))
            ->with('Biometrics',json_encode($biometrics,JSON_NUMERIC_CHECK))
            ->with(['allcats'=>$allcats,'audits'=>$audits, 'states'=>$states,'usrs'=>$usrs]);
        }else if(auth()->user()->role=='Manager'){
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get()->toArray();
            $allcats = inventory::select('category', \DB::raw('COUNT(id) as quantity'))->where('state',auth()->user()->state)
            ->groupBy('category')
            ->get();

            $audits = audit::orderBy('created_at','desc')->paginate(10);

            $laptopscount = inventory ::select(DB::raw("count(*) as count"))->where('state',auth()->user()->state)
            ->where('category','Laptops')
            ->groupBy(DB::raw("state"))
            ->orderBy("state")
            ->get()->toArray();

            $laptops = array_column($laptopscount, 'count');

            $phonescount = inventory ::select(DB::raw("count(*) as count"))->where('state',auth()->user()->state)
            ->where('category','Phones')
            ->groupBy(DB::raw("state"))
            ->orderBy("state")
            ->get()->toArray();

            $phones = array_column($phonescount, 'count');

            $biometricscount = inventory ::select(DB::raw("count(*) as count"))->where('state',auth()->user()->state)
            ->where('category','Biometrics')
            ->groupBy(DB::raw("state"))
            ->orderBy("state")
            ->get()->toArray();

            $biometrics = array_column($biometricscount, 'count');
            $states = "'".Auth()->user()->state."'";
            // dd($laptops);

            return view('dashboard')
            ->with('Laptops',json_encode($laptops,JSON_NUMERIC_CHECK))
            ->with('Phones',json_encode($phones,JSON_NUMERIC_CHECK))
            ->with('Biometrics',json_encode($biometrics,JSON_NUMERIC_CHECK))
            ->with(['allcats'=>$allcats,'audits'=>$audits,'states'=>$states,'usrs'=>$usrs]);
        }else if(auth()->user()->role=="Facility"){
            $categories = category::select('id','category_name')->get();
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->get();
            return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs]);
        }else{
            $categories = category::select('id','category_name')->get();
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
            return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs]);
        }
    }

    public function user_dashboard()
    {
        return redirect()->route('dashboard');
    }

}
