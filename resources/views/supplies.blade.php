@extends('template')
@section('content')
    <div class="container" style="width: 98%">
        <div class="row">
            <div class="col m3 offset-m9">
                <a href="{{url('add-stock')}}" class="btn">Add New Supply</a>
            </div>
        </div>

        @if ($supplies != null)


            <table id="products" class="display" style="width:100%;">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>Qty Supplied</th>
                        <th>Date Supplied</th>
                        <th>Supplied To</th>
                        <th>Vendor</th>
                        <th>Batch No</th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>

                    @foreach ($supplies as $sup)
                        <tr>
                            <td>{{ $sup->Item->item_name }}</td>
                            <td>{{ $sup->quantity_supplied }}</td>
                            <td>{{ $sup->date_supplied }}</td>
                            <td>{{ $sup->supplied_to }}</td>
                            <td>{{ $sup->supplier }}</td>
                            <td>{{ $sup->batchno }}</td>
                            <th>{{$sup->remarks}}</th>
                        </tr>
                    @endforeach
                </tbody>

            </table>

        @else
            <blockquote>No items found in the database.</blockquote>
        @endif
    </div>
@endsection
