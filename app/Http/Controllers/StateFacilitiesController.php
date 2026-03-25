<?php

namespace App\Http\Controllers;

use App\facilities;
use Illuminate\Http\Request;

class StateFacilitiesController extends Controller
{
    /**
     * Display facilities for a specific state with DataTables pagination
     *
     * @param  string  $state
     * @return \Illuminate\Http\Response
     */
    public function index($state)
    {
        $state = urldecode($state);
        
        return view('state-facilities', ['state' => $state]);
    }

    /**
     * Get facilities data for DataTables (JSON format)
     *
     * @param  Request  $request
     * @param  string   $state
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFacilitiesData(Request $request, $state)
    {
        $state = urldecode($state);
        
        $facilities = facilities::where('state', $state)
                                ->orderBy('facility_name', 'asc')
                                ->get();

        // Prepare data for DataTables
        $data = [];
        foreach ($facilities as $facility) {
            $deleteUrl = route('facilities.destroy', $facility->id);
            $viewUrl = url('/facility/' . $facility->id);
            $inventoryUrl = url('/facilityitems/' . $facility->id);
            
            $actions = "<div class='fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action' style='position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important'>
                <a class='btn-floating btn-small dark-purple waves-effect waves-light' style='display: inline-block'>
                    <i class='small material-icons'>menu</i>
                </a>
                <ul style='top: 0px !important'>
                    <li>
                        <form method='POST' action='{$deleteUrl}' style='display: inline;'>
                            " . csrf_field() . "
                            <input type='hidden' name='_method' value='DELETE'>
                            <button onclick=\"return confirm('Are you sure you want to delete this facility?')\" class='btn-floating btn-small waves-effect red waves-light tooltipped' data-position='top' data-tooltip='Delete this Item'>
                                <i class='material-icons'>delete</i>
                            </button>
                        </form>
                    </li>
                    <li>
                        <a href='{$viewUrl}' class='btn-floating btn-small waves-effect green waves-light tooltipped' data-position='top' data-tooltip='View/Update this Facility' target='_blank'>
                            <i class='material-icons'>remove_red_eye</i>
                        </a>
                    </li>
                    <li>
                        <a href='{$inventoryUrl}' class='btn-floating btn-small waves-effect blue waves-light tooltipped' data-position='top' data-tooltip='Facility Inventory' target='_blank'>
                            <i class='material-icons'>list</i>
                        </a>
                    </li>
                </ul>
            </div>";

            $data[] = [
                'facility_name' => $facility->facility_name,
                'facility_no' => $facility->facility_no,
                'state' => $facility->state,
                'lga' => $facility->lga,
                'town' => $facility->town,
                'phone_number' => $facility->phone_number,
                'actions' => $actions,
                'id' => $facility->id
            ];
        }

        return response()->json([
            'data' => $data,
            'recordsTotal' => count($data),
            'recordsFiltered' => count($data)
        ]);
    }

    /**
     * Get unique states for the state list
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public static function getUniqueStates()
    {
        $states = facilities::select('state')
                            ->distinct()
                            ->orderBy('state', 'asc')
                            ->pluck('state');

        return $states;
    }
}
