@extends('template')

@section('content')
    
    <div class = "row" style="width:98%; margin:auto;">
        <h5 class="text-center">Activities / Audit Trails</h5>
    
        @if ($audits!=NULL)
          
        <table id="audits" class="display responsive-table" style="width:100%;;">
            <thead class="thead-dark">
                <tr>
                    <th>Date & Time</th>
                    <th>Event/Action</th>
                    <th>Description</th>
                    <th>User</th>
                    
                   
                </tr>
            </thead>
            <tbody>
                @foreach ($audits as $au)                 
                
                <tr>
                    <td>{{$au->created_at}}</td>
                    <td>{{$au->action}}</td>
                    <td>{{$au->description}}</td>
                    <td>{{$au->doneby}}</td>
                    
                  
                </tr>
                @endforeach
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
        <div class="col m6 offset-m3">{{$audits->links()}}</div>
        @else
            <blockquote>No Audit trails found in the database.</blockquote>
        @endif

    </div>
@endsection