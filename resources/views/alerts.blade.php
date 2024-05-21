@if (session()->has('message'))
<div class="row">
    <div id="card-alert" class="card green white-text col m6 offset-m3" style="padding: 10px; display: block; text-align: center;" role="alert">
        {!!session()->get('message')!!}
    </div>
</div>         
@endif

@if (session()->has('error'))
<div class="row">
    <div id="card-alert" class="card red white-text col m6 offset-m3" style="padding: 10px; display: block; text-align: center;" role="alert">
        {!!session()->get('error')!!}
    </div>
</div>         
@endif

@if ($errors->all())
    <div id="card-alert" class="card red white-text" role="alert">
        @foreach ($errors->all() as $error)

        <li>{!!$error!!}</li>
            
        @endforeach
        
    </div>            
@endif