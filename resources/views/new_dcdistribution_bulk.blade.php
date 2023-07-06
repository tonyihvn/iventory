@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m12" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Item Distribution</h3>


                <form method="POST" action="{{ route('saveBulkdcDistribution') }}" enctype="multipart/form-data">
                    @csrf

                    @foreach ($dctools as $dctool)
                        <div class="row">
                            <div class="input-field col s10">
                                <select name="items[]" id="item" materialize="material_select">

                                    <option value='{{ $dctool->id }}'>{{ $dctool->tool_name }} - {{ $dctool->category }}
                                        ({{ $dctool->stock->quantity_remaining ?? '' }} in stock)
                                    </option>


                                </select>
                                <label for="item">Tool Name</label>
                            </div>
                            <div class="input-field col s2">
                                <input id="quantity_sent" type="number" class="validate" name="quantity_sent[]" required>
                                <label for="quantity_sent">Quantity Sent</label>
                            </div>

                        </div>
                    @endforeach
                    <div class="row">
                        <div class="input-field col s12">
                            <select name="sent_from" id="sent_from" materialize="material_select" class="select2">
                                <option value='{{ auth()->user()->facility }}' selected>
                                    {{ auth()->user()->facilityName->facility_name }}
                                </option>
                                @foreach ($facilities as $faci)
                                    <option value='{{ $faci->id }}'>{{ $faci->facility_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="sent_from" class="active">Sent From</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <select name="sent_to" id="sent_to" materialize="material_select" class="select2">
                                @foreach ($facilities as $faci)
                                    <option value='{{ $faci->id }}'>{{ $faci->facility_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="sent_to" class="active">Send To</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="file-field input-field col s8">
                            <div class="btn">
                                <span>Upload Documents</span>
                                <input type="file" name="documents" required>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>
                        <div class="input-field col s4">
                            <input id="batchno" type="text" class="validate" name="batchno">
                            <label for="batchno">Batch Number/Particulars</label>
                        </div>
                    </div>



                    <div class="row">
                        <div class="input-field col s4">
                            <input id="date_sent" type="text" class="datepicker" name="date_sent">
                            <label for="date_sent">Date Sent</label>
                        </div>
                        <div class="input-field col s4">
                            <input id="sent_by" type="text" class="validate" name="sent_by">
                            <label for="sent_by">Sender Name</label>
                        </div>

                        <div class="input-field col s4">
                            <input id="received_by" type="text" class="validate" name="received_by">
                            <label for="received_by">Reciever Name</label>
                        </div>

                    </div>


                    <div class="input-field">
                        <textarea id="remarks" class="materialize-textarea" name="remarks"></textarea>
                        <label for="remarks">Notes / Remarks</label>
                    </div>
                    <div class="input-field text-right right" style="margin-bottom:20px;">
                        <button type="submit" class="btn">
                            Send DC Tool
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection
