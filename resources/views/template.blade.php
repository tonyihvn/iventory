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
    <link rel="stylesheet" href="{{ asset('/css/searchPanes.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/fixedHeader.dataTables.min.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('/css/pmain.scss') }}"> --}}
    <link rel="stylesheet"
        href="{{ asset('https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css') }}">
    <style>
        nav {
            background-color: white !important;
            box-shadow: none !important;
        }

        .select2 {
            width: 100%;
            position: relative !important;
        }

        #float {
            position: fixed;
            bottom: 1em;
            right: 2em;
            z-index: 1000000000000000000;
        }

        .btn{
            background-color: #0d201e !important;
        }

        body {
            background-image: url("{{ asset('/public/images/inventorybg.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0.9; /* To make the image faint */
        }

        .card, .row, form, table{
            background-color: white !important;
        }

        footer .container, footer .container .row{
            background-color: transparent !important;
        }

        #card-alert{
            background-color: green !important;
        }

        body .container{
            color: black !important;
            width: 100% !important;
        }

        .s12 a.breadcrumb{
            padding-left: 10px;
        }
        .breadcrumb, span.breadcrumb::before, a.breadcrumb::before{
            color: rgb(92, 91, 91) !important;
            font-size: 0.8em;
            text-decoration: italic;
        }
    </style>
</head>

