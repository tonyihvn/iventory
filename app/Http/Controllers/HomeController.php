<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\inventory;
use App\audit;
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
        /*

        DB::table('usermetas')
                 ->select('browser', DB::raw('count(*) as total'))
                 ->groupBy('browser')
                 ->get();
        */

        if(auth()->user()->role=='Admin'){
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

            // dd($laptops);

            return view('dashboard')
            ->with('Laptops',json_encode($laptops,JSON_NUMERIC_CHECK))
            ->with('Phones',json_encode($phones,JSON_NUMERIC_CHECK))
            ->with('Biometrics',json_encode($biometrics,JSON_NUMERIC_CHECK))
            ->with(['allcats'=>$allcats,'audits'=>$audits]);
        }else{
            return redirect()->route('inventory');
        }

    }
    /*
    public function user_dashboard()
    {
        $inventories = inventory::where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->paginate(100);
        return view('user_dashboard', compact('inventories'));
    }
    */
}
