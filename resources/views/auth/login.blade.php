@extends('guest_template')
@section('content')
<style>
    #gflogin {
            background-image: url("{{ asset('/public/images/gfbg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.9; /* To make the image faint */
        }
</style>
<div class="container">
    <div class="row" style="padding-left: 10px !important;">

            <div class="card blue-grey darken-1 col m6" id="gflogin" style="height: 100%;">
                <div class="card-content white-text">
                <span class="card-title" style="text-align: center; font-weight: bold; text-shadow: 2px 2px black;">IHVN GF Inventory</span>
                <p style="text-align: center; padding-top: 20%">
                    <a class="btn btn-link" style="background-color: red; border: double 2px white;"    href="{{ url('/') }}">
                        Login to GF Inventory
                    </a>
                </p>
                </div>

            </div>

            <div class="col m6">

                <h3 class="card-header text-center" style="text-align:center;">{{ __('Login') }}</h3>
                {{-- <h5 style="text-align: center; color: darkblue;">GF Inventory</h5> --}}


                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="input-field">


                                <input id="email" type="email" class="validate initialized @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            <label for="email">{{ __('E-Mail Address') }}</label>
                        </div>

                        <div class="input-field">



                                <input id="password" type="password" class="validate initialized @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            <label for="password">{{ __('Password') }}</label>
                        </div>

                        <div class="input-field">

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>

                        </div>

                        <div class="input-field text-right right" style="margin-bottom:20px;">

                                <button type="submit" class="btn" id="login" onclick="storeLock()">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif

                        </div>
                    </form>
            </div>



    </div>
</div>
@endsection
