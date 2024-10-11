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
                    <div class="col m6 card">
                        <div class="card-title">
                            Chart
                        </div>
                        <hr>
                        <div id="basic-area" class="card-content"></div>

                    </div>
                    <div class="col m6 card blue-grey lighten-5">
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
                                        <th>Action</th>
                                    </tr>

                                    @foreach ($requests as $req)
                                        <tr>
                                            <td>{{ $req->user->name }} / {{$req->user->state}}</td>
                                            <td>{{date('Y-m-d',strtotime($req->created_at)) }}</td>
                                            <td>{{ $req->request_status }}</td>
                                            <td><a href="{{url('request/'.$req->id)}}" class="btn">Attend</a></td>

                                        </tr>
                                    @endforeach
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>


            </div>

            <div style="padding: 35px;" align="center" class="row card">
                <div class="col m4">

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
                                                $index = array_search($au->doneby, array_column($usrs, 'id'));
                                            @endphp
                                            <tr>
                                                <td>{{ $au->created_at }}</td>
                                                <td>{{ $au->action }}</td>
                                                <td>{{ $au->description }}</td>
                                                <td>{{ $usrs[$index]['name'] }}</td>

                                            </tr>
                                        @endforeach
                                    @else
                                        @foreach ($audits as $au)
                                            @if ($index = array_search($au->doneby, array_column($usrs, 'id')) == true)
                                                <tr>
                                                    <td>{{ $au->created_at }}</td>
                                                    <td>{{ $au->action }}</td>
                                                    <td>{{ $au->description }}</td>
                                                    <td>{{ $usrs[$index]['name'] }}</td>


                                                </tr>
                                            @endif
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
