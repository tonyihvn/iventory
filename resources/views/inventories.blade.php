@extends('template')

@section('content')

    <div class="row" style="width:98% !important; margin:auto;" id="printable" data-logo="{{ $site_settings->logo }}">

        <h4 class=" center">
            @if (auth()->user()->role == 'User')
                My Items Inventory
            @else
                {{ auth()->user()->role != 'Admin' ? auth()->user()->state. ' - ' : '' }}
                {{ isset($category) ? "Inventory of " .$category : 'List of All Items' }}
            @endif

        </h4>

        @if ($inventories != null)

            @if (auth()->user()->role != 'Observer')
                <div>
                    <a href="{{ url('/add_item') }}" class="btn btn-small btn-floating right pulse tooltipped"
                        data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>

                </div>
            @endif
            <form action="{{ route('fixItems') }}" method="POST">
                @csrf

                @if (auth()->user()->role != 'Observer')
                    <div class="row">
                        <div class="input-field col s1">
                            <label>With Selected:</label>
                        </div>
                        <div class="input-field col s2">

                            <select name="facility" id="facility" materialize="material_select" class="select2">
                                <option value="" disabled selected>Change Facility</option>
                                @foreach ($facilities as $facility)
                                    <option value='{{ $facility->id }}'>{{ $facility->facility_name }}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="input-field col s2">
                            <select name="category">
                                <option value='' disabled selected>Change Category</option>
                                @foreach ($categories as $ca)
                                    <option value='{{ $ca->category_name }}'>{{ $ca->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="input-field col s1">
                            <select name="status">
                                <option value='' disabled selected>Change Status</option>
                                @if (Auth()->user()->role != 'User')
                                    <option value="Operational">Operational</option>
                                    <option value="Not Operational">Not Operational</option>
                                    <option value="Lost">Lost</option>
                                    <option value="Archieved">Archived </option>
                                    <option value="Need Repairs">Need Repairs</option>
                                @endif
                            </select>
                        </div>

                        <div class="input-field col s2">
                            <select name="item_id"  materialize="material_select" class="select2">
                                <option value='' disabled selected>Update Unique Name</option>
                                @foreach ($items as $it)
                                    <option value='{{ $it->id }}'>{{ $it->item_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if (auth()->user()->role == 'Admin')
                            <div class="input-field col s1">
                                <select name="delete_items"  materialize="material_select">
                                    <option value='' disabled selected>Delete?</option>
                                <option value="Delete">Delete</option>
                                </select>
                            </div>
                        @endif

                        <div class="input-field text-right col s2">

                            <button type="submit" class="btn"  onclick="return confirm('Are you sure you want to save this actions. Please ensure that you check the edited items?')">
                                Save
                            </button>

                        </div>

                    </div>
                @endif

                <table id="products" class="print_table display responsive-table" style="width:100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th class="form-check">
                                <input type="checkbox" name="all" id="select-all">
                                <label for="select-all">All</label>
                            </th>
                            <th class="filter">State</th>
                            <th class="filter">Item Name</th>
                            <th class="filter">ID/IHVN/TAG No</th>
                            <th class="filter">Category</th>
                            <th class="filter">Facility</th>

                            <th class="filter">Assigned To/User</th>
                            <th class="filter">Status</th>

                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($inventories as $inv)
                            <tr>
                                <td class="form-check">
                                    <input type="checkbox" class="iselect" name="tid[]" id="t{{ $inv->id }}"
                                        value="{{ $inv->id }}"><label for="t{{ $inv->id }}"></label>
                                </td>
                                <td>{{ $inv->state }}</td>
                                <td>{{ $inv->item_name }}</td>
                                <td>{{ $inv->serial_no }} / {{ $inv->ihvn_no }} / {{ $inv->tag_no }}</td>
                                <td>{{ $inv->category }}</td>
                                <td>
                                    {{ $inv->facility_id == 2 ? $inv->facility : $facilities[array_search($inv->facility_id, array_column($facilities->toArray(), 'id'))]['facility_name'] }}
                                </td>
                                <td>{{ $inv->user_id == 2 ? $inv->assigned_to : $usrs[array_search($inv->user_id, array_column($usrs->toArray(), 'id'))]['name'] }}
                                </td>
                                <td>{{ $inv->status }}</td>
                                <td>
                                    @if (auth()->user()->role != 'Observer')
                                        <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action"
                                            style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                            <a class="btn-floating btn-small dark-purple waves-effect waves-light"
                                                style="display: inline-block">
                                                <i class="small material-icons">menu</i>
                                            </a>
                                            <ul style="top: 0px !important">
                                                @if (Auth()->user()->role == 'Admin')
                                                    <li>

                                                        <button
                                                            onclick="return confirm('Are you sure you want to delete this item?')"
                                                            class="btn-floating btn-small waves-effect red waves-light tooltipped"
                                                            data-position="top" data-tooltip="Delete this Item"><i
                                                                class="material-icons">delete</i></button>

                                                    </li>
                                                @endif
                                                <li>
                                                    <a href="{{ url('/print_item/' . $inv->id) }}" target="_blank"
                                                        class="btn-floating btn-small waves-effect blue waves-light tooltipped"
                                                        data-position="top" data-tooltip="View Item"><i
                                                            class="material-icons">remove_red_eye</i></a>
                                                </li>
                                                <li>
                                                    <a href="{{ url('/item/' . $inv->id) }}"
                                                        class="btn-floating btn-small waves-effect blue waves-light tooltipped"
                                                        data-position="top" data-tooltip="Edit Item"><i
                                                            class="material-icons">create</i></a>
                                                </li>

                                                <li>
                                                    <a href="{{ url('/move_item/' . $inv->id) }}"
                                                        class="btn-floating btn-small waves-effect blue waves-light tooltipped"
                                                        data-position="top" data-tooltip="Move / Transfer Item"><i
                                                            class="material-icons">repeat</i></a>
                                                </li>


                                            </ul>
                                        </div>
                                    @endif

                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </form>
            <div id="float">
                <button id="enable">Enable FixedHeader</button> <button id="disable">Disable FixedHeader</button>
            </div>
        @else
            <blockquote>No items found in the database.</blockquote>
        @endif

    </div>

    <!-- Modal Structure -->
    <div id="fileModal" class="modal bottom-sheet">
        <div class="modal-content">
            <h4 id="filename"></h4>
            <p><iframe src="" id="fileframe" frameborder="0" style="width:100%;min-height:640px;"></iframe></p>
        </div>
        <div class="modal-footer">
            <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>
@endsection
