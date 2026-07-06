<?php

namespace App\Http\Controllers;

use App\inventory;
use App\items;
use App\facilities;
use App\department;
use App\unit;
use App\requests;
use App\concurrency;
use App\User;
use App\settings;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Display distribution form and list of previous distributions
     */
    public function index()
    {
        $itemList = items::select('id', 'item_name')->get();
        $states = inventory::select('state')->distinct()->pluck('state')->toArray();
        $facilities = facilities::select('id', 'facility_name', 'state')->get();
        $users = User::select('id', 'name', 'facility', 'state')->get();
        $requestList = requests::select('id', 'item_name')->get();

        // Get previous distributions
        $distributions = DB::table('concurrencies')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('distribution.index', compact(
            'itemList',
            'states',
            'facilities',
            'users',
            'requestList',
            'distributions'
        ));
    }

    /**
     * Store distribution data
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'items' => 'required|array',
                'items.*.item_id' => 'required|exists:items,id',
                'items.*.serial_number' => 'required|string',
                'items.*.tag_number' => 'required|string',
                'state' => 'required|string',
                'facility_id' => 'nullable|exists:facilities,id',
                'user_id' => 'nullable|exists:users,id',
                'sender_name' => 'required|string',
                'receiver_name' => 'required|string',
                'distribution_date' => 'required|date',
                'request_id' => 'nullable|exists:requests,id',
                'details' => 'nullable|string',
                'delivery_note' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png',
            ]);

            $distributionData = [];
            $filename = null;

            // Handle file upload
            if ($request->hasFile('delivery_note')) {
                $file = $request->file('delivery_note');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/distributions'), $filename);
            }

            DB::beginTransaction();

            foreach ($validated['items'] as $item) {
                // Find the inventory item by serial number and tag number
                $inventoryItem = inventory::where('serial_no', $item['serial_number'])
                    ->where('tag_no', $item['tag_number'])
                    ->first();

                if (!$inventoryItem) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Item not found with serial: ' . $item['serial_number'] . ' and tag: ' . $item['tag_number']
                    ], 404);
                }

                // Get item name
                $itemName = items::find($item['item_id'])->item_name ?? 'Unknown';

                // Create concurrency record (distribution record)
                $distribution = new concurrency();
                $distribution->state = $validated['state'];
                $distribution->location = optional(facilities::find($validated['facility_id']))->facility_name;
                $distribution->model = $itemName;
                $distribution->serial_number = $item['serial_number'];
                $distribution->tag_number = $item['tag_number'];
                $distribution->user = $validated['user_id'] ? optional(User::find($validated['user_id']))->name : null;
                $distribution->date_of_purchase = $inventoryItem->date_purchased;
                $distribution->category = $inventoryItem->category;
                $distribution->batch = $inventoryItem->remarks;
                $distribution->condition = $inventoryItem->status;
                $distribution->date_delivered = $validated['distribution_date'];
                $distribution->received_by = $validated['receiver_name'];
                $distribution->comments = 'Sender: ' . $validated['sender_name'] . ' | ' . ($validated['details'] ?? '');
                $distribution->other_info = 'Request ID: ' . ($validated['request_id'] ?? 'N/A') . ' | File: ' . ($filename ?? 'N/A');
                $distribution->save();

                // Update inventory record with distribution info
                $inventoryItem->assigned_to = $validated['receiver_name'];
                $inventoryItem->user_id = $validated['user_id'] ?? $inventoryItem->user_id;
                $inventoryItem->facility_id = $validated['facility_id'] ?? $inventoryItem->facility_id;
                $inventoryItem->save();

                $distributionData[] = [
                    'inventory_id' => $inventoryItem->id,
                    'concurrency_id' => $distribution->id,
                    'item_name' => $itemName
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($distributionData) . ' item(s) distributed successfully',
                'data' => $distributionData
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get distribution details
     */
    public function show($id)
    {
        $distribution = concurrency::find($id);

        if (!$distribution) {
            return response()->json([
                'success' => false,
                'message' => 'Distribution not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $distribution
        ]);
    }

    /**
     * Get facilities by state (AJAX)
     */
    public function getFacilitiesByState(Request $request)
    {
        $state = $request->state;
        $facilities = facilities::where('state', $state)
            ->select('id', 'facility_name')
            ->get();

        return response()->json($facilities);
    }

    /**
     * Get items by state and facility (AJAX)
     */
    public function getItemsByLocation(Request $request)
    {
        $query = inventory::query();

        if ($request->has('state') && $request->state) {
            $query->where('state', $request->state);
        }

        if ($request->has('facility_id') && $request->facility_id) {
            $query->where('facility_id', $request->facility_id);
        }

        $items = $query->select('id', 'item_name', 'serial_no', 'tag_no', 'status')
            ->with('facilities')
            ->get();

        return response()->json($items);
    }
}
