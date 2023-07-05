@extends('print_template')
@section('content')
    <div class="container" style="width: 100% !important">
        <b style="text-align: center;">DCT TOOLS RECEIPT CONFIRMATION</b>
        <hr>

        <div class="card" style="padding: 20px !important;">
            @if (auth()->user()->role == 'DCTUser')
                <b>FACILITY NAME: <br>
                    {{ isset($distribution[0]) ? $distribution[0]->facilityName->facility_name : 'No Distribution to your facility yet' }}
                </b>
            @else
                <b>STATE: <br>
                    {{ auth()->user()->state }}
                </b>
            @endif


        </div>
        <hr>

        <b style="text-align: center">Tools Recieved</b>

        <form action="{{ url('saveConfirmation') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row">

                <div class="file-field input-field col s4">
                    <div class="btn">
                        <span>Upload Delivery Note</span>
                        <input type="file" name="documents">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                    </div>
                </div>

                <div class="input-field col s5">
                    <input id="remarks" type="text" class="validate" name="remarks">
                    <label for="remarks">Remarks</label>
                </div>



                <div class="input-field text-right col s3">

                    <button type="submit" class="btn">
                        Confirm Delivery
                    </button>

                </div>
            </div>


            <table class="table display card" id="products" style="font-size: 0.8em">
                <thead>
                    <tr>
                        <th>Select</th>
                        <th>Date Sent</th>
                        <th style="width: 20% !important">Tool Name</th>
                        <th>Qty</th>
                        <th>From</th>
                        <th>Batch Number</th>
                        <th>Qty Recieved</th>
                        <th>Notes</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($distribution as $itd)
                        <tr>
                            <td class="form-check">
                                <input type="checkbox" class="iselect" name="tool_name[]" id="t{{ $itd->id }}"
                                    value="{{ $itd->id }}"><label for="t{{ $itd->id }}"></label>
                            </td>
                            <td>{{ $itd->date_sent }}</td>
                            <td>{{ $itd->dcTool->tool_name }}</td>
                            <td>{{ $itd->quantity_sent }}</td>
                            <td>{{ $itd->sentFrom->facility_name }}</td>
                            <td>{{ $itd->batchno }}</td>
                            <td class="form-input">
                                <input type="text" class="iselect" name="qty_recieved[]" id="qtyr{{ $itd->id }}"
                                    value="{{ $itd->quantity_sent }}"><label for="qtyr{{ $itd->id }}"></label>
                            </td>
                            <td>    @if($itd->documents!="")
                                    S: <a href="{{ asset('uploads/' . $itd->documents) }}"
                                    target="_blank">{{ $itd->documents }}</a>
                                    @endif
                                    @if($itd->rdocuments!="")
                                    <hr>R:
                                    <a href="{{ asset('uploads/' . $itd->rdocuments) }}"
                                        target="_blank">{{ $itd->rdocuments }}</a>
                                    @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
@endsection
