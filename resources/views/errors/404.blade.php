@extends('errors.layout')

@section('title', '404 - Page Not Found')

@section('content')
    <div class="error-header">
        <div class="error-icon">🔍</div>
        <div class="error-code">404</div>
        <div class="error-title">Page Not Found</div>
        <div class="error-subtitle">The page you're looking for doesn't exist</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            The requested resource was not found on this server. This could mean the page has been moved, deleted, or you may have entered an incorrect URL.
        </div>

        <div class="error-details">
            <div class="detail-item">
                <div class="detail-label">What You Can Do:</div>
                <div class="detail-value" style="font-family: inherit; color: inherit; background: transparent; padding: 0;">
                    <ul style="margin-left: 20px;">
                        <li>Check the URL for typos</li>
                        <li>Return to the home page and navigate from there</li>
                        <li>Use the search function to find what you're looking for</li>
                        <li>Contact support if you believe this is an error</li>
                    </ul>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Requested URL:</div>
                <div class="detail-value">{{ request()->fullUrl() }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Request Method:</div>
                <div class="detail-value">{{ request()->method() }}</div>
            </div>
        </div>

        <div class="actions">
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
            <a href="javascript:history.back();" class="btn btn-secondary">Go Back</a>
        </div>
    </div>
@endsection