<body>

    <nav>
        <div class="nav-wrapper teal">
            <a href="#" data-activates="mobile-demo" class="button-collapse show-on-large"><i
                    class="material-icons">menu</i></a>
            <a href="{{ url('/') }}" class="brand-logo"><img
                    src="{{ asset('/uploads/' . $site_settings->logo) }}"
                    alt="{{ $site_settings->organization_name }}" height="60" width="auto"></a>

            <ul class="right hide-on-med-and-down">
                <li class="input-field">
                    <form action="{{ route('item_search') }}" method="POST" style="position: relative;"
                        id="searchform" class="row">
                        @csrf
                        <input type="text" placeholder="Search Inventory" class="searchbox col s8" name="keyword">
                        <button class="btn btn-small btn-floating tooltipped col s2" data-position="top"
                            data-tooltip="Search with IHVN Tag No, Item Name, etc" style="margin-top: 10px;"><i
                                class="material-icons" style="margin-top:-10px !important;">search</i></button>

                    </form>
                </li>
                <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                @auth
                    @if (Auth()->user()->role == 'Admin')
                        <li><a href="{{ url('/edit_settings/1') }}">Settings</a></li>
                    @endif
                @endauth
                <li><a href="{{ url('/help') }}">Help</a></li>
                <li>


                    <a class="btn-flat dropdown-button waves-effect waves-light white-text large" href="#"
                        data-activates="profile-dropdown">Welcome @auth {{ auth()->user()->name }} @endauth <i
                            class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                    <ul id="profile-dropdown" class="dropdown-content">
                        <li><a href="{{ url('/edit_user/' . Auth()->user()->id) }}"><i
                                    class="material-icons">person</i>Profile</a></li>
                        <li class="divider"></li>
                        <li><a href="#lockscreenModal" class="modal-trigger"
                                onclick="lockScreen('{{ auth()->user()->name }}')"><i
                                    class="material-icons">lock</i>Lock</a></li>
                        <li><a href="{{ url('/logout') }}"><i class="material-icons">exit_to_app</i>Logout</a></li>
                    </ul>

                </li>
            </ul>



            <ul class="side-nav teal darken-2" id="mobile-demo">
                <li class="teal center"><a href="#" style="color: white !important;"><i class="material-icons">menu</i>IHVN GF ASSETS </a></li>
                <li class="white">
                    <div class="divider"></div>
                </li>
                <li  class="white"><a class="collapsible-header waves-effect waves-blue" href="{{ url('/') }}"><i
                            class="material-icons">dashboard</i>DASHBOARD</a></li>

                @if (auth()->user()->role == 'Admin')
                    <li class="white">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">list</i>Inventory Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/inventories') }}"><i
                                                    class="material-icons">assignment</i>Inventory of Items<span
                                                    class="new badge right yellow grey lighten-1"
                                                    data-badge-caption="updated"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/add_item') }}"><i
                                                    class="material-icons">library_add</i>Add Item to Inventory<span
                                                    class="new badge right yellow darken-3"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/movements') }}"><i
                                                    class="material-icons">transfer_within_a_station</i>Transfer/Movements Report<span
                                                    class="new badge right yellow darken-3"></span></a></li>

                                        <li><a class="waves-effect waves-blue" href="{{ url('/requests') }}"><i
                                                    class="material-icons">receipt</i>Item Requests</a></li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">apps</i>Items Manager<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/uitems') }}"><i
                                                    class="material-icons">view_list</i>List of All Items<span
                                                    class="new badge right yellow grey lighten-1"
                                                    data-badge-caption="updated"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/update-tagnumbers') }}"><i
                                                    class="material-icons">confirmation_number</i>Change Tag Numbers<span
                                                    class="new badge right yellow darken-3"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/categories') }}"><i
                                                    class="material-icons">view_module</i>Item Categories<span
                                                    class="new badge right yellow darken-3"></span></a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="white">
                        <div class="divider"></div>
                    </li>
                    <li class="white">
                        <ul class="collapsible collapsible-accordion">

                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">network_check</i>Supply / Stock Manager<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/uitems') }}#products"><i
                                                    class="material-icons">gradient</i>Item Stock<span
                                                    class="new badge right yellow grey lighten-1"
                                                    data-badge-caption="updated"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/supplies') }}"><i
                                                    class="material-icons">local_shipping</i>Item Supply / History<span
                                                    class="new badge right yellow darken-3"></span></a></li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">

                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">assignment_turned_in</i>Data Quality Tools<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/dataquality') }}#products"><i
                                                    class="material-icons">border_color</i>Inventory Status Update<span
                                                    class="new badge right yellow grey lighten-1"
                                                    data-badge-caption="updated"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/concurrency') }}"><i
                                                    class="material-icons">playlist_add_check</i>Data Concurrency Tool<span
                                                    class="new badge right yellow darken-3"></span></a></li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">
                            <li class="white">
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">remove_from_queue</i>System Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/users') }}"><i
                                                    class="material-icons">people</i>Users</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/facilities') }}"><i
                                                    class="material-icons">home</i>Facilities</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/departments') }}"><i
                                                    class="material-icons">domain</i>Departments</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/units') }}"><i
                                                    class="material-icons">crop_din</i>Units</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/audits') }}"><i
                                                    class="material-icons">dehaze</i>Audit Trail</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/edit_settings/1') }}"><i
                                                    class="material-icons">settings</i>System Settings</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>


                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">

                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">assignment</i>DCTools Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('dctools') }}"><i
                                                    class="material-icons">swap_horiz</i>All DCTools</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/add-dctool') }}"><i
                                                    class="material-icons">swap_horiz</i>Add DCTools</a></li>
                                        <li><a class="waves-effect waves-green"
                                                href="{{ url('confirm-delivery') }}"><i
                                                    class="material-icons">swap_horiz</i>Confirm Item Delivery</a></li>
                                        <li><a class="waves-effect waves-green" href="{{ url('requests') }}"><i
                                                    class="material-icons">swap_horiz</i>Request for DCTools</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/distribution-report') }}"><i
                                            class="material-icons">swap_horiz</i>DCT Distribution Report</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/new-dctreport') }}"><i
                                                    class="material-icons">swap_horiz</i>Generate DCT Report</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                @elseif(auth()->user()->role == 'DCTAdmin' || auth()->user()->role == 'DCTManager' || auth()->user()->role == 'DCTUser')
                    <li class="white">
                        <div class="divider"></div>
                    </li>
                    <li class="white">
                        <ul class="collapsible collapsible-accordion">

                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">list</i>DCTools Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('dctools') }}"><i
                                                    class="material-icons">swap_horiz</i>All DCTools</a></li>
                                            @if(auth()->user()->role == 'DCTAdmin')
                                                <li><a class="waves-effect waves-blue" href="{{ url('/add-dctool') }}"><i
                                                            class="material-icons">swap_horiz</i>Add DCTools</a></li>
                                            @endif
                                        <li><a class="waves-effect waves-green"
                                                href="{{ url('confirm-delivery') }}"><i
                                                    class="material-icons">swap_horiz</i>Confirm Item Delivery</a></li>
                                        <li><a class="waves-effect waves-green" href="{{ url('requests') }}"><i
                                                    class="material-icons">swap_horiz</i>Request for DCTools</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/new-dctreport') }}"><i
                                                    class="material-icons">swap_horiz</i>Utilization Report</a>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/distribution-report') }}"><i
                                            class="material-icons">swap_horiz</i>DCT Distribution Report</a></li>
                                        </li>
                                            @if(auth()->user()->role == 'DCTAdmin' || auth()->user()->role == 'DCTManager')
                                                <li><a class="waves-effect waves-blue" href="{{ url('/register') }}"><i
                                                    class="material-icons">swap_horiz</i>Create Users</a></li>
                                                    <li><a class="waves-effect waves-blue" href="{{ url('/users') }}"><i
                                                        class="material-icons">swap_horiz</i>Users</a></li>
                                            @endif
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                @elseif(auth()->user()->role == 'Manager')
                    <li class="white">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">assignment</i>Inventory Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/inventories') }}"><i
                                                    class="material-icons">assignment</i>Inventory of Items<span
                                                    class="new badge right yellow grey lighten-1"
                                                    data-badge-caption="updated"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/add_item') }}"><i
                                                    class="material-icons">library_add</i>Add Item to Inventory<span
                                                    class="new badge right yellow darken-3"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/movements') }}"><i
                                                    class="material-icons">transfer_within_a_station</i>Transfer/Movements Report<span
                                                    class="new badge right yellow darken-3"></span></a></li>

                                        <li><a class="waves-effect waves-blue" href="{{ url('/requests') }}"><i
                                                    class="material-icons">receipt</i>Item Requests</a></li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">apps</i>Items Manager<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/update-tagnumbers') }}"><i
                                                    class="material-icons">confirmation_number</i>Change Tag Numbers<span
                                                    class="new badge right yellow darken-3"></span></a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">

                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">assignment_turned_in</i>Data Quality Tools<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/dataquality') }}#products"><i
                                                    class="material-icons">border_color</i>Inventory Status Update<span
                                                    class="new badge right yellow grey lighten-1"
                                                    data-badge-caption="updated"></span></a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/concurrency') }}"><i
                                                    class="material-icons">playlist_add_check</i>Data Concurrency Tool<span
                                                    class="new badge right yellow darken-3"></span></a></li>

                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">
                            <li class="white">
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">remove_from_queue</i>System Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/users') }}"><i
                                                    class="material-icons">people</i>Users</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/facilities') }}"><i
                                                    class="material-icons">home</i>Facilities</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/departments') }}"><i
                                                    class="material-icons">domain</i>Departments</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/units') }}"><i
                                                    class="material-icons">crop_din</i>Units</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>


                    <li class="white">
                        <div class="divider"></div>
                    </li>

                    <li class="white">
                        <ul class="collapsible collapsible-accordion">

                            <li>
                                <a class="collapsible-header waves-effect waves-blue"><i
                                        class="material-icons">library_books</i>DCTools Management<i
                                        class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a class="waves-effect waves-blue" href="{{ url('dctools') }}"><i
                                                    class="material-icons">swap_horiz</i>All DCTools</a></li>

                                        <li><a class="waves-effect waves-green"
                                                href="{{ url('confirm-delivery') }}"><i
                                                    class="material-icons">swap_horiz</i>Confirm Item Delivery</a></li>
                                        <li><a class="waves-effect waves-green" href="{{ url('requests') }}"><i
                                                    class="material-icons">swap_horiz</i>Request for DCTools</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/distribution-report') }}"><i
                                            class="material-icons">swap_horiz</i>DCT Distribution Report</a></li>
                                        <li><a class="waves-effect waves-blue" href="{{ url('/new-dctreport') }}"><i
                                                    class="material-icons">swap_horiz</i>Generate DCT Report</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="white">
                    <div class="divider"></div>
                </li>
                <li class="white"><a href="{{ url('/help') }}"><i class="material-icons">help</i>Help</a></li>
                <li class="green">
                    <a class="btn-flat dropdown-button waves-effect waves-light white-text large" href="#"
                        data-activates="profile-dropdown2">@auth {{ auth()->user()->name }} @endauth <i
                            class="material-icons right" style="margin-right:0;">arrow_drop_down</i></a>
                    <ul id="profile-dropdown2" class="dropdown-content">
                        <li><a href="#"><i class="material-icons">person</i>Profile</a></li>
                        <li class="divider"></li>
                        <a href="#" id="unlock" class="btn btn-large">Click Here to Unlock Screen</a>
                        <hr>
                        <li><a href="#lockscreenModal" class="lockscreen modal-trigger"
                                data-username="{{ auth()->user()->name }}"><i class="material-icons">lock</i>Lock</a>
                        </li>
                        <li><a href="{{ url('/logout') }}"><i class="material-icons">exit_to_app</i>Logout</a></li>
                    </ul>

                </li>
            </ul>

        </div>
    </nav>


    <div class="container">
        {{ Breadcrumbs::render() }}
        <p>@include('/alerts')</p>
    </div>

    @yield('content')
    <main>
        <div class="container">{{ $instruction ?? '' }}</div>
    </main>

    <!-- Gitter Chat Link -->
    @if (Auth()->user()->role == 'Admin')
        <div class="fixed-action-btn click-to-toggle hide_on_print" style="bottom: 45px; right: 24px;">
            <a class="btn-floating btn-large pink waves-effect waves-light">
                <i class="large material-icons">apps</i>
            </a>

            <ul>
                <li>
                    <a class="btn-floating tooltipped" data-position="top" data-tooltip="View All Inventory/Items"
                        href="{{ url('/inventories') }}"><i class="material-icons" title>storage</i></a>
                </li>
                <li>
                    <a class="btn-floating tooltipped" data-position="top" data-tooltip="Facilities"
                        href="{{ url('/facilities') }}"><i class="material-icons" title>house</i></a>
                </li>
                <li>
                    <a class="btn-floating red tooltipped" data-position="top" data-tooltip="Item Movement/Transfers"
                        href="{{ url('/movements') }}"><i class="material-icons">repeat</i></a>
                </li>
                @if (Auth()->user()->role == 'Admin')
                    <li>
                        <a class="btn-floating purple darken-1 tooltipped" data-position="top"
                            data-tooltip="Our Suppliers" href="{{ url('/suppliers') }}"><i
                                class="material-icons">local_shipping
                            </i></a>
                    </li>
                @endif
                <li>
                    <a class="btn-floating green tooltipped" data-position="top" data-tooltip="Users/Operators"
                        href="{{ url('/users') }}"><i class="material-icons">people</i></a>
                </li>

                <li>
                    <a class="btn-floating blue btn-large tooltipped" data-position="top" data-tooltip="Add New Item"
                        href="add_item"><i class="material-icons">add</i></a>
                </li>
            </ul>
        </div>
    @endif

    <footer class="page-footer teal darken-2">
        <div class="container">
            <div class="row">
                <div class="col l6 s12">
                    <h5 class="white-text">{{ $site_settings->organization_name }}</h5>
                    <p class="grey-text text-lighten-4">{{ $site_settings->description }}</p>
                </div>
                <div class="col l2 offset-l2 s6">
                    <h6>Links</h6>
                    <ul>
                        <li><a href="{{ url('/dashboard') }}" class="grey-text text-lighten-3">Dashboard</a></li>
                        <li><a href="{{ url('/inventories') }}" class="grey-text text-lighten-3">Inventories</a></li>
                    </ul>
                </div>
                <div class="col l2 s6">
                    <h6>System Managment</h6>
                    <ul>
                        <li><a href="{{ url('/help') }}" class="grey-text text-lighten-3">Help</a></li>
                        @if (Auth()->user()->role == 'Admin')
                            <li><a href="{{ url('/edit_settings/1') }}" class="grey-text text-lighten-3">Settings</a>
                            </li>
                        @endif
                        <li><a href="{{ url('/help') }}" class="grey-text text-lighten-3">Support</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-copyright  black darken-4">
            <div class="container">Developed by <a href="https://valueminds.org">Value Minds</a></div>
        </div>
    </footer>

    <!-- Lockscreen Modal -->
    <div id="lockscreenModal" class="modal bottom-sheet">
        <div class="modal-content center row">
            <img src="{{ url('/uploads/' . $site_settings->logo) }}" alt="{{ $site_settings->organization_name }}"
                height="60" width="auto">
            <hr>
            <div class="card col m6 offset-m3">
                <h5 class="green">Screen Locked!</h5>
                <i class="material-icons large">lock</i>
                <h6 id="username"></h6>
                <p class="center">
                    <a href="#" id="unlock" class="btn btn-large" onclick="closeModal()">Click Here to
                        Unlock Screen</a>
                    <hr>

                    <a href="logout" id="notuser">Logout</a>
                </p>
            </div>
        </div>

    </div>

