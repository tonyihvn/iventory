@extends('errors.layout')

@section('title', '422 - Validation Error')

@section('content')
    <div class="error-header">
        <div class="error-icon">✋</div>
        <div class="error-code">422</div>
        <div class="error-title">Validation Error</div>
        <div class="error-subtitle">The data you submitted could not be processed</div>
    </div>

    <div class="error-body">
        <div class="error-message">
            The form submission contains invalid data or is missing required fields. Please check your input and try again.
        </div>

        <div class="error-details">
            <div class="detail-item">
                <div class="detail-label">What Went Wrong:</div>
                <div class="detail-value" style="font-family: inherit; color: inherit; background: transparent; padding: 0;">
                    <ul style="margin-left: 20px;">
                        <li>One or more required fields are empty</li>
                        <li>Invalid data format was provided</li>
                        <li>File upload requirements not met</li>
                        <li>Data conflicts with existing records</li>
                    </ul>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Request Method:</div>
                <div class="detail-value">{{ request()->method() }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Endpoint:</div>
                <div class="detail-value">{{ request()->path() }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Timestamp:</div>
                <div class="detail-value">{{ now() }}</div>
            </div>
        </div>

        <div class="actions">
            <a href="javascript:history.back();" class="btn btn-primary">Go Back & Check</a>
            <a href="{{ url('/') }}" class="btn btn-secondary">Go to Home</a>
        </div>
    </div>
@endsection
