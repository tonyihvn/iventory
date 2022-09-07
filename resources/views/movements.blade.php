@extends('template')

@section('content')
        <div>
              <a href="movements" class="btn btn-small btn-floating right pulse"  onclick='printtag("printable");'><i class="material-icons">printer</i></a>
        </div>
    <div class = "row" style="width:98%; margin:auto;" id="printable" data-logo="{{$site_settings->logo}}">
        <h5 class="text-center center" style="text-align-center">Movements, Transfers and Transactions</h5>

        @if ($movements!=NULL)

        <table id="products" class="print_table display responsive-table" style="width:100%;;">
            <thead class="thead-dark">
                <tr>
                    <th>Item Name</th>
                    <th>ID</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($movements as $mv)

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

                        <a href="uploads/{{$mv->inventories_id}}/{{$mv->file}}">Open File</a>
                          <!--<a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="https://view.officeapps.live.com/op/view.aspx?src=http://localhost/uploads/{{$mv->inventories_id}}/{{urlencode($mv->file)}}" data-filename="{{$mv->item_name}}"><i class="material-icons">remove_red_eye</i></a>

                          <a href="#fileModal" id="viewfile" onclick="viewFile()" class="btn-floating btn-small waves-effect waves-light btn modal-trigger tooltipped" data-position="top" data-tooltip="View / Edit Item" data-src="http://docs.google.com/gview?url=http://localhost/uploads/{{$mv->inventories_id}}/{{urlencode($mv->file)}}" data-filename="{{$mv->item_name}}"><i class="material-icons">remove_red_eye</i></a>-->

                        @elseif($mv->file=="Multiple Files")
                        @php
                            $files = scandir(public_path() . '/uploads/'.$mv->inventories_id.'/');
                            foreach ($files as $key => $file) {
                                echo '<a href="uploads/'.$mv->inventories_id.'/'.$file.'" target="_blank">'.$file.'</a><br>';
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
                    <td>
                        <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                    <i class="small material-icons">menu</i>
                                </a>
                                <ul style="top: 0px !important">

                                    <li>
                                            <form method="POST" action="{{route('movements.destroy',$mv->id)}}">
                                                @csrf
                                                @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this transaction history?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item"><i class="material-icons">delete</i></button>
                                            </form>
                                    </li>

                                    <li>
                                            <a href="item/{{$mv->inventories_id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="View Item History"><i class="material-icons">remove_red_eye</i></a>
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
                    <th>From</th>
                    <th>To</th>
                    <th>Reason</th>
                    <th>Date</th>
                    <th>File</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
        <div class="col m6 offset-m3" style="background-color: white !important;">{{$movements->links()}}</div>
        @else
            <blockquote>No movement transaction found in the database.</blockquote>
        @endif

    </div>
@endsection
