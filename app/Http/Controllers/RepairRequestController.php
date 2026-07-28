<?php

namespace App\Http\Controllers;

use App\RepairRequest;
use App\inventory;
use App\items;
use App\facilities;
use App\User;
use App\settings;
use App\concurrency;
use Auth;
use Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepairRequestController extends Controller
{
    /**
     * Display the repair request form and list
     */
    public function index()
    {
        $itemList = items::select('id', 'item_name')->get();
        $facilities = facilities::select('id', 'facility_name', 'state')->orderBy('facility_name')->get();

        // Determine whether the current user is restricted to a single state
        $user = Auth::user();
        $userState = optional($user)->state;
        $isAdmin = $user && in_array($user->role, ['Admin', 'Super']);

        if (!$isAdmin && $userState && strtolower($userState) !== 'all') {
            $facilities = $facilities->where('state', $userState)->values();
            $states = [$userState];
        } else {
            // Build the state list from facilities so the state -> facility cascade always matches
            $states = $facilities->pluck('state')
                ->filter()
                ->unique()
                ->sort()
                ->values()
                ->toArray();
        }

        // Source serial/tag numbers from the inventory table (the canonical inventory data)
        $serialQuery = inventory::whereNotNull('serial_no')->where('serial_no', '!=', '');
        $tagQuery = inventory::whereNotNull('tag_no')->where('tag_no', '!=', '');

        // Only restrict by state for non-admin users that belong to a specific state
        if (!$isAdmin && $userState && strtolower($userState) !== 'all') {
            $serialQuery->where('state', $userState);
            $tagQuery->where('state', $userState);
        }

        $serialNumbers = $serialQuery->distinct()->orderBy('serial_no')->pluck('serial_no')->toArray();
        $tagNumbers = $tagQuery->distinct()->orderBy('tag_no')->pluck('tag_no')->toArray();

        // Get previous repair requests - filter by user role
        $query = RepairRequest::with('item', 'facility', 'user');
        
        // Apply role-based filtering
        if (in_array($user->role, ['Admin', 'Super', 'Observer', 'IT'])) {
            // Admin, Super, Observer, IT see all requests
            $query = $query;
        } else {
            // Other users (Manager, Facility, User) see only their own requests
            $query = $query->where('user_id', $user->id);
        }
        
        $repairRequests = $query->orderBy('created_at', 'desc')->paginate(50);

        return view('repair_requests.index', compact(
            'itemList',
            'states',
            'facilities',
            'repairRequests',
            'serialNumbers',
            'tagNumbers',
            'isAdmin',
            'userState'
        ));
    }

    /**
     * Delete repair request
     */
    public function destroy($id)
    {
        $repairRequest = RepairRequest::findOrFail($id);
        
        // Only Admin and Super can delete
        if (!Auth::user() || !in_array(Auth::user()->role, ['Admin', 'Super'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $repairRequest->delete();
        return response()->json(['success' => true, 'message' => 'Repair request deleted successfully']);
    }

    /**
     * Store repair request
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            $userState = optional($user)->state;
            $isAdmin = $user && in_array($user->role, ['Admin', 'Super']);

            // Get top-level state and facility (shared across all requests)
            $topLevelState = $request->input('state');
            $topLevelFacilityId = $request->input('facility_id');

            $validated = $request->validate([
                'requests' => 'required|array',
                'requests.*.item_id' => 'nullable|exists:items,id',
                'requests.*.serial_number' => 'nullable|string',
                'requests.*.tag_number' => 'nullable|string',
                'requests.*.description' => 'nullable|string',
                'requests.*.status' => 'nullable|in:Need Repairs,Not Operational,Lost,Irreparable',
                'state' => 'nullable|string',
                'facility_id' => 'nullable|exists:facilities,id',
            ]);

            DB::beginTransaction();

            $processedRequests = [];

            foreach ($validated['requests'] as $reqData) {
                // Use top-level state/facility if not provided in individual request
                $state = !empty($reqData['state']) ? $reqData['state'] : $topLevelState;
                $facilityId = !empty($reqData['facility_id']) ? $reqData['facility_id'] : $topLevelFacilityId;
                
                if (!$isAdmin && (!$state || strtolower($state) === 'all')) {
                    DB::rollBack();
                    // Check if this is a form submission (not AJAX)
                    if (!$request->expectsJson()) {
                        return redirect()->back()->with('error', 'Sender state is required for non-admin users.');
                    }
                    return response()->json([
                        'success' => false,
                        'message' => 'Sender state is required for non-admin users.'
                    ], 422);
                }

                $serialNumber = $reqData['serial_number'] ?? null;
                $tagNumber = $reqData['tag_number'] ?? null;
                $itemId = $reqData['item_id'] ?? null;
                $status = $reqData['status'] ?? 'Need Repairs';
                $description = $reqData['description'] ?? '';

                // If both serial and tag numbers are provided
                if ($serialNumber && $tagNumber) {
                    // Look for existing inventory item with these serial/tag numbers
                    $inventoryItem = inventory::where('serial_no', $serialNumber)
                        ->where('tag_no', $tagNumber)
                        ->first();

                    // If item doesn't exist, create it in inventories and concurrencies tables
                    if (!$inventoryItem) {
                        // Get facility name if facility_id is provided
                        $facility = $facilityId ? facilities::find($facilityId) : null;
                        $facilityName = $facility ? $facility->facility_name : 'Unknown Facility';

                        // Create new inventory record
                        $inventoryItem = new inventory();
                        $inventoryItem->item_id = $itemId; // Link to items table if available
                        $inventoryItem->item_name = $itemId ? items::find($itemId)->item_name : 'Item with Serial: ' . $serialNumber;
                        $inventoryItem->serial_no = $serialNumber;
                        $inventoryItem->tag_no = $tagNumber;
                        $inventoryItem->state = $state;
                        $inventoryItem->facility = $facilityName;
                        $inventoryItem->facility_id = $facilityId;
                        $inventoryItem->status = $status;
                        $inventoryItem->remarks = $description;
                        $inventoryItem->category = 'Equipment'; // Default category
                        $inventoryItem->department_id = 1; // Default department (adjust as needed)
                        $inventoryItem->unit_id = 1; // Default unit (adjust as needed)
                        $inventoryItem->user_id = Auth::id();
                        $inventoryItem->added_by = Auth::user()->name;
                        $inventoryItem->save();

                        // Create corresponding concurrency record
                        $concurrencyRecord = new concurrency();
                        $concurrencyRecord->state = $state;
                        $concurrencyRecord->location = $facilityName;
                        $concurrencyRecord->serial_number = $serialNumber;
                        $concurrencyRecord->tag_number = $tagNumber;
                        $concurrencyRecord->condition = $status;
                        $concurrencyRecord->comments = $description;
                        $concurrencyRecord->save();
                    } else {
                        // Update existing inventory item status
                        $inventoryItem->status = $status;
                        $inventoryItem->remarks = $description;
                        $inventoryItem->save();
                    }
                } else if (!$itemId && ($serialNumber || $tagNumber)) {
                    // If only serial OR tag is provided (not both), we cannot create/link to inventory
                    // But we can still create the repair request with the provided data
                }

                // Create repair request
                $repairRequest = new RepairRequest();
                $repairRequest->state = $state;
                $repairRequest->facility_id = $facilityId ?? null;
                $repairRequest->item_id = $itemId ?? null;
                $repairRequest->serial_number = $serialNumber ?? '';
                $repairRequest->tag_number = $tagNumber ?? '';
                $repairRequest->description = $description;
                $repairRequest->status = $status;
                $repairRequest->user_id = Auth::id();
                $repairRequest->save();

                $processedRequests[] = $repairRequest;
            }

            DB::commit();

            // Send email to admin
            $this->sendAdminNotification($processedRequests);

            // Check if this is a form submission (not AJAX)
            if (!$request->expectsJson()) {
                return redirect()->route('repair-requests.index')->with('success', count($processedRequests) . ' repair request(s) submitted successfully');
            }

            return response()->json([
                'success' => true,
                'message' => count($processedRequests) . ' repair request(s) submitted successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            if (!$request->expectsJson()) {
                return redirect()->back()->withErrors($e->errors())->withInput();
            }
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log the detailed error for debugging
            \Log::error('Repair Request Store Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Check if this is a form submission (not AJAX)
            if (!$request->expectsJson()) {
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notification email to admin
     */
    private function sendAdminNotification($repairRequests)
    {
        try {
            $adminEmail = config('app.admin_email', env('ADMIN_EMAIL'));
            
            if (!$adminEmail) {
                \Log::warning('ADMIN_EMAIL not configured in .env');
                return;
            }

            $itemList = '';
            foreach ($repairRequests as $req) {
                $itemList .= "- " . $req->item->item_name . " (Serial: " . $req->serial_number . ", Tag: " . $req->tag_number . ")\n";
            }

            $message = "
New Repair/Maintenance Request(s) Submitted

Submitted By: " . Auth::user()->name . "
Number of Items: " . count($repairRequests) . "
Submitted At: " . now() . "

Items:
" . $itemList . "

Please review and approve these requests in the system.

Thank you.
            ";

            Mail::raw($message, function ($mail) use ($adminEmail) {
                $mail->to($adminEmail)
                    ->subject('New Repair/Maintenance Request(s)');
            });

        } catch (\Exception $e) {
            \Log::error('Failed to send repair request notification email: ' . $e->getMessage());
        }
    }

    /**
     * Get facilities by state
     */
    public function getFacilitiesByState(Request $request)
    {
        $state = $request->state;
        $facs = facilities::where('state', $state)
            ->select('id', 'facility_name')
            ->get();

        return response()->json($facs);
    }

    /**
     * Get items by state and facility
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

        $invItems = $query->select('id', 'item_name', 'serial_no', 'tag_no', 'status')
            ->with('items')
            ->get();

        return response()->json($invItems);
    }

    /**
     * Update repair request status (admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $repairRequest = RepairRequest::find($id);

        if (!$repairRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Repair request not found'
            ], 404);
        }

        $repairRequest->admin_status = $request->admin_status;
        $repairRequest->admin_remarks = $request->admin_remarks;
        $repairRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    /**
     * Get repair request details
     */
    public function show($id)
    {
        $repairRequest = RepairRequest::with('item', 'facility', 'user')->find($id);

        if (!$repairRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Repair request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'id' => $repairRequest->id,
            'item_id' => $repairRequest->item_id,
            'facility_id' => $repairRequest->facility_id,
            'user_id' => $repairRequest->user_id,
            'serial_number' => $repairRequest->serial_number,
            'tag_number' => $repairRequest->tag_number,
            'description' => $repairRequest->description,
            'status' => $repairRequest->status,
            'state' => $repairRequest->state,
            'admin_status' => $repairRequest->admin_status,
            'admin_remarks' => $repairRequest->admin_remarks,
            'it_review' => $repairRequest->it_review,
            'estimated_cost' => $repairRequest->estimated_cost,
            'created_at' => $repairRequest->created_at
        ]);
    }

    /**
     * Update repair request with IT review (IT role)
     */
    public function updateITReview(Request $request, $id)
    {
        $user = Auth::user();

        // Allow IT staff as well as Admin/Super to submit reviews (matches the
        // roles the "IT Review" button is displayed to in the view).
        if (!$user || !in_array($user->role, ['Super', 'Admin', 'IT'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only IT staff can submit reviews.'
            ], 403);
        }

        $repairRequest = RepairRequest::find($id);

        if (!$repairRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Repair request not found'
            ], 404);
        }

        $validated = $request->validate([
            'it_review' => 'required|string',
            'estimated_cost' => 'required|numeric|min:0'
        ]);

        $repairRequest->it_review = $validated['it_review'];
        $repairRequest->estimated_cost = $validated['estimated_cost'];
        $repairRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'IT review submitted successfully'
        ]);
    }
}
