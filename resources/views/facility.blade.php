@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col s6 offset-s3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">Update Facility</h3>

                
                    <form method="POST" action="{{route('facilities.update', $facility->id)}}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">

                        <input type="hidden" name="id" value="{{$facility->id}}">

                        <div class="input-field">
                                <input id="facility_name" type="text" class="validate" name="facility_name" value="{{$facility->facility_name}}" required autofocus>
                                <label for="facility_name">Facility Name</label>
                        </div>

                        <div class="input-field">
                                <input id="facility_no" type="text" class="validate" name="facility_no" value="{{$facility->facility_no}}" required>
                                <label for="facility_no">Facility Datim Number</label>
                        </div>

                        <div class="input-field">
                                <select  onchange="toggleLGA(this);" name="state" id="state">
                                    <option  value="{{$facility->state}}" selected="selected">{{$facility->state}}</option>
                                    <option value="Abuja FCT">Abuja FCT</option>
                                    <option value="Abia">Abia</option>
                                    <option value="Adamawa">Adamawa</option>
                                    <option value="Akwa Ibom">Akwa Ibom</option>
                                    <option value="Anambra">Anambra</option>
                                    <option value="Bauchi">Bauchi</option>
                                    <option value="Bayelsa">Bayelsa</option>
                                    <option value="Benue">Benue</option>
                                    <option value="Borno">Borno</option>
                                    <option value="Cross River">Cross River</option>
                                    <option value="Delta">Delta</option>
                                    <option value="Ebonyi">Ebonyi</option>
                                    <option value="Edo">Edo</option>
                                    <option value="Ekiti">Ekiti</option>
                                    <option value="Enugu">Enugu</option>
                                    <option value="Gombe">Gombe</option>
                                    <option value="Imo">Imo</option>
                                    <option value="Jigawa">Jigawa</option>
                                    <option value="Kaduna">Kaduna</option>
                                    <option value="Kano">Kano</option>
                                    <option value="Katsina">Katsina</option>
                                    <option value="Kebbi">Kebbi</option>
                                    <option value="Kogi">Kogi</option>
                                    <option value="Kwara">Kwara</option>
                                    <option value="Lagos">Lagos</option>
                                    <option value="Nassarawa">Nassarawa</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Ogun">Ogun</option>
                                    <option value="Ondo">Ondo</option>
                                    <option value="Osun">Osun</option>
                                    <option value="Oyo">Oyo</option>
                                    <option value="Plateau">Plateau</option>
                                    <option value="Rivers">Rivers</option>
                                    <option value="Sokoto">Sokoto</option>
                                    <option value="Taraba">Taraba</option>
                                    <option value="Yobe">Yobe</option>
                                    <option value="Zamfara">Zamfara</option>
                                    <option value="Outside Nigeria">Outside Nigeria</option>
                                </select>
                                <label for="state">State (For Nigeria)</label>
                        </div>

                        <div class="input-field">
                                <select name="lga" id="lga" class="select-lga">
                                <option  value="{{$facility->lga}}" selected="selected">{{$facility->lga}}</option>
                                </select>
                                <label for="lga">LGA</label>
                        </div>

                        <div class="input-field">
                                <input id="town" type="text" class="validate" name="town" value="{{$facility->town}}"  required>
                                <label for="town">Town/District</label>
                        </div>

                        <div class="input-field">
                                <input id="address" type="text" class="validate" name="address" value="{{$facility->address}}"  required>
                                <label for="address">Address</label>
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number" value="{{$facility->phone_number}}"  required>
                                <label for="phone_number">Phone Number</label>
                        </div>

                        <div class="input-field">
                                <input id="contact_person" type="text" class="validate" name="contact_person" value="{{$facility->contact_person}}"  required>
                                <label for="contact_person">Contact Person</label>
                        </div>


                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Update Facility
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
<script src="{{asset('/js/lga.js')}}"></script>
@endsection
