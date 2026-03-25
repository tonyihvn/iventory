@extends('template')

@section('content')

    <div class="row" style="width:98%; margin:auto;">
        <div class="col s12 m3">
            <h6 class="text-center"><strong>Filter by State</strong></h6>
            <div style="border: 1px solid #ddd; padding: 15px; border-radius: 5px; max-height: 600px; overflow-y: auto;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 10px;">
                        <a href="{{url('/facilities')}}" class="btn-small waves-effect waves-light" style="width: 100%; text-align: center; display: block;">
                            All Facilities
                        </a>
                    </li>
                    @php
                        $states = App\Http\Controllers\StateFacilitiesController::getUniqueStates();
                    @endphp
                    @forelse ($states as $state)
                        <li style="margin-bottom: 10px;">
                            <a href="{{url('/state-facilities/' . urlencode($state))}}" class="btn-small waves-effect waves-light" style="width: 100%; text-align: center; display: block;">
                                {{ $state }}
                            </a>
                        </li>
                    @empty
                        <li><em style="color: #999;">No states available</em></li>
                    @endforelse
                </ul>
            </div>
        </div>

        <div class="col s12 m9">
            <h5 class="text-center">List of Facilities</h5>

            @if ($facilities!=NULL)
              <div>
                  <a href="{{url('/add_facility')}}" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
              </div>
            <table id="products" class="display responsive-table" style="width:100%;;">
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
                    @foreach ($facilities as $facility)
                    <tr>
                        <td>{{$facility->facility_name}}</td>
                        <td>{{$facility->facility_no}}</td>
                        <td>{{$facility->state}}</td>
                        <td>{{$facility->lga}}</td>
                        <td>{{$facility->town}}</td>
                        <td>{{$facility->phone_number}}</td>

                        <td>
                            <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                    <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                        <i class="small material-icons">menu</i>
                                    </a>
                                    <ul style="top: 0px !important">

                                        <li>
                                                <form method="POST" action="{{route('facilities.destroy',$facility->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                <button onclick="return confirm('Are you sure you want to delete this facility?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item"><i class="material-icons">delete</i></button>
                                                </form>
                                        </li>


                                        <li>
                                                <a href="{{url('/facility/'.$facility->id)}}" class="btn-floating btn-small waves-effect green waves-light tooltipped" data-position="top" data-tooltip="View/Update this Facility"  target="_blank"><i class="material-icons" >remove_red_eye</i></a>
                                        </li>

                                        <li>
                                                <a href="{{url('/facilityitems/'.$facility->id)}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Facility Inventory" target="_blank"><i class="material-icons">list</i></a>
                                        </li>


                                    </ul>
                            </div>

                        </td>
                        <td>{{$facility->id}}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="7">

                                    {{ $facilities->links() }}

                        </td>
                    </tr>
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

            @else
                <blockquote>No Products found in the database.</blockquote>
            @endif
        </div>
    </div>
@endsection
