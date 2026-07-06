@extends('template')

@section('content')
<div class="container">
    <div class="row" style="margin-top: 40px;">
        <div class="col s12 m8 offset-m2">
            <div class="card red lighten-5">
                <div class="card-content">
                    <span class="card-title red-text">
                        <i class="material-icons left">error_outline</i> Oops! Something Went Wrong
                    </span>
                    
                    <div style="margin-top: 20px;">
                        <h5>Error Details</h5>
                        <div class="error-details card white" style="padding: 15px; margin: 10px 0;">
                            <p><strong>Error Message:</strong></p>
                            <p style="color: #d32f2f; font-family: monospace;">{{ $exception->getMessage() ?? 'Unknown Error' }}</p>
                            
                            <p style="margin-top: 15px;"><strong>Current Page:</strong></p>
                            <p style="font-family: monospace;">{{ $_SERVER['REQUEST_URI'] ?? 'Unknown' }}</p>
                        </div>
                    </div>

                    <div style="margin-top: 30px;">
                        <h5>Report This Error to Admin</h5>
                        <p style="color: #666;">Help us fix this issue by reporting it. You can take a screenshot and describe what you were doing.</p>

                        <form id="errorReportForm" style="margin-top: 20px;">
                            @csrf
                            
                            <!-- Hidden error data -->
                            <input type="hidden" id="errorMessage" name="error_message" value="{{ $exception->getMessage() ?? 'Unknown Error' }}">
                            <input type="hidden" id="errorPage" name="error_page" value="{{ $_SERVER['REQUEST_URI'] ?? '' }}">
                            <input type="hidden" id="errorFile" name="error_file" value="{{ $exception->getFile() ?? '' }}">
                            <input type="hidden" id="errorLine" name="error_line" value="{{ $exception->getLine() ?? '' }}">
                            <input type="hidden" id="screenshotData" name="screenshot_data">

                            <div class="row">
                                <div class="input-field col s12">
                                    <textarea id="userDescription" name="user_description" class="materialize-textarea" placeholder="Describe what you were trying to do..."></textarea>
                                    <label for="userDescription">What were you trying to do? *</label>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <div class="card-panel grey lighten-4" style="padding: 15px;">
                                        <p style="margin: 0 0 10px 0;"><strong>Screenshot (Optional)</strong></p>
                                        <p style="font-size: 12px; color: #666; margin: 0 0 10px 0;">Help us understand the issue better by including a screenshot.</p>
                                        
                                        <button type="button" id="captureScreenBtn" class="btn blue">
                                            <i class="material-icons left">screenshot_monitor</i> Capture Screenshot
                                        </button>
                                        
                                        <div id="screenshotPreview" style="margin-top: 15px; display: none;">
                                            <p><strong>Screenshot Preview:</strong></p>
                                            <img id="screenshotImg" style="max-width: 100%; max-height: 300px; border: 1px solid #ddd; border-radius: 4px;">
                                            <button type="button" id="removeScreenBtn" class="btn red" style="margin-top: 10px;">
                                                <i class="material-icons left">delete</i> Remove Screenshot
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col s12">
                                    <button type="submit" class="btn green">
                                        <i class="material-icons left">send</i> Send Report to Admin
                                    </button>
                                    <a href="javascript:window.history.back();" class="btn grey" style="margin-left: 10px;">
                                        <i class="material-icons left">arrow_back</i> Go Back
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    M.AutoInit();

    let screenshotCaptured = false;

    // Capture screenshot
    document.getElementById('captureScreenBtn').addEventListener('click', function(e) {
        e.preventDefault();
        
        M.toast({html: 'Capturing screenshot...'});
        
        // Capture only the main content, not the error report form
        const contentArea = document.querySelector('.error-details') || document.body;
        html2canvas(contentArea, {
            allowTaint: true,
            useCORS: true,
            scale: 0.8,
            backgroundColor: '#ffffff',
            logging: false
        }).then(canvas => {
            const imageData = canvas.toDataURL('image/png');
            document.getElementById('screenshotData').value = imageData;
            document.getElementById('screenshotImg').src = imageData;
            document.getElementById('screenshotPreview').style.display = 'block';
            screenshotCaptured = true;
            M.toast({html: 'Screenshot captured successfully!'});
            
            // Attach listener to remove button after it's visible
            attachRemoveScreenListener();
        }).catch(error => {
            console.error('Error capturing screenshot:', error);
            M.toast({html: 'Error capturing screenshot. Please try again. ' + error.message});
        });
    });

    // Function to attach remove screenshot listener
    function attachRemoveScreenListener() {
        const removeBtn = document.getElementById('removeScreenBtn');
        if (removeBtn) {
            removeBtn.onclick = function(e) {
                e.preventDefault();
                document.getElementById('screenshotData').value = '';
                document.getElementById('screenshotPreview').style.display = 'none';
                screenshotCaptured = false;
                M.toast({html: 'Screenshot removed'});
            };
        }
    }

    // Submit form
    document.getElementById('errorReportForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const description = document.getElementById('userDescription').value.trim();

        if (!description) {
            M.toast({html: 'Please describe what you were trying to do'});
            return;
        }

        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="material-icons left">hourglass_empty</i> Sending...';

        const formData = new FormData(this);

        fetch('/error-report', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                M.toast({html: 'Error report sent to admin. Thank you!'});
                setTimeout(() => {
                    window.location.href = '/';
                }, 2000);
            } else {
                M.toast({html: 'Error: ' + (data.message || 'Failed to send report')});
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="material-icons left">send</i> Send Report to Admin';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            M.toast({html: 'Error sending report. Please try again.'});
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="material-icons left">send</i> Send Report to Admin';
        });
    });
});
</script>

<style>
    .error-details {
        border-left: 4px solid #d32f2f;
    }

    .card-title {
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .btn {
        border-radius: 4px;
    }
</style>
@endsection
