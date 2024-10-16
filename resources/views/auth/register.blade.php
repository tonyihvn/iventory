@extends('template')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card col m8 offset-m2">

                <div class="card-header">{{ __('Register') }}</div>


                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-field">


                        <input id="name" type="text" class="validate @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Full Name') }}</label>

                    </div>
                    <div class="row">
                        <div class="input-field col s6">
                            <label for="email"
                                class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>


                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                        </div>

                        <div class="input-field col s6">
                            <input id="phone_number" type="text" class="validate" name="phone_number" required>
                            <label for="phone_number" class="col-md-4 col-form-label text-md-right">Phone Number</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <select name="state" id="state" materialize="material_select">

                                <option value="{{ Auth()->user()->state }}" selected>{{ Auth()->user()->state }}</option>
                                @if (Auth()->user()->role == 'Admin' || Auth()->user()->role=='DCTAdmin')
                                    <option value="ANAMBRA">ANAMBRA</option>
                                    <option value="KWARA">KWARA</option>
                                    <option value="EBONYI">EBONYI</option>
                                    <option value="GOMBE">GOMBE</option>
                                    <option value="FCT">FCT</option>
                                    <option value="Rivers">RIVERS</option>
                                    <option value="Nasarawa">NASARAWA</option>
                                    <option value="Katsina">KATSINA</option>
                                @endif
                            </select>
                            <label for="state">Select State</label>
                        </div>

                        <div class="input-field col s6">
                            <select name="facility" id="facility" materialize="material_select" class="select2">
                                <option value="{{ Auth()->user()->facility }}" selected>Facility</option>
                                @foreach ($facilities as $facility)
                                    <option value="{{ $facility->id }}">{{ $facility->facility_name }}</option>
                                @endforeach
                            </select>
                            <label for="facility" class="active">Select Facility</label>
                        </div>

                    </div>

                    <div class="row">
                        <div class="input-field col s4">
                            <select name="department" id="department" materialize="material_select">
                                <option value="{{ Auth()->user()->department }}" selected>Department</option>
                                @foreach ($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                            <label for="department">Select Department</label>
                        </div>

                        <div class="input-field col s4">
                            <select name="unit" id="unit" materialize="material_select">
                                <option value="{{ Auth()->user()->unit }}" selected>Unit</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                                @endforeach
                            </select>
                            <label for="unit">Select Unit</label>
                        </div>

                        <div class="input-field col s4">
                            <select name="role" id="role">
                                <option value="User" selected>Select User Role</option>
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
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <label for="password"
                                class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>


                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required
                                autocomplete="new-password">

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
                                required autocomplete="new-password">

                        </div>
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
