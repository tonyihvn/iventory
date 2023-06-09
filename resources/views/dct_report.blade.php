@extends('largesheet_template')
@section('content')
    <div style="width: 100% !important; overflow: visible;">
        <b style="text-align: center;">DCT REPORT {{ $utilization[0]->remarks }}. <br> FROM {{ $from }} TO
            {{ $to }}</b> <small><i>Key: Qty = Quantity, St. Bal. = Stock Balance</i></small>

        <hr>
        <table id="products" class="print_table display striped bordered highlight" style="font-size: 0.8em;">
            <thead>
                <tr>
                    <th rowspan="2">DCT Tool Name</th>
                    <th rowspan="2">Category</th>

                    @foreach ($utilization->unique('facility_id') as $facility)
                        <th colspan="3">{{ $facility->facilityName->facility_name }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    @foreach ($utilization->unique('facility_id') as $facility)
                        <th>Benchmark</th>
                        <th>Qty Used</th>
                        <th>St. Bal.</th>
                    @endforeach
                </tr>


            </thead>
            <tbody>
                @php
                    $countfacilities = $utilization->unique('facility_id')->count();
                @endphp
                @foreach ($utilization->unique('item_id') as $key => $dist)
                    <tr>
                        <td>{{ $dist->toolName->tool_name }}</td>
                        <td>{{ $dist->toolName->category }}</td>


                        @foreach ($utilization->unique('facility_id') as $facility)
                            @php
                                $item = $utilization
                                    ->where('facility_id', $facility->facility_id)
                                    ->where('item_id', $dist->item_id)
                                    ->first();
                            @endphp
                            @if (isset($item))
                                <td>
                                    {{ $item->benchmark }}
                                </td>
                                <td>{{ $item->quantity_used }}</td>
                                <td>{{ $item->benchmark - $item->quantity_used }}</td>
                            @else
                                <td>0</td>
                                <td>0</td>
                                <td>0</td>
                            @endif
                        @endforeach



                    </tr>
                @endforeach
            </tbody>
        </table>


    </div>
@endsection
