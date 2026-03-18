@extends('template')
@php
    $dashboard = '';
@endphp
@section('content')
    <style>
        .card-panel {
            min-height: 450px;
            margin: 0;
        }

        .highcharts-root {
            font-family: "Roboto" !important;
        }

        .highcharts-button-symbol {
            display: none;
        }

        .highcharts-title {
            font-size: 1.5rem !important;
        }

        .page-item a{
            margin: 2px !important;
            padding-top: 7px !important;
            padding-bottom: 12px;
            /* all: unset !important; */
        }



    </style>
    <main>
        <div class="row">
            <div style="padding: 35px;">

                <div class="row">
                    <div class="col s12 card" style="padding: 24px;">
                        <div class="card-title" style="margin-bottom: 8px;">
                            <b>Nigeria State Asset Distribution</b>
                        </div>
                        <div id="nigeria-map" style="height: 700px; width: 100%;"></div>
                    </div>
                </div>


                <div class="row" style="margin-bottom:24px;">
                    <div class="col s12 m6 l3">
                        <div class="card blue lighten-1 white-text">
                            <div class="card-content center">
                                <i class="material-icons">laptop</i>
                                <span class="card-title">Laptop Computers</span>
                                <h4>{{ $allcats->where('category', 'Laptops')->first()->quantity ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col s12 m6 l3">
                        <div class="card purple lighten-1 white-text">
                            <div class="card-content center">
                                <i class="material-icons">desktop_windows</i>
                                <span class="card-title">Desktop Computers</span>
                                <h4>{{ $allcats->where('category', 'Desktop Computers')->first()->quantity ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col s12 m6 l3">
                        <div class="card orange lighten-1 white-text">
                            <div class="card-content center">
                                <i class="material-icons">smartphone</i>
                                <span class="card-title">Smart Phones</span>
                                <h4>{{ $allcats->where('category', 'Phones')->first()->quantity ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col s12 m6 l3">
                        <div class="card green lighten-1 white-text">
                            <div class="card-content center">
                                <i class="material-icons">fingerprint</i>
                                <span class="card-title">Biometrics Devices</span>
                                <h4>{{ $allcats->where('category', 'Biometrics')->first()->quantity ?? 0 }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col m8 card">
                        <div class="card-title">
                            Chart
                        </div>
                        <hr>
                        <div id="basic-area" class="card-content"></div>
                    </div>
                    <div class="col m4 card blue-grey lighten-5">
                        <div class="card-title">
                            Requests / Reminders
                        </div>
                        <hr>
                        <div class="card-content">
                            <table class="table striped display responsive-table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Sender / State</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        @if (Auth()->user()->role=="Admin")
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                    @foreach ($requests as $req)
                                        <tr>
                                            <td>{{ $req->user->name }} / {{$req->user->state}}</td>
                                            <td>{{date('Y-m-d',strtotime($req->created_at)) }}</td>
                                            <td>{{ $req->request_status }}</td>
                                            @if (Auth()->user()->role=="Admin")
                                                <td><a href="{{url('request/'.$req->id)}}" class="btn">Attend</a></td>
                                            @endif
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="4"><a href="{{ url('requests') }}" class="btn pull-center">View All</a></td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <h4 class="center">View Inventory By State</h4>
                    @if (Auth()->user()->role=="Admin" || Auth()->user()->role=="Observer")
                        @foreach ($statelist as $selected_state)
                            <a href="{{ url('state-inventory/'.$selected_state) }}" class="btn ">{{$selected_state}}</a>
                        @endforeach
                    @endif
                </div>
            </div>

            <hr>
            
            @if(auth()->user()->role=='Admin' || auth()->user()->role=='Observer')            
                <div class="row card">
                    <div class="col m6">
                        

                        <div class="left card-title">
                            <b>Inventory Categories</b>
                        </div>



                        <table id="categories" class="table striped display responsive-table"
                            style="width:100%; font-size:0.7em !important;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Category</th>
                                    <th>Quantity / Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allcats as $cat)
                                    <tr>
                                        <td><a
                                                href="{{ url('/inventorycategory/' . $cat->category) }}">{{ $cat->category }}</a>
                                        </td>
                                        <td>{{ $cat->quantity }}</td>


                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Category</th>
                                    <th>Quantity / Items</th>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                    <div class="col m6">
                        <h5>DCT STOCK BALANCE</h5>
                        <table id="dcts" class="table striped display responsive-table"
                            style="width:100%; font-size:0.7em !important;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>DCT</th>
                                    <th>Quantity / Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dc_stocks as $dct)
                                    <tr>
                                        <td>{{ $dct->Item->tool_name ?? '' }}</td>
                                        <td>{{ $dct->quantity_remaining }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2"><a href="{{ url('dctools') }}" class="btn pull-center">View All</a></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            @endif

            <div style="padding: 35px;" align="center" class="row card">
                <div class="col m4">

                        <div class="left card-title">
                            <b>ITEMS STOCK BALANCE</b>
                        </div>
                        <table id="items" class="table striped display responsive-table"
                            style="width:100%; font-size:0.7em !important;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Item</th>
                                    <th>Quantity / Items</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inv_stock as $item)
                                    <tr>
                                        <td>{{ $item->Item->item_name }}</td>
                                        <td>{{ $item->quantity_remaining }}</td>
                                    </tr>
                                @endforeach
                            
                            </tbody>
                            <tr>
                                <td colspan="2"><a href="{{ url('uitems') }}#products" class="btn pull-center">View All</a></td>
                            </tr>
                        </table>

                </div>

                <div class="col m8">
                        <div class="left card-title">
                            <b>Latest Activity</b>
                        </div>

                        @if ($audits != null)

                            <table id="audits" class="table striped display responsive-table"
                                style="width:100%; font-size:0.7em !important;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Event/Action</th>
                                        <th>Description</th>
                                        <th>User</th>


                                    </tr>
                                </thead>
                                <tbody>
                                    @if (Auth()->user()->role == 'Admin')
                                        @foreach ($audits as $au)
                                            @php
                                                $usr = $usrs->firstWhere('id',$au->doneby);
                                            @endphp
                                            <tr>
                                                <td>{{ $au->created_at }}</td>
                                                <td>{{ $au->action }}</td>
                                                <td>{{ $au->description }}</td>
                                                <td>{{ isset($usr) ? $usr->name : $au->doneby }}</td>

                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($audits as $au)
                                            @php
                                                $usr = $usrs->firstWhere('id',$au->doneby);
                                            @endphp
                                                <tr>
                                                    <td>{{ $au->created_at }}</td>
                                                    <td>{{ $au->action }}</td>
                                                    <td>{{ $au->description }}</td>
                                                    <td>{{ isset($usr) ? $usr->name : $au->doneby }}</td>
                                                </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Datetime</th>
                                        <th>Event/Action</th>
                                        <th>Description</th>
                                        <th>User</th>
                                    </tr>
                                </tfoot>
                            </table>
                            @if (Auth()->user()->role == 'Admin')
                                <div class="row">{{ $audits->links() }}</div>
                            @endif
                        @else
                            <blockquote>No Audit trails found in the database.</blockquote>
                        @endif


                </div>
            </div>

        </div>
    </main>
@endsection