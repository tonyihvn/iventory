@extends('template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card col m8 offset-m2">
            
                <div class="card-header">{{ __('Register') }}</div>

                
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="input-field">
                            
                            
                                <input id="name" type="text" class="validate @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        </div>

                        <div class="input-field">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number" required>                               
                                <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number</label>
                        </div>

                        <div class="input-field">
                            <select name="facility" id="facility" materialize="material_select">
                                <option value="facility" selected>Facility</option>
                                @foreach ($facilities as $facility)                                            
                                <option value="{{$facility->id}}">{{$facility->facility_name}}</option>
                                @endforeach
                            </select>
                            <label for="facility">Select Facility</label>
                        </div>

                        <div class="input-field">
                            <select name="department" id="department" materialize="material_select">
                                <option value="department" selected>Department</option>
                                @foreach ($departments as $department)                                            
                                <option value="{{$department->id}}">{{$department->department_name}}</option>
                                @endforeach
                            </select>
                            <label for="department">Select Department</label>
                        </div>

                        <div class="input-field">
                            <select name="unit" id="unit" materialize="material_select">
                                <option value="unit" selected>Unit</option>
                                @foreach ($units as $unit)                                            
                                <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                @endforeach
                            </select>
                            <label for="unit">Select Unit</label>
                        </div>

                        <div class="input-field">
                            <select name="role" id="role">
                                <option value="role" selected>User Role</option>
                                <option value="Admin">Admin</option>
                                <option value="Manager">Manager</option>
                                <option value="User">User</option>
                            </select>
                            <label for="role">Select Role</label>
                        </div>

                        <div class="input-field">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        

                        <div class="input-field">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                            
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            
                        </div>

                        <div class="input-field" style="text-align:right; margin-bottom: 20px;">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
