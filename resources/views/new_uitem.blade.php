@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Add New Item</h3>


                <form method="POST" action="{{ route('newuItem') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="0">

                    <div class="row">
                        <div class="input-field col s12">
                            <input id="item_name" type="text" class="validate" name="item_name" required autofocus>
                            <label for="item_name">Item Name</label>
                        </div>
                    </div>

                    <div class="input-field col s12">
                        <textarea id="specifications" class="materialize-textarea" name="specifications"></textarea>
                        <label for="specifications" class="active">Specifications</label>
                    </div>




                    <div class="input-field text-right right" style="margin-bottom:20px;">

                        <button type="submit" class="btn">
                            Add Item
                        </button>

                    </div>
                </form>


            </div>
        </div>

        @if ($items != null)


            <table id="products" class="display" style="width:100%;">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50% !important;">Item Name</th>
                        <th>Specifications</th>
                        <th>Stock Bal.</th>
                        @if (auth()->user()->role == 'Admin')
                            <th>Actions</th>
                        @endif
                    </tr>
                </thead>
                <tbody>

                    @foreach ($items as $dc)
                        <tr>

                            <td>{{ $dc->item_name }}</td>
                            <td>{{ $dc->specifications }}</td>

                                <td>{{ $dc->stock->quantity_remaining ?? 0 }}</td>


                            @if (auth()->user()->role == 'Admin')
                                <td>

                                    <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action"
                                        style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                        <a class="btn-floating btn-small dark-purple waves-effect waves-light"
                                            style="display: inline-block">
                                            <i class="small material-icons">menu</i>
                                        </a>
                                        <ul style="top: 0px !important">
                                            @if (Auth()->user()->role == 'Admin')
                                                <li>
                                                    <a href="{{ url('/deleteuitem/' . $dc->id) }}"
                                                        class="btn-floating btn-small waves-effect red waves-light tooltipped"
                                                        data-position="top" data-tooltip="Remove Item"><i
                                                            class="material-icons">remove</i></a>
                                                </li>
                                            @endif
                                            <li>
                                                <a href="{{ url('/edit_uitem/' . $dc->id) }}"
                                                    class="btn-floating btn-small waves-effect purple waves-light tooltipped"
                                                    data-position="top" data-tooltip="Enter Utilization"><i
                                                        class="material-icons">create</i></a>
                                            </li>

                                        </ul>
                                    </div>


                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>

            </table>

        @else
            <blockquote>No items found in the database.</blockquote>
        @endif
    </div>
@endsection
