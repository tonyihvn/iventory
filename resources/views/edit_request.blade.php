@extends('template')
@section('content')
    <div class="container">



                    @php
                        $position = strpos($item->item_name,", :::");
                    @endphp
                    @if (($position === false))
                        {{$item->item_name}}, Quantity: {{ $item->quantity_requested }}
                    @else

                        @php
                            // Given data
                            $data = rtrim($item->item_name, ", :::");
                            // Split the data by ":::" to get individual items
                            $items = explode(", :::", $data);
                            // Initialize arrays to store item names and quantities
                            $itemNames = [];
                            $itemQuantities = [];

                        @endphp
                        <table id="products" class="display" style="width:100%;">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($items as $it)
                                    @php $splitString = explode(" - Qty: ", $it); @endphp

                                        <tr>
                                            <td>{{isset($splitString[0]) ? $splitString[0] : ''}}</td>
                                            <td>{{isset($splitString[1]) ? $splitString[1] : ''}}</td>
                                        </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @endif


            <tr>
                <td colspan="2"><strong>Reason:</strong> <br> {{ $item->request_reason }}</td>

            </tr>



            <tr>
                <td><b>Date Requested: </b>{{ $item->created_at }}</td>
                <td>Location To Be Used: {{ $item->location }}</td>
            </tr>

            <tr>
                <td>Requested By:</td>
                <td>{{ $item->user->name }} from : {{ $item->state }}</td>
            </tr>



            <tr>
                <td>Comments:</td>
                <td>{{ $item->comments }}
                    <hr>
                    <b>Approval Remarks:</b>
                    {{ $item->remarks }}
                </td>
            </tr>


            <tr>
                <td colspan="2">
                    <h5>Update Request</h5>
                    <form action="{{ route('update_request') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <div class="input-field">
                            <input type="text" name="remarks" id="remarks" value="{{ $item->remarks }}"
                                class="validate">
                            <label for="remarks">Enter Remarks / Comments</label>
                        </div>
                        <div class="row">


                            <div class="input-field col s6">

                                <select name="request_status" id="request_status" materialize="material_select">
                                    <option value="{{ $item->request_status }}" selected>{{ $item->request_status }}
                                    </option>
                                    @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin')
                                        <option value="Recieved">Recieved</option>
                                        <option value="Processing">Processing</option>
                                        <option value="Delivered">Delivered</option>
                                        <option value="Cancelled">Cancelled</option>
                                    @endif
                                </select>
                                <label>Set Status</label>

                            </div>

                            <div class="input-field col s6 text-right right" style="margin-bottom:20px;">

                                <button type="submit" class="btn right">
                                    Update Request
                                </button>

                            </div>
                        </div>
                    </form>
                </td>
            </tr>

        </table>



    </div>
@endsection
