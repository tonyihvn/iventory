@extends('print_template')
@section('content')
    <div class="container" style="width: 100% !important">
        <b style="text-align: center;">DCT TOOL REPORT</b>
        <hr>

        <div class="card" style="padding: 20px !important;">
            <b>ITEM NAME: <br> {{ $item->tool_name }} </b>

            <b>Category: {{ $item->category }}</b> <br>
            @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin')
                <small>
                    <i>Stock Balance:
                        {{ $item->stock->quantity_remaining ?? '' }}</i>
                </small>
            @endif
            <p>{{ $item->description }}</p>
        </div>
        <hr>
        @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin')
            <b style="text-align: center">Supplies</b>

            <table class="table display card">
                <thead>
                    <tr>
                        <th>Date of Supply</th>
                        <th>Quantity</th>
                        <th>Supplier</th>
                        <th>Batch No</th>
                        <th>Supplied To</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemsupplies as $its)
                        <tr>
                            <td>{{ $its->date_supplied }}</td>
                            <td>{{ $its->quantity_supplied }}</td>
                            <td>{{ $its->supplier }}</td>
                            <td>{{ $its->batchno }}</td>
                            <td>{{ $its->supplied_to }}</td>
                            <td>{{ $its->remarks }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        <hr>
        <b>Item Distributiion</b><br>
        @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin' || auth()->user()->state == 'FCT')
            <b>FCT</b>
            <table style="background-color: #ccc">
                <tr>
                    <td>Total Received: <b>{{ $itemdist->where('sent_to', 346)->sum('quantity_sent') }}</b></td>
                    <td>Total Issued to Facilities:
                        <b>{{ $itemdist->where('sentfrom_state', 'FCT')->sum('quantity_sent') }}</b>
                    </td>
                    <td>FCT Stock Balance:
                        <b>{{ $itemdist->where('sent_to', 346)->sum('quantity_sent') - $itemdist->where('sent_from', 346)->sum('quantity_sent') }}</b>
                    </td>

                </tr>
            </table>
            <table class="table display card">
                <thead>
                    <tr>
                        <th>Date Sent</th>
                        <th>Quantity</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Sender/Reciever</th>
                        <th>Download Notes</th>
                        <th>Utilization</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemdist->where('sentto_state', 'FCT') as $itd)
                        <tr>
                            <td>{{ $itd->date_sent }}</td>
                            <td>{{ $itd->quantity_sent }}</td>
                            <td>{{ $itd->sentFrom->facility_name ?? '' }}</td>
                            <td>{{ $itd->sentTo->facility_name ?? '' }}</td>
                            <td><small>{{ $itd->sent_by . '/' . $itd->received_by }}</small></td>
                            <td><a href="{{ asset('uploads/' . $itd->documents) }}"
                                    target="_blank">{{ $itd->documents }}</a>
                            </td>
                            <td>
                                @if ($itd->sentfrom_state != 'WAREHOUSE')
                                    <a href="{{ url('/futilization/' . $itd->sent_to) }}">View Usage</a>
                                @endif
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin' || auth()->user()->state == 'Nasarawa')
            <hr>
            <b>Nasarawa</b>
            <table style="background-color: #ccc">
                <tr>
                    <td>Total Received: <b>{{ $itemdist->where('sentto_state', 'Nasarawa')->sum('quantity_sent') }}</b>
                    </td>
                    <td>Total Issued to Facilities:
                        <b>{{ $itemdist->where('sentfrom_state', 'Nasarawa')->sum('quantity_sent') }}</b>
                    </td>
                    <td>FCT Stock Balance:
                        <b>{{ $itemdist->where('sentto_state', 'Nasarawa')->sum('quantity_sent') - $itemdist->where('sentfrom_state', 'Nasarawa')->sum('quantity_sent') }}</b>
                    </td>

                </tr>
            </table>
            <table class="table display card">
                <thead>
                    <tr>
                        <th>Date Sent</th>
                        <th>Quantity</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Sender/Reciever</th>
                        <th>Download Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemdist->where('sentto_state', 'Nasarawa') as $itd)
                        <tr>
                            <td>{{ $itd->date_sent }}</td>
                            <td>{{ $itd->quantity_sent }}</td>
                            <td>{{ $itd->sentFrom->facility_name }}</td>
                            <td>{{ $itd->sentTo->facility_name }}</td>
                            <td>{{ $itd->sent_by }}</td>
                            <td><a href="{{ asset('uploads/' . $itd->documents) }}"
                                    target="_blank">{{ $itd->documents }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin' || auth()->user()->state == 'Rivers')
            <hr>
            <b>Rivers</b>
            <table style="background-color: #ccc">
                <tr>
                    <td>Total Received: <b>{{ $itemdist->where('sentto_state', 'Rivers')->sum('quantity_sent') }}</b></td>
                    <td>Total Issued to Facilities:
                        <b>{{ $itemdist->where('sentfrom_state', 'Rivers')->sum('quantity_sent') }}</b>
                    </td>
                    <td>FCT Stock Balance:
                        <b>{{ $itemdist->where('sentto_state', 'Rivers')->sum('quantity_sent') - $itemdist->where('sentfrom_state', 'Rivers')->sum('quantity_sent') }}</b>
                    </td>

                </tr>
            </table>
            <table class="table display card">
                <thead>
                    <tr>
                        <th>Date Sent</th>
                        <th>Quantity</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Sender/Reciever</th>
                        <th>Download Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemdist->where('sentto_state', 'Rivers') as $itd)
                        <tr>
                            <td>{{ $itd->date_sent }}</td>
                            <td>{{ $itd->quantity_sent }}</td>
                            <td>{{ $itd->sentFrom->facility_name }}</td>
                            <td>{{ $itd->sentTo->facility_name }}</td>
                            <td>{{ $itd->sent_by }}</td>
                            <td><a href="{{ asset('uploads/' . $itd->documents) }}"
                                    target="_blank">{{ $itd->documents }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
        @if (auth()->user()->role == 'Admin' || auth()->user()->role == 'DCTAdmin' || auth()->user()->state == 'Katsina')
            <hr>
            <b>Katsina</b>
            <table style="background-color: #ccc">
                <tr>
                    <td>Total Received: <b>{{ $itemdist->where('sentto_state', 'Katsina')->sum('quantity_sent') }}</b></td>
                    <td>Total Issued to Facilities:
                        <b>{{ $itemdist->where('sentfrom_state', 'Katsina')->sum('quantity_sent') }}</b>
                    </td>
                    <td>FCT Stock Balance:
                        <b>{{ $itemdist->where('sentto_state', 'Katsina')->sum('quantity_sent') - $itemdist->where('sentfrom_state', 'Katsina')->sum('quantity_sent') }}</b>
                    </td>

                </tr>
            </table>
            <table class="table display card">
                <thead>
                    <tr>
                        <th>Date Sent</th>
                        <th>Quantity</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Sender/Reciever</th>
                        <th>Download Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($itemdist->where('sentto_state', 'Katsina') as $itd)
                        <tr>
                            <td>{{ $itd->date_sent }}</td>
                            <td>{{ $itd->quantity_sent }}</td>
                            <td>{{ $itd->sentFrom->facility_name }}</td>
                            <td>{{ $itd->sentTo->facility_name }}</td>
                            <td>{{ $itd->sent_by }}</td>
                            <td><a href="{{ asset('uploads/' . $itd->documents) }}"
                                    target="_blank">{{ $itd->documents }}</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