</body>
<script src="{{ asset('/js/jquery-3.5.1.js') }}"></script>
<script src="{{ asset('/js/materialize.min.js') }}"></script>
<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.fixedHeader.min.js') }}"></script>
<script src="{{ asset('/js/select2.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset('/js/dataTables.searchPanes.min.js') }}"></script>
<script src="{{ asset('/js/pmain.js') }}"></script>


{{-- <script src="{{ asset('/js/exporting.js') }}"></script> --}}
<?php if(isset($dashboard)){ ?>
    <script src="{{ asset('/js/highcharts.js') }}"></script>

<script type="text/javascript">
    $(function() {
        var laptops = <?php echo $Laptops ?? ''; ?>;
        var phones = <?php echo $Phones ?? ''; ?>;
        var biometrics = <?php echo $Biometrics ?? ''; ?>;

        var categoris = [<?php echo $states; ?>];


        $('#basic-area').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Gadget Distribution(s)'
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
                }
            ]
        });
    });
</script>
<?php } ?>
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

<script>
    $('#new_username').hide();

    var optionText = $('#item_id option:selected').text();
    $('#item_name').val(optionText);

    $('#dctool_select').hide();
    $('#user').change(function() {
        if ($(this).val() == "0") {
            $('#new_username').show();
        } else {
            $('#new_username').hide();
        }
    });

    $('#item_id').change(function() {
        var optionText = $('#item_id option:selected').text();  // Get the text of the selected option
        $('#item_name').val(optionText);  // Set the text to the item_name input field
    });

    $(document).ready(function() {
        $('.select2').select2();
    });

    $("#type").change(function() {

        var type = $('select[name=type] option').filter(':selected').val();

        if (type == "DCT Tools") {
            $("#dctool_select").show();
            $("#gadgets").hide();
            $("#quantity_requested").hide();
        } else {
            $("#dctool_select").hide();
            $("#gadgets").show();
            $("#quantity_requested").show();
        }
    });

    function updateqtyrName(iid){
        if($('#t'+iid).is(':checked'))
        {
            $('#qtyr'+iid).attr('name', 'qty_received[]');
        }else
        {
            $('#qtyr'+iid).attr('name', 'qty_recieved[]');
        }
    }

    function updateInvRecord(iid){
        if($('#t'+iid).is(':checked'))
        {
            $('#ihvntag'+iid).attr('name', 'sihvn_no[]');
            $('#serialno'+iid).attr('name', 'sserial_no[]');
            $('#tagno'+iid).attr('name', 'stag_no[]');
            $('#facility_id'+iid).attr('name', 'sfacility_id[]');
            $('#user_id'+iid).attr('name', 'suser_id[]');
            $('#status'+iid).attr('name', 'sstatus[]');
        }else
        {
            $('#ihvntag'+iid).attr('name', 'ihvn_no[]');
            $('#serialno'+iid).attr('name', 'serial_no[]');
            $('#tagno'+iid).attr('name', 'tag_no[]');
            $('#facility'+iid).attr('name', 'facility_id[]');
            $('#user'+iid).attr('name', 'user_id[]');
            $('#status'+iid).attr('name', 'status[]');
        }
    }

    function updateInvFacility(iid){
        var val = $('#facilityl'+iid).val()
        var fid = $('#facilities option').filter(function() {
            return this.value == val;
        }).data('fid');
        /* if value doesn't match an option, xyz will be undefined*/
        var fidd = fid ? fid : '';
        $("#sfacility_id"+iid).val(fidd);
        $("#facility_id"+iid).val(fidd);
    };

    function updateInvUser(iid){
        var val = $('#userl'+iid).val()
        var uid = $('#users option').filter(function() {
            return this.value == val;
        }).data('uid');
        /* if value doesn't match an option, xyz will be undefined*/
        var uidd = uid ? uid : '';
        $("#suser_id"+iid).val(uidd);
        $("#user_id"+iid).val(uidd);
    };

</script>

</html>
