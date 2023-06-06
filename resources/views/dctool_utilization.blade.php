@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add Utilization</h3>


                <form method="POST" action="{{ route('savedcUtilization') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="input-field col s12">
                            <select name="item" id="item" materialize="material_select">
                                <option value='{{ $dctool->id }}'>{{ $dctool->tool_name }} - {{ $dctool->category }}
                                </option>
                            </select>
                            <label for="item">Tool Name</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s12">
                            <input id="remarks" type="text" class="validate" name="remarks">
                            <label for="remarks">Quarter / Title</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="input-field col s6">
                            <input id="date_sent" type="text" class="datepicker" name="date_from">
                            <label for="date_sent">From (to)</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="date_sent" type="text" class="datepicker" name="date_to">
                            <label for="date_sent">To (date)</label>
                        </div>
                    </div>

                    <table id="products" class="display responsive-table">
                        <thead>
                            <tr>
                                <th style="width: 80%">Facility Name</th>
                                <th>Quantity Used</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($facilities as $faci)
                                <tr>
                                    <td><input name="facility_id[]" value="{{ $faci->id }}"
                                            type="hidden">{{ $faci->facility_name }}</td>
                                    <td><input type="number" name="quantity_used[]" value="" class="validate"></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                    <div class="input-field text-right right" style="margin-bottom:20px;">
                        <button type="submit" class="btn">
                            Save Utilization
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>
    <script src="{{ asset('/js/lga.js') }}"></script>
@endsection
