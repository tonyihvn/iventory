@extends('template')

@section('content')

<div class="row">
    <div class="col s12">
        <div class="card">
            <div class="card-content">
                <span class="card-title">
                    <i class="material-icons">construction</i> Repair & Maintenance Requests
                </span>

                <!-- Request Form -->
                <div class="section">
                    <h5>Submit New Request</h5>
                    
                    <form id="requestForm" novalidate>
                        @csrf

                        @php
                            $isRestrictedSender = !$isAdmin && !empty($userState) && strtolower($userState) !== 'all';
                        @endphp

                        <div class="row">
                            <div class="input-field col s12 m6">
                                <select id="rr_state" class="select2" {{ $isRestrictedSender ? 'disabled' : '' }}>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state }}" {{ $isRestrictedSender && $state === $userState ? 'selected' : '' }}>{{ $state }}</option>
                                    @endforeach
                                </select>
                                <label for="rr_state" class="active">State</label>
                            </div>

                            <div class="input-field col s12 m6">
                                <select id="rr_facility" class="select2">
                                    <option value="">Select Facility</option>
                                    @foreach($facilities as $fac)
                                        <option value="{{ $fac->id }}" data-state="{{ $fac->state }}">{{ $fac->facility_name }}</option>
                                    @endforeach
                                </select>
                                <label for="rr_facility" class="active">Facility (optional)</label>
                            </div>
                        </div>

                        <div id="requestsContainer">
                            <!-- First row shown by default -->
                            <div class="request-row card-panel grey lighten-5" id="request-0">
                                <div class="row">
                                    <div class="col s12">
                                        <button type="button" class="btn-small red right remove-request" data-row="0">
                                            <i class="material-icons">delete</i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12 m6">
                                        <select class="item-select select2" data-row="0">
                                            <option value="">Select Item</option>
                                            @foreach($itemList as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                            @endforeach
                                        </select>
                                        <label class="active">Item Name</label>
                                    </div>

                                    <div class="input-field col s12 m6">
                                        <select class="status-select select2" data-row="0">
                                            <option value="">Select Status</option>
                                            <option value="Need Repairs">Need Repairs</option>
                                            <option value="Not Operational">Not Operational</option>
                                            <option value="Lost">Lost</option>
                                            <option value="Irreparable">Irreparable</option>
                                        </select>
                                        <label class="active">Status</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12 m6">
                                        <select class="serial-select select2" data-row="0">
                                            <option value="">Select Serial Number</option>
                                            @foreach($serialNumbers as $serial)
                                                <option value="{{ $serial }}">{{ $serial }}</option>
                                            @endforeach
                                        </select>
                                        <label>Serial Number</label>
                                    </div>

                                    <div class="input-field col s12 m6">
                                        <select class="tag-select select2" data-row="0">
                                            <option value="">Select Tag Number</option>
                                            @foreach($tagNumbers as $tag)
                                                <option value="{{ $tag }}">{{ $tag }}</option>
                                            @endforeach
                                        </select>
                                        <label class="active">Tag Number</label>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="input-field col s12">
                                        <textarea class="materialize-textarea description-input" data-row="0" placeholder="Describe the issue..."></textarea>
                                        <label class="active">Description</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="button" class="btn teal" id="addRequestBtn">
                                    <i class="material-icons left">add</i> Add More
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12">
                                <button type="submit" class="btn green">
                                    <i class="material-icons left">send</i> Submit Requests
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <hr>

                <!-- Requests List -->
                <div class="section">
                    <div class="row">
                        <div class="col s12">
                            <h5>Previous Requests</h5>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="products" class="striped bordered hover">
                            <thead class="teal white-text">
                                <tr>
                                    <th>State</th>
                                    <th>Facility</th>
                                    <th>Item</th>
                                    <th>Serial/Tag</th>
                                    <th>Status</th>
                                    <th>Description</th>
                                    <th>Submitted By</th>
                                    <th>Date</th>
                                    <th>Admin Status</th>
                                    <th>Admin Comment</th>
                                    <th>IT Comment</th>
                                    <th>Estimated Cost (₦)</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($repairRequests as $request)
                                    <tr data-request-id="{{ $request->id }}">
                                        <td>{{ $request->state ?? 'N/A' }}</td>
                                        <td>{{ optional($request->facility)->facility_name ?? 'N/A' }}</td>
                                        <td>{{ optional($request->item)->item_name ?? 'N/A' }}</td>
                                        <td>{{ $request->serial_number }} / {{ $request->tag_number }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ 
                                                $request->status == 'Need Repairs' ? '#ff9800' : 
                                                ($request->status == 'Not Operational' ? '#f44336' : 
                                                ($request->status == 'Lost' ? '#9c27b0' : '#4caf50'))
                                            }}">
                                                {{ $request->status }}
                                            </span>
                                        </td>
                                        <td>{{ substr($request->description, 0, 50) }}...</td>
                                        <td>{{ optional($request->user)->name ?? 'N/A' }}</td>
                                        <td>{{ $request->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ 
                                                $request->admin_status == 'Approved' ? '#4caf50' : 
                                                ($request->admin_status == 'Rejected' ? '#f44336' : '#9e9e9e')
                                            }}">
                                                {{ $request->admin_status ?: 'Pending' }}
                                            </span>
                                        </td>
                                        <td>{{ substr($request->admin_remarks ?? '', 0, 40) }}{{ strlen($request->admin_remarks ?? '') > 40 ? '...' : '' }}</td>
                                        <td>{{ substr($request->it_review ?? '', 0, 40) }}{{ strlen($request->it_review ?? '') > 40 ? '...' : '' }}</td>
                                        <td>{{ $request->estimated_cost ? number_format($request->estimated_cost, 2) : '-' }}</td>
                                        <td>
                                            @if(Auth::user()->role == 'Super' || Auth::user()->role == 'Admin')
                                                <button type="button" class="btn-small blue edit-request" data-id="{{ $request->id }}" data-status="{{ $request->admin_status }}" data-remarks="{{ $request->admin_remarks }}">
                                                    <i class="material-icons">edit</i>
                                                </button>
                                            @endif
                                            @if(Auth::user()->role == 'IT' || Auth::user()->role == 'Admin')
                                                <button type="button" class="btn-small orange it-review-btn" data-id="{{ $request->id }}" title="IT Review">
                                                    <i class="material-icons">assessment</i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn-small teal view-request" data-id="{{ $request->id }}">
                                                <i class="material-icons">visibility</i>
                                            </button>
                                            @if(Auth::user()->role == 'Super' || Auth::user()->role == 'Admin')
                                                <button type="button" class="btn-small red delete-request" data-id="{{ $request->id }}" title="Delete Request">
                                                    <i class="material-icons">delete</i>
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="13" class="center">No repair requests found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="center">
                        {{ $repairRequests->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Request Modal -->
<div id="editRequestModal" class="modal">
    <div class="modal-content">
        <h4>Update Request Status</h4>
        
        <div class="row">
            <div class="input-field col s12">
                <select id="adminStatus">
                    <option value="">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <label for="adminStatus">Admin Status</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <textarea id="adminRemarks" class="materialize-textarea" placeholder="Add remarks..."></textarea>
                <label for="adminRemarks">Admin Remarks</label>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="modal-close btn-flat">Cancel</button>
        <button type="button" class="btn green" id="saveStatusBtn">Save</button>
    </div>
</div>

<!-- IT Review Modal -->
<div id="itReviewModal" class="modal">
    <div class="modal-content">
        <h4>IT Review & Estimated Cost</h4>
        
        <div class="row">
            <div class="input-field col s12">
                <textarea id="itReview" class="materialize-textarea" placeholder="Enter your IT review..."></textarea>
                <label for="itReview">IT Review</label>
            </div>
        </div>

        <div class="row">
            <div class="input-field col s12">
                <input type="number" id="estimatedCost" step="0.01" min="0" placeholder="Enter estimated cost">
                <label for="estimatedCost">Estimated Cost (₦)</label>
            </div>
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="modal-close btn-flat">Cancel</button>
        <button type="button" class="btn orange" id="saveITReviewBtn">Submit Review</button>
    </div>
</div>

<!-- View Request Modal -->
<div id="viewRequestModal" class="modal">
    <div class="modal-content">
        <h4>Request Details</h4>
        <div id="requestDetails"></div>
    </div>

    <div class="modal-footer">
        <button type="button" class="modal-close btn-flat">Close</button>
        <button type="button" id="printRequestBtn" class="btn waves-effect waves-light blue" title="Print Request">
            <i class="material-icons left">print</i>Print
        </button>
        <button type="button" id="downloadPdfBtn" class="btn waves-effect waves-light red" title="Download PDF">
            <i class="material-icons left">download</i>PDF
        </button>
    </div>
</div>

<script>
// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    function showToast(message) {
        if (typeof M !== 'undefined' && typeof M.toast === 'function') {
            M.toast({ html: message });
            return;
        }

        if (typeof Materialize !== 'undefined' && typeof Materialize.toast === 'function') {
            Materialize.toast(message, 4000);
            return;
        }

        alert(message);
    }

    // Initialize Materialize
    if (typeof M !== 'undefined') {
        M.AutoInit();
    } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
        jQuery('.modal').modal();
    }
    
    // Initialize Select2
    if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
        jQuery('.select2').each(function() {
            if (!jQuery(this).hasClass('select2-hidden-accessible')) {
                jQuery(this).select2({ width: '100%' });
            }
        });
    }

    // DataTables is initialized by pmain.js, no need to reinitialize here

    let requestCount = 1;  // Start from 1 since 0 is already shown

    // Handle Add More button click
    document.getElementById('addRequestBtn').addEventListener('click', function(e) {
        e.preventDefault();
        addRequestRow();
    });

    function addRequestRow() {
        requestCount++;
        const html = `
            <div class="request-row card-panel grey lighten-5" id="request-${requestCount}">
                <div class="row">
                    <div class="col s12">
                        <button type="button" class="btn-small red right remove-request" data-row="${requestCount}">
                            <i class="material-icons">delete</i>
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <select class="item-select select2" data-row="${requestCount}">
                            <option value="">Select Item</option>
                            @foreach($itemList as $item)
                                <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                            @endforeach
                        </select>
                        <label>Item Name</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <select class="status-select select2" data-row="${requestCount}">
                            <option value="">Select Status</option>
                            <option value="Need Repairs">Need Repairs</option>
                            <option value="Not Operational">Not Operational</option>
                            <option value="Lost">Lost</option>
                            <option value="Irreparable">Irreparable</option>
                        </select>
                        <label>Status</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12 m6">
                        <select class="serial-select select2" data-row="${requestCount}">
                            <option value="">Select Serial Number</option>
                            @foreach($serialNumbers as $serial)
                                <option value="{{ $serial }}">{{ $serial }}</option>
                            @endforeach
                        </select>
                        <label>Serial Number</label>
                    </div>

                    <div class="input-field col s12 m6">
                        <select class="tag-select select2" data-row="${requestCount}">
                            <option value="">Select Tag Number</option>
                            @foreach($tagNumbers as $tag)
                                <option value="{{ $tag }}">{{ $tag }}</option>
                            @endforeach
                        </select>
                        <label>Tag Number</label>
                    </div>
                </div>

                <div class="row">
                    <div class="input-field col s12">
                        <textarea class="materialize-textarea description-input" data-row="${requestCount}" placeholder="Describe the issue..."></textarea>
                        <label>Description</label>
                    </div>
                </div>
            </div>
        `;

        document.getElementById('requestsContainer').insertAdjacentHTML('beforeend', html);

        // Initialize Select2 on the newly added row's dropdowns.
        // NOTE: This app uses Materialize v0.98 (global is `Materialize`, not `M`),
        // so the Select2 init must NOT be nested inside a `typeof M` check.
        setTimeout(() => {
            const newRow = document.getElementById(`request-${requestCount}`);
            if (!newRow) return;

            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                jQuery(newRow).find('.select2').each(function () {
                    if (!jQuery(this).hasClass('select2-hidden-accessible')) {
                        jQuery(this).select2({ width: '100%' });
                    }
                });
            }

            // Refresh text field labels (supports both Materialize v1.x and v0.x)
            if (typeof M !== 'undefined' && M.updateTextFields) {
                M.updateTextFields();
            } else if (typeof Materialize !== 'undefined' && Materialize.updateTextFields) {
                Materialize.updateTextFields();
            }
        }, 50);
    }

    // Handle remove request
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-request')) {
            const row = e.target.closest('.remove-request').dataset.row;
            const element = document.getElementById(`request-${row}`);
            if (element) {
                element.remove();
            }
        }
    });

    // Handle state change - show the facilities that belong to the selected state.
    // Facilities are rebuilt (not disabled) so only the matching ones are listed;
    // when no state is selected the full list is shown. Facility stays optional.
    const stateSelect = document.getElementById('rr_state');
    const facilitySelect = document.getElementById('rr_facility');
    if (stateSelect && facilitySelect) {
        // Capture the complete facility list once so we can rebuild per state.
        const allFacilityOptions = Array.from(facilitySelect.options).map(function (o) {
            return { value: o.value, text: o.textContent, state: o.dataset.state || '' };
        });

        function renderFacilitiesForState(state) {
            const previous = facilitySelect.value;
            facilitySelect.innerHTML = '';

            allFacilityOptions.forEach(function (o) {
                // Always keep the empty placeholder; otherwise only keep facilities
                // for the selected state (or all when no state is selected).
                if (o.value === '' || !state || !o.state || o.state === state) {
                    const opt = document.createElement('option');
                    opt.value = o.value;
                    opt.textContent = o.text;
                    if (o.state) opt.setAttribute('data-state', o.state);
                    facilitySelect.appendChild(opt);
                }
            });

            // Preserve the previous selection if it still applies, else clear it.
            const stillAvailable = Array.from(facilitySelect.options).some(function (o) {
                return o.value === previous && previous !== '';
            });
            facilitySelect.value = stillAvailable ? previous : '';

            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                jQuery(facilitySelect).trigger('change.select2');
            }
        }

        stateSelect.addEventListener('change', function () {
            renderFacilitiesForState(this.value);
        });

        // Apply once on load in case a state is already selected.
        renderFacilitiesForState(stateSelect.value);
    }

    // Handle form submission
    document.getElementById('requestForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const stateSelectEl = document.getElementById('rr_state');
        const state = stateSelectEl ? stateSelectEl.value : '';
        const facility = document.getElementById('rr_facility').value;
        const rows = document.querySelectorAll('.request-row');

        if (rows.length === 0) {
            showToast('Please add at least one request row.');
            return;
        }

        const requests = [];
        for (const row of rows) {
            // Read each row's own fields. Select2 keeps the underlying <select>
            // element's value in sync, so reading `.value` per row is reliable
            // even when several rows are added via "Add More".
            const itemSelectEl = row.querySelector('.item-select');
            const statusSelectEl = row.querySelector('.status-select');
            const serialSelectEl = row.querySelector('.serial-select');
            const tagSelectEl = row.querySelector('.tag-select');
            const descriptionEl = row.querySelector('.description-input');

            const readValue = (el) => {
                if (!el) return '';
                if (typeof jQuery !== 'undefined' && jQuery(el).hasClass('select2-hidden-accessible')) {
                    return jQuery(el).val() || el.value || '';
                }
                return el.value || '';
            };

            const itemId = readValue(itemSelectEl);
            const statusValue = readValue(statusSelectEl);
            const serialValue = readValue(serialSelectEl);
            const tagValue = readValue(tagSelectEl);
            const descriptionValue = descriptionEl ? descriptionEl.value : '';

            // Accept all rows, even if fields are empty
            const payload = {
                item_id: itemId || null,
                status: statusValue || '',
                serial_number: serialValue || '',
                tag_number: tagValue || '',
                description: descriptionValue || '',
                state: state || '',
                facility_id: facility || null
            };

            requests.push(payload);
        }

        fetch('/repair-requests/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
            },
            body: JSON.stringify({ requests: requests })
        })
        .then(async response => {
            const data = await response.json().catch(() => ({
                success: false,
                message: 'Unexpected server response.'
            }));

            if (!response.ok) {
                throw new Error(data.message || `Request failed with status ${response.status}`);
            }

            return data;
        })
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Requests submitted successfully.');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Error: ' + (data.message || 'Unable to submit requests.'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast(error.message || 'An error occurred while submitting requests.');
        });
    });

    // Handle edit request
    document.addEventListener('click', function(e) {
        if (e.target.closest('.edit-request')) {

            const currentRequestId = e.target.closest('.edit-request').dataset.id;
            const status = e.target.closest('.edit-request').dataset.status;
            const remarks = e.target.closest('.edit-request').dataset.remarks;

            document.getElementById('adminStatus').value = status || '';
            document.getElementById('adminRemarks').value = remarks || '';
            if (typeof M !== 'undefined') {
                M.AutoInit();
                const modalInstance = M.Modal.getInstance(document.getElementById('editRequestModal')) || M.Modal.init(document.getElementById('editRequestModal'));
                modalInstance.open();
            } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                jQuery('#editRequestModal').modal('open');
            }

            // Save status button
            const saveBtn = document.getElementById('saveStatusBtn');
            saveBtn.onclick = function() {
                if (!currentRequestId) {
                    showToast('Error: Unable to identify the request. Please refresh and try again.');
                    return;
                }

                const newStatus = document.getElementById('adminStatus').value;
                const newRemarks = document.getElementById('adminRemarks').value;

                // Use POST with method override so it also works on servers that
                // block native PUT/DELETE requests (common on shared hosting).
                fetch(`/repair-requests/${currentRequestId}/status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify({ admin_status: newStatus, admin_remarks: newRemarks })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Request status updated.');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Error: ' + (data.message || 'Unable to update request status.'));
                    }
                });
            };
        }
    });

    // Handle IT Review button click
    document.addEventListener('click', function(e) {
        if (e.target.closest('.it-review-btn')) {
            const currentRequestId = e.target.closest('.it-review-btn').dataset.id;

            // Clear form fields
            document.getElementById('itReview').value = '';
            document.getElementById('estimatedCost').value = '';

            if (typeof M !== 'undefined') {
                M.AutoInit();
                const modalInstance = M.Modal.getInstance(document.getElementById('itReviewModal')) || M.Modal.init(document.getElementById('itReviewModal'));
                modalInstance.open();
            } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                jQuery('#itReviewModal').modal('open');
            }

            // Save IT Review button
            const saveBtn = document.getElementById('saveITReviewBtn');
            saveBtn.onclick = function() {
                if (!currentRequestId) {
                    showToast('Error: Unable to identify the request. Please refresh and try again.');
                    return;
                }

                const itReview = document.getElementById('itReview').value;
                const estimatedCost = document.getElementById('estimatedCost').value;

                if (!itReview || !estimatedCost) {
                    showToast('Please fill in all fields.');
                    return;
                }

                // Use POST with method override so it also works on servers that
                // block native PUT/DELETE requests (common on shared hosting).
                fetch(`/repair-requests/${currentRequestId}/it-review`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    body: JSON.stringify({ it_review: itReview, estimated_cost: estimatedCost })
                })
                .then(response => {
                    // Check if response is not ok (including 403, 405, etc.)
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'IT review submitted successfully.');
                        if (typeof M !== 'undefined') {
                            M.Modal.getInstance(document.getElementById('itReviewModal')).close();
                        } else if (typeof jQuery !== 'undefined') {
                            jQuery('#itReviewModal').modal('close');
                        }
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Error: ' + (data.message || 'Unable to submit IT review.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error: ' + (error.message || 'An error occurred while submitting IT review.'));
                });
            };
        }
    });

    // Handle view request
    document.addEventListener('click', function(e) {
        if (e.target.closest('.view-request')) {
            const requestId = e.target.closest('.view-request').dataset.id;
            const row = e.target.closest('tr');
            const item = row.cells[0].textContent;
            const serial_tag = row.cells[1].textContent;
            const status = row.cells[2].textContent;
            const description = row.cells[3].textContent;
            const submitted_by = row.cells[4].textContent;
            const date = row.cells[5].textContent;
            const admin_status = row.cells[6].textContent;

            // Fetch full request details to get admin_remarks, it_review, and estimated_cost
            fetch(`/repair-requests/${requestId}`)
                .then(response => response.json())
                .catch(() => null)
                .then(data => {
                    let admin_remarks = '';
                    let it_review = '';
                    let estimated_cost = '';

                    if (data && data.admin_remarks) {
                        admin_remarks = data.admin_remarks;
                    }
                    if (data && data.it_review) {
                        it_review = data.it_review;
                    }
                    if (data && data.estimated_cost) {
                        estimated_cost = data.estimated_cost;
                    }

                    let detailsHtml = `
                        <table class="striped">
                            <tr><td><strong>Item:</strong></td><td>${item}</td></tr>
                            <tr><td><strong>Serial/Tag:</strong></td><td>${serial_tag}</td></tr>
                            <tr><td><strong>Status:</strong></td><td>${status}</td></tr>
                            <tr><td><strong>Description:</strong></td><td>${description}</td></tr>
                            <tr><td><strong>Submitted By:</strong></td><td>${submitted_by}</td></tr>
                            <tr><td><strong>Date:</strong></td><td>${date}</td></tr>
                            <tr><td><strong>Admin Status:</strong></td><td>${admin_status}</td></tr>
                    `;

                    if (admin_remarks) {
                        detailsHtml += `<tr><td><strong>Admin Remarks:</strong></td><td>${admin_remarks}</td></tr>`;
                    }

                    if (it_review) {
                        detailsHtml += `<tr><td><strong>IT Review:</strong></td><td>${it_review}</td></tr>`;
                    }

                    if (estimated_cost) {
                        detailsHtml += `<tr><td><strong>Estimated Cost:</strong></td><td>₦${parseFloat(estimated_cost).toLocaleString()}</td></tr>`;
                    }

                    detailsHtml += `</table>`;

                    // Store request data for print/download functions
                    document.getElementById('viewRequestModal').dataset.requestId = requestId;
                    window.currentRequestData = {
                        id: requestId,
                        item: item,
                        serial_tag: serial_tag,
                        status: status,
                        description: description,
                        submitted_by: submitted_by,
                        date: date,
                        admin_status: admin_status,
                        admin_remarks: admin_remarks,
                        it_review: it_review,
                        estimated_cost: estimated_cost
                    };

                    document.getElementById('requestDetails').innerHTML = detailsHtml;
                    if (typeof M !== 'undefined') {
                        const modalInstance = M.Modal.getInstance(document.getElementById('viewRequestModal')) || M.Modal.init(document.getElementById('viewRequestModal'));
                        modalInstance.open();
                    } else if (typeof jQuery !== 'undefined' && jQuery.fn.modal) {
                        jQuery('#viewRequestModal').modal('open');
                    }
                });
        }
    });

    // Handle delete request
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-request')) {
            const requestId = e.target.closest('.delete-request').dataset.id;
            
            // Show confirmation dialog
            if (confirm('Are you sure you want to delete this repair request? This action cannot be undone.')) {
                // Use POST with method override so it also works on servers that
                // block native PUT/DELETE requests (common on shared hosting).
                fetch(`/repair-requests/${requestId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'X-HTTP-Method-Override': 'DELETE'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message || 'Repair request deleted successfully.');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        showToast('Error: ' + (data.message || 'Unable to delete repair request.'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Error: ' + (error.message || 'An error occurred while deleting the request.'));
                });
            }
        }
    });

    // Export functions disabled - using DataTables buttons instead
    /* 
    document.getElementById('exportCsv').addEventListener('click', function() {
        exportTableToCSV('repair-requests.csv');
    });

    document.getElementById('exportExcel').addEventListener('click', function() {
        exportTableToExcel('repair-requests.xlsx');
    });

    document.getElementById('printTable').addEventListener('click', function() {
        printTable();
    });
    */

    function exportTableToCSV(filename) {
        const table = document.getElementById('products');
        let csv = [];
        const headers = [];
        
        // Get headers
        const headerCells = table.querySelectorAll('thead tr th');
        headerCells.forEach(cell => {
            headers.push('"' + cell.textContent.trim().replace(/"/g, '""') + '"');
        });
        csv.push(headers.join(','));

        // Get rows
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells.length > 0) {
                const rowData = [];
                cells.forEach((cell, index) => {
                    // Skip the last column (Actions)
                    if (index < cells.length - 1) {
                        let text = cell.textContent.trim().replace(/"/g, '""');
                        rowData.push('"' + text + '"');
                    }
                });
                if (rowData.length > 0) {
                    csv.push(rowData.join(','));
                }
            }
        });

        downloadFile(csv.join('\n'), filename, 'text/csv');
    }

    function exportTableToExcel(filename) {
        const table = document.getElementById('products');
        let html = '<html><head><meta charset="UTF-8"></head><body>';
        html += '<table border="1">';
        
        // Add headers
        html += '<thead>';
        const headerCells = table.querySelectorAll('thead tr th');
        html += '<tr>';
        headerCells.forEach(cell => {
            html += '<th style="background-color:#26a69a;color:white;font-weight:bold;">' + cell.textContent.trim() + '</th>';
        });
        html += '</tr></thead>';

        // Add rows
        html += '<tbody>';
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            html += '<tr>';
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                // Skip the last column (Actions)
                if (index < cells.length - 1) {
                    html += '<td>' + cell.textContent.trim() + '</td>';
                }
            });
            html += '</tr>';
        });
        html += '</tbody></table></body></html>';

        downloadFile(html, filename, 'application/vnd.ms-excel');
    }

    function downloadFile(content, filename, type) {
        const blob = new Blob([content], { type: type });
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    function printTable() {
        const table = document.getElementById('products');
        const printWindow = window.open('', '', 'height=600,width=800');
        
        let html = `
            <html>
            <head>
                <title>Repair Requests Report</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #26a69a; color: white; font-weight: bold; }
                    tr:nth-child(even) { background-color: #f9f9f9; }
                    .badge { padding: 3px 6px; border-radius: 3px; color: white; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <h2>Repair Requests Report</h2>
                <p>Generated on: ${new Date().toLocaleString()}</p>
        `;

        html += '<table>';
        
        // Add headers
        html += '<thead><tr>';
        const headerCells = table.querySelectorAll('thead tr th');
        headerCells.forEach(cell => {
            html += '<th>' + cell.textContent.trim() + '</th>';
        });
        html += '</tr></thead>';

        // Add rows
        html += '<tbody>';
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            html += '<tr>';
            const cells = row.querySelectorAll('td');
            cells.forEach((cell, index) => {
                if (index < cells.length - 1) {
                    html += '<td>' + cell.innerHTML + '</td>';
                }
            });
            html += '</tr>';
        });
        html += '</tbody></table>';

        html += `
            </body>
            </html>
        `;
        
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
        }, 250);
    }

    // Print individual request
    document.getElementById('printRequestBtn').addEventListener('click', function() {
        if (!window.currentRequestData) {
            showToast('No request data available');
            return;
        }

        const data = window.currentRequestData;
        const printWindow = window.open('', '', 'height=600,width=800');
        
        let html = `
            <html>
            <head>
                <title>Repair Request #${data.id}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { border-bottom: 2px solid #26a69a; padding-bottom: 10px; margin-bottom: 20px; }
                    .title { font-size: 20px; font-weight: bold; color: #26a69a; }
                    .date-printed { color: #666; font-size: 12px; margin-top: 5px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    tr { border-bottom: 1px solid #ddd; }
                    td { padding: 10px; }
                    td:first-child { font-weight: bold; width: 30%; color: #26a69a; }
                    td:last-child { color: #333; }
                    .footer { margin-top: 30px; font-size: 12px; color: #999; }
                    @media print { body { margin: 0; } }
                </style>
            </head>
            <body>
                <div class="header">
                    <div class="title">Repair Request #${data.id}</div>
                    <div class="date-printed">Printed on: ${new Date().toLocaleString()}</div>
                </div>
                <table>
                    <tr>
                        <td>Item:</td>
                        <td>${data.item || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>Serial/Tag:</td>
                        <td>${data.serial_tag || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>${data.status || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>Description:</td>
                        <td>${data.description || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>Submitted By:</td>
                        <td>${data.submitted_by || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td>${data.date || 'N/A'}</td>
                    </tr>
                    <tr>
                        <td>Admin Status:</td>
                        <td>${data.admin_status || 'N/A'}</td>
                    </tr>
                    ${data.admin_remarks ? `<tr><td>Admin Remarks:</td><td>${data.admin_remarks}</td></tr>` : ''}
                    ${data.it_review ? `<tr><td>IT Review:</td><td>${data.it_review}</td></tr>` : ''}
                    ${data.estimated_cost ? `<tr><td>Estimated Cost:</td><td>₦${parseFloat(data.estimated_cost).toLocaleString()}</td></tr>` : ''}
                </table>
                <div class="footer">
                    <p>This is an automatically generated document from the Repair Request System.</p>
                </div>
            </body>
            </html>
        `;
        
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.focus();
        setTimeout(() => {
            printWindow.print();
        }, 250);
    });

    // Download request as PDF
    document.getElementById('downloadPdfBtn').addEventListener('click', function() {
        if (!window.currentRequestData) {
            showToast('No request data available');
            return;
        }

        if (typeof pdfMake === 'undefined') {
            showToast('PDF library not available');
            return;
        }

        const data = window.currentRequestData;
        const docContent = [
            {
                text: 'Repair Request #' + data.id,
                fontSize: 18,
                bold: true,
                color: '#26a69a',
                margin: [0, 0, 0, 20]
            },
            {
                text: 'Generated on: ' + new Date().toLocaleString(),
                fontSize: 10,
                color: '#999',
                margin: [0, 0, 0, 20]
            }
        ];

        // Add a table with request details
        const tableBody = [
            [{ text: 'Field', bold: true, color: 'white', fillColor: '#26a69a' }, 
             { text: 'Value', bold: true, color: 'white', fillColor: '#26a69a' }]
        ];

        const fields = [
            { label: 'Item', value: data.item || 'N/A' },
            { label: 'Serial/Tag', value: data.serial_tag || 'N/A' },
            { label: 'Status', value: data.status || 'N/A' },
            { label: 'Description', value: data.description || 'N/A' },
            { label: 'Submitted By', value: data.submitted_by || 'N/A' },
            { label: 'Date', value: data.date || 'N/A' },
            { label: 'Admin Status', value: data.admin_status || 'N/A' }
        ];

        if (data.admin_remarks) {
            fields.push({ label: 'Admin Remarks', value: data.admin_remarks });
        }
        if (data.it_review) {
            fields.push({ label: 'IT Review', value: data.it_review });
        }
        if (data.estimated_cost) {
            fields.push({ label: 'Estimated Cost', value: '₦' + parseFloat(data.estimated_cost).toLocaleString() });
        }

        fields.forEach(field => {
            tableBody.push([field.label, field.value]);
        });

        docContent.push({
            table: {
                headerRows: 1,
                widths: ['30%', '70%'],
                body: tableBody
            },
            layout: {
                fillColor: function(rowIndex) {
                    return (rowIndex % 2 === 0) ? '#f9f9f9' : null;
                }
            }
        });

        docContent.push({
            text: 'This is an automatically generated document from the Repair Request System.',
            fontSize: 9,
            color: '#999',
            margin: [0, 30, 0, 0],
            italics: true
        });

        const docDefinition = {
            content: docContent,
            defaultStyle: {
                font: 'Helvetica'
            }
        };

        pdfMake.createPdf(docDefinition).download('repair-request-' + data.id + '.pdf');
        showToast('PDF downloaded successfully');
    });
});
</script>

<style>
    .request-row {
        margin-bottom: 20px;
        border-left: 4px solid #26a69a;
    }

    .badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 12px;
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
</style>

@endsection
