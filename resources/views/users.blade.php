@extends('template')

@section('content')
    
    <div class = "row" style="width:98%; margin:auto;">
        <div class="col s12">
            <h5 class="text-center">Users</h5>

            @php
            $clients = \App\User::all();
            @endphp
        
            @if ($clients!=NULL)
            <div>
                <a href="/register" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
            </div>
            <table id="audits" class="display responsive-table" style="width:100%;;">
                <thead class="thead-dark">
                    <tr>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Phone Number</th>
                        <th>Unit</th>
                        <th>Department</th>
                        <th>Facility</th>
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
                        <td>{{$ca->unit}}</td>
                        <td>{{$ca->department}}</td>
                        <td>{{$ca->facility}}</td>
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
                                                <a href="edit_user/{{$ca->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Edit User" target="_blank"><i class="material-icons">remove_red_eye</i></a>         
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