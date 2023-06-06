@extends('print_template')
@section('content')
    <div class="container" style="width: 100% !important">
        <b style="text-align: center;">DCT TOOLS UTILIZATION</b>
        <hr>

        <div class="card" style="padding: 20px !important;">
            <b>FACILITY NAME: <br>
                {{ $utilization[0]->facilityName->facility_name ?? $distribution[0]->facilityName->facility_name }} </b>

        </div>
        <hr>

        <b style="text-align: center">Tools Recieved</b>

        <table class="table display card">
            <thead>
                <tr>
                    <th>Date Sent</th>
                    <th>Quantity</th>
                    <th>From</th>
                    <th>Sender/Reciever</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($distribution as $itd)
                    <tr>
                        <td>{{ $itd->date_sent }}</td>
                        <td>{{ $itd->quantity_sent }}</td>
                        <td>{{ $itd->sentFrom->facility_name }}</td>
                        <td><small>{{ $itd->sent_by }}/{{ $itd->received_by }}</small></td>
                        <td><a href="{{ asset('uploads/' . $itd->documents) }}" target="_blank">{{ $itd->documents }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <b>USAGE REPORT</b>
        <table id="products" class="print_table display responsive-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Tool Name</th>
                    <th>Duration</th>
                    <th>Benchmark</th>
                    <th>Quantity Used</th>
                    <th>Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($utilization as $utilz)
                    <tr>
                        <td>{{ $utilz->remarks }}</td>
                        <td>{{ $utilz->toolName->tool_name }}</td>
                        <td>{{ $utilz->dated_from . ' - ' . $utilz->dated_to }}</td>
                        <td>{{ $utilz->benchmark }}</td>
                        <td>{{ $utilz->quantity_used }}</td>
                        <td>{{ $utilz->benchmark - $utilz->quantity_used }}</td>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection
