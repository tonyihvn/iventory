<head>
  <title>IHVN Inventory Management System - I-ventory</title>
  <link rel="stylesheet" href="{{asset('/css/materialize.min.css')}}">    
    <link rel="stylesheet" href="{{asset('/css/material-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/css/animate.min.css')}}">    
    <link rel="stylesheet" href="{{asset('/css/font-awesome.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/select2.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/select2.css')}}">
    <link rel="stylesheet" href="{{asset('/css/dataTables.material.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/jquery.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/pmain.scss')}}">
    <style>
            #printable{
                margin-top: 0px !important;
            }
    </style>
</head>

<body onload="window.print()">


<main>
    <div class="container">
        @yield('content')
    </div>
</main>
    
</body>
    <script src="{{asset('/js/jquery-3.5.1.js')}}"></script>

    <script src="{{asset('/js/pmain.js')}}"></script>
    <script src="{{asset('/js/materialize.min.js')}}"></script>
    <!--<script src="/js/material2.min.css"></script>-->
    <!--<script src="{{asset('/js/sweetalert2.all.min.js')}}"></script>-->
    <script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/select2.min.js')}}"></script>
</html>