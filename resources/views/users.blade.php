@extends('template')

@section('content')

    <div class = "row" style="width:98%; margin:auto;">
        <div class="col s12">
            <h5 class="text-center">Users</h5>

            @php
                if(auth()->user()->role=="Admin"){
                    $clients = \App\User::all();
                }else{
                    $clients = \App\User::where('state',Auth()->user()->state)->get();
                }

                $facilities = \App\facilities::select('id','facility_name')->get();
                $departments = \App\department::select('id','department_name')->get();
                $units = \App\unit::select('id','unit_name')->get();


            @endphp

            @if ($clients!=NULL)
            <div>
                <a href="{{url('/register')}}" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
            </div>
            <table id="products" class="display responsive-table" style="width:100%;">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Phone Number</th>
                        <th>Unit</th>
                        <th>Department</th>
                        <th>Facility</th>
                        <th>State</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $ca)

                    <tr>
                        <td>{{$ca->name}}</td>
                        <td>{{$ca->email}}</td>
                        <td>{{$ca->phone_number}}</td>
                        <td>{{ $units[array_search($ca->unit, array_column($units->toArray(), 'id'))]['unit_name']}}</td>
                        <td>{{ $departments[array_search($ca->department, array_column($departments->toArray(), 'id'))]['department_name']}}</td>
                        <td>{{ $facilities[array_search($ca->facility, array_column($facilities->toArray(), 'id'))]['facility_name']}}</td>
                        <td>{{$ca->state}}</td>
                        <td>{{$ca->role}}</td>
                        <td>
                            <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                    <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                        <i class="small material-icons">menu</i>
                                    </a>
                                    <ul style="top: 0px !important">

                                        <li>
                                                <form method="POST" action="{{route('deleteUser',$ca->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                <button onclick="return confirm('Are you sure you want to delete this user?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this User"><i class="material-icons">delete</i></button>
                                                </form>
                                        </li>

                                        <li>
                                                <a href="{{url('/edit_user/'.$ca->id)}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Edit User" target="_blank"><i class="material-icons">remove_red_eye</i></a>
                                        </li>


                                    </ul>
                            </div>

                        </td>

                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Phone Number</th>
                        <th>Unit</th>
                        <th>Department</th>
                        <th>Facility</th>
                        <th>State</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
           @else
                <blockquote>No User found in the database.</blockquote>
            @endif
        </div>


    </div>
@endsection
