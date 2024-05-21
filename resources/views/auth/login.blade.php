@extends('guest_template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">

                <h3 class="card-header text-center" style="text-align:center;">{{ __('Login') }}</h3>


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
