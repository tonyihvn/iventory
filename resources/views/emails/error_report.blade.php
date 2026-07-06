<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }
        .container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 20px auto;
        }
        .header {
            border-bottom: 3px solid #d32f2f;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h2 {
            color: #d32f2f;
            margin: 0;
        }
        .content {
            line-height: 1.6;
        }
        .info-box {
            background-color: #f9f9f9;
            border-left: 4px solid #d32f2f;
            padding: 12px;
            margin: 15px 0;
        }
        .info-box strong {
            color: #333;
            display: block;
            margin-bottom: 5px;
        }
        .info-box p {
            margin: 5px 0;
            color: #666;
            font-size: 13px;
        }
        .section-title {
            font-weight: bold;
            color: #333;
            margin-top: 20px;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
        .action-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #d32f2f;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            margin: 10px 0;
        }
        code {
            background-color: #f0f0f0;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
            color: #d32f2f;
        }
        .screenshot-section {
            margin-top: 20px;
            text-align: center;
        }
        .screenshot-section img {
            max-width: 100%;
            max-height: 300px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>🔴 Error Report from Inventory System</h2>
        </div>

        <div class="content">
            <p>A user has reported an error in the system. Please review the details below:</p>

            <div class="info-box">
                <strong>User Information:</strong>
                <p><strong>Name:</strong> {{ $user_name }}</p>
                <p><strong>Email:</strong> {{ $user_email }}</p>
                <p><strong>Time:</strong> {{ $timestamp->format('M d, Y H:i:s') }}</p>
            </div>

            <div class="section-title">What was the user trying to do?</div>
            <div class="info-box">
                <p>{{ $user_description }}</p>
            </div>

            <div class="section-title">Error Details</div>
            <div class="info-box">
                <strong>Error Message:</strong>
                <p><code>{{ $error_message }}</code></p>

                <strong style="display: block; margin-top: 10px;">Error Location:</strong>
                <p>File: <code>{{ $error_file }}</code></p>
                <p>Line: <code>{{ $error_line }}</code></p>

                <strong style="display: block; margin-top: 10px;">Page URL:</strong>
                <p><code>{{ $error_page }}</code></p>

                <strong style="display: block; margin-top: 10px;">User Agent:</strong>
                <p><code style="font-size: 11px;">{{ $user_agent }}</code></p>

                <strong style="display: block; margin-top: 10px;">IP Address:</strong>
                <p><code>{{ $ip_address }}</code></p>
            </div>

            @if($screenshot_path)
            <div class="section-title">Screenshot</div>
            <div class="screenshot-section">
                <p>User has provided a screenshot of the error:</p>
                <img src="{{ asset($screenshot_path) }}" alt="Error Screenshot">
                <p style="font-size: 12px; color: #999; margin-top: 10px;">
                    <a href="{{ url('/' . $screenshot_path) }}" target="_blank">View Full Size</a>
                </p>
            </div>
            @endif

            <div class="section-title">Recommended Actions</div>
            <ul style="color: #666;">
                <li>Review the error message and location</li>
                <li>Check the application logs for more details</li>
                <li>Test the affected feature to reproduce the issue</li>
                <li>Implement a fix and deploy</li>
                <li>Notify the user when the issue is resolved</li>
            </ul>

            <div style="text-align: center; margin-top: 30px;">
                <a href="{{ url('/error-reports') }}" class="action-button">View Error Reports Dashboard</a>
            </div>
        </div>

        <div class="footer">
            <p>This is an automated error report from the Inventory Management System</p>
            <p>Please do not reply to this email</p>
        </div>
    </div>
</body>
</html>
