@extends('template')

@section('content')
    
    <main>
        <div class="row">
            <div class="text-center" style="text-align: center; margin-top: 10px;">
                <a href="/add_sales" class="btn btn-large pulse"><i class="material-icons">shopping</i> Add New Sales</a>
            </div>
                   <hr>
            <div class="col m6">
            <div style="padding: 35px;" align="center" class="card">
            <div class="row">
                <div class="left card-title">
                <b>User Management</b>
                </div>
            </div>

            <div class="row">
                <a href="personnels">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">person</i>
                    <span class="indigo-text text-lighten-1"><h6>Staff</h6></span>
                </div>
                </a>

                <a href="customers">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">people</i>
                    <span class="indigo-text text-lighten-1"><h6>Customers</h6></span>
                </div>
                </a>
            </div>
            </div>
        </div>

        <div class="col m6">
            <div style="padding: 35px;" align="center" class="card">
            <div class="row">
                <div class="left card-title">
                <b>Product Management</b>
                </div>
            </div>
            <div class="row">
                <a href="products">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">store</i>
                    <span class="indigo-text text-lighten-1"><h6>Products/Stock</h6></span>
                </div>
                </a>

                <a href="inventory">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">assignment</i>
                    <span class="indigo-text text-lighten-1"><h6>Inventory</h6></span>
                </div>
                </a>
            </div>
            </div>
        </div>
        </div>

        <div class="row">
        <div class="col m6">
            <div style="padding: 35px;" align="center" class="card">
            <div class="row">
                <div class="left card-title">
                <b>Sales Management</b>
                </div>
            </div>

            <div class="row">
                <a href="today_sales">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">local_offer</i>
                    <span class="indigo-text text-lighten-1"><h6>Todays Sales</h6></span>
                </div>
                </a>

               

                <a href="view_sales">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">loyalty</i>
                    <span class="indigo-text text-lighten-1"><h6>Sales Report</h6></span>
                </div>
                </a>
            </div>
            </div>
        </div>

        <div class="col m6">
            <div style="padding: 35px;" align="center" class="card">
            <div class="row">
                <div class="left card-title">
                <b>Suppliers</b>
                </div>
            </div>
            <div class="row">
                <a href="suppliers">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">view_list</i>
                    <span class="indigo-text text-lighten-1"><h6>Our Suppliers</h6></span>
                </div>
                </a>

                <a href="contact_customers">
                <div style="padding: 20px;" class="grey lighten-3 col s6 waves-effect">
                    <i class="indigo-text text-lighten-1 large material-icons">view_list</i>
                    <span class="truncate indigo-text text-lighten-1"><h6>Contatcs</h6></span>
                </div>
                </a>
            </div>
            </div>
        </div>
        </div>

        
    </main>

@endsection