<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\inventory;
use App\audit;
use App\User;
use App\facilities;
use App\category;
use App\dctools;
use App\items;
use App\multifacilities;
use App\concurrency;
use App\stocks;
use App\dcstocks;
use App\requests;
use Auth;
use DB;
use Illuminate\Support\Facades\Log;

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
        $items = items::select('id','item_name')->get();
        $otherfacilities = multifacilities::select('facility_id')->where('user_id',Auth::id())->get()->toArray();
        $assignedFacilities = facilities::whereIn('id',$otherfacilities)->get();
        $selectedFacility = Auth::user()->facility;

        if(auth()->user()->role=='Admin' || auth()->user()->role=='Observer'){
            $requests = requests::where('request_status','!=','Delivered')->orderBy('created_at','DESC')->with('user')->paginate(5);
            $usrs = User::select('id','name')->get();
            $inv_stock = stocks::select('item_id', 'quantity_remaining')->paginate(20);
            $dc_stocks = dcstocks::select('item_id', 'quantity_remaining')->paginate(20);
            $allcats = inventory::select('category', \DB::raw('COUNT(id) as quantity'))
            ->groupBy('category')
            ->get();

            $audits = audit::orderBy('created_at','desc')->paginate(20);

           $statelist = inventory::select('state')->distinct()->orderBy("state")->pluck('state')->toArray();
            $states = "'" . implode("','", $statelist) . "'";

            // Step 1: Get all distinct states (sorted)
$statelist = Inventory::select('state')
    ->distinct()
    ->orderBy('state')
    ->pluck('state')
    ->toArray();

// Step 2: Define categories you want to count
$categories = ['Laptops', 'Phones', 'Biometrics', 'Desktop Computers', 'Vehicles'];

// Step 3: Fetch all counts grouped by state and category
$rawCounts = Inventory::select('state', 'category', DB::raw('COUNT(*) as count'))
    ->whereIn('category', $categories)
    ->groupBy('state', 'category')
    ->get();

// Step 4: Organize results into a lookup array like: $counts[state][category] = number
$counts = [];
foreach ($rawCounts as $row) {
    $counts[$row->state][$row->category] = $row->count;
}

// Step 5: Build aligned arrays with zeros where missing
$laptops = [];
$phones = [];
$biometrics = [];
$desktops = [];
$vehicles = [];

foreach ($statelist as $state) {
    $laptops[]    = $counts[$state]['Laptops'] ?? 0;
    $phones[]     = $counts[$state]['Phones'] ?? 0;
    $biometrics[] = $counts[$state]['Biometrics'] ?? 0;
    $desktops[]   = $counts[$state]['Desktop Computers'] ?? 0;
    $vehicles[]   = $counts[$state]['Vehicles'] ?? 0;
}

            // dd($states);
        
            return view('dashboard')
            ->with('Laptops',json_encode($laptops,JSON_NUMERIC_CHECK))
            ->with('Phones',json_encode($phones,JSON_NUMERIC_CHECK))
            ->with('Biometrics',json_encode($biometrics,JSON_NUMERIC_CHECK))
            ->with('Desktops',json_encode($desktops,JSON_NUMERIC_CHECK))
            ->with('Vehicles',json_encode($vehicles,JSON_NUMERIC_CHECK))
            ->with(['allcats'=>$allcats,'audits'=>$audits, 'states'=>$states,'usrs'=>$usrs])
            ->with(['requests'=>$requests])
            ->with(['inv_stock'=>$inv_stock])
            ->with(['dc_stocks'=>$dc_stocks])
            ->with(['statelist'=>$statelist]);

        }elseif(auth()->user()->role=='Manager'){
            $requests = requests::with('user')->where('state',auth()->user()->state)->where('request_status','!=','Delivered')->paginate(50);
            $usrs = User::select('id','name')->where('state',auth()->user()->state)->get();

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
            ->with('Vehicles',json_encode($vehicles,JSON_NUMERIC_CHECK))
            ->with(['allcats'=>$allcats,'audits'=>$audits,'states'=>$states,'usrs'=>$usrs])->with(['requests'=>$requests]);
        }
        else if(auth()->user()->role=="Facility"){
            $categories = category::select('id','category_name')->get();
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->get();
            return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);
        }
        else if(auth()->user()->role=="User"){
            $categories = category::select('id','category_name')->get();
            $usrs = User::select('id','name')->where('facility',auth()->user()->facility)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('facility_id',auth()->user()->facility)->orderBy('item_name', 'asc')->get();
            return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);
        }else if(auth()->user()->role=="DCTAdmin" || auth()->user()->role=="DCTManager" || auth()->user()->role=="DCTUser"){
            $dctools = dctools::with('distributions')->get();

            return view('dctools',compact('dctools','assignedFacilities','selectedFacility'));
        }else{
            $categories = category::select('id','category_name')->get();
            $usrs = User::select('id','name')->where('id',auth()->user()->id)->get();
            $facilities = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $inventories = inventory::select('id','state','item_name','serial_no','ihvn_no','tag_no','category','facility','facility_id','user_id','assigned_to','status')->where('user_id',auth()->user()->id)->orderBy('item_name', 'asc')->get();
            return view('inventories', compact('inventories'), ['facilities'=>$facilities,'categories'=>$categories,'usrs'=>$usrs,'items'=>$items]);
        }
    }


    public function concurrency(){

        $state = User::select('state')->where('state',Auth::user()->state)->first()->state;
        $models = concurrency::select('model')->distinct('model')->get();
        if(Auth::user()->role=="Admin"){
            $locations = facilities::select('id','facility_name')->get();
            $users = User::select('id','name')->get();
            $assets = concurrency::all();
        }elseif(Auth::user()->role=="Manager"){
            $locations = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $users = User::select('id','name')->where('state',auth()->user()->state)->get();
            $assets = concurrency::where('state',$state)->get();
        }elseif(Auth::user()->role=="Facility"){
            $locations = null;
            $assets = concurrency::where('location',Auth::user()->facilityName->facility_name)->get();
            $users = User::select('id','name')->where('state',auth()->user()->state)->get();
        }else{
            $locations = null;
            $assets = null;
            $state = null;
            $users = null;

        }
        return view('concurrency', compact('assets','state','locations','models','users'));
    }

    public function concurrencyUpdate(Request $request)
    {
        $assets = $request->input('assets');
        Log::info("Submitted Data:",$assets);
        foreach ($assets as $assetData) {
            $asset = concurrency::find($assetData['id']);
            if (!empty($asset)) {
                // Update each column present in the row
                foreach ($assetData as $column => $value) {
                    if ($column !== 'id') {
                        $asset->$column = $value;
                    }
                    if ($column == 'date_of_purchase') {
                        $asset->$column = date("Y-m-d",strtotime($value));
                    }
                }
                $asset->save();
            }else{
                if(!is_numeric($assetData['id'])){
                    $assetData['id'] = 0;
                }
                if (Auth::user()->role!=="Admin"){
                    $assetData['state'] = Auth::user()->state;
                }
                $newAsset = concurrency::updateOrCreate(['id'=>$assetData['id']],['other_info'=>'New','state'=>$assetData['state']]);

                // Update each column present in the row
                foreach ($assetData as $column => $value) {
                    if ($column !== 'id') {

                        $newAsset->$column = $value;
                    }


                    if ($column == 'date_of_purchase') {
                        $newAsset->$column = date("Y-m-d",strtotime($value));
                    }


                }
                $newAsset->save();
            }
        }

        audit::create([
            'action'=>"Concurrency update saved",
            'description'=>'Updates on: '.json_encode($assets),
            'doneby'=>Auth::user()->id
        ]);

        return response()->json(['success' => true]);

    }

    public function user_dashboard()
    {
        return redirect()->route('dashboard');
    }

}
