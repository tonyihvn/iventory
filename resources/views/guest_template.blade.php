<head>

  <link rel="stylesheet" href="{{asset('/css/materialize.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/material-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/css/animate.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dataTables.material.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/pmain.scss')}}">
    <title>I-ventory | IHVN Inventory Management System</title>
</head>

<body>

<nav>
    <div class="nav-wrapper teal">
      <a href="#" data-activates="mobile-demo" class="button-collapse show-on-large"><i class="material-icons">menu</i></a>
      <a href="{{url('/')}}" class="brand-logo" target="_blank">I-ventory</a>

      <ul class="right hide-on-med-and-down">
      <li>


                Welcome, please login

        </li>

        <li><a href="https://codepen.io/collection/nbBqgY/" target="_blank">Help</a></li>

      </ul>



      <ul class="side-nav teal darken-2" id="mobile-demo">
      <li class="teal center"><a href="#"><i class="material-icons">menu</i>I-VENTORY </a></li
        <li class="white"><div class="divider"></div></li>


      </ul>

    </div>
  </nav>

<div class="container">
  <p>@include('/alerts')</p>
</div>

@yield('content')

<!-- Gitter Chat Link -->
 </body>
<script src="{{asset('/js/jquery.js')}}"></script>
    <script src="{{asset('/js/materialize.js')}}"></script>
    <!--<script src="/js/material2.min.css"></script>-->
    <script src="{{asset('/js/pmain.js')}}"></script>
    <script src="{{asset('/js/select2.min.js')}}"></script>
    <script src="{{asset('/js/sweetalert2.all.min.js')}}"></script>
    <script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/select2.min.js')}}"></script>
</html>
