@extends('template')

@section('content')

    <div class="row" style="width:98%; margin:auto;">
        <a href="{{ url('/requests') }}" class="btn green right">All Requests</a>
        @if (!auth()->user()->role == 'Admin' || !auth()->user()->role == 'DCTAdmin')
            <div class="col s12 l8 offset-l2">
                <h3 class="card-header text-center" style="text-align:center;">New Item Request Form</h3>


                <form method="POST" action="{{ route('new_request') }}">
                    @csrf
                    <div class="row">
                        <div class="input-field col s4">
                            <select name="type" id="type" materialize="material_select">

                                <option value="Gadgets" selected>Gadgets</option>
                                <option value="DCT Tools">DCT Tools</option>

                            </select>
                            <label for="type">Select State</label>
                        </div>
                        <div class="input-field col s8">
                            <input list="dctools" id="item_name" type="text" class="validate" name="item_name"
                                placeholder="e.g. New Laptop, Ipad, Pen, Chair etc" required autofocus>

                            <label for="item_name">Item Name</label>

                            <datalist id="dctool">
                                @foreach ($dctools as $dctool)
                                    <option value="{{ $dctool->tool_name }}">
                                @endforeach

                            </datalist>

                        </div>


                    </div>

                    <div class="row">
                        <div class="input-field col s4">

                            <input id="quantity_requested" type="number" class="validate" name="quantity_requested"
                                value="1">
                            <label for="quantity_requested">Quantity Needed</label>
                        </div>

                        <div class="input-field  col s4">
                            <select name="state" id="state" materialize="material_select">

                                <option disabled selected>State</option>
                                <option value="FCT">FCT</option>
                                <option value="RIVERS">RIVERS</option>
                                <option value="NASARAWA">NASARAWA</option>
                                <option value="KATSINA">KATSINA</option>

                            </select>
                            <label for="state">Select State</label>
                        </div>

                        <div class="input-field col s4">
                            <select name="user_id" id="user_id" materialize="material_select">
                                <option value="" disabled selected>To Be Used By: </option>
                                <option value="1">Facility</option>
                                @foreach ($users as $user)
                                    <option value='{{ $user->id }}'>{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <label class="active">Select who will use this item</label>
                        </div>
                    </div>

                    <div class="input-field">
                        <select name="location" id="location" materialize="material_select">
                            <option value="" disabled selected>Facility</option>
                            @foreach ($facilities as $facility)
                                <option value='{{ $facility->facility_name }}'>{{ $facility->facility_name }}</option>
                            @endforeach
                        </select>
                        <label for="location">Select Location/ Facility</label>
                    </div>



                    <div class="input-field">
                        <textarea name="request_reason" id="request_reason" class="materialize-textarea"></textarea>
                        <label for="request_reason">Describe the Item and the Reason</label>
                    </div>

                    <div class="input-field">
                        <textarea name="comments" id="comments" class="materialize-textarea"></textarea>
                        <label for="comments">Comments</label>
                    </div>

                    <div class="input-field col">
                        <select name="requested_status" id="requested_status" materialize="material_select">
                            <option value="Sent" selected>Send</option>
                            @if (auth()->user()->role == 'Admin')
                                <option value="Recieved">Recieved</option>
                                <option value="Processing">Processing</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            @endif
                        </select>
                        <label>Set Status</label>
                    </div>

                    <div class="input-field text-right right" style="margin-bottom:20px;">

                        <button type="submit" class="btn">
                            Send Request
                        </button>

                    </div>
                </form>
            </div>
        @endif
        <div class="col s12">
            <h5 class="text-center center">All Item requests</h5>

            @if ($requests != null)
                <table id="requests" class="display responsive-table striped" style="width:100%;;">
                    <thead class="thead-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Requested By</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Comments/ <b>Remarks</b></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($requests as $re)
                            <tr>
                                <td>{{ $re->item_name }}</td>
                                <td>{{ $re->user->name }}</td>
                                <td>{{ $re->quantity_requested }}</td>
                                <td>{{ $re->request_status }}</td>
                                <td>{{ $re->comments }} <b> {{ $re->remarks }}</b></td>
                                <td>
                                    <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action"
                                        style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                        <a class="btn-floating btn-small dark-purple waves-effect waves-light"
                                            style="display: inline-block">
                                            <i class="small material-icons">menu</i>
                                        </a>
                                        <ul style="top: 0px !important">

                                            <li>
                                                <form method="POST" action="{{ route('request_destroy') }}">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $re->id }}">
                                                    <button
                                                        onclick="return confirm('Are you sure you want to delete this category?')"
                                                        class="btn-floating btn-small waves-effect red waves-light tooltipped"
                                                        data-position="top" data-tooltip="Delete this Item"><i
                                                            class="material-icons">delete</i></button>
                                                </form>
                                            </li>

                                            <li>
                                                <a href="{{ url('/request/' . $re->id) }}"
                                                    class="btn-floating btn-small waves-effect blue waves-light tooltipped"
                                                    data-position="top" data-tooltip="Update Request" target="_blank"><i
                                                        class="material-icons">list</i></a>
                                            </li>


                                        </ul>
                                    </div>

                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th>Item Name</th>
                            <th>Requested By</th>
                            <th>Qty</th>
                            <th>Status</th>
                            <th>Comments/ <b>Remarks</b></th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                </table>
                <div class="col m6 offset-m3">{{ $requests->links() }}</div>
            @else
                <blockquote>No Category found in the database.</blockquote>
            @endif
        </div>



    </div>
@endsection
