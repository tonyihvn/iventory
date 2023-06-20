<head>
    <title>IHVN Inventory Management System - I-ventory</title>
    <link rel="stylesheet" href="{{ asset('/css/materialize.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/material-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/dataTables.material.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/pmain.scss') }}">
    <style>
        #printable {
            margin-top: 0px !important;
        }
    </style>
</head>
<!-- onload="window.print()" -->

<body>


    <main style="padding: 20px;">
        @yield('content')
    </main>

</body>
<script src="{{ asset('/js/jquery-3.5.1.js') }}"></script>

<script src="{{ asset('/js/pmain.js') }}"></script>
<script src="{{ asset('/js/materialize.min.js') }}"></script>
<!--<script src="/js/material2.min.css"></script>-->
<!--<script src="{{ asset('/js/sweetalert2.all.min.js') }}"></script>-->
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/select2.min.js') }}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js" />
</script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js" />
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" />
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" />
</script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" />
</script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js" />
</script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js" />
</script>

</html>
