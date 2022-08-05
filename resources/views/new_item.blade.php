@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add New Item</h3>


                    <form method="POST" action="{{ route('inventories.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="input-field col s6">
                                    <input id="item_name" type="text" class="validate" name="item_name" required autofocus>
                                    <label for="item_name">Item Name</label>
                            </div>

                            <div class="input-field col s6">
                                    <input id="serial_no" type="text" class="validate" name="serial_no">
                                    <label for="serial_no">Device ID</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <input id="ihvn_no" type="text" class="validate" value="" name="ihvn_no">
                                    <label for="ihvn_no">IHVN Tag Number</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="tag_no" type="text" class="validate" name="tag_no">
                                <label for="tag_no">Serial Number</label>
                            </div>
                        </div>

                        <div class="input-field">
                                <textarea id="description" class="materialize-textarea" name="description"></textarea>
                                <label for="description" >Description</label>
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
                            <div class="input-field col s6">
                                    <select name="category" id="category" materialize="material_select">
                                        <option value="" disabled>Select Category</option>
                                        @foreach ($categories as $ca)
                                            <option value='{{$ca->category_name}}'>{{$ca->category_name}}</option>
                                        @endforeach
                                    </select>
                                    <label for="category">Item Category</label>
                            </div>

                            <div class="input-field col s6">
                                    <input name="type" id="type" list="type" type="text" class="validate">
                                    <datalist id="type">
                                            <option>Wooden</option>
                                            <option>Metal</option>
                                    </datalist>
                                    <label for="type">Type (of material,... etc)</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                    <input id="date_purchased" type="date" class="datepicker" name="date_purchased">
                                    <label for="date_purchased">Date Purchased</label>
                            </div>

                            <div class="input-field col s6">
                                    <input id="quantity" type="text" class="validate" name="quantity">
                                    <label for="quantity">Quantity Purchased</label>
                            </div>
                        </div>

                        <div class="input-field">
                                <input id="supplier" type="text" class="validate" name="supplier">
                                <label for="supplier">Supplier</label>
                        </div>

                        <div class="input-field">
                                <input id="status" type="text" class="validate" name="status">
                                <label for="status">Physical Condition/Status</label>
                        </div>


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

                                <option disabled selected>State</option>
                                <option value="FCT">FCT</option>
                                <option value="RIVERS">RIVERS</option>
                                <option value="NASARAWA">NASARAWA</option>
                                <option value="KATSINA">KATSINA</option>

                            </select>
                            <label for="state">Select State</label>
                        </div>


                        <div class="input-field">
                            <select name="facility" id="facility" materialize="material_select">
                                <option value="" disabled selected>Facility</option>
                                @foreach ($facilities as $facility)
                                <option value='{{$facility->id}}'>{{$facility->facility_name}}</option>
                                @endforeach
                            </select>
                            <label for="facility">Select Facility</label>
                        </div>

                        <div class="input-field">
                            <select name="department" id="department" materialize="material_select">
                                <option value="" disabled selected>Department</option>
                                @foreach ($departments as $department)
                                <option value='{{$department->id}}'>{{$department->department_name}}</option>
                                @endforeach
                            </select>
                            <label>Select Department</label>
                        </div>

                        <div class="input-field">
                            <select name="unit" id="unit" materialize="material_select">
                                <option value="" disabled selected>Unit</option>
                                @foreach ($units as $unit)
                                <option value='{{$unit->id}}'>{{$unit->unit_name}}</option>
                                @endforeach
                            </select>
                            <label>Select Unit</label>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <select name="user" id="user" materialize="material_select">
                                    <option value="" disabled selected>Assigned To</option>
                                    @foreach ($users as $user)
                                    <option value='{{$user->id}}'>{{$user->name}}</option>
                                    @endforeach
                                </select>
                                <label>Select User</label>
                            </div>

                            <div class="input-field col s6">
                                <select name="added_by" class="initialized" materialize="material_select">
                                    @auth
                                        <option value='{{auth()->user()->id}}' selected>{{auth()->user()->name}}</option>
                                    @endauth
                                    @guest
                                        <option value="Anonymous" selected>Added By</option>
                                    @endguest

                                </select>
                                <label>Added By</label>
                            </div>
                        </div>

                        <div class="input-field">
                                <input id="internal_location" type="text" class="validate" name="internal_location">
                                <label for="internal_location">Internal Location</label>
                        </div>

                        <div class="input-field">
                                <input id="remarks" type="text" class="validate" name="remarks">
                                <label for="remarks">Remarks</label>
                        </div>

                        <div class="input-field text-right right" style="margin-bottom:20px;">

                                <button type="submit" class="btn">
                                    Add Item
                                </button>

                        </div>
                    </form>


        </div>
    </div>
</div>
<script src="{{asset('/js/lga.js')}}"></script>
@endsection
