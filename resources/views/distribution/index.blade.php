@extends('template')
@section('content')

<style>
    .distribution-container {
        margin-top: 20px;
    }

    .form-section {
        background: #f5f5f5;
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 30px;
    }

    .items-table {
        width: 100%;
        margin-top: 15px;
        border-collapse: collapse;
    }

    .items-table thead {
        background: #3f51b5;
        color: white;
    }

    .items-table th,
    .items-table td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .items-table tbody tr:nth-child(odd) {
        background: #f9f9f9;
    }

    .items-table tbody tr:hover {
        background: #efefef;
    }

    .btn-remove {
        background: #f44336;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
    }

    .btn-remove:hover {
        background: #da190b;
    }

    .btn-add-item {
        background: #4CAF50;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        margin-bottom: 10px;
    }

    .btn-add-item:hover {
        background: #45a049;
    }

    .btn-submit {
        background: #008CBA;
        color: white;
        padding: 12px 30px;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-submit:hover {
        background: #007399;
    }

    .distributions-section {
        margin-top: 40px;
    }

    .distribution-row {
        background: white;
        padding: 15px;
        margin-bottom: 10px;
        border-left: 5px solid #3f51b5;
        border-radius: 3px;
    }

    .distribution-row h4 {
        margin: 0 0 10px 0;
        color: #333;
    }

    .distribution-detail {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        font-size: 14px;
    }

    .distribution-detail div {
        margin-bottom: 5px;
    }

    .label {
        font-weight: bold;
        color: #555;
    }

    .alert {
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 3px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .alert-info {
        background: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }

    .item-row {
        background: #f0f0f0;
        padding: 10px;
        margin: 10px 0;
        border-radius: 3px;
        border-left: 3px solid #4CAF50;
    }

    .item-controls {
        display: flex;
        gap: 10px;
        margin-top: 10px;
    }

    .input-field {
        margin-bottom: 15px;
    }

    .input-field label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
        color: #333;
    }

    .input-field input,
    .input-field select,
    .input-field textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 3px;
        font-size: 14px;
        box-sizing: border-box;
    }

    .input-field textarea {
        resize: vertical;
        min-height: 80px;
    }

    .two-column {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    @media (max-width: 768px) {
        .two-column {
            grid-template-columns: 1fr;
        }

        .distribution-detail {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container distribution-container">
    <div class="row">
        <div class="col s12">
            <h2 style="text-align: center; color: #3f51b5; margin-bottom: 30px;">
                <i class="material-icons" style="vertical-align: middle; margin-right: 10px;">local_shipping</i>
                Item Distribution Management
            </h2>

            <!-- Alert Messages -->
            <div id="alertContainer"></div>

            <!-- Distribution Form Section -->
            <div class="form-section">
                <h3 style="color: #3f51b5; margin-top: 0;">Distribution Form</h3>

                <form id="distributionForm" enctype="multipart/form-data">
                    @csrf

                    <!-- State and Location Selection -->
                    <div class="two-column">
                        <div class="input-field">
                            <label for="state">State <span style="color: red;">*</span></label>
                            <select id="state" name="state" required class="select2">
                                <option value="">-- Select State --</option>
                                @foreach($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="facility_id">Facility (Optional)</label>
                            <select id="facility_id" name="facility_id" class="select2">
                                <option value="">-- Select Facility --</option>
                            </select>
                        </div>
                    </div>

                    <!-- User and Persons Selection -->
                    <div class="two-column">
                        <div class="input-field">
                            <label for="user_id">User/Department (Optional)</label>
                            <select id="user_id" name="user_id" class="select2">
                                <option value="">-- Select User --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" data-state="{{ $user->state }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="distribution_date">Distribution Date <span style="color: red;">*</span></label>
                            <input type="date" id="distribution_date" name="distribution_date" required>
                        </div>
                    </div>

                    <!-- Sender and Receiver -->
                    <div class="two-column">
                        <div class="input-field">
                            <label for="sender_name">Sender Name <span style="color: red;">*</span></label>
                            <input type="text" id="sender_name" name="sender_name" placeholder="Enter sender name"
                                required>
                        </div>

                        <div class="input-field">
                            <label for="receiver_name">Receiver Name <span style="color: red;">*</span></label>
                            <input type="text" id="receiver_name" name="receiver_name" placeholder="Enter receiver name"
                                required>
                        </div>
                    </div>

                    <!-- Request and Details -->
                    <div class="two-column">
                        <div class="input-field">
                            <label for="request_id">Request Being Attended To (Optional)</label>
                            <select id="request_id" name="request_id" class="select2">
                                <option value="">-- Select Request --</option>
                                @foreach($requestList as $req)
                                    <option value="{{ $req->id }}">{{ $req->request_title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-field">
                            <label for="delivery_note">Upload Delivery Note (Optional)</label>
                            <input type="file" id="delivery_note" name="delivery_note"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="input-field">
                        <label for="details">Details (Optional)</label>
                        <textarea id="details" name="details" placeholder="Additional details about the distribution"></textarea>
                    </div>

                    <!-- Items Section -->
                    <div style="background: white; padding: 20px; border-radius: 5px; margin-top: 20px;">
                        <h4 style="color: #3f51b5; margin-top: 0;">Select Items for Distribution</h4>

                        <button type="button" class="btn-add-item" onclick="addItemRow()">
                            <i class="material-icons" style="vertical-align: middle;">add</i>
                            Add Item
                        </button>

                        <table class="items-table">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Serial Number</th>
                                    <th>Tag Number</th>
                                    <th>Quantity</th>
                                    <th>Additional Tags</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="itemsTableBody">
                                <!-- Items will be added here dynamically -->
                            </tbody>
                        </table>

                        <div id="noItemsMessage"
                            style="text-align: center; padding: 20px; color: #999;">
                            No items selected yet. Click "Add Item" to select items for distribution.
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div style="margin-top: 30px; text-align: center;">
                        <button type="submit" class="btn-submit">
                            <i class="material-icons" style="vertical-align: middle;">send</i>
                            Submit Distribution
                        </button>
                    </div>
                </form>
            </div>

            <!-- Distributions Report Section -->
            <div class="distributions-section">
                <h3 style="color: #3f51b5; margin-top: 0; margin-bottom: 20px;">
                    <i class="material-icons" style="vertical-align: middle;">history</i>
                    Previous Distributions
                </h3>

                @if($distributions->count() > 0)
                    <div style="overflow-x: auto;">
                        <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
                            <thead>
                                <tr style="background: #3f51b5; color: white;">
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Item</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">State</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Location</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Serial Number</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Tag Number</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">User</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Condition</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Received By</th>
                                    <th style="padding: 12px; text-align: left; border: 1px solid #ddd;">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($distributions as $dist)
                                    <tr style="border-bottom: 1px solid #ddd;">
                                        <td style="padding: 10px; border: 1px solid #ddd; color: #3f51b5; font-weight: bold;">{{ $dist->model ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $dist->state ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $dist->location ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd; font-size: 12px;">{{ $dist->serial_number ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd; font-size: 12px;">{{ $dist->tag_number ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $dist->user ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $dist->condition ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd;">{{ $dist->received_by ?? 'N/A' }}</td>
                                        <td style="padding: 10px; border: 1px solid #ddd; font-size: 12px;">{{ $dist->date_delivered ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div style="text-align: center; margin-top: 30px;">
                        {{ $distributions->links() }}
                    </div>
                @else
                    <div class="alert alert-info">
                        No distributions recorded yet.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal for Item Selection -->
<div id="itemSelectionModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.4);">
    <div style="background-color: #fefefe; margin: 5% auto; padding: 20px; border: 1px solid #888; width: 80%; max-width: 800px; border-radius: 5px; max-height: 70vh; overflow-y: auto;">
        <span style="color: #aaa; float: right; font-size: 28px; cursor: pointer;" onclick="closeItemModal()">&times;</span>
        <h3 style="color: #3f51b5; margin-top: 0;">Select Items</h3>

        <div id="itemsList" style="max-height: 400px; overflow-y: auto;">
            <!-- Items will be loaded here -->
        </div>

        <div style="text-align: right; margin-top: 20px;">
            <button type="button" onclick="closeItemModal()"
                style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 3px; cursor: pointer; margin-right: 10px;">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    let itemRowCount = 0;

    // Wait for libraries to be ready before initializing
    function initializeDistributionForm() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('distribution_date').value = today;

        // Initialize Select2 for all dropdowns with proper configuration
        if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
            try {
                $('#state').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select State',
                    minimumResultsForSearch: 0
                });
                $('#facility_id').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select Facility',
                    minimumResultsForSearch: 0
                });
                $('#user_id').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select User',
                    minimumResultsForSearch: 0
                });
                $('#request_id').select2({
                    width: '100%',
                    allowClear: true,
                    placeholder: 'Select Request',
                    minimumResultsForSearch: 0
                });
                
                // ATTACH EVENT LISTENERS after Select2 is initialized
                $('#state').on('change.select2', function(e) {
                    loadFacilitiesByState();
                });
                $('#state').on('select2:select', function(e) {
                    loadFacilitiesByState();
                });
                
            } catch (e) {
                console.log('Select2 initialization error:', e.message);
            }
        }

        // Update Materialize label status
        if (typeof M !== 'undefined' && M.updateTextFields) {
            M.updateTextFields();
        }

        // Load initial facilities
        loadFacilitiesByState();
    }

    // Wait for librariesReady event or initialize immediately if libraries are loaded
    document.addEventListener('librariesReady', initializeDistributionForm);
    
    if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
        // Libraries already loaded, initialize immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeDistributionForm);
        } else {
            initializeDistributionForm();
        }
    } else {
        // Fallback: wait for DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            // Give libraries a moment to load
            setTimeout(initializeDistributionForm, 500);
        });
    }

    // State change handler - properly handle Select2 events
    // (Now handled in initializeDistributionForm above)

    function loadFacilitiesByState() {
        const state = document.getElementById('state').value;
        const facilitySelect = document.getElementById('facility_id');

        if (!state) {
            facilitySelect.innerHTML = '<option value="">-- Select Facility --</option>';
            // Reinitialize Select2
            if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                try {
                    jQuery(facilitySelect).select2('destroy').select2({
                        width: '100%',
                        allowClear: true,
                        placeholder: 'Select Facility',
                        minimumResultsForSearch: 0
                    });
                } catch (e) {}
            }
            return;
        }

        fetch(`{{ route('get-facilities-by-state') }}?state=${encodeURIComponent(state)}`)
            .then(response => response.json())
            .then(data => {
                facilitySelect.innerHTML = '<option value="">-- Select Facility --</option>';
                data.forEach(facility => {
                    facilitySelect.innerHTML +=
                        `<option value="${facility.id}">${facility.facility_name}</option>`;
                });
                // Reinitialize Select2
                if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                    try {
                        jQuery(facilitySelect).select2('destroy').select2({
                            width: '100%',
                            allowClear: true,
                            placeholder: 'Select Facility',
                            minimumResultsForSearch: 0
                        });
                    } catch (e) {}
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Add item row
    function addItemRow() {
        const state = document.getElementById('state').value;

        if (!state) {
            showAlert('Please select a state first', 'danger');
            return;
        }

        const facilityId = document.getElementById('facility_id').value;
        const url = new URL(`{{ route('get-items-by-location') }}`, window.location.origin);
        url.searchParams.append('state', state);
        if (facilityId) {
            url.searchParams.append('facility_id', facilityId);
        }

        fetch(url)
            .then(response => response.json())
            .then(items => {
                showItemSelectionModal(items);
            })
            .catch(error => console.error('Error:', error));
    }

    function showItemSelectionModal(items) {
        const itemsList = document.getElementById('itemsList');
        itemsList.innerHTML = '';

        if (items.length === 0) {
            itemsList.innerHTML =
                '<div class="alert alert-info">No items available for the selected location.</div>';
        } else {
            items.forEach(item => {
                itemsList.innerHTML += `
                    <div class="item-row" style="cursor: pointer;" onclick="selectItem(${item.id}, '${item.item_name}', '${item.serial_no}', '${item.tag_no}')">
                        <strong>${item.item_name}</strong><br>
                        Serial: ${item.serial_no} | Tag: ${item.tag_no} | Status: ${item.status}
                    </div>
                `;
            });
        }

        document.getElementById('itemSelectionModal').style.display = 'block';
    }

    function closeItemModal() {
        document.getElementById('itemSelectionModal').style.display = 'none';
    }

    function selectItem(itemId, itemName, serialNo, tagNo) {
        addItemToTable(itemId, itemName, serialNo, tagNo);
        closeItemModal();
    }

    function addItemToTable(itemId, itemName, serialNo, tagNo) {
        const tableBody = document.getElementById('itemsTableBody');
        const noItemsMsg = document.getElementById('noItemsMessage');

        if (tableBody.children.length === 0) {
            noItemsMsg.style.display = 'none';
        }

        const rowId = 'item-row-' + (++itemRowCount);

        const row = document.createElement('tr');
        row.id = rowId;
        row.innerHTML = `
            <td>
                <input type="hidden" name="items[${itemRowCount}][item_id]" value="${itemId}">
                ${itemName}
            </td>
            <td>
                <input type="text" name="items[${itemRowCount}][serial_number]" value="${serialNo}" required>
            </td>
            <td>
                <input type="text" name="items[${itemRowCount}][tag_number]" value="${tagNo}" required>
            </td>
            <td>
                <input type="number" name="items[${itemRowCount}][quantity]" value="1" min="1" style="width: 60px;">
            </td>
            <td>
                <input type="text" name="items[${itemRowCount}][additional_tags]" placeholder="Additional tags">
            </td>
            <td>
                <button type="button" class="btn-remove" onclick="removeItemRow('${rowId}')">Remove</button>
            </td>
        `;

        tableBody.appendChild(row);
    }

    function removeItemRow(rowId) {
        const row = document.getElementById(rowId);
        row.remove();

        if (document.getElementById('itemsTableBody').children.length === 0) {
            document.getElementById('noItemsMessage').style.display = 'block';
        }
    }

    // Form submission
    document.getElementById('distributionForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const itemsCount = document.getElementById('itemsTableBody').children.length;

        if (itemsCount === 0) {
            showAlert('Please add at least one item', 'danger');
            return;
        }

        const formData = new FormData(this);

        fetch('{{ route("distribution.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert(data.message, 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                } else {
                    showAlert(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('An error occurred. Please try again.', 'danger');
            });
    });

    function showAlert(message, type) {
        const alertContainer = document.getElementById('alertContainer');
        const alertClass = `alert alert-${type}`;
        alertContainer.innerHTML = `<div class="${alertClass}">${message}</div>`;

        // Auto-hide after 5 seconds
        setTimeout(() => {
            alertContainer.innerHTML = '';
        }, 5000);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('itemSelectionModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

@endsection
