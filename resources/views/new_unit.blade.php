@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">Add New Unit</h3>

                
                    <form method="POST" action="{{ route('units.store') }}">
                        @csrf

                        <div class="input-field">
                                <input id="unit_name" type="text" class="validate" name="unit_name" required autofocus>
                                <label for="unit_name">Unit Name</label>
                        </div>
                        
                        <div class="input-field">
                                <select name="facility" id="facility" class="validate"  materialize="material_select">
                                    <option value="" selected>Select Facility</option>
                                    @foreach ($facilities as $facility)                                            
                                    <option value="{{$facility->facility_name}}">{{$facility->facility_name}}</option>
                                    @endforeach
                                </select>
                                <label for="facility">Facility</label>
                        </div>

                        <div class="input-field">
                                <select name="department" id="department" class="validate"  materialize="material_select">
                                    <option value="" selected>Select Department</option>
                                    @foreach ($departments as $department)                                            
                                    <option value="{{$department->department_name}}">{{$department->department_name}}</option>
                                    @endforeach
                                </select>
                                <label for="department">Department</label>
                        </div>

                        <div class="input-field">
                                <input id="internal_location" type="text" class="validate" name="internal_location" required>
                                <label for="internal_location">Internal Location</label>
                        </div>

                        <div class="input-field">
                                <textarea name="description" id="description" class="materialize-textarea"></textarea>
                                <label for="description">Notes / Description</label>
                        </div>

                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Add Unit
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
