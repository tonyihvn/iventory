@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m10 offset-m1" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add New Supply</h3>


                <form method="POST" action="{{ route('newBulkDCTSupply') }}" enctype="multipart/form-data">
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
                                <input id="quantity_supplied" type="number" class="validate" name="quantity_supplied[]"
                                    required>
                                <label for="quantity_supplied">Quantity Supplied</label>
                            </div>

                        </div>
                    @endforeach


                    <div class="row">
                        <div class="input-field col s6">
                            <input id="date_supplied" type="date" class="datepicker" name="date_supplied">
                            <label for="date_supplied">Date Supplied</label>
                        </div>


                        <div class="input-field col s6">
                            <select name="supplier" id="supplier" materialize="material_select" class="select2">
                                <option value='NA' selected>
                                    NA
                                </option>
                                @foreach ($suppliers as $sup)
                                    <option value='{{ $sup->supplier_name }} - ({{ $sup->company_name }})'>
                                        {{ $sup->supplier_name }} - ({{ $sup->company_name }})
                                    </option>
                                @endforeach
                            </select>
                            <label for="supplier" class="active">Supplier</label>
                        </div>
                    </div>

                    <div class="row">

                        <div class="input-field">
                            <input id="supplied_to" type="text" class="validate" value="GHLI Warehouse, Idu, Abuja"
                                name="supplied_to">
                            <label for="supplied_to">Supplied To</label>
                        </div>
                        <div class="input-field">
                            <input id="batchno" type="text" class="validate" name="batchno">
                            <label for="batchno">Batch Number</label>
                        </div>
                    </div>

                    <div class="input-field">
                        <textarea id="remarks" class="materialize-textarea" name="remarks"></textarea>
                        <label for="remarks">Description/ Remarks</label>
                    </div>
                    <div class="input-field text-right right" style="margin-bottom:20px;">

                        <button type="submit" class="btn">
                            Add Supplies
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>
    <script src="{{ asset('/js/lga.js') }}"></script>
@endsection
