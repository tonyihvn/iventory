<?php

namespace App\Http\Controllers;

use App\files;
use App\items;
use App\facilities;
use App\User;
use Auth;
use Illuminate\Http\Request;

class FileManagementController extends Controller
{
    /**
     * Display file management page
     */
    public function index()
    {
        $itemList = items::select('id', 'item_name')->get();
        $states = facilities::select('state')->distinct()->pluck('state')->toArray();
        $facilities = facilities::select('id', 'facility_name', 'state')->get();
        $categories = [
            'Document',
            'Image',
            'Video',
            'Audio',
            'Spreadsheet',
            'Presentation',
            'PDF',
            'Maintenance Record',
            'Invoice',
            'Report',
            'Other'
        ];

        // Get uploaded files
        $uploadedFiles = files::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('file_management.index', compact(
            'itemList',
            'states',
            'facilities',
            'categories',
            'uploadedFiles'
        ));
    }

    /**
     * Store uploaded file
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'category' => 'required|string',
                'details' => 'required|string',
                'state' => 'required|string',
                'facility_id' => 'nullable|exists:facilities,id',
                'item_id' => 'nullable|exists:items,id',
                'file' => 'required|file|max:50000', // 50MB max
            ]);

            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->move(public_path('uploads/files'), $filename);

                $fileRecord = new files();
                $fileRecord->category = $validated['category'];
                $fileRecord->details = $validated['details'];
                $fileRecord->state = $validated['state'];
                $fileRecord->facility_id = $validated['facility_id'] ?? null;
                $fileRecord->item_id = $validated['item_id'] ?? null;
                $fileRecord->user_id = Auth::id();
                $fileRecord->file_name = $filename;
                $fileRecord->file_size = $file->getSize();
                $fileRecord->file_type = $file->getMimeType();
                $fileRecord->original_name = $file->getClientOriginalName();
                $fileRecord->save();

                return response()->json([
                    'success' => true,
                    'message' => 'File uploaded successfully',
                    'file' => $fileRecord
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No file provided'
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download file
     */
    public function download($id)
    {
        $file = files::find($id);

        if (!$file) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        $filePath = public_path('uploads/files/' . $file->file_name);

        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File does not exist'
            ], 404);
        }

        return response()->download($filePath, $file->original_name);
    }

    /**
     * Delete file
     */
    public function delete($id)
    {
        try {
            $file = files::find($id);

            if (!$file) {
                return response()->json([
                    'success' => false,
                    'message' => 'File not found'
                ], 404);
            }

            $filePath = public_path('uploads/files/' . $file->file_name);

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file->delete();

            return response()->json([
                'success' => true,
                'message' => 'File deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
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
}
