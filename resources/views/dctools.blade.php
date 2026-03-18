@extends('template')

@section('content')
    <div class="row" style="width:98% !important; margin:auto;" id="printable" data-logo="{{ $site_settings->logo }}">

        <h4 class=" center">
            Data Collection Tools
            @if (auth()->user()->role == 'DCTManager')
                for {{ auth()->user()->state }}
            @endif
        </h4>

        @if ($dctools != null)
            @if (auth()->user()->role == 'DCTAdmin')
                <div>
                    <a href="{{ url('add-dctool') }}" class="btn btn-small btn-floating right pulse tooltipped"
                        data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>

                </div>
            @endif

            @if (isset($assignedFacilities))
            <div class="row" style="float: right;">
                <form action="{{route('switchFacility')}}" method="post">
                    @csrf
                    <div class="input-field col s6">
                        <select name="facility_id" id="facility_id" materialize="material_select" class="select2">

                            <option value="" selected disabled>
                                Select to Switch
                            </option>
                            @foreach ($assignedFacilities as $facility)
                                <option value="{{ $facility->id }}">{{ $facility->facility_name }}</option>
                            @endforeach
                        </select>
                        <label for="facility_id" class="active">Switch to Another Facility</label>
                    </div>

                    <div class="input-field col s6" style="text-align:right; margin-bottom: 20px;">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                Switch
                            </button>
                        </div>
                    </div>


                </form>
            </div>
        @endif
            <form action="{{ url('bulkToolAction') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="input-field col s5 offset-s2">
                        <select name="action">
                            <option value='' disabled selected>With Selected</option>
                            @if (Auth()->user()->role == 'Admin' || Auth()->user()->role == 'DCTAdmin')
                                <option value="Supply">Enter Supply Record</option>
                                <option value="Distribution">Enter Distribution Record</option>
                            @elseif (Auth()->user()->role == 'DCTManager')
                                <option value="Distribution">Enter Distribution Record</option>
                            @endif
                        </select>
                    </div>



                    <div class="input-field text-right col s3">

                        <button type="submit" class="btn">
                            Go
                        </button>

                    </div>
                </div>

                <table id="products" class="display" style="width:100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Select</th>
                            <th style="width: 50% !important;">Tool Name</th>
                            <th>Category</th>
                            <th>Program Area</th>
                            <th>Stock Bal.</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($dctools as $dc)
                            <tr>
                                <td class="form-check">
                                    <input type="checkbox" class="iselect" name="tool_name[]" id="t{{ $dc->id }}"
                                        value="{{ $dc->id }}"><label for="t{{ $dc->id }}"></label>
                                </td>
                                <td>{{ $dc->tool_name }}</td>
                                <td>{{ $dc->category }}</td>
                                <td>{{ $dc->description }}</td>
                              
                                @if (auth()->user()->role == 'DCTManager' || auth()->user()->role == 'Manager')
                                    <td>{{ isset($dc->distributions) ?  $dc->distributions->where('sentto_state', auth()->user()->state)->sum('quantity_received') - $dc->distributions->where('sentfrom_state', auth()->user()->state)->sum('quantity_sent')  : 0 }}
                                    </td>
                                @elseif (auth()->user()->role == 'DCTUser')
                                    <td>{{ isset($dc->distributions) ? $dc->distributions->where('sent_to', $selectedFacility ? $selectedFacility : auth()->user()->facilityName->id)->sum('quantity_received') - $dc->distributions->where('sent_from', $selectedFacility ? $selectedFacility : auth()->user()->facilityName->id)->sum('quantity_sent') : 0 }}
                                    </td>
                                @elseif (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin')
                                    <td>{{ $dc->stock->quantity_remaining ?? 0 }}</td>
                                @endif
                      

                                <td>

                                    <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action"
                                        style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                        <a class="btn-floating btn-small dark-purple waves-effect waves-light"
                                            style="display: inline-block">
                                            <i class="small material-icons">menu</i>
                                        </a>
                                        <ul style="top: 0px !important">
                                            @if (Auth()->user()->role == 'Admin' || Auth()->user()->role == 'DCTAdmin')
                                                <li>
                                                    <a href="{{ url('/add-dcstock/' . $dc->id) }}" target="_blank"
                                                        class="btn-floating btn-small waves-effect blue waves-light tooltipped"
                                                        data-position="top" data-tooltip="Add to Stock"><i
                                                            class="material-icons">add</i></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/edit-dctool/' . $dc->id) }}"
                                                        class="btn-floating btn-small waves-effect green waves-light tooltipped"
                                                        data-position="top" data-tooltip="Edit DCTool"><i
                                                            class="material-icons">edit</i></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/del-dct/' . $dc->id) }}"
                                                        class="btn-floating btn-small waves-effect red waves-light tooltipped"
                                                        data-position="top" data-tooltip="Delete DCTool"><i
                                                            class="material-icons">remove</i></a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ url('/dcutilization/' . $dc->id) }}" target="_blank"
                                                    class="btn-floating btn-small waves-effect purple waves-light tooltipped"
                                                    data-position="top" data-tooltip="Enter Utilization"><i
                                                        class="material-icons">settings</i></a>
                                            </li>

                                            <li>
                                                <a href="{{ url('/send-dctools/' . $dc->id) }}" target="_blank"
                                                    class="btn-floating btn-small waves-effect orange waves-light tooltipped"
                                                    data-position="top" data-tooltip="Send / Transfer to Facilities"><i
                                                        class="material-icons">repeat</i></a>
                                            </li>
                                            @if (Auth()->user()->role != 'DCTUser')
                                                <li>
                                                    <a href="{{ url('/dctreport/' . $dc->id) }}" target="_blank"
                                                        class="btn-floating btn-small waves-effect cyan waves-light tooltipped"
                                                        data-position="top" data-tooltip="View Item Report"><i
                                                            class="material-icons">list</i></a>
                                                </li>
                                            @endif


                                        </ul>
                                    </div>


                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </form>
        @else
            <blockquote>No items found in the database.</blockquote>
        @endif

    </div>
@endsection
