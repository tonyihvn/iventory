@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons">folder</i> File Management
                </span>

                <!-- File Upload Form -->
                <div class="section">
                    <h5>Upload New File</h5>
                    
                    <form id="fileForm">
                        @csrf

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select id="category" name="category" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                                <label for="category">Category</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <select id="state" name="state" required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}">{{ $state }}</option>
                                    @endforeach
                                </select>
                                <label for="state">State</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select id="facility" name="facility_id">
                                    <option value="">Select Facility (Optional)</option>
                                    @foreach($facilities as $fac)
                                        <option value="{{ $fac->id }}" data-state="{{ $fac->state }}">{{ $fac->facility_name }}</option>
                                    @endforeach
                                </select>
                                <label for="facility">Facility</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <select id="item" name="item_id">
                                    <option value="">Select Item (Optional)</option>
                                    @foreach($itemList as $item)
                                        <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                    @endforeach
                                </select>
                                <label for="item">Item</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <textarea id="details" name="details" class="materialize-textarea" placeholder="Enter file details, description..." required></textarea>
                                <label for="details">File Details</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="file-field input-field col s12">
                                <div class="btn teal">
                                    <span>Select File</span>
                                    <input type="file" id="fileInput" name="file" required>
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text" placeholder="Choose a file (Max 50MB)">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn green">
                                    <i class="material-icons left">cloud_upload</i> Upload File
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <!-- Files List -->
                <div class="section">
                    <h5>Uploaded Files</h5>

                    <div class="table-responsive">
                        <table class="striped bordered hover">
                            <thead class="teal white-text">
                                <tr>
                                    <th>File Name</th>
                                    <th>Category</th>
                                    <th>Details</th>
                                    <th>State</th>
                                    <th>Facility</th>
                                    <th>Item</th>
                                    <th>Uploaded By</th>
                                    <th>Size</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($uploadedFiles as $file)
                                    <tr>
                                        <td>{{ $file->original_name }}</td>
                                        <td>
                                            <span class="badge badge-category">{{ $file->category }}</span>
                                        </td>
                                        <td>{{ substr($file->details, 0, 30) }}...</td>
                                        <td>{{ $file->state }}</td>
                                        <td>{{ optional($file->facility)->facility_name ?? '-' }}</td>
                                        <td>{{ optional($file->item)->item_name ?? '-' }}</td>
                                        <td>{{ optional($file->user)->name ?? 'N/A' }}</td>
                                        <td>{{ round($file->file_size / 1024 / 1024, 2) }} MB</td>
                                        <td>{{ $file->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <a href="/file-management/{{ $file->id }}/download" class="btn-small blue" title="Download">
                                                <i class="material-icons">download</i>
                                            </a>
                                            @if(Auth::user()->id == $file->user_id || Auth::user()->role == 'Super' || Auth::user()->role == 'Admin')
                                                <button type="button" class="btn-small red delete-file" data-id="{{ $file->id }}">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="center">No files uploaded yet</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="center">
                        {{ $uploadedFiles->links('pagination::material') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    M.AutoInit();

    // Filter facilities by state
    document.getElementById('state').addEventListener('change', function() {
        const state = this.value;
        const facilitySelect = document.getElementById('facility');
        facilitySelect.querySelectorAll('option').forEach(opt => {
            if (opt.dataset.state === state || !opt.dataset.state) {
                opt.style.display = 'block';
            } else {
                opt.style.display = 'none';
            }
        });
        M.FormSelect.getInstance(facilitySelect).destroy();
        new M.FormSelect(facilitySelect);
    });

    // Upload form submission
    document.getElementById('fileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        fetch('/file-management/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                M.toast({html: data.message});
                setTimeout(() => location.reload(), 1000);
            } else {
                M.toast({html: 'Error: ' + data.message});
            }
        })
        .catch(error => {
            console.error('Error:', error);
            M.toast({html: 'An error occurred during upload'});
        });
    });

    // Delete file
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-file')) {
            const id = e.target.closest('.delete-file').dataset.id;
            
            if (confirm('Are you sure you want to delete this file?')) {
                fetch(`/file-management/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        M.toast({html: data.message});
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        M.toast({html: 'Error: ' + data.message});
                    }
                });
            }
        }
    });
});
</script>

<style>
    .badge-category {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        background-color: #2196F3;
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    .table-responsive {
        overflow-x: auto;
    }

    table {
        width: 100%;
    }

    tbody tr:hover {
        background-color: #f5f5f5;
    }

    .btn-small {
        padding: 0 8px;
        height: 32px;
        line-height: 32px;
    }

    .file-field input[type=file] {
        height: 100%;
        width: 100%;
    }
</style>

@endsection
