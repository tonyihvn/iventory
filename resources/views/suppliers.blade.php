@extends('template')

@section('content')
    
    <div class = "row" style="width:98%; margin:auto;">
        <div class="col s12">
            <h5 class="text-center">Suppliers</h5>
        
            @if ($clients!=NULL)
            <div>
                <a href="/add_facility" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
            </div>
            <table id="audits" class="display responsive-table" style="width:100%;;">
                <thead class="thead-dark">
                    <tr>
                        <th>Client Name</th>
                        <th>Organization</th>
                        <th>Phone Number</th>
                        <th>E-mail</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Actions</th>
                        
                    
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $ca)                 
                    
                    <tr>
                        <td>{{$ca->supplier_name}}</td>
                        <td>{{$ca->company_name}}</td>
                        <td>{{$ca->phone_number}}</td>
                        <td>{{$ca->email}}</td>
                        <td>{{$ca->items}}</td>
                        <td>{{$ca->category}}</td>
                        <td>                    
                            <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                    <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                        <i class="small material-icons">menu</i>
                                    </a>
                                    <ul style="top: 0px !important">
                                        
                                        <li>
                                                <form method="POST" action="{{route('categories.destroy',$ca->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                <button onclick="return confirm('Are you sure you want to delete this category?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item"><i class="material-icons">delete</i></button>
                                                </form>
                                        </li>
                                                        
                                        <li>
                                                <a href="client/{{$ca->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Category Inventory" target="_blank"><i class="material-icons">list</i></a>         
                                        </li>

                                        
                                    </ul>
                            </div>
                            
                        </td>
                        
                    
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>                    
                        <th>Client Name</th>
                        <th>Organization</th>
                        <th>Phone Number</th>
                        <th>E-mail</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
           @else
                <blockquote>No Category found in the database.</blockquote>
            @endif
        </div>

     
    </div>
@endsection