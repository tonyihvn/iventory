@extends('errors.layout')

@section('title', '{{ $exception->getCode() ?? 500 }} - {{ class_basename($exception) }}')

@section('content')
    <div class="error-header">
        <div class="error-icon">🐛</div>
        <div class="error-code">{{ $exception->getCode() ?? 500 }}</div>
        <div class="error-title">{{ class_basename($exception) }}</div>
        <div class="error-subtitle">Developer Debug Information</div>
    </div>

    <div class="error-body">
        <div class="debug-warning">
            ⚠️ <strong>Debug Mode Enabled:</strong> This detailed error information is only visible because APP_DEBUG is enabled. This will be hidden in production.
        </div>

        <!-- Tabs -->
        <div class="tabs">
            <button class="tab-button active" onclick="switchTab(event, 'message')">Message</button>
            <button class="tab-button" onclick="switchTab(event, 'stacktrace')">Stack Trace</button>
            <button class="tab-button" onclick="switchTab(event, 'context')">Context</button>
            <button class="tab-button" onclick="switchTab(event, 'request')">Request</button>
        </div>

        <!-- Message Tab -->
        <div id="message" class="tab-content active">
            <div class="error-message">
                {{ $exception->getMessage() ?: 'No error message provided' }}
            </div>

            <div class="error-details">
                <div class="detail-item">
                    <div class="detail-label">Exception Class:</div>
                    <div class="detail-value">{{ get_class($exception) }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">File:</div>
                    <div class="detail-value">{{ $exception->getFile() }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Line:</div>
                    <div class="detail-value">{{ $exception->getLine() }}</div>
                </div>

                @if(method_exists($exception, 'getStatusCode'))
                    <div class="detail-item">
                        <div class="detail-label">HTTP Status:</div>
                        <div class="detail-value">{{ $exception->getStatusCode() }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Stack Trace Tab -->
        <div id="stacktrace" class="tab-content">
            <div class="detail-label" style="margin-bottom: 15px;">Call Stack (Most Recent First):</div>
            <div class="stack-trace">
                @forelse($exception->getTrace() as $frame)
                    <div class="stack-frame">
                        <div class="stack-frame-file">
                            {{ isset($frame['file']) ? str_replace(base_path(), '', $frame['file']) : 'Unknown' }}
                            <span class="stack-frame-line">
                                @if(isset($frame['line']))
                                    :{{ $frame['line'] }}
                                @endif
                            </span>
                        </div>
                        <div class="stack-frame-function">
                            @if(isset($frame['class']))
                                {{ $frame['class'] }}{{ $frame['type'] ?? '' }}
                            @endif
                            {{ $frame['function'] ?? 'unknown' }}
                            @if(isset($frame['args']))
                                ({{ count($frame['args']) }} argument{{ count($frame['args']) !== 1 ? 's' : '' }})
                            @endif
                        </div>
                    </div>
                @empty
                    <div style="color: #999;">No stack trace available</div>
                @endforelse
            </div>
        </div>

        <!-- Context Tab -->
        <div id="context" class="tab-content">
            <div class="error-details">
                @if(method_exists($exception, 'context') && $exception->context())
                    @forelse($exception->context() as $key => $value)
                        <div class="detail-item">
                            <div class="detail-label">{{ $key }}:</div>
                            <div class="detail-value">
                                @if(is_array($value) || is_object($value))
                                    <pre>{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                @else
                                    {{ $value }}
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="color: #999;">No context information available</div>
                    @endforelse
                @else
                    <div style="color: #999;">No context information available</div>
                @endif
            </div>
        </div>

        <!-- Request Tab -->
        <div id="request" class="tab-content">
            <div class="error-details">
                <div class="detail-item">
                    <div class="detail-label">URL:</div>
                    <div class="detail-value">{{ request()->fullUrl() }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">Method:</div>
                    <div class="detail-value">{{ request()->method() }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">IP Address:</div>
                    <div class="detail-value">{{ request()->ip() }}</div>
                </div>

                <div class="detail-item">
                    <div class="detail-label">User Agent:</div>
                    <div class="detail-value">{{ request()->userAgent() }}</div>
                </div>

                @if(request()->has('query') && !empty(request()->query()))
                    <div class="detail-item">
                        <div class="detail-label">Query Parameters:</div>
                        <div class="detail-value">
                            <pre>{{ json_encode(request()->query(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    </div>
                @endif

                @if(request()->isMethod('POST') && !empty(request()->except(['password', 'password_confirmation', '_token'])))
                    <div class="detail-item">
                        <div class="detail-label">Post Data (Sensitive fields hidden):</div>
                        <div class="detail-value">
                            <pre>{{ json_encode(request()->except(['password', 'password_confirmation', '_token']), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>
                    </div>
                @endif

                @if(auth()->check())
                    <div class="detail-item">
                        <div class="detail-label">Authenticated User:</div>
                        <div class="detail-value">{{ auth()->user()->name ?? 'Unknown' }} (ID: {{ auth()->user()->id }})</div>
                    </div>
                @endif
            </div>
        </div>

        <div class="actions" style="margin-top: 40px;">
            <a href="{{ url('/') }}" class="btn btn-primary">Go to Home</a>
            <a href="javascript:history.back();" class="btn btn-secondary">Go Back</a>
        </div>
    </div>

    <script>
        function switchTab(evt, tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.remove('active'));

            // Remove active class from all buttons
            const buttons = document.querySelectorAll('.tab-button');
            buttons.forEach(button => button.classList.remove('active'));

            // Show the selected tab
            document.getElementById(tabName).classList.add('active');
            evt.currentTarget.classList.add('active');
        }
    </script>
@endsection
