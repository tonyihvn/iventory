@extends('template')

@section('content')

<div class="row" style="width:98%; margin:auto;">
    <h5 class="text-center">Facilities in {{ $state }}</h5>

    <div style="margin-bottom: 20px;">
        <a href="{{url('/facilities')}}" class="btn btn-small waves-effect waves-light"><i class="material-icons left">arrow_back</i>Back to All Facilities</a>
        <a href="{{url('/add_facility')}}" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
    </div>

    <!-- Debug Info -->
    @php
        $testFacilities = \App\facilities::where('state', $state)->get();
    @endphp

    <table id="stateFacilitiesTable" class="display responsive-table" style="width:100%;">
        <thead class="thead-dark">
            <tr>
                <th>Facility Name</th>
                <th>Datim Facility No</th>
                <th>State</th>
                <th>LGA</th>
                <th>Town</th>
                <th>Phone No.</th>
                <th>Actions</th>
                <th>S/N</th>
            </tr>
        </thead>
        <tbody>
            @foreach($testFacilities as $facility)
            <tr>
                <td>{{$facility->facility_name}}</td>
                <td>{{$facility->facility_no}}</td>
                <td>{{$facility->state}}</td>
                <td>{{$facility->lga}}</td>
                <td>{{$facility->town}}</td>
                <td>{{$facility->phone_number}}</td>
                <td>
                    <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                        <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block">
                            <i class="small material-icons">menu</i>
                        </a>
                        <ul style="top: 0px !important">
                            <li>
                                <form method="POST" action="{{route('facilities.destroy',$facility->id)}}">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Are you sure you want to delete this facility?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item">
                                        <i class="material-icons">delete</i>
                                    </button>
                                </form>
                            </li>
                            <li>
                                <a href="{{url('/facility/'.$facility->id)}}" class="btn-floating btn-small waves-effect green waves-light tooltipped" data-position="top" data-tooltip="View/Update this Facility" target="_blank">
                                    <i class="material-icons">remove_red_eye</i>
                                </a>
                            </li>
                            <li>
                                <a href="{{url('/facilityitems/'.$facility->id)}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Facility Inventory" target="_blank">
                                    <i class="material-icons">list</i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </td>
                <td>{{$facility->id}}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Facility Name</th>
                <th>FacilityNo</th>
                <th>State</th>
                <th>LGA</th>
                <th>Town</th>
                <th>Phone No.</th>
                <th>Actions</th>
                <th>S/N</th>
            </tr>
        </tfoot>
    </table>
</div>

<script>
    // Wait for jQuery to be available
    var initializeDataTable = function() {
        if (typeof jQuery === 'undefined' || typeof jQuery.fn.DataTable === 'undefined') {
            // Retry if jQuery or DataTables not loaded
            setTimeout(initializeDataTable, 100);
            return;
        }
        
        console.log('Initializing DataTable');
        
        // Initialize DataTables on the pre-populated table
        jQuery('#stateFacilitiesTable').DataTable({
            paging: true,
            pageLength: 50,
            lengthMenu: [10, 25, 50, 100],
            searching: true,
            ordering: true,
            responsive: true,
            autoWidth: false,
            dom: 'lfrtp',
            initComplete: function() {
                console.log('DataTable initialization complete');
            }
        });
    };
    
    // Initialize when document is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeDataTable);
    } else {
        initializeDataTable();
    }
</script>

@endsection
