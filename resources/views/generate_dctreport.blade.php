@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Generate DCT Report</h3>


                <form method="POST" action="{{ route('generateDCTReport') }}" target="_blank" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="input-field col s12">

                            <select name="items[]" id="items" materialize="material_select" class="select2" multiple>
                                <option value="All">All Tools</option>
                                @foreach ($dctools as $dctool)
                                    <option value='{{ $dctool->id }}'>{{ $dctool->tool_name }} - {{ $dctool->category }}
                                    </option>
                                @endforeach

                            </select>
                            <label for="items" class="active">Select Tool(s)</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s6">
                            <input id="date_from" type="text" class="datepicker" name="date_from">
                            <label for="date_from">From (date)</label>
                        </div>

                        <div class="input-field col s6">
                            <input id="date_to" type="text" class="datepicker" name="date_to">
                            <label for="date_to">To (date)</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s12">
                            <select name="facilities[]" id="facilities" materialize="material_select" class="select2"
                                multiple>
                                <option value="All">All Facilities</option>
                                <option value='{{ auth()->user()->facility }}' selected>
                                    {{ auth()->user()->facilityName->facility_name }}
                                </option>
                                @foreach ($facilities as $faci)
                                    <option value='{{ $faci->id }}'>{{ $faci->facility_name }}
                                    </option>
                                @endforeach
                            </select>
                            <label for="facilities" class="active">Select Facilities</label>
                        </div>
                    </div>
                    <div class="input-field text-right right" style="margin-bottom:20px;">
                        <button type="submit" class="btn">
                            Generate
                        </button>
                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection
