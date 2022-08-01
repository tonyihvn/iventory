@extends('template')
@section('content')

<div class="container">
    <div class="row">
        <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">
            
                <h3 class="card-header text-center" style="text-align:center;">Move / Transfer Item</h3>

                    <form method="POST" action="{{route('movements.store')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">                        
                            <div class="input-field col s6">
                                    <input type="hidden" name="itemid" value="{{$item->id}}">
                                    <input type="hidden" name="old" value="{{$item->user->name.' - '.$item->unit->unit_name.', '.$item->department->department_name.', '.$item->facilities->facility_name}}">
                                    
                                    <input type="text" class="validate" value="{{$item->item_name}}" readonly>
                            </div>

                            <div class="input-field col s6">
                                    <input id="serial_no" type="text" class="validate" name="serial_no" value="{{$item->serial_no}}" readonly>
                                    <label for="serial_no">Serial Number</label>
                            </div>
                        </div>

                        <div class="input-field">
                                <textarea id="reason" class="materialize-textarea" name="reason"></textarea>                         
                                <label for="reason" >Reason for Movement</label>
                        </div>

                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Upload Supporting Docs (e.g. Police Report,etc)</span>
                                <input type="file" name="file[]" multiple>
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div> 

                        <div class="row">
                        <input name="from_user" id="from_user" type="hidden" value='{{$item->user->id}}'>
                            <div class="input-field col s6">                                    
                                    <input id="from_user" class="validate initialized" value='From: {{$item->user->name}}' readonly>                                    
                                
                            </div>

                            <div class="input-field col s6">
                                <select name="to_user" class="initialized">
                                    <option selected value='{{$item->user->id}}'>{{$item->user->name}}</option>
                                    @foreach ($users as $user)      
                                    <option value="Not Applicable">Not Applicable</option>                                      
                                    <option value='{{$user->id}}'>{{$user->name}}</option>
                                    @endforeach
                                </select>
                                <label>To User</label>
                            </div>
                        </div>
                       
                        <h5>New Facility / User Location</h5>
                        <hr>

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
                                    @foreach ($users as $user)                                            
                                    <option value='{{$user->id}}'>{{$user->name}}</option>
                                    @endforeach
                                </select>
                                <label>Approved By</label>
                            </div>

                            <div class="input-field col s6">
                                <select name="approved_by" class="initialized">
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
                                <input id="date_moved" type="date" class="datepicker" name="date_moved">
                                <label for="date_moved">Date Moved</label>
                        </div>
                        
                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Move Item
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
<script src="{{asset('/js/lga.js')}}"></script>
@endsection
