@extends('template')
@section('content')

<div class="container">
    <div class="row">
        <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">
            <div>
              <a href="{{url('//print_item/'.$item->id)}}" class="btn btn-small btn-floating right green pulse tooltipped"  data-position="top" data-tooltip="Print this Item" target="_blank"><i class="material-icons">printer</i></a>
            </div>
                <h3 class="card-header text-center" style="text-align:center;">View / Update Item</h3>


                    <form method="POST" action="{{route('inventories.update', $item->id)}}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">

                        <input type="hidden" name="id" value="{{$item->id}}">
                        <div class="row">
                            <div class="input-field col s6">
                                    <input id="item_name" type="text" class="validate" name="item_name" value="{{$item->item_name}}" required autofocus>
                                    <label for="item_name">Item Name</label>
                            </div>

                            <div class="input-field col s6">
                                    <input id="serial_no" type="text" class="validate" name="serial_no" value="{{$item->serial_no}}">
                                    <label for="serial_no">Serial Number</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s6">
                                <input id="ihvn_no" type="text" class="validate"  value="{{$item->ihvn_no}}" name="ihvn_no">
                                    <label for="ihvn_no">IHVN Number</label>
                            </div>
                            <div class="input-field col s6">
                                <input id="tag_no" type="text" class="validate"  value="{{$item->tag_no}}" name="tag_no">
                                <label for="tag_no">Tag Number</label>
                            </div>
                        </div>

                        <div class="input-field">
                                <textarea id="description" class="materialize-textarea" name="description">{{$item->description}}</textarea>
                                <label for="description" >Description</label>
                        </div>
                        <div class="row">
                            <div class="input-field col s4">
                                    <select name="category" class="initialized">
                                        <option value='{{$item->category}}'>{{$item->category}}</option>
                                        @foreach ($categories as $ca)
                                            <option value='{{$ca->category_name}}'>{{$ca->category_name}}</option>
                                        @endforeach
                                    </select>
                                    <label>Item Category</label>
                            </div>

                            <div class="input-field col s4">
                                    <input name="type" id="type" list="type" type="text"  value="{{$item->type}}" class="validate">
                                    <datalist id="type">
                                            <option>Wooden</option>
                                            <option>Metal</option>
                                    </datalist>
                                    <label for="type">Type (of material,... etc)</label>
                            </div>

                            <div class="input-field col s4">
                                <select name="status" class="initialized">
                                    <option value="{{$item->status}}" selected>{{$item->status}}</option>
                                    @if(Auth()->user()->role!="User")
                                        <option value="Operational">Operational</option>
                                        <option value="Not Operational">Not Operational</option>
                                        <option value="Lost">Lost</option>
                                        <option value="Archieved">Archived  </option>
                                        <option value="Need Repairs">Need Repairs</option>
                                    @endif
                                </select>
                                <label for="status">Physical Condition/Status</label>
                            </div>
                        </div>

                        @if (Auth()->user()->role=="Admin")
                            <div class="row">
                                <div class="input-field col s4">
                                        <input id="date_purchased" type="date" class="datepicker" name="date_purchased" value="{{$item->date_purchased}}">
                                        <label for="date_purchased">Date Purchased</label>
                                </div>

                                <div class="input-field col s4">
                                        <input id="quantity" type="text" class="validate" name="quantity" value="{{$item->quantity}}">
                                        <label for="quantity">Quantity Purchased</label>
                                </div>

                                <div class="input-field col s8">
                                    <input id="supplier" type="text" class="validate" name="supplier" value="{{$item->supplier}}">
                                    <label for="supplier">Supplier</label>
                                </div>


                            </div>
                        @endif


                        @if ($item->inventoryspec!=NULL)
                        <table class="table">
                            <thead>
                                <tr class="spechead">
                                    <th>Property</th>
                                    <th>Value/Description</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="item_list">
                            @foreach($item->inventoryspec as $key => $is)

                            <tr scope='row' class='row{{$key}}'>
                                <td class='input-field'><input type='text' name='property[]' value='{{$is->property}}' placeholder='e.g. Color, Brand etc'></td><td class='input-field'><td class='input-field'><input type='text' name='value[]' value='{{$is->value}}' placeholder='e.g. Red, HP etc'></td>
                                <td><a href='#' class='btn-floating red btn-small delpos' onClick='delRow({{$key}})'><i class='small material-icons'>remove</i></a></td>
                            </tr>

                            @endforeach

                            </tbody>
                        </table>
                        @endif
                        <div>
                        <a class="btn btn-small cyan pulse waves-effect center waves-light add_item" href="#" id="1">
                            Add Specifications / Properties
                            <i class="material-icons">add</i>
                        </a>
                        </div>

                        <h5>Facility / User Location</h5>
                        <hr>


                        <div class="input-field">
                            <select name="state" id="state" materialize="material_select">
                                <option value="{{$item->state}}" selected>{{$item->state}}</option>

                                <option value="FCT">FCT</option>
                                <option value="RIVERS">RIVERS</option>
                                <option value="NASARAWA">NASARAWA</option>
                                <option value="KATSINA">KATSINA</option>

                            </select>
                            <label for="facility">Select State</label>
                        </div>

                        <div class="input-field">
                            <select name="facility" class="initialized">
                                <option selected value='{{$item->facility_id}}'>{{$item->facilities->facility_name}}</option>
                                @foreach ($facilities as $facility)
                                <option value='{{$facility->id}}'>{{$facility->facility_name}}</option>
                                @endforeach
                            </select>
                            <label>Select Facility</label>
                        </div>

                        <div class="input-field">
                            <select name="department" class="initialized">
                                <option selected value='{{$item->department_id}}'>{{$item->department->department_name}}</option>
                                @foreach ($departments as $department)
                                <option value='{{$department->id}}'>{{$department->department_name}}</option>
                                @endforeach
                            </select>
                            <label>Select Department</label>
                        </div>

                        <div class="input-field">
                            <select name="unit" class="initialized">
                                <option selected value='{{$item->unit_id}}'>{{$item->unit->unit_name}}</option>
                                @foreach ($units as $unit)
                                <option value='{{$unit->id}}'>{{$unit->unit_name}}</option>
                                @endforeach
                            </select>
                            <label>Select Unit</label>
                        </div>
                        <div class="row">
                            <div class="input-field col s6">
                                <select name="user" class="initialized">
                                    <option selected value='{{$item->user->id}}'>{{$item->user->name}}</option>
                                    @if (Auth()->user()->role!="User")
                                        @foreach ($users as $user)
                                            <option value='{{$user->id}}'>{{$user->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <label>Select User</label>
                            </div>

                            <div class="input-field col s6">
                                <select name="added_by" class="initialized">
                                    @auth
                                        <option value='{{auth()->user()->id}}' selected>{{auth()->user()->name}}</option>
                                    @endauth
                                    @guest
                                        <option value="1" selected>Added By</option>
                                    @endguest

                                </select>
                                <label>Modified By</label>
                            </div>
                        </div>
                        <div class="input-field">
                                <input id="remarks" type="text" class="validate" name="remarks" value="{{$item->remarks}}">
                                <label for="remarks">Remarks</label>
                        </div>

                        <div class="input-field text-right right" style="margin-bottom:20px;">

                                <button type="submit" class="btn">
                                    Update Item
                                </button>

                        </div>
                    </form>


        </div>
    </div>
</div>
<script src="{{asset('/js/lga.js')}}"></script>
@endsection
