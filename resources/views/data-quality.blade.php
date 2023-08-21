@extends('template')

@section('content')

    <div class="row" style="width:98% !important; margin:auto;" id="printable" data-logo="{{ $site_settings->logo }}">
        <div id="float">
            <button id="enable">Enable FixedHeader</button> <button id="disable">Disable FixedHeader</button>
        </div>
        <h4 class=" center">
            @if (auth()->user()->role == 'User')
                My Inventory
            @else
                {{ auth()->user()->role != 'Admin' ? auth()->user()->state : '' }}
                {{ isset($category) ? $category : 'Inventory' }}
            @endif

        </h4>

        @if ($inventories != null)


            <form action="{{ route('updateInventory') }}" method="POST">
                @csrf

                            <datalist id="facilities">
                                @foreach ($facilities as $facility)
                                    <option value='{{ $facility->facility_name }}' data-fid="{{ $facility->id }}">
                                @endforeach
                            </datalist>

                            <datalist id="users">
                                @foreach ($usrs as $us)
                                    <option value='{{ trim($us->name) }}' data-uid="{{ $us->id }}">
                                @endforeach
                            </datalist>

                            <datalist id="status">
                                    <option value="Operational">
                                    <option value="Not Operational">
                                    <option value="Lost">
                                    <option value="Archieved">
                                    <option value="Need Repairs">
                            </datalist>




                <table id="products" class="print_table display responsive-table" style="width:100%;">
                    <thead class="thead-dark">
                        <tr>
                            <th class="form-check" style="width: 3% !important">
                                <input type="checkbox" name="all" id="select-all">
                                <label for="select-all">All</label>
                            </th>
                            @if (auth()->user()->role=="Admin")
                                <th class="filter">State</th>
                            @endif
                            <th>Category</th>

                            <th class="filter">Item Name</th>
                            <th class="filter">IHVN Tag No</th>
                            <th>Serial Number</th>
                            <th>Service Tag/Others</th>

                            <th class="filter" style="width: 20% !important">Current Facility</th>

                            <th class="filter">Assigned To/User</th>
                            <th class="filter">Status</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($inventories as $inv)
                            <tr>
                                <td class="form-check">
                                    <input type="checkbox" class="validate" name="tid[]" id="t{{ $inv->id }}"  onclick="updateInvRecord({{$inv->id}})"
                                        value="{{ $inv->id }}"><label for="t{{ $inv->id }}"></label>
                                </td>
                                @if (auth()->user()->role=="Admin")
                                    <td style="font-size: 0.8em;">{{ $inv->state }}</td>
                                @endif
                                <td style="font-size: 0.8em;">{{ $inv->category }}</td>
                                <td>{{ $inv->item_name }}</td>
                                <td><input type="text" class="validate" name="ihvn_no[]" id="ihvntag{{ $inv->id }}"
                                        value="{{ $inv->ihvn_no }}"></td>
                                <td><input type="text" class="validate" name="serial_no[]" id="serialno{{ $inv->id }}"
                                        value="{{ $inv->serial_no }}"></td>
                                <td><input type="text" class="validate" name="tag_no[]" id="tagno{{ $inv->id }}"
                                        value="{{ $inv->tag_no }}"></td>
                                <td><input  class="validate" list="facilities" value="{{ $inv->facility_id == 2 ? $inv->facility : $facilities[array_search($inv->facility_id, array_column($facilities->toArray(), 'id'))]['facility_name'] }}" id="facilityl{{ $inv->id }}" onchange="updateInvFacility({{$inv->id}})">
                                            <input type="hidden" name="facility_id[]"  id="facility_id{{ $inv->id }}"
                                            value="{{ $inv->facility_id ?? '' }}">
                                        </td>
                                <td><input list="users" class="validate" id="userl{{ $inv->id }}" onchange="updateInvUser({{$inv->id}})"
                                        value="{{ $inv->user_id == 2 ? $inv->assigned_to : $usrs[array_search($inv->user_id, array_column($usrs->toArray(), 'id'))]['name'] }}">
                                        <input type="hidden" name="user_id[]"  id="user_id{{ $inv->id }}"
                                            value="{{ $inv->user_id ?? '' }}">
                                </td>
                                <td><input list="status" class="validate" name="status[]" id="status{{ $inv->id }}"
                                        value="{{ $inv->status }}"></td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
                <div class="input-field text-right col s4 offset-s8" style="margin-bottom: 20px;">

                    <button type="submit" class="btn" onclick="return confirm('Please ensure that you have checked (from the left) all the items you modified?')">
                        Update Records
                    </button>

                </div>
            </form>
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
