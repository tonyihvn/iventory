@extends('template')

@section('content')

    <div class="row" style="width:98% !important; margin:auto;" id="printable" data-logo="{{ $site_settings->logo }}">

        <h4 class=" center">
            DC Tools
        </h4>

        @if ($dctools != null)
            @if (auth()->user()->role == 'DCTAdmin')
                <div>
                    <a href="{{ url('add-dctool') }}" class="btn btn-small btn-floating right pulse tooltipped"
                        data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>

                </div>
            @endif

            <table id="products" class="display responsive-table" style="width:100%;">
                <thead class="thead-dark">
                    <tr>

                        <th style="width: 50% !important;">Tool Name</th>
                        <th>Category</th>
                        <th>Stock Bal.</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($dctools as $dc)
                        <tr>

                            <td>{{ $dc->tool_name }}</td>
                            <td>{{ $dc->category }}</td>
                            <td>{{ $dc->stock->quantity_remaining ?? '' }}</td>

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
                                                <a href="{{ url('/add-dcstock/' . $dc->id) }}"
                                                    class="btn-floating btn-small waves-effect blue waves-light tooltipped"
                                                    data-position="top" data-tooltip="Add to Stock"><i
                                                        class="material-icons">add</i></a>
                                            </li>
                                        @endif
                                        <li>
                                            <a href="{{ url('/dcutilization/' . $dc->id) }}"
                                                class="btn-floating btn-small waves-effect purple waves-light tooltipped"
                                                data-position="top" data-tooltip="Enter Utilization"><i
                                                    class="material-icons">settings</i></a>
                                        </li>

                                        <li>
                                            <a href="{{ url('/send-dctools/' . $dc->id) }}"
                                                class="btn-floating btn-small waves-effect orange waves-light tooltipped"
                                                data-position="top" data-tooltip="Send / Transfer to Facilities"><i
                                                    class="material-icons">repeat</i></a>
                                        </li>
                                        <li>
                                            <a href="{{ url('/dctreport/' . $dc->id) }}"
                                                class="btn-floating btn-small waves-effect cyan waves-light tooltipped"
                                                data-position="top" data-tooltip="View Item Report"><i
                                                    class="material-icons">list</i></a>
                                        </li>


                                    </ul>
                                </div>


                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        @else
            <blockquote>No items found in the database.</blockquote>
        @endif

    </div>

@endsection
