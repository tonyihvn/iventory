@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">Add New Facility</h3>

                
                    <form method="POST" action="{{ route('facilities.store') }}">
                        @csrf

                        <div class="input-field">
                                <input id="facility_name" type="text" class="validate" name="facility_name" required autofocus>
                                <label for="facility_name">Facility Name</label>
                        </div>

                        <div class="input-field">
                                <input id="facility_no" type="text" class="validate" name="facility_no">
                                <label for="facility_no">Facility Datim Number</label>
                        </div>

                        <div class="input-field">
                                <select  onchange="getLGA();" name="state" id="state" class="browser-default validate">
                                    <option value="" selected="selected">- Select State -</option>
                                    <option value="FCT">Abuja FCT</option>
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
                                    <option value="Nasarawa">Nasarawa</option>
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
                                <label for="state" class="active">State (For Nigeria)</label>
                        </div>

                        <div class="input-field">
                                <select
                            name="lga"
                            id="lga"
                            class="select-lga browser-default"
                            required
                            >
                            <option value="" disabled selected>Select LGA</option>
                            </select>
                            <label for="lga" class="active">LGA</label>
                        </div>

                        <div class="input-field">
                                <input id="town" type="text" class="validate" name="town">
                                <label for="town">Town/District</label>
                        </div>

                        <div class="input-field">
                                <input id="address" type="text" class="validate" name="address">
                                <label for="address">Address</label>
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number">
                                <label for="phone_number">Phone Number</label>
                        </div>

                        <div class="input-field">
                                <input id="contact_person" type="text" class="validate" name="contact_person">
                                <label for="contact_person">Contact Person</label>
                        </div>


                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Add Facility
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
<script src="{{asset('/js/lga2.js')}}"></script>
@endsection
