@extends('template')
@section('content')

<div class="container">
    
                    <div class="center">
                        <img src="/uploads/{{$site_settings->logo}}" alt="No Logo Uploaded!" height="80" width="auto">
                    </div>
                <h5 class="card-header text-center" style="text-align:center;">{{$site_settings->organization_name}}</h5>
                <div class="col m9">
               

                    <table class="striped">
                            <tr>
                            <td>Item Name: <br> <h4>{{$item->item_name}}</h4></td>
                            <td>Serial No: <br> <h4>{{$item->serial_no}}</h4></td>
                        </tr>

                        <tr>
                            <td colspan="2"><strong>Description:</strong> <br>  {{$item->description}}</td>
                            
                        </tr>

                        <tr>
                            <td colspan="2">
                                @if ($item->inventoryspec!=NULL)

                                
                                <table class="table highlight" style="width: 50%; margin:auto">
                                    
                                    <thead>
                                        <tr>
                                            <td colspan="2">Specifications / Properties: </td>
                                            
                                        </tr>
                                        <tr>
                                            <th>Property</th>
                                            <th>Value/Description</th>                                  
                                        </tr>
                                    </thead>
                                    <tbody id="item_list">
                                    @foreach($item->inventoryspec as $key => $is)
                                
                                    <tr scope='row' class='row{{$key}}'>
                                        <td class='input-field'>{{$is->property}}</td>
                                        <td class='input-field'>{{$is->value}}</td>
                                        
                                    </tr>
                                    
                                    @endforeach                              
                                        
                                    </tbody>
                                </table>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <td>Date Purchased: {{$item->date_purchased}}</td>
                            <td>Quantity Purchased: {{$item->quantity_purchased}}</td>
                        </tr>

                        <tr>
                            <td>Supplier:</td>
                            <td>{{$item->supplier}}</td>
                        </tr>

                        <tr>
                            <td>Physical Condition:</td>
                            <td>{{$item->status}}</td>
                        </tr>

                        <tr>
                            <td colspan="2">Facility / User Location: </td>
                            
                        </tr>

                        <tr>
                            <td>Facility: </td>
                            <td>{{$item->facilities->facility_name}}</td>
                        </tr>

                        <tr>
                            <td>Department: </td>
                            <td>{{$item->department->department_name}}</td>
                        </tr>

                        <tr>
                            <td>Unit: </td>
                            <td>{{$item->unit->unit_name}}</td>
                        </tr>

                        <tr>
                            <td>User: {{$item->user->user_id}}</td>
                            <td>Added By: {{$item->added_by}}</td>
                        </tr>

                        <tr>
                            <td>Remarks: </td>
                            <td>{{$item->remarks}}</td>
                        </tr>
                    </table>
                </div>

                
                <div class="col s3">
                    <h5 class="text-center">Record Files {{$item_name}}</h5>
                
                    @if ($files!=NULL)
                    <div>
                        <a href="/add_facility" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
                    </div>
                    <table id="audits" class="display responsive-table" style="width:100%;;">
                        <thead class="thead-dark">
                            <tr>
                                <th>File Name</th>
                                <th>View File</th>
                                
                            
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($files as $fa)                 
                            
                            <tr>
                                <td>{{$fa->filename}}</td>
                                
                                <td>
                                    @if(substr({{$inv->file}}, -3)=="jpg" || substr({{$inv->file}}, -3)=="png" || substr({{$inv->file}}, -3)=="peg")
                                        <img src="/uploads/{{$inv->id}}/{{$inv->file}}"/>
                                    @elseif(substr({{$inv->file}}, -3)=="pdf")
                                        <iframe src="/uploads/{{$inv->id}}/{{$inv->file}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                    @elseif(substr({{$inv->file}}, -3)=="doc" || substr({{$inv->file}}, -3)=="ocx" || substr({{$inv->file}}, -3)=="ptx" || substr({{$inv->file}}, -3)=="ppt")
                                        <iframe src="https://view.officeapps.live.com/op/view.aspx?src={{urlendoe('/uploads/'.{{$inv->id}}/{{$inv->file}})}}" frameborder="0" style="width:100%;min-height:640px;"></iframe>
                                    @elseif({{$inv->file}}=="Multiple Files")
                                        <a href="/files/{{$item->id}}}">View Files</a>
                                        @elseif(substr({{$inv->file}},-3)=="zip")
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
                            
                            
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        </div>    </table>
                    <div class="col m6 offset-m3">{{$categories->links()}}</div>
                    @else
                        <blockquote>No Files found in the database.</blockquote>
                    @endif
                </div>
            
                
                
       
</div>
<script src="{{asset('/js/lga.js')}}"></script>
@endsection
