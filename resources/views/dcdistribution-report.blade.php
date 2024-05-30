@extends('template')

@section('content')
    <div class="row" style="width:98% !important; margin:auto;" id="printable" data-logo="{{ $site_settings->logo }}">

        <h4 class=" center">
            DCTools Distribution Report

        </h4>

        @if ($distribution != null)
        <table id="products" class="display" style="width:100%;">
            <thead class="thead-dark">
                <tr>
                    <th>Tool Name</th>
                    <th>Qty Sent</th>
                    <th>Date</th>
                    <th>Sent From / State</th>
                    <th>Sent To / State</th>
                    <th>Send Ref</th>
                    <th>Sender</th>
                    <th>Send Remarks</th>
                    <th>Qty Received</th>
                    <th>Reciever</th>
                    <th>Batch No.</th>
                    <th>Receiver Ref</th>
                    <th>Receiver Remarks</th>

                </tr>
            </thead>
            <tbody>

                @foreach ($distribution as $dc)
                    <tr>

                        <td>{{$dc->dcTool->name}}</td>
                        <td>{{$dc->quantity_sent}}</td>
                        <td>{{$dc->date_sent}}</td>
                        <td>{{$dc->sentFrom->facility_name ?? ""}} / {{$dc->sentfrom_state}}</td>
                        <td>{{$dc->sentTo->facility_name ?? ""}} / {{$dc->sentto_state}}</td>
                        <td><a href="{{ asset('uploads/' . $dc->documents) }}"
                            target="_blank">{{ $dc->documents }}</a></td>
                        <td>{{$dc->sent_by}}</td>
                        <td>{{$dc->remarks}}</td>
                        <td>{{$dc->quantity_received}}</td>
                        <td>{{$dc->received_by}}</td>
                        <td>{{$dc->batchno}}</td>
                        <td><a href="{{ asset('uploads/' . $dc->rdocuments) }}"
                            target="_blank">{{ $dc->rdocuments }}</a></td>
                        <td>{{$dc->rremarks}}</td>

                    </tr>
                @endforeach
            </tbody>

        </table>
        @else
            <blockquote>No items found in the database.</blockquote>
        @endif

    </div>
@endsection
