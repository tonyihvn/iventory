@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">Update System Settings</h3>

                    <div class="center">
                        <img src="/uploads/{{$settings->logo}}" alt="No Logo Uploaded!" height="80" width="auto">
                    </div>
                    <form method="POST" action="{{route('settings.update', $settings->id)}}" enctype="multipart/form-data" >
                        @csrf
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="id" value="{{$settings->id}}">
                        <input type="hidden" name="oldlogo" value="{{$settings->logo}}">

                        <div class="input-field">
                                <input id="organization_name" type="text" class="validate" name="organization_name" value="{{$settings->organization_name}}" required autofocus>
                                <label for="organization_name">Organization Name</label>
                        </div>

                        <div class="input-field">
                                <textarea id="description" class="materialize-textarea" name="description">{{$settings->description}}</textarea>                         
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
                                <input id="address" type="text" class="validate" name="address" value="{{$settings->address}}" required>
                                <label for="address">Address</label>
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number" value="{{$settings->phone_number}}" required>
                                <label for="phone_number">Phone Number</label>
                        </div>

                        <div class="input-field">
                                <input id="copyright" type="text" class="validate" name="copyright" value="{{$settings->copyright}}" required>
                                <label for="copyright">Copyright Text / Info</label>
                        </div>


                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Update Settings
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
