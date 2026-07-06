@extends('errors.layout')

@section('title', '{{ $code }} - Error')

@section('content')
    <div class="error-header">
        <div class="error-icon">⚠️</div>
        <div class="error-code">{{ $code }}</div>
        <div class="error-title">{{ $title ?? 'An Error Occurred' }}</div>
        <div class="error-subtitle">{{ $message ?? 'Something went wrong' }}</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            An error occurred while processing your request. Please try again or contact support if the problem persists.
        </div>

        <div class="error-details">
            <div class="detail-item">
                <div class="detail-label">Error Code:</div>
                <div class="detail-value">{{ $code }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Request:</div>
                <div class="detail-value">{{ request()->method() }} {{ request()->path() }}</div>
            </div>

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
