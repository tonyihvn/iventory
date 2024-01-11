@extends('print_template')
@section('content')

    <div class="container">

        <div class="center">
            <img src="/uploads/{{ $site_settings->logo }}" alt="No Image Uploaded!" height="80" width="auto">
        </div>

        <table class="striped">
            <tr>
                <td style="width: 50%;">Item Name <br>
                    <h5>{{ $item->item_name }}</h5>
                </td>
                <td>IHVN Tag No.: <br>
                    <b>{{ $item->ihvn_no }}</b><br>
                    Serial No: <br>
                    <b>{{ $item->serial_no }}</b>
                </td>
                <td>
                    @if (substr($item->file, -3) == 'jpg' || substr($item->file, -3) == 'png' || substr($item->file, -3) == 'peg')
                        <img src="/uploads/{{ $item->id }}/{{ $item->file }}" height="200" width="auto" />
                    @endif
                </td>
            </tr>

            <tr>
                <td colspan="2"><strong>Description:</strong> <br> {{ $item->description }}</td>

            </tr>



            <tr>
                <td colspan="2">
                    @if ($item->item_id!="")

                    {{$item->uniqueName->specifications}}

                    @endif
                    @if ($item->inventoryspec != null)
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
                                @foreach ($item->inventoryspec as $key => $is)
                                    <tr scope='row' class='row{{ $key }}'>
                                        <td class='input-field' style="font-weight: bold;">{{ $is->property }}</td>
                                        <td class='input-field'>{{ $is->value }}</td>

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    @endif
                </td>
            </tr>

            <tr>
                <td width="40%" style="font-weight: bold;">Date Purchased: {{ $item->date_purchased }}</td>
                <td>Quantity Purchased: {{ $item->quantity_purchased }}</td>
            </tr>

            <tr>
                <td style="font-weight: bold;">Supplier:</td>
                <td>{{ $item->supplier }}</td>
            </tr>

            <tr>
                <td style="font-weight: bold;">Physical Condition:</td>
                <td>{{ $item->status }}</td>
            </tr>

            <tr>
                <td colspan="2">Facility / User Location: </td>

            </tr>

            <tr>
                <td style="font-weight: bold;">Facility: </td>
                <td>{{ $item->facility ?? $item->facilities->facility_name }}
                    {!! ucwords($item->state) . ' State' ?? '' !!}</td>
            </tr>

            <tr>
                <td style="font-weight: bold;">Department: </td>
                <td>{{ $item->department->department_name }}</td>
            </tr>

            <tr>
                <td style="font-weight: bold;">Unit: </td>
                <td>{{ $item->unit->unit_name }}</td>
            </tr>

            <tr>
                <td style="font-weight: bold;">User:
                    <span style="font-weight: normal !important">
                        {{ $item->assigned_to ?? \App\User::where('id', $item->user_id)->first()->name }} </span>
                </td>
                <td><b>Added By:</b> {{ \App\User::where('id', $item->added_by)->first()->name }}</td>
            </tr>

            <tr>
                <td colspan="2"> <b>Remarks:</b> <br>{{ $item->remarks }}</td>
            </tr>
        </table>

        @if ($item->movement!=NULL)
            <h4>Movements for this item (Transfers)</h4>
            <table class="print_table display striped" style="width:100%;;">
                <thead class="thead-dark">
                    <tr>
                        <th>Item Name</th>
                        <th>ID</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Reason</th>
                        <th>Date</th>
                        <th>File</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($item->movement as $mv)

                    <tr>

                        <td>{{$mv->inventory->item_name}}</td>
                        <td>{{$mv->inventory->serial_no}}</td>
                        <td>{{$mv->from}}</td>
                        <td>{{ \App\User::where('id',$mv->to)->first()->name}}</td>
                        <td>{{$mv->reason}}</td>
                        <td>{{$mv->date_moved}}</td>
                        <td>
                            @if(substr($mv->file, -3)=="jpg" || substr($mv->file, -3)=="png" || substr($mv->file, -3)=="peg")
                            <img src="/uploads/{{$mv->inventories_id}}/{{$mv->file}}" height="80" width="auto"/>
                            @elseif(substr($mv->file, -3)=="pdf")
                            <a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="/uploads/{{$mv->inventories_id}}/{{$mv->file}}" data-filename="{{$mv->item_name}}"><i class="material-icons">remove_red_eye</i></a>
                            @elseif(substr($mv->file, -3)=="doc" || substr($mv->file, -3)=="ocx" || substr($mv->file, -3)=="ptx" || substr($mv->file, -3)=="ppt" || substr($mv->file, -3)=="xls")

                            <a href="{{url('/uploads/'.$mv->inventories_id.'/'.$mv->file)}}">Open File</a>
                            <!--<a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="https://view.officeapps.live.com/op/view.aspx?src=http://localhost/uploads/{{$mv->inventories_id}}/{{urlencode($mv->file)}}" data-filename="{{$mv->item_name}}"><i class="material-icons">remove_red_eye</i></a>

                            <a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="http://docs.google.com/gview?url=http://localhost/uploads/{{$mv->inventories_id}}/{{urlencode($mv->file)}}" data-filename="{{$mv->item_name}}"><i class="material-icons">remove_red_eye</i></a>-->

                            @elseif($mv->file=="Multiple Files")
                            @php
                                $files = scandir('uploads/'.$mv->inventories_id);
                                foreach ($files as $key => $file) {
                                    echo '<a href="/uploads/'.$mv->inventories_id.'/'.$file.'" target="_blank">'.$file.'</a><br>';
                                }
                            @endphp

                            @elseif(substr($mv->file,-3)=="zip")
                            @php
                                $za = new ZipArchive();

                                $za->open($mv->file);

                                for( $i = 0; $i < $za->numFiles; $i++ ){
                                    $stat = $za->statIndex( $i );
                                    print_r( basename( $stat['name'] ) . PHP_EOL );
                                }
                            @endphp

                            @else

                            @endif
                        </td>
                        <td>

                    </tr>
                    @endforeach
                </tbody>

            </table>
        @else
            <blockquote>No movement transaction found in the database.</blockquote>
        @endif


    </div>
@endsection
