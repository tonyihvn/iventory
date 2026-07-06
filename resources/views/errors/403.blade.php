@extends('errors.layout')

@section('title', '403 - Access Denied')

@section('content')
    <div class="error-header">
        <div class="error-icon">🚫</div>
        <div class="error-code">403</div>
        <div class="error-title">Access Denied</div>
        <div class="error-subtitle">You don't have permission to access this resource</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            You don't have the necessary permissions to access this page or resource. If you believe you should have access, please contact your administrator.
        </div>

        <div class="error-details">
            <div class="detail-item">
                <div class="detail-label">What You Can Do:</div>
                <div class="detail-value" style="font-family: inherit; color: inherit; background: transparent; padding: 0;">
                    <ul style="margin-left: 20px;">
                        <li>Request access from your administrator</li>
                        <li>Return to the home page</li>
                        <li>Check if you're logged in with the correct account</li>
                        <li>Contact support for assistance</li>
                    </ul>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Requested Resource:</div>
                <div class="detail-value">{{ request()->path() }}</div>
            </div>

            @if(auth()->check())
                <div class="detail-item">
                    <div class="detail-label">Your Role:</div>
                    <div class="detail-value">{{ auth()->user()->role ?? 'User' }}</div>
                </div>
            @endif
        </div>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
            <a href="javascript:history.back();" class="btn btn-secondary">Go Back</a>
        </div>
    </div>
@endsection
