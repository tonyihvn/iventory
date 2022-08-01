@extends('template')

@section('content')
<style>

    .card-panel {
    min-height: 450px;
    margin: 0;
    }

    .highcharts-root {
    font-family: "Roboto" !important;
    }

    .highcharts-button-symbol {
    display: none;
    }

    .highcharts-title {
    font-size: 1.5rem !important;
    }
</style>
    <main>
        <div class="row">
            <div class="text-center" style="text-align: center; margin-top: 10px;">
                <a href="/add_item" class="btn btn-large pulse"><i class="material-icons">add</i> Add New Item</a>
                <a href="/inventories" class="btn btn-large green pulse"><i class="material-icons">remove_red_eye</i>Inventories</a>
            </div>
            <hr>
            
            <div style="padding: 35px;" align="center" class="card">
                <div class="row">
                    <div class="left card-title">
                    <b>Chart of Inventories</b>
                    </div>
                </div>

                <div class="row">
                    <div id="basic-area" class="card-panel"></div>                
                </div>
            </div>

            <div style="padding: 35px;" align="center" class="row card">
                <div class="col m4">
                    <div class="row">
                        <div class="left card-title">
                        <b>Inventory Categories</b>
                        </div>
                    </div>

                    <div class="row">
                        <table id="categories" class="table striped display responsive-table" style="width:100%; font-size:0.8em !important;">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Category</th>
                                    <th>Quantity / Items</th>  
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($allcats as $cat)                 
                                
                                <tr>
                                    <td><a href="/inventorycategory/{{$cat->category}}/">{{$cat->category}}</a></td>
                                    <td>{{$cat->quantity}}</td>
                                    
                                  
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>                    
                                    <th>Category</th>
                                    <th>Quantity / Items</th>  
                                </tr>
                            </tfoot>
                        </table>     
                    </div>
                </div>

                <div class="col m8">
                    <div class="row">
                        <div class="left card-title">
                        <b>Latest Activity</b>
                        </div>
                        
                            @if ($audits!=NULL)
                              
                            <table id="audits" class="table striped display responsive-table" style="width:100%; font-size:0.7em !important;">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date & Time</th>
                                        <th>Event/Action</th>
                                        <th>Description</th>
                                        <th>User</th>
                                        
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($audits as $au)                 
                                    
                                    <tr>
                                        <td>{{$au->created_at}}</td>
                                        <td>{{$au->action}}</td>
                                        <td>{{$au->description}}</td>
                                        <td>{{$au->doneby}}</td>
                                        
                                      
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>                    
                                        <th>Datetime</th>
                                        <th>Event/Action</th>
                                        <th>Description</th>
                                        <th>User</th>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="col m6 offset-m3">{{$audits->links()}}</div>
                            @else
                                <blockquote>No Audit trails found in the database.</blockquote>
                            @endif
                    
                    </div>

                    <div class="row">
                                       
                    </div>
                </div>
            </div>
        
        </div>
    </main>    
@endsection