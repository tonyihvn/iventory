@extends('template')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="card col m8 offset-m2">
            
                <div class="card-header">Update User</div>

                
                    <form method="POST" action="{{ route('updateUser') }}">
                        @csrf
                        <input name="_method" type="hidden" value="PUT">
                        <input type="hidden" name="id" value="{{$user->id}}">
                        <div class="input-field">
                            
                            
                                <input id="name" type="text" class="validate @error('name') is-invalid @enderror" name="name" value="{{$user->name}}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                        </div>

                        <div class="input-field">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email}}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            
                        </div>

                        <div class="input-field">
                                <input id="phone_number" type="text" class="validate" name="phone_number" value="{{$user->phone_number}}">                               
                                <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number</label>
                        </div>

                        <div class="input-field">
                            <select name="facility" id="facility" materialize="material_select">
                                <option value="{{$user->facility}}" selected>{{$user->facility}}</option>
                                @foreach ($facilities as $facility)                                            
                                <option value="{{$facility->id}}">{{$facility->facility_name}}</option>
                                @endforeach
                            </select>
                            <label for="facility">Select Facility</label>
                        </div>

                        <div class="input-field">
                            <select name="department" id="department" materialize="material_select">
                                <option value="{{$user->name}}" department>{{$user->department}}</option>
                                @foreach ($departments as $department)                                            
                                <option value="{{$department->id}}">{{$department->department_name}}</option>
                                @endforeach
                            </select>
                            <label for="department">Select Department</label>
                        </div>

                        <div class="input-field">
                            <select name="unit" id="unit" materialize="material_select">
                                <option value="{{$user->unit}}" selected>{{$user->unit}}</option>
                                @foreach ($units as $unit)                                            
                                <option value="{{$unit->id}}">{{$unit->unit_name}}</option>
                                @endforeach
                            </select>
                            <label for="unit">Select Unit</label>
                        </div>

                        <div class="input-field">
                            <select name="role" id="role">
                                <option value="{{$user->role}}" selected>{{$user->role}}</option>
                                <option value="Admin">Admin</option>
                                <option value="Manager">Manager</option>
                                <option value="User">User</option>
                            </select>
                            <label for="role">Select Role</label>
                        </div>

                      
                        <div class="input-field" style="text-align:right; margin-bottom: 20px;">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Update User
                                </button>
                            </div>
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
