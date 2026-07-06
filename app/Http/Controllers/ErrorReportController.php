<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\audit;
use Illuminate\Support\Facades\Mail;
use Auth;
use DB;

class ErrorReportController extends Controller
{
    /**
     * Store error report
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_description' => 'required|string|max:1000',
                'error_message' => 'nullable|string',
                'error_page' => 'nullable|string',
                'error_file' => 'nullable|string',
                'error_line' => 'nullable|string',
                'screenshot_data' => 'nullable|string'
            ]);

            $screenshot_path = null;

            // Save screenshot if provided
            if ($request->has('screenshot_data') && $request->screenshot_data) {
                try {
                    // Decode base64 image
                    $image_data = $request->screenshot_data;
                    
                    // Remove data:image/png;base64, prefix
                    if (strpos($image_data, 'data:image') === 0) {
                        $image_data = substr($image_data, strpos($image_data, ',') + 1);
                    }
                    
                    $image = base64_decode($image_data);
                    $filename = 'error_screenshot_' . time() . '_' . uniqid() . '.png';
                    $path = public_path('uploads/error_reports');
                    
                    // Create directory if it doesn't exist
                    if (!file_exists($path)) {
                        mkdir($path, 0755, true);
                    }
                    
                    file_put_contents($path . '/' . $filename, $image);
                    $screenshot_path = 'uploads/error_reports/' . $filename;
                } catch (\Exception $e) {
                    // Continue without screenshot if saving fails
                    \Log::error('Error saving screenshot: ' . $e->getMessage());
                }
            }

            // Log the error report
            $report_data = [
                'user_id' => Auth::id() ?? null,
                'user_name' => Auth::user()->name ?? 'Guest',
                'user_email' => Auth::user()->email ?? 'unknown@example.com',
                'user_description' => $request->user_description,
                'error_message' => $request->error_message,
                'error_page' => $request->error_page,
                'error_file' => $request->error_file,
                'error_line' => $request->error_line,
                'screenshot_path' => $screenshot_path,
                'timestamp' => now(),
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip()
            ];

            // Store in database - create a simple log table entry
            DB::table('error_reports')->insertOrIgnore([
                'user_id' => $report_data['user_id'],
                'user_name' => $report_data['user_name'],
                'user_email' => $report_data['user_email'],
                'user_description' => $report_data['user_description'],
                'error_message' => $report_data['error_message'],
                'error_page' => $report_data['error_page'],
                'error_file' => $report_data['error_file'],
                'error_line' => $report_data['error_line'],
                'screenshot_path' => $screenshot_path,
                'user_agent' => $report_data['user_agent'],
                'ip_address' => $report_data['ip_address'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Send email to admins
            try {
                $admins = User::whereIn('role', ['Super', 'Admin'])->pluck('email')->toArray();
                
                if (!empty($admins)) {
                    Mail::send('emails.error_report', $report_data, function ($message) use ($admins) {
                        $message->to($admins)
                                ->subject('[ERROR REPORT] ' . substr($_SERVER['REQUEST_URI'] ?? 'Unknown', 0, 100));
                    });
                }
            } catch (\Exception $e) {
                \Log::error('Error sending error report email: ' . $e->getMessage());
            }

            // Log audit
            try {
                audit::create([
                    'action' => 'Error Report Submitted',
                    'details' => 'Page: ' . ($request->error_page ?? 'Unknown'),
                    'user_id' => Auth::id() ?? null
                ]);
            } catch (\Exception $e) {
                \Log::error('Error logging audit: ' . $e->getMessage());
            }

            return response()->json([
                'success' => true,
                'message' => 'Error report sent successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Error storing error report: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to send error report: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * View error reports (Admin only)
     */
    public function index()
    {
        if (auth()->check() && (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin')) {
            $reports = DB::table('error_reports')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('errors.reports_list', compact('reports'));
        }

        abort(403, 'Unauthorized');
    }

    /**
     * View single error report (Admin only)
     */
    public function show($id)
    {
        if (auth()->check() && (auth()->user()->role == 'Super' || auth()->user()->role == 'Admin')) {
            $report = DB::table('error_reports')->find($id);

            if (!$report) {
                abort(404, 'Report not found');
            }

            return view('errors.report_detail', compact('report'));
        }

        abort(403, 'Unauthorized');
    }
}
