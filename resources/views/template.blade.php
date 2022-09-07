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
    <link rel="stylesheet" href="{{asset('/css/searchPanes.dataTables.min.css')}}">
    <link rel="stylesheet" href="{{asset('/css/pmain.scss')}}">
    <link rel="stylesheet" href="{{asset('https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css')}}">
    <style>
      nav{
        background-color: white !important;
        box-shadow: none !important;

    </style>
</head>

<body>

<nav>
    <div class="nav-wrapper teal">
      <a href="#" data-activates="mobile-demo" class="button-collapse show-on-large"><i class="material-icons">menu</i></a>
      <a href="dashboard" class="brand-logo"><img src="uploads/{{$site_settings->logo}}" alt="{{$site_settings->organization_name}}" height="60" width="auto"></a>

      <ul class="right hide-on-med-and-down">
        <li class="input-field">
            <form action="item_search" method="POST" style="position: relative;" id="searchform" class="row">
                @csrf
                <input type="text" placeholder="Search Inventory" class="searchbox col s8" name="keyword">
                <button class="btn btn-small btn-floating tooltipped col s2" data-position="top" data-tooltip="Search with IHVN Tag No, Item Name, etc" style="margin-top: 10px;"><i class="material-icons" style="margin-top:-10px !important;">search</i></button>

            </form>
        </li>
        <li><a href="dashboard" >Dashboard</a></li>
        @auth
            @if (Auth()->user()->role=="Admin")
                <li><a href="edit_settings/1" >Settings</a></li>
            @endif
        @endauth
        <li><a href="{{url('/help')}}" >Help</a></li>
        <li>


                <a class="btn-flat dropdown-button waves-effect waves-light white-text large" href="#" data-activates="profile-dropdown">Welcome @auth {{auth()->user()->name}} @endauth <i class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                <ul id="profile-dropdown" class="dropdown-content">
                    <li><a href="edit_user/{{Auth()->user()->id}}"><i class="material-icons">person</i>Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="#lockscreenModal" class="modal-trigger" onclick="lockScreen('{{auth()->user()->name}}')"><i class="material-icons">lock</i>Lock</a></li>
                    <li><a href="logout"><i class="material-icons">exit_to_app</i>Logout</a></li>
                </ul>

        </li>
      </ul>



      <ul class="side-nav teal darken-2" id="mobile-demo">
      <li class="teal center"><a href="#"><i class="material-icons">menu</i>I-VENTORY </a></li>
      <li><a class="collapsible-header waves-effect waves-blue"href=""><i class="material-icons">dashboard</i>DASHBOARD</a></li>

      @if (auth()->user()->role=='Admin')
        <li class="white">
          <ul class="collapsible collapsible-accordion">

                <li>
                  <a class="collapsible-header waves-effect waves-blue"><i class="material-icons">list</i>Inventory Management<i class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                  <div class="collapsible-body">
                    <ul>
                      <li><a class="waves-effect waves-blue" href="inventories"><i class="material-icons">fullscreen</i>View All<span class="new badge right yellow grey lighten-1" data-badge-caption="updated"></span></a></li>
                      <li><a class="waves-effect waves-blue" href="add_item"><i class="material-icons">swap_horiz</i>Add New<span class="new badge right yellow darken-3"></span></a></li>
                      <li><a class="waves-effect waves-blue"href="movements"><i class="material-icons">transfer</i>Movements<span class="new badge right yellow darken-3"></span></a></li>
                      <li><a class="waves-effect waves-blue"href="categories"><i class="material-icons">swap_horiz</i>Categories<span class="new badge right yellow darken-3"></span></a></li>
                      <li><a class="waves-effect waves-blue"href="damaged"><i class="material-icons">fullscreen</i>Damaged<span class="new badge right yellow grey lighten-1" data-badge-caption="updated"></span></a></li>
                      <li><a class="waves-effect waves-blue"href="lost"><i class="material-icons">swap_horiz</i>Lost<span class="new badge right yellow darken-3"></span></a></li>
                      <li><a class="waves-effect waves-blue"href="archived"><i class="material-icons">swap_horiz</i>Archived<span class="new badge right yellow darken-3"></span></a></li>
                      <li><a class="waves-effect waves-blue"href="requests"><i class="material-icons">swap_horiz</i>Item Request</a></li>

                    </ul>
                  </div>
                </li>
              </ul>
            </li>

      @elseif(auth()->user()->role=='Manager')
        <li><a class="waves-effect waves-blue"href="inventories"><i class="material-icons">fullscreen</i>View All<span class="new badge right yellow grey lighten-1" data-badge-caption="updated"></span></a></li>
        <li><a class="waves-effect waves-blue"href="add_item"><i class="material-icons">swap_horiz</i>Add New<span class="new badge right yellow darken-3"></span></a></li>
        <li><a class="waves-effect waves-blue"href="movements"><i class="material-icons">transfer</i>Movements<span class="new badge right yellow darken-3"></span></a></li>
        <li><a class="waves-effect waves-blue"href="facilities"><i class="material-icons">swap_horiz</i>Facilities</a></li>
        <li><a class="waves-effect waves-blue"href="departments"><i class="material-icons">fullscreen</i>Departments</a></li>
        <li><a class="waves-effect waves-blue"href="units"><i class="material-icons">swap_horiz</i>Units</a></li>


        <li class="white"><a href="home">My Inventories</a></li>
        <li class="white"><a href="categories">Categories</a></li>
        <li class="white"><a href="requests">Item Request</a></li>
        <li class="white"><a href="users">State Users</a></li>

      @endif
        <li class="white"><div class="divider"></div></li>

      @if (auth()->user()->role=='Admin')
        <li class="white">
          <ul class="collapsible collapsible-accordion">
            <li>
              <a class="collapsible-header waves-effect waves-blue"><i class="material-icons">folder_open</i>System Management<i class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
              <div class="collapsible-body">
                <ul>
                  <li><a class="waves-effect waves-blue"href="users"><i class="material-icons">fullscreen</i>Users</a></li>
                  <li><a class="waves-effect waves-blue"href="facilities"><i class="material-icons">swap_horiz</i>Facilities</a></li>
                  <li><a class="waves-effect waves-blue"href="departments"><i class="material-icons">fullscreen</i>Departments</a></li>
                  <li><a class="waves-effect waves-blue"href="units"><i class="material-icons">swap_horiz</i>Units</a></li>
                  <li><a class="waves-effect waves-blue"href="audits"><i class="material-icons">swap_horiz</i>Audit Trail</a></li>
                </ul>
              </div>
            </li>
          </ul>
        </li>

        <li class="white"><div class="divider"></div></li>

        <li class="white"><a class="waves-effect waves-blue"href="reports"><i class="material-icons">swap_horiz</i>Inventory Report</a></li>
        <li class="white"><a href="edit_settings/1"><i class="material-icons">settings</i>Settings</a></li>
        <li class="white"><div class="divider"></div></li>
      @endif

        <li class="white"><a href="help"><i class="material-icons">help</i>Help</a></li>
        <li class="green">
                <a class="btn-flat dropdown-button waves-effect waves-light white-text large" href="#" data-activates="profile-dropdown2">@auth {{auth()->user()->name}} @endauth <i class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                <ul id="profile-dropdown2" class="dropdown-content">
                    <li><a href="#"><i class="material-icons">person</i>Profile</a></li>
                    <li class="divider"></li>
        <a href="#" id="unlock" class="btn btn-large">Click Here to Unlock Screen</a><hr>
                <li><a href="#lockscreenModal" class="lockscreen modal-trigger" data-username="{{auth()->user()->name}}"><i class="material-icons">lock</i>Lock</a></li>
                    <li><a href="logout"><i class="material-icons">exit_to_app</i>Logout</a></li>
                </ul>

        </li>
      </ul>



    </div>
  </nav>

<div class="container">
  <p>@include('/alerts')</p>
</div>

@yield('content')
<main>
<div class="container">{{$instruction ?? ''}}</div>
</main>

<!-- Gitter Chat Link -->
@if(Auth()->user()->role!="User")
  <div class="fixed-action-btn click-to-toggle hide_on_print" style="bottom: 45px; right: 24px;">
      <a class="btn-floating btn-large pink waves-effect waves-light">
          <i class="large material-icons">apps</i>
      </a>

      <ul>
          <li>
              <a class="btn-floating tooltipped" data-position="top" data-tooltip="View All Inventory/Items"href="inventories"><i class="material-icons" title>storage</i></a>
          </li>
          <li>
              <a class="btn-floating tooltipped" data-position="top" data-tooltip="Facilities"href="facilities"><i class="material-icons" title>house</i></a>
          </li>
          <li>
          <a class="btn-floating red tooltipped" data-position="top" data-tooltip="Item Movement/Transfers"href="movements"><i class="material-icons">repeat</i></a>
          </li>
            @if (Auth()->user()->role=="Admin")

                <li>
                <a class="btn-floating purple darken-1 tooltipped" data-position="top" data-tooltip="Our Suppliers"href="suppliers"><i class="material-icons">local_shipping
                    </i></a>
                </li>
            @endif
          <li>
          <a class="btn-floating green tooltipped" data-position="top" data-tooltip="Users/Operators"href="users"><i class="material-icons">people</i></a>
          </li>

          <li>
          <a class="btn-floating blue btn-large tooltipped" data-position="top" data-tooltip="Add New Item"href="add_item"><i class="material-icons">add</i></a>
          </li>
      </ul>
  </div>
@endif

  <footer class="page-footer teal darken-2">
    <div class="container">
      <div class="row">
        <div class="col l6 s12">
          <h5 class="white-text">{{$site_settings->organization_name}}</h5>
          <p class="grey-text text-lighten-4">{{$site_settings->description}}</p>
        </div>
        <div class="col l2 offset-l2 s6">
          <h6>Links</h6>
          <ul>
            <li><a href="dashboard" class="grey-text text-lighten-3">Dashboard</a></li>
            <li><a href="inventories" class="grey-text text-lighten-3">Inventories</a></li>
          </ul>
        </div>
        <div class="col l2 s6">
          <h6>System Managment</h6>
          <ul>
            <li><a href="help" class="grey-text text-lighten-3">Help</a></li>
            @if(Auth()->user()->role=="Admin")
                <li><a href="edit_settings/1" class="grey-text text-lighten-3">Settings</a></li>
            @endif
            <li><a href="help" class="grey-text text-lighten-3">Support</a></li>
          </ul>
        </div>
      </div>
    </div>
    <div class="footer-copyright  teal darken-4">
      <div class="container">Developed by <a href="https://ihvnigeria.org">IHVN, HI</a></div>
    </div>
  </footer>

  <!-- Lockscreen Modal -->
  <div id="lockscreenModal" class="modal bottom-sheet">
      <div class="modal-content center row">
        <img src="./uploads/{{$site_settings->logo}}" alt="{{$site_settings->organization_name}}" height="60" width="auto">
        <hr>
        <div class="card col m6 offset-m3">
          <h5 class="green">Screen Locked!</h5>
          <i class="material-icons large">lock</i>
          <h6 id="username"></h6>
          <p class="center">
            <a href="#" id="unlock" class="btn btn-large" onclick="closeModal()">Click Here to Unlock Screen</a><hr>

            <a href="logout" id="notuser">Logout</a>
          </p>
        </div>
      </div>

  </div>

</body>
    <script src="{{asset('/js/jquery-3.5.1.js')}}"></script>

    <script src="{{asset('/js/pmain.js')}}"></script>
    <script src="{{asset('/js/materialize.min.js')}}"></script>

    <script src="{{asset('/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{asset('/js/select2.min.js')}}"></script>
    <script src="{{asset('/js/dataTables.select.min.js')}}"></script>
    <script src="{{asset('/js/dataTables.searchPanes.min.js')}}"></script>
    <script src="{{asset('/js/highcharts.js')}}"></script>
    <script src="{{asset('/js/exporting.js')}}"></script>
    <?php if(isset($dashboard)){ ?>
      <script type="text/javascript">
        $(function () {
        var laptops = <?php echo $Laptops ?? ''; ?>;
        var phones = <?php echo $Phones ?? ''; ?>;
        var biometrics = <?php echo $Biometrics ?? ''; ?>;

        var categoris = [<?php echo $states; ?>];


        $('#basic-area').highcharts({
            chart: {
            type: 'column'
            },
            title: {
            text: 'Gadget/Equipment Distribution Accross States'
            },
            xAxis: {
                categories: categoris

            },
            yAxis: {
                title: {
                text: 'Quantity / Total'
            }
            },
            series: [{
            name: 'Phones',
            data: phones
            }, {
            name: 'Laptops',
            data: laptops
            },
            {
            name: 'Finger Print Scanners',
            data: biometrics
            }]
        });
        });
      </script>
    <?php } ?>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js" /></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.flash.min.js" /></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" /></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js" /></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js" /></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.html5.min.js" /></script>
    <script type="text/javascript" src="https://cdn.datatables.net/buttons/1.6.2/js/buttons.print.min.js" /></script>

</html>
