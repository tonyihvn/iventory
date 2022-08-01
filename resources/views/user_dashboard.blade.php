@extends('template')

@section('content')


        
            <div class="input-field col m2 center">
                <a href="/home" class="btn btn-small btn-floating pulse green center tooltipped" data-position="top" data-tooltip="Print this page"  onclick='printtag("printable");'><i class="material-icons center">printer</i></a>
            </div>
        

    <div class = "row" style="width:98%; margin:auto;" id="printable" data-logo="{{$site_settings->logo}}">
    
        <h4 class=" center">My List of Items</h4>
        
        @if ($inventories!=NULL)
          <div class="hide_on_print">
              <a href="add_item" class="btn btn-small btn-floating right pulse tooltipped" data-position="top" data-tooltip="Add New Item"><i class="material-icons">add</i></a>
          </div>
        <table id="products" class="print_table display responsive-table" style="width:100%;;">
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
                    <th>File</th>
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
                        @if(substr($inv->file, -3)=="jpg" || substr($inv->file, -3)=="png" || substr($inv->file, -3)=="peg")
                          <img src="/uploads/{{$inv->id}}/{{$inv->file}}"/>
                        @elseif(substr($inv->file, -3)=="pdf")
                          <a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="/uploads/{{$inv->id}}/{{$inv->file}}" data-filename="{{$inv->item_name}}"><i class="material-icons">remove_red_eye</i></a>
                        @elseif(substr($inv->file, -3)=="doc" || substr($inv->file, -3)=="ocx" || substr($inv->file, -3)=="ptx" || substr($inv->file, -3)=="ppt" || substr($inv->file, -3)=="xls")
  
                        <a href="/uploads/{{$inv->id}}/{{$inv->file}}">Open File</a>
                          <!--<a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="https://view.officeapps.live.com/op/view.aspx?src=http://localhost/uploads/{{$inv->id}}/{{urlencode($inv->file)}}" data-filename="{{$inv->item_name}}"><i class="material-icons">remove_red_eye</i></a>
  
                          <a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="http://docs.google.com/gview?url=http://localhost/uploads/{{$inv->id}}/{{urlencode($inv->file)}}" data-filename="{{$inv->item_name}}"><i class="material-icons">remove_red_eye</i></a>-->
                          
                        @elseif($inv->file=="Multiple Files")
                          <a href="/files/{{$item->id}}}">View Files</a>
                        @elseif(substr($inv->file,-3)=="zip")
                          @php
                              $za = new ZipArchive(); 
  
                              $za->open($inv->file); 
                              
                              for( $i = 0; $i < $za->numFiles; $i++ ){ 
                                  $stat = $za->statIndex( $i ); 
                                  print_r( basename( $stat['name'] ) . PHP_EOL ); 
                              }
                          @endphp
  
                        @else
                          Nil
                        @endif                    
                    </td>
                    <td>                    
                        <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                    <i class="small material-icons">menu</i>
                                </a>
                                <ul style="top: 0px !important">
                                    <li>
                                            <a href="print_item/{{$inv->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="View / Edit Item"><i class="material-icons">remove_red_eye</i></a>         
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
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
        <div class="col m6 offset-m3">{{$inventories->links()}}</div>
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