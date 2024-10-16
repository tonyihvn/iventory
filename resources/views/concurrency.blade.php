
@extends('template')
@section('content')
<link href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        .edited-cell {
            background-color: lightgreen !important;
        }
        .empty-cell {
            background-color: rgb(248, 215, 215); /* Light red color */
        }
    </style>
    <div class="row" style="padding: 5px;">
        <h3 style="text-align: center">Assets Table {{$state}}</h3>
        <div class="row" style="text-align: center">
            <div>
                <a id="addRowButton" href="#" class="btn btn-small btn-floating left pulse tooltipped"
                    data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>

            </div>
            <button id="updateButton" class="btn btn-primary">Update Assets</button>

            <!-- Datalist for preloading locations -->
            <datalist id="locationList">
                @foreach($locations as $location)
                    <option value="{{ $location->facility_name }}">
                @endforeach
            </datalist>

            <datalist id="conditionList">
                    <option value="Operational">
                    <option value="Not Operational">
                    <option value="Lost">
                    <option value="Archived">
                    <option value="Need Repairs">
            </datalist>

            <datalist id="modelList">
                @foreach($models as $model)
                    <option value="{{ $model->model }}">
                @endforeach
            </datalist>
        </div>
        <table id="products" class="display">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>State</th>
                    @if (Auth()->user()->role!=="Facility")
                        <th>Location</th>
                    @endif
                    <th>Model</th>
                    <th>Serial Number</th>
                    <th>Tag Number</th>
                    <th>User</th>
                    @if (Auth()->user()->role=="Admin")
                        <th>Date of Purchase</th>
                    @endif
                    <th>Grant</th>
                    <th>Category</th>

                    <th>Batch</th>
                    <th>Condition</th>
                    <th>Date Delivered</th>
                    <th>Received By</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach($assets as $asset)
                <tr data-id="{{ $asset->id }}">
                    <td>{{ $asset->id }}</td>
                    <td contenteditable="false" data-column="state">{{ $asset->state }}</td>



                    @if (Auth()->user()->role!=="Facility")
                        @php
                            // Find the facility name by searching through the $facilities
                            $location = $locations->firstWhere('id', $asset->location);
                        @endphp
                        <td class="location-column" data-column="location">{{ $location ? $location->facility_name : 'Unknown Facility' }}</td>
                    @endif
                    <td class="model-column" data-column="model">{{ $asset->model }}</td>
                    {{-- <td contenteditable="true" data-column="model">{{ $asset->model }}</td> --}}
                    <td contenteditable="true" data-column="serial_number">{{ $asset->serial_number }}</td>
                    <td contenteditable="true" data-column="tag_number">{{ $asset->tag_number }}</td>
                    @php
                        // Find the facility name by searching through the $facilities
                        $user = $users->firstWhere('id', $asset->user);
                    @endphp
                    <td contenteditable="true" data-column="user">{{ $user ? $user->name : 'Unknown User' }}</td>
                    @if (Auth()->user()->role=="Admin")
                        <td contenteditable="true" data-column="date_of_purchase">{{ $asset->date_of_purchase }}</td>
                    @endif
                    <td contenteditable="true" data-column="grant">{{ $asset->grant }}</td>
                    <td contenteditable="true" data-column="category">{{ $asset->category }}</td>
                    <td contenteditable="true" data-column="batch">{{ $asset->batch }}</td>
                    <td class="condition-column" data-column="condition">{{ $asset->condition }}</td>

                    {{-- <td contenteditable="true" data-column="condition">{{ $asset->condition }}</td> --}}
                    <td contenteditable="true" data-column="date_delivered">{{ $asset->date_delivered }}</td>
                    <td contenteditable="true" data-column="received_by">{{ $asset->received_by }}</td>
                    <td contenteditable="true" data-column="comments">{{ $asset->comments }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {

            // $('#assetsTable').DataTable(); // Initialize DataTable
            let modifiedRows = [];
            let newRows = [];
            let newRowCounter = 1;

            $('td').each(function() {
                if ($(this).text().trim() === '') {
                    $(this).css('background-color', 'rgb(248, 215, 215)'); // Set background to light red
                }
            });

            // Add a new row
            $('#addRowButton').click(function() {
                let newRowId = `new-${newRowCounter++}`; // Unique ID for the new row

                let newRow = `<tr data-id="${newRowId}">
                    <td>New</td>
                    @if (Auth()->user()->role=="Admin")
                        <td contenteditable="true" data-column="state"></td>
                    @elseif (Auth()->user()->role=="Manager" || Auth()->user()->role=="Facility")
                        <td contenteditable="false" data-column="state">{{$state}}</td>
                    @endif

                    @if (Auth()->user()->role=="Admin" || Auth()->user()->role=="Manager")
                        <td class="location-column" data-column="location">
                        </td>

                    @elseif (Auth()->user()->role=="Facility")
                        <td contenteditable="false" data-column="location">{{Auth::user()->facilityName->facility_name}}</td>
                    @endif
                    <td contenteditable="true" class="model-column" data-column="model"></td>
                    <td contenteditable="true" data-column="serial_number"></td>
                    <td contenteditable="true" data-column="tag_number"></td>
                    <td contenteditable="true" data-column="user"></td>
                    @if (Auth()->user()->role=="Admin")
                        <td contenteditable="true" data-column="date_of_purchase"></td>
                    @endif
                    <td contenteditable="true" data-column="grant"></td>
                    <td contenteditable="true" data-column="category"></td>
                    <td contenteditable="true" data-column="batch"></td>
                    <td contenteditable="true" class="condition-column" data-column="condition"></td>
                    <td contenteditable="true" data-column="date_delivered"></td>
                    <td contenteditable="true" data-column="received_by"></td>
                    <td contenteditable="true" data-column="comments"></td>
                    <td><button class="btn btn-danger deleteRowButton">Delete</button></td>
                </tr>`;

                $('#products tbody').prepend(newRow);
            });

             // Handle location cell click and datalist behavior
            $(document).on('click', '.location-column', function() {
                let currentElement = $(this);
                let originalLocation = currentElement.text().trim();

                if (!currentElement.find('input').length) {
                    let inputElement = `<input type="text" list="locationList" class="location-input" value="${originalLocation}">`;
                    currentElement.html(inputElement);
                    let inputField = currentElement.find('input');
                    inputField.focus(); // Automatically focus the input

                    inputField.on('blur', function() {
                        let newLocation = inputField.val().trim();
                        inputField.remove();
                        currentElement.text(newLocation);

                        if (newLocation !== originalLocation) {
                            currentElement.addClass('edited-cell');
                            $(this).css('background-color', 'lightgreen');
                            updateModifiedRows(currentElement.closest('tr'), 'location', newLocation);
                        } else {

                            currentElement.removeClass('edited-cell');
                        }
                    });
                }
            });

            // Handle condition cell click and datalist behavior
            $(document).on('click', '.condition-column', function() {
                let currentElement = $(this);
                let originalCondition = currentElement.text().trim();

                if (!currentElement.find('input').length) {
                    let inputElement = `<input type="text" list="conditionList" class="condition-input" value="${originalCondition}">`;
                    currentElement.html(inputElement);
                    let inputField = currentElement.find('input');
                    inputField.focus(); // Automatically focus the input

                    inputField.on('blur', function() {
                        let newCondition = inputField.val().trim();
                        inputField.remove();
                        currentElement.text(newCondition);

                        if (newCondition !== originalCondition) {
                            currentElement.addClass('edited-cell');
                            $(this).css('background-color', 'lightgreen');
                            updateModifiedRows(currentElement.closest('tr'), 'condition', newCondition);
                        } else {
                            currentElement.removeClass('edited-cell');
                        }
                    });
                }
            });

            // Handle model cell click and datalist behavior
            $(document).on('click', '.model-column', function() {
                let currentElement = $(this);
                let originalModel = currentElement.text().trim();

                if (!currentElement.find('input').length) {
                    let inputElement = `<input type="text" list="modelList" class="model-input" value="${originalModel}">`;
                    currentElement.html(inputElement);
                    let inputField = currentElement.find('input');
                    inputField.focus(); // Automatically focus the input

                    inputField.on('blur', function() {
                        let newModel = inputField.val().trim();
                        inputField.remove();
                        currentElement.text(newModel);

                        if (newModel !== originalModel) {
                            currentElement.addClass('edited-cell');
                            updateModifiedRows(currentElement.closest('tr'), 'model', newModel);
                        } else {
                            currentElement.removeClass('edited-cell');
                        }
                    });
                }
            });

            // Track changes in other editable cells
            $(document).on('focus', '[contenteditable="true"]', function() {
                let cell = $(this);
                // Store original content when cell is first focused
                if (!cell.data('original')) {
                    cell.data('original', cell.text().trim());
                }
            });



            $(document).on('blur', '[contenteditable="true"]', function() {
                let cell = $(this);
                let originalContent = cell.data('original');
                let newContent = cell.text().trim();
                let column = cell.data('column');

                if (newContent !== originalContent) {
                    cell.addClass('edited-cell');
                    $(this).css('background-color', 'lightgreen');
                    updateModifiedRows(cell.closest('tr'), column, newContent);
                } else {
                    cell.removeClass('edited-cell');
                }

                // Update original content after blur
                cell.data('original', newContent);
            });

            // Function to update modifiedRows with changed data
            function updateModifiedRows(row, column, newValue) {
                let id = row.data('id');

                if (id && id.startsWith('new')) {
                    let existingRow = newRows.find(row => row.id === id);
                    if (!existingRow) {
                        let rowData = { id: id };
                        rowData[column] = newValue;
                        newRows.push(rowData);
                    } else {
                        existingRow[column] = newValue;
                    }
                } else {
                    let existingRow = modifiedRows.find(row => row.id === id);
                    if (!existingRow) {
                        let rowData = { id: id };
                        rowData[column] = newValue;
                        modifiedRows.push(rowData);
                    } else {
                        existingRow[column] = newValue;
                    }
                }
            }

            // Delete row
            $(document).on('click', '.deleteRowButton', function() {
                $(this).closest('tr').remove();
            });

            // Handle the "Update" button click
            $('#updateButton').click(function() {
                let dataToSave = modifiedRows.concat(newRows);

                if (dataToSave.length > 0) {
                    alert(JSON.stringify(dataToSave, null, 2));
                    $.ajax({
                        url: '{{ route("concurrencyUpdate") }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            assets: dataToSave
                        },
                        success: function(response) {
                            if (response.success) {
                                alert('Data updated successfully');
                                modifiedRows = []; // Clear modified rows after success
                                newRows = []; // Clear new rows after success
                                window.location.reload();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        },
                        error: function() {
                            alert('Error updating data');
                        }
                    });
                } else {
                    alert('No changes detected');
                }
            });

        });
    </script>
@endsection('content')
