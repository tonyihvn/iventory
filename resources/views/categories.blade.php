@extends('template')

@section('content')
    
    <div class = "row" style="width:98%; margin:auto;">
        <div class="col s9">
            <h5 class="text-center">Item Categories</h5>
        
            @if ($categories!=NULL)
            <div>
                <a href="/add_facility" class="btn btn-small btn-floating right pulse"><i class="material-icons">add</i></a>
            </div>
            <table id="audits" class="display responsive-table" style="width:100%;;">
                <thead class="thead-dark">
                    <tr>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                        
                    
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $ca)                 
                    
                    <tr>
                        <td>{{$ca->category_name}}</td>
                        <td>{{$ca->description}}</td>
                        <td>                    
                            <div class="fixed-action-btn horizontal direction-top direction-left click-to-toggle sales_action" style="position: relative !important; float: text-align: center; display: inline-block; bottom: 0px !important; padding: 0px !important">
                                    <a class="btn-floating btn-small dark-purple waves-effect waves-light" style="display: inline-block" >
                                        <i class="small material-icons">menu</i>
                                    </a>
                                    <ul style="top: 0px !important">
                                        
                                        <li>
                                                <form method="POST" action="{{route('categories.destroy',$ca->id)}}">
                                                    @csrf
                                                    @method('DELETE')
                                                <button onclick="return confirm('Are you sure you want to delete this category?')" class="btn-floating btn-small waves-effect red waves-light tooltipped" data-position="top" data-tooltip="Delete this Item"><i class="material-icons">delete</i></button>
                                                </form>
                                        </li>
                                                        
                                        <li>
                                                <a href="category/{{$ca->id}}" class="btn-floating btn-small waves-effect blue waves-light tooltipped" data-position="top" data-tooltip="Category Inventory" target="_blank"><i class="material-icons">list</i></a>         
                                        </li>

                                        
                                    </ul>
                            </div>
                            
                        </td>
                        
                    
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>                    
                        <th>Datetime</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </tfoot>
            </table>
            <div class="col m6 offset-m3">{{$categories->links()}}</div>
            @else
                <blockquote>No Category found in the database.</blockquote>
            @endif
        </div>

        <div class="col s3">
            <h3 class="card-header text-center" style="text-align:center;">Add New Category</h3>

                    
            <form method="POST" action="{{ route('categories.store') }}">
                @csrf

                <div class="input-field">
                        <input id="category_name" type="text" class="validate" name="category_name" required autofocus>
                        <label for="category_name">Category Name</label>
                </div>

                <div class="input-field">
                        <textarea name="description" id="description" class="materialize-textarea"></textarea>
                        <label for="description">Notes / Description</label>
                </div>

                <div class="input-field text-right right" style="margin-bottom:20px;">
                    
                        <button type="submit" class="btn">
                            Add Category
                        </button>                               
                
                </div>
            </form>
        </div>

    </div>
@endsection