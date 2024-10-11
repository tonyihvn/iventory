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
            $requests = requests::where('request_status','!=','Delivered')->with('user')->paginate(50);
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
            ->with(['allcats'=>$allcats,'audits'=>$audits, 'states'=>$states,'usrs'=>$usrs])
            ->with(['requests'=>$requests]);
        }elseif(auth()->user()->role=='Manager'){
            $requests = requests::with('user')->where('state',auth()->user()->state)->where('request_status','!=','Delivered')->paginate(50);
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
            $assets = concurrency::all();
        }elseif(Auth::user()->role=="Manager"){
            $locations = facilities::select('id','facility_name')->where('state',auth()->user()->state)->get();
            $assets = concurrency::where('state',$state)->get();
        }elseif(Auth::user()->role=="Facility"){
            $locations = null;
            $assets = concurrency::where('location',Auth::user()->facilityName->facility_name)->get();
        }else{
            $locations = null;
            $assets = null;
            $state = null;
        }
        return view('concurrency', compact('assets','state','locations','models'));
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
                $newAsset = concurrency::updateOrCreate(['id'=>$assetData['id']],['other_info'=>'New']);

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
        return response()->json(['success' => true]);

    }

    // public function user_dashboard()
    // {
    //     return redirect()->route('dashboard');
    // }

}
