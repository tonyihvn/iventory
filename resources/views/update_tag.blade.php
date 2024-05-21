@extends('template')
@section('content')
    <div class="container">
        <div class="row">
            <div class="card col m8 offset-m2" style="margin-top:20px; padding: 35px;">

                <h3 class="card-header text-center" style="text-align:center;">Update Tag Number(s)</h3>


                <form method="POST" action="{{ route('update-tags') }}">
                    @csrf

                    <table class="table">
                        <thead>
                            <tr class="spechead">
                                <th>Old Tag Number</th>
                                <th>New Tag Number</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="item_list">

                            <tr scope='row' class='row1'>
                                <td class='input-field'><input type='text' name='oldtag[]' placeholder='Current Tag'>
                                </td>
                                <td class='input-field'>
                                <td class='input-field'><input type='text' name='newtag[]' placeholder='New Tag No'></td>
                                <td><a href='#' class='btn-floating red btn-small delpos' onClick='delRow(1)'><i
                                            class='small material-icons'>remove</i></a></td>
                            </tr>



                        </tbody>
                    </table>

                    <div>
                        <a class="btn btn-small cyan pulse waves-effect center waves-light add_item" href="#"
                            id="1">
                            Add Another Item
                            <i class="material-icons">add</i>
                        </a>
                    </div>


                    <div class="input-field text-right right" style="margin-bottom:20px;">

                        <button type="submit" class="btn">
                            Update Tag Number(s)
                        </button>

                    </div>
                </form>


            </div>
        </div>
    </div>
@endsection
