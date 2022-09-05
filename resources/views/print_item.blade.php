@extends('print_template')
@section('content')

<div class="container">

                    <div class="center">
                        <img src="/uploads/{{$site_settings->logo}}" alt="No Image Uploaded!" height="80" width="auto">
                    </div>

                <table class="striped">
                    <tr>
                        <td colspan="2">Item Name: <br> <h4>{{$item->item_name}}</h4><br>
                            Serial No: <br> <h4>{{$item->serial_no}}</h4></td>
                        <td>
                            @if(substr($item->file, -3)=="jpg" || substr($item->file, -3)=="png" || substr($item->file, -3)=="peg")
                                    <img src="/uploads/{{$item->id}}/{{$item->file}}" height="200" width="auto"/>
                            @endif
                        </td>
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
                                    <tr scope='row'>
                                        <th style="text-align: left !important;">Property</th>
                                        <th>Value/Description</th>
                                    </tr>
                                </thead>
                                <tbody id="item_list">
                                @foreach($item->inventoryspec as $key => $is)

                                <tr scope='row' class='row{{$key}}'>
                                    <td class='input-field' style="font-weight: bold;">{{$is->property}}</td>
                                    <td class='input-field'>{{$is->value}}</td>

                                </tr>

                                @endforeach

                                </tbody>
                            </table>
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <td width="40%" style="font-weight: bold;">Date Purchased: {{$item->date_purchased}}</td>
                        <td>Quantity Purchased: {{$item->quantity_purchased}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">Supplier:</td>
                        <td>{{$item->supplier}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">Physical Condition:</td>
                        <td>{{$item->status}}</td>
                    </tr>

                    <tr>
                        <td colspan="2">Facility / User Location: </td>

                    </tr>

                    <tr>
                        <td style="font-weight: bold;">Facility: </td>
                        <td>{{$item->facilities->facility_name}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">Department: </td>
                        <td>{{$item->department->department_name}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">Unit: </td>
                        <td>{{$item->unit->unit_name}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">User: {{ \App\User::where('id',$item->user_id)->first()->name}}</td>
                        <td>Added By: {{ \App\User::where('id',$item->added_by)->first()->name}}</td>
                    </tr>

                    <tr>
                        <td style="font-weight: bold;">Remarks: </td>
                        <td>{{$item->remarks}}</td>
                    </tr>
                </table>



</div>
<script src="{{asset('/js/lga.js')}}"></script>
@endsection
