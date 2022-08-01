@extends('template')

@section('content')


        <form action="product_search" class="row" style="width: 50%; margin: auto; text-align: center;" method="post">
            @csrf
            <div class="input-field col m8">
                <select name="keyword" id="keyword" class="browser-default">
                        <option value="-" selected>Search All Items Here</option>
                        @foreach ($all_items as $it)                  
                        
                            <option value="{{$it->id}}">{{$it->item_name}} - {{$it->serial_no}} - {{$it->facilities->facility_name}} - {{$it->user->name}}</option>                                                 
                        
                        @endforeach
                </select>
            </div>
            <div class="input-field col m2">
                <button type="submit" class="btn green"><i class="material-icons right">search</i></button>
            </div>
            <div class="input-field col m2">
                <a href="/inventories" class="btn btn-small btn-floating pulse green center tooltipped" data-position="top" data-tooltip="Print this page"  onclick='printtag("printable");'><i class="material-icons center">printer</i></a>
            </div>
        </form>

    <div class = "row" style="width:98%; margin:auto;" id="printable" data-logo="{{$site_settings->logo}}">
    
        <h4 class=" center">List of inventories</h4>
        
        @if ($inventories!=NULL)
          <div>
              <a href="add_item" class="btn btn-small btn-floating right pulse tooltipped" data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>
          </div>
        <table id="report" class="print_table display responsive-table" style="width:100%;;">
            <thead class="thead-dark">
                <tr>                    
                    <th>Item Name</th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Facility</th>
                    <th>Department</th>                    
                    <th>Unit</th>
                    <th>Internal Location</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $inv)                 
                
                <tr>
                    
                    <td>{{$inv->item_name}}</td>
                    <td>{{$inv->serial_no}}</td>
                    <td>{{$inv->category}}</td>
                    <td>{{$inv->type}}</td>                    
                    <td>{{$inv->facilities->facility_name}}</td>
                    <td>{{$inv->department->department_name}}</td>
                    <td>{{$inv->unit->unit_name}}</td>
                    <td>{{$inv->internal_location}}</td>
                    <td>{{$inv->user->name}}</td>
                    <td>{{$inv->status}}</td>
                    <td>                    
                        <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                    <i class="small material-icons">menu</i>
                                </a>
                                <ul style="top: 0px !important">
                                    
                                    <li>
                                            <form method="POST" action="{{route('inventories.destroy',$inv->id)}}">
                                                @csrf
                                                @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this item?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item"><i class="material-icons">delete</i></button>
                                            </form>
                                    </li>
                                                    
                                    <li>
                                            <a href="item/{{$inv->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="View / Edit Item"><i class="material-icons">remove_red_eye</i></a>         
                                    </li>

                                    <li>
                                            <a href="move_item/{{$inv->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Move / Transfer Item"><i class="material-icons">transfer</i></a>         
                                    </li>

                                    
                                </ul>
                        </div>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>                    
                    <th>Item Name</th>
                    <th>ID</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Facility</th>       
                    <th>Department</th>  
                    <th>Unit</th>           
                    <th>Internal Location</th>
                    <th>User</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
        <div class="col m6 offset-m3">{{$inventories->links()}}</div>
        @else
            <blockquote>No Department found in the database.</blockquote>
        @endif

    </div>
@endsection