@extends('errors.layout')

@section('title', '500 - Server Error')

@section('content')
    <div class="error-header">
        <div class="error-icon">⚠️</div>
        <div class="error-code">500</div>
        <div class="error-title">Server Error</div>
        <div class="error-subtitle">Something went wrong on our end</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            An unexpected error occurred while processing your request. Our team has been notified and we're working to resolve this issue.
        </div>

        <div class="error-details">
            <div class="detail-item">
                <div class="detail-label">What You Can Do:</div>
                <div class="detail-value" style="font-family: inherit; color: inherit; background: transparent; padding: 0;">
                    <ul style="margin-left: 20px;">
                        <li>Try refreshing the page</li>
                        <li>Clear your browser cache and cookies</li>
                        <li>Try again in a few moments</li>
                        <li>Contact support if the problem persists</li>
                    </ul>
                </div>
            </div>

            @if(config('app.debug'))
                <div class="detail-item">
                    <div class="debug-warning">
                        <strong>Debug Mode is Enabled:</strong> Error details are shown below for development purposes only.
                    </div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Error ID:</div>
                    <div class="detail-value">{{ request()->header('X-Request-ID', 'N/A') }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Timestamp:</div>
                    <div class="detail-value">{{ now() }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Request:</div>
                    <div class="detail-value">{{ request()->method() }} {{ request()->path() }}</div>
                </div>
            @endif

            <div class="detail-item">
                <div class="detail-label">Timestamp:</div>
                <div class="detail-value">{{ now() }}</div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
            <a href="javascript:history.back();" class="btn btn-secondary">Go Back</a>
        </div>
    </div>
@endsection
