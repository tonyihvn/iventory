@extends('errors.layout')

@section('title', '419 - Session Expired')

@section('content')
    <div class="error-header">
        <div class="error-icon">⏱️</div>
        <div class="error-code">419</div>
        <div class="error-title">Session Expired</div>
        <div class="error-subtitle">Your session has expired for security reasons</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            Your session has expired due to inactivity or a security refresh. This is a normal security measure to protect your account. Please log in again to continue.
        </div>

        <div class="error-details">
            <div class="detail-item">
                <div class="detail-label">What You Can Do:</div>
                <div class="detail-value" style="font-family: inherit; color: inherit; background: transparent; padding: 0;">
                    <ul style="margin-left: 20px;">
                        <li>Log in again with your credentials</li>
                        <li>Refresh the page and try your action again</li>
                        <li>Clear browser cookies and try logging in</li>
                        <li>Contact support if you continue having issues</li>
                    </ul>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Reason:</div>
                <div class="detail-value">CSRF Token Mismatch or Session Timeout</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Timestamp:</div>
                <div class="detail-value">{{ now() }}</div>
            </div>
        </div>

        <div class="actions">
            @if(auth()->check())
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Log Out & Log In Again</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary">Go to Login</a>
            @endif
            <a href="{{ url('/') }}" class="btn btn-secondary">Go to Home</a>
        </div>
    </div>
@endsection
