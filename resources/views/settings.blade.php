@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">System Setup</h3>
                
                    <form method="POST" action="{{ route('settings.store') }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="input-field">
                                <input id="organization_name" type="text" class="validate" name="organization_name" required autofocus>
                                <label for="organization_name">Organization Name</label>
                        </div>

                        <div class="input-field">
                                <textarea id="description" class="materialize-textarea" name="description"></textarea>                         
                                <label for="description" >Description</label>
                        </div>

                        <div class="file-field input-field">
                            <div class="btn">
                                <span>Upload Logo</span>
                                <input type="file" name="logo">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text">
                            </div>
                        </div>                        
                        

                        <div class="input-field">
                                <input id="address" type="text" class="validate" name="address" required>
                                <label for="address">Address</label>
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number" required>
                                <label for="phone_number">Phone Number</label>
                        </div>

                        <div class="input-field">
                                <input id="copyright" type="text" class="validate" name="copyright" required>
                                <label for="copyright">Copyright Text / Info</label>
                        </div>


                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Save Settings
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
