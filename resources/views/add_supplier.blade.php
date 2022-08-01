@extends('template')
@section('content')
<div class="container">
    <div class="row">
        <div class="card col m6 offset-m3" style="margin-top:20px;">
            
                <h3 class="card-header text-center" style="text-align:center;">Add New Supplier</h3>
                
                    <form method="POST" action="{{ route('suppliers.store') }}" enctype="multipart/form-data" >
                        @csrf

                        <div class="input-field">
                            <input id="supplier_name" type="text" class="validate" name="supplier_name" required autofocus>
                            <label for="supplier_name">Client Name</label>
                        </div>

                        <div class="input-field">
                                <input id="company_name" type="text" class="validate" name="company_name" autofocus>
                                <label for="company_name">Org./Company Name</label>
                        </div>


                        <div class="input-field">
                            <input id="phone_number" type="text" class="validate" name="phone_number" autofocus>
                            <label for="phone_number">Phone Number</label>
                        </div>

                        <div class="input-field">
                            <input id="email" type="email" class="validate" name="email" autofocus>
                            <label for="email">E-mail</label>
                        </div>

                        <div class="input-field">
                            <textarea id="items" class="materialize-textarea" name="items"></textarea>
                            <label for="items">Description</label>
                        </div>

                        <div class="input-field">
                            <input id="category" type="text" class="validate" name="category" autofocus>
                            <label for="category">Category</label>
                        </div>

                        
                        <div class="input-field text-right right" style="margin-bottom:20px;">
                            
                                <button type="submit" class="btn">
                                    Save
                                </button>                               
                        
                        </div>
                    </form>
                
            
        </div>
    </div>
</div>
@endsection
