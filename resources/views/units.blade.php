@extends('template')

@section('content')

    <div class = "row" style="width:98%; margin:auto;">
        <h5 class="text-center">List of Units</h5>

        @if ($units!=NULL)
          <div>
              <a href="{{url('/add_unit')}}" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
          </div>
        <table id="products" class="display responsive-table" style="width:100%;;">
            <thead class="thead-dark">
                <tr>

                    <th>Unit Name</th>
                    <th>Department</th>
                    <th>Facility Name</th>
                    <th>Internal Location</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($units as $un)

                <tr>

                    <td>{{$un->unit_name}}</td>
                    <td>{{$un->department}}</td>
                    <td>{{$un->facility}}</td>
                    <td>{{$un->internal_location}}</td>
                    <td>
                        <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                    <i class="small material-icons">menu</i>
                                </a>
                                <ul style="top: 0px !important">

                                    <li>
                                            <form method="POST" action="{{route('units.destroy',$un->id)}}">
                                                @csrf
                                                @method('DELETE')
                                            <button onclick="return confirm('Are you sure you want to delete this unit?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item"><i class="material-icons">delete</i></button>
                                            </form>
                                    </li>

                                    <li>
                                            <a href="{{url('/unit/'.$un->id)}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="unit Inventory" target="_blank"><i class="material-icons">list</i></a>
                                    </li>


                                </ul>
                        </div>

                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>

                    <th>Unit Name</th>
                    <th>Department</th>
                    <th>Facility Name</th>
                    <th>Internal Location</th>
                    <th>Actions</th>
                </tr>
            </tfoot>
        </table>
        <div class="col m6 offset-m3">{{$units->links()}}</div>
        @else
            <blockquote>No unit found in the database.</blockquote>
        @endif

    </div>
@endsection
