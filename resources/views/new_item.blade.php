@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add New Item</h3>


                <form method="POST" action="{{ route('inventories.store') }}" enctype="multipart/form-data">
                    @csrf
                    <small style="color: green; text-align: center;"><i>For multiple device entry seperate IHVN Tag No,
                            Serial Number, Device ID by commas</i></small>
                    <div class="row">
                        <div class="input-field col s12">
                            <select name="item_id" id="item_id" materialize="material_select" class="select2">
                                <option value="" disabled>Select Item Unique Name</option>
                                @foreach ($items as $it)
                                    <option value='{{ $it->id }}'>{{ $it->item_name }}</option>
                                @endforeach
                            </select>
                            <label for="item_id" class="active">Item Name (Unique)</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field col s4">
                            <input id="item_name" type="text" class="validate" name="item_name" required autofocus>
                            <label for="item_name">Item Name</label>
                        </div>

                        <div class="input-field col s2">
                            <input id="quantity_added" type="number" min="1" class="validate" name="quantity_added"
                                value="1" required>
                            <label for="quantity_added">Quantity</label>
                        </div>

                        <div class="input-field col s6">
                            <input id="ihvn_no" type="text" class="validate" value="" name="ihvn_no">
                            <label for="ihvn_no">IHVN Tag Number(s)</label>
                        </div>


                    </div>
                    <div class="row">

                        <div class="input-field col s6">
                            <input id="serial_no" type="text" class="validate" name="serial_no">
                            <label for="serial_no">Serial Number(s)</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="tag_no" type="text" class="validate" name="tag_no">
                            <label for="tag_no">Device ID(s)</label>
                        </div>
                    </div>

                    <div class="input-field">
                        <textarea id="description" class="materialize-textarea" name="description"></textarea>
                        <label for="description">Description</label>
                    </div>

                    <div class="file-field input-field">
                        <div class="btn">
                            <span>Upload Image</span>
                            <input type="file" name="file[]" multiple>
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" type="text">
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s4">
                            <select name="category" id="category" materialize="material_select">
                                <option value="" disabled>Select Category</option>
                                @foreach ($categories as $ca)
                                    <option value='{{ $ca->category_name }}'>{{ $ca->category_name }}</option>
                                @endforeach
                            </select>
                            <label for="category">Item Category</label>
                        </div>

                        <div class="input-field col s4">
                            <input name="type" id="type" list="type" type="text" class="validate">
                            <datalist id="type">
                                <option>Wooden</option>
                                <option>Metal</option>
                            </datalist>
                            <label for="type">Type (of material,... etc)</label>
                        </div>
                        <div class="input-field col s4">
                            <select name="status">
                                <option value='' disabled selected>Change Status</option>
                                <option value="Operational">Operational</option>
                                <option value="Not Operational">Not Operational</option>
                                <option value="Lost">Lost</option>
                                <option value="Archieved">Archived </option>
                                <option value="Need Repairs">Need Repairs</option>
                            </select>
                        </div>
                    </div>
                    @if (Auth()->user()->role == 'Admin')
                        <div class="row">
                            <div class="input-field col s4">
                                <input id="date_purchased" type="date" class="datepicker" name="date_purchased">
                                <label for="date_purchased">Date Delivered</label>
                            </div>

                            <div class="input-field col s4">
                                <input id="quantity" type="text" class="validate" name="quantity">
                                <label for="quantity">Quantity Delivered</label>
                            </div>

                            <div class="input-field col s4">
                                <input id="supplier" type="text" class="validate" name="supplier">
                                <label for="supplier">Recieved By</label>
                            </div>
                        </div>
                    @endif



                    <table class="table">
                        <thead>
                            <tr class="spechead">
                                <th>Property</th>
                                <th>Value/Description</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="item_list">


                        </tbody>
                    </table>

                    <a class="btn btn-small cyan pulse waves-effect waves-light add_item" href="#" id="1">
                        Add Specifications / Properties
                        <i class="material-icons">add</i>
                    </a>

                    <h5>Facility / User Location</h5>
                    <hr>

                    <div class="input-field">
                        <select name="state" id="state" materialize="material_select">
                            <option value="{{ Auth()->user()->state }}" selected>Auth()->user()->state</option>
                            @if (Auth()->user()->role=="Admin" || Auth()->user()->role=="Super")
                                <option value="ANAMBRA">ANAMBRA</option>
                                <option value="KWARA">KWARA</option>
                                <option value="EBONYI">EBONYI</option>
                                <option value="GOMBE">GOMBE</option>

                                <option value="FCT">FCT</option>
                                <option value="RIVERS">RIVERS</option>
                                <option value="NASARAWA">NASARAWA</option>
                                <option value="KATSINA">KATSINA</option>
                            @endif

                        </select>
                        <label for="state">Select State</label>
                    </div>


                    <div class="input-field">
                        <select name="facility" id="facility" materialize="material_select" class="select2">
                            <option value="" disabled selected>Facility</option>
                            @foreach ($facilities as $facility)
                                <option value='{{ $facility->id }}'>{{ $facility->facility_name }}</option>
                            @endforeach
                        </select>
                        <label for="facility" class="active">Select Facility</label>
                    </div>

                    <div class="input-field">
                        <select name="department" id="department" materialize="material_select">
                            <option value="" disabled selected>Department</option>
                            @foreach ($departments as $department)
                                <option value='{{ $department->id }}'>{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        <label>Select Department</label>
                    </div>

                    <div class="input-field">
                        <select name="unit" id="unit" materialize="material_select">
                            <option value="" disabled selected>Unit</option>
                            @foreach ($units as $unit)
                                <option value='{{ $unit->id }}'>{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                        <label>Select Unit</label>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <select name="user[]" id="user" materialize="material_select" class="select2" multiple>
                                <option value="{{ Auth()->user()->id }}" selected>{{ Auth()->user()->name }}</option>
                                @if (Auth()->user()->role != 'User')
                                    @foreach ($users as $user)
                                        <option value='{{ $user->id }}'>{{ $user->name }}</option>
                                    @endforeach
                                @endif
                                <option value="0">Not Listed - Add User's Name</option>
                            </select>
                            <label for="user" class="active">Select User</label>
                        </div>

                        <div class="input-field col s6" id="new_username">
                            <input id="new_user" type="text" class="validate" name="new_user">
                            <label for="new_user" class="active">Enter Name</label>
                        </div>
                    </div>

                    <div class="row">

                        <div class="input-field col s6">
                            <select name="added_by" class="initialized" materialize="material_select">
                                @auth
                                    <option value='{{ auth()->user()->id }}' selected>{{ auth()->user()->name }}</option>
                                @endauth
                                @guest
                                    <option value="Anonymous" selected>Added By</option>
                                @endguest

                            </select>
                            <label>Added By</label>
                        </div>

                        <div class="input-field col s6">
                            <input id="internal_location" type="text" class="validate" name="internal_location">
                            <label for="internal_location">Internal Location - e.g ICT Room</label>
                        </div>
                    </div>


                    <row>
                        <div class="input-field col s12">
                            <input id="remarks" type="text" class="validate" name="remarks">
                            <label for="remarks">Batch No (e.g. Oct2024)</label>
                        </div>
                    </row>

                    <row>

                        <div class="input-field col s6">
                            <input id="concurrency" type="checkbox" class="validate" name="concurrency" value="Yes">
                            <label for="concurrency">Add to Concurrency Record</label>
                        </div>

                        @if (Auth()->user()->role=="Admin" || Auth()->user()->role=="Super")
                            <div class="input-field col s6">
                                <input id="deduct_stock" type="checkbox" class="validate" name="deduct_stock" value="Yes">
                                <label for="deduct_stock">Deduct from Stock</label>
                            </div>
                        @endif

                    </row>
                    <div class="input-field text-right right" style="margin-bottom:20px;">

                        <button type="submit" class="btn">
                            Add Item
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection
