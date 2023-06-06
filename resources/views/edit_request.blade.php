@extends('template')
@section('content')
    <div class="container">


        <table class="striped">
            <tr>
                <td style="width:35%;">Item Name: <br>
                    <h4>{{ $item->item_name }}</h4>
                </td>
                <td>Quantity Needed: <br>
                    <h4>{{ $item->quantity_requested }}</h4>
                </td>
            </tr>

            <tr>
                <td colspan="2"><strong>Reason:</strong> <br> {{ $item->request_reason }}</td>

            </tr>



            <tr>
                <td><b>Date Requested: </b>{{ $item->created_at }}</td>
                <td>Location To Be Used: {{ \App\facilities::where('id', $item->location)->first()->facility_name }}</td>
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
                                    @if (auth()->user()->role == 'Admin')
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
