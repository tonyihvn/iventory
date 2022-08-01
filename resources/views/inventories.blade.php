@extends('template')

@section('content')

    <div class = "row" style="width:98% !important; margin:auto;" id="printable" data-logo="{{$site_settings->logo}}">
    
        <h4 class=" center">List of inventories</h4>
        
        @if ($inventories!=NULL)
          <div>
              <a href="add_item" class="btn btn-small btn-floating right pulse tooltipped" data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>
          </div>
        <table id="products" class="print_table display nowrap responsive-table" style="width:100%;;">
            <thead class="thead-dark">
                <tr>         
                    <th>State</th>           
                    <th>Item Name</th>
                    <th>ID/IHVN/TAG No</th>
                    <th>Category</th>
                    <th>Facility</th>
                    
                    <th>User</th>
                    <th>Status</th>
                   
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventories as $inv)                 
                
                <tr>
                    <td>{{$inv->state}}</td>
                    <td>{{$inv->item_name}}</td>
                    <td>{{$inv->serial_no}} / {{$inv->ihvn_no}} / {{$inv->tag_no}}</td>
                    <td>{{$inv->category}}</td>
                    <td>{{$inv->facility}}</td>                    
                    <td>{{$inv->assigned_to}}</td>
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
                                        <a href="/print_item/{{$inv->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="View Item"><i class="material-icons">remove_red_eye</i></a>         
                                    </li>                 
                                    <li>
                                            <a href="/item/{{$inv->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Edit Item"><i class="material-icons">create</i></a>         
                                    </li>

                                    <li>
                                            <a href="/move_item/{{$inv->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Move / Transfer Item"><i class="material-icons">repeat</i></a>         
                                    </li>

                                    
                                </ul>
                        </div>
                        
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
        
        
        
        @else
            <blockquote>No Department found in the database.</blockquote>
        @endif

    </div>

    <!-- Modal Structure -->
    <div id="fileModal" class="modal bottom-sheet">
        <div class="modal-content">
        <h4 id="filename"></h4>
        <p><iframe src="" id="fileframe" frameborder="0" style="width:100%;min-height:640px;"></iframe></p>
        </div>
        <div class="modal-footer">
        <a href="#!" class="modal-close waves-effect waves-green btn-flat">Close</a>
        </div>
    </div>


@endsection