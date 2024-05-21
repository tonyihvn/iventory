@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add New Supply</h3>


                <form method="POST" action="{{ route('newSupply') }}" enctype="multipart/form-data">
                    @csrf
                    <small style="color: green; text-align: center;"><i>For multiple device entry seperate IHVN Tag No,
                            Serial Number, Device ID by commas</i></small>

                    <div class="row">
                        <div class="input-field col s12">
                            <select name="item" id="item" materialize="material_select"  class="select2">
                                <option value="" disabled>Select Item</option>
                                @foreach ($items->unique('item_name') as $it)
                                    <option value='{{ $it->id }}'>{{ $it->item_name }}</option>
                                @endforeach
                            </select>
                            <label for="item" class="active">Item Name</label>
                        </div>


                    </div>


                    <div class="row">
                        <div class="input-field col s4">
                            <input id="date_supplied" type="date" class="datepicker" name="date_supplied">
                            <label for="date_supplied">Date Supplied</label>
                        </div>

                        <div class="input-field col s4">
                            <input id="quantity_supplied" type="number" class="validate" name="quantity_supplied">
                            <label for="quantity_supplied">Quantity Supplied</label>
                        </div>

                        <div class="input-field col s4">
                            <input id="supplier" type="text" class="validate" name="supplier">
                            <label for="supplier">Supplier</label>
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
