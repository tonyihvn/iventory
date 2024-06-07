@extends('template')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card col m8">

                <div class="card-header"><b>Update Account/Profile</b>
                    <hr>
                </div>


                <form method="POST" action="{{ route('updateUser') }}">
                    @csrf
                    <input name="_method" type="hidden" value="PUT">
                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <input type="hidden" name="oldpassword" value="{{ $user->password }}">
                    <div class="input-field">


                        <input id="name" type="text" class="validate @error('name') is-invalid @enderror"
                            name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                    </div>

                    <div class="input-field">
                        <label for="email"
                            class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>


                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ $user->email }}" required autocomplete="email">

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class="input-field">
                        <input id="phone_number" type="text" class="validate" name="phone_number"
                            value="{{ $user->phone_number }}">
                        <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number</label>
                    </div>

                    <div class="input-field">
                        <select name="state" id="state" materialize="material_select">

                            <option value="{{ Auth()->user()->state }}" selected>{{ Auth()->user()->state }}</option>
                            @if (Auth()->user()->role == 'Admin')
                                <option value="FCT">FCT</option>
                                <option value="Rivers">RIVERS</option>
                                <option value="Nasarawa">NASARAWA</option>
                                <option value="Katsina">KATSINA</option>
                            @endif

                        </select>
                        <label for="state">Select State</label>
                    </div>

                    <div class="input-field">
                        <select name="facility" id="facility" materialize="material_select">

                            <option value="{{ $user->facility }}" selected>
                                {{ $facilities[array_search($user->facility, array_column($facilities->toArray(), 'id'))]['facility_name'] }}
                            </option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}">{{ $facility->facility_name }}</option>
                            @endforeach
                        </select>
                        <label for="facility">Select Facility</label>
                    </div>

                    <div class="input-field">
                        <select name="department" id="department" materialize="material_select">
                            <option value="{{ $user->name }}" department>
                                {{ $departments[array_search($user->department, array_column($departments->toArray(), 'id'))]['department_name'] }}
                            </option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                        <label for="department">Select Department</label>
                    </div>

                    <div class="input-field">
                        <select name="unit" id="unit" materialize="material_select">
                            <option value="{{ $user->unit }}" selected>
                                {{ $units[array_search($user->unit, array_column($units->toArray(), 'id'))]['unit_name'] }}
                            </option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                            @endforeach
                        </select>
                        <label for="unit">Select Unit</label>
                    </div>

                    <div class="input-field">
                        <select name="role" id="role">
                            <option value="{{ $user->role }}" selected>{{ $user->role }}</option>
                            <option value="User">Select User Role</option>
                            @if (Auth()->user()->role == 'Admin')
                                <option value="Admin">Admin</option>
                                <option value="DCTAdmin">DCTAdmin</option>
                                <option value="DCTManager">DCT State Manager</option>
                                <option value="DCTUser">DCT Facility User</option>
                            @endif
                            @if (Auth()->user()->role == 'Admin' || Auth()->user()->role == 'Manager')
                                    <option value="Manager">State Manager</option>
                                    <option value="Facility">Facility Manager</option>
                                @endif
                                @if (Auth()->user()->role == 'DCTAdmin')
                                    <option value="DCTManager">State Manager</option>
                                    <option value="DCTUser">Facility Manager</option>
                                @endif
                                @if (Auth()->user()->role == 'DCTManager')
                                    <option value="DCTUser">Facility Manager</option>
                                @endif
                        </select>
                        <label for="role">Select Role</label>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>


                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>



                        <div class="input-field col s6">
                            <label for="password-confirm"
                                class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>


                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                >

                        </div>
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
            <card class="col m4">
                <form action="{{route('addMoreFacilities')}}">
                    <input type="hidden" name="user_id" value="{{$user->id}}">

                    <div class="input-field">
                        <select name="facilities[]" id="facilities" materialize="material_select" class="select2">

                            <option value="{{ $user->facility }}" selected>
                                {{ $facilities[array_search($user->facility, array_column($facilities->toArray(), 'id'))]['facility_name'] }}
                            </option>
                            @foreach ($facilities as $facility)
                                <option value="{{ $facility->id }}">{{ $facility->facility_name }}</option>
                            @endforeach
                        </select>
                        <label for="facilities">Add More Facilities</label>
                    </div>

                    <div class="input-field" style="text-align:right; margin-bottom: 20px;">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Add Facilities
                            </button>
                        </div>
                    </div>


                </form>
            </card>
        </div>
    </div>
@endsection
