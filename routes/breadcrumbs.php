<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Home
Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
    $trail->push('Home', route('home'));
});

// Home > Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Concurrency
Breadcrumbs::for('dataquality', function (BreadcrumbTrail $trail) {
    $trail->parent('inventory');
    $trail->push('Update Item Status', route('dataquality'));
});

// Home > Concurrency
Breadcrumbs::for('concurrency', function (BreadcrumbTrail $trail) {
    $trail->parent('dataquality');
    $trail->push('Concurrency', route('concurrency'));
});

// Home > Settings
Breadcrumbs::for('system-admin', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('System Administration', route('settings.index'));
});

// Home > Settings > Edit
Breadcrumbs::for('settings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('system-admin');
    $trail->push("Settings", route('settings.index'));
});

// Home > Settings > Edit
Breadcrumbs::for('edit_settings', function (BreadcrumbTrail $trail, $id) {
    $trail->parent('system-admin');
    $trail->push("Edit App Setting", route('edit_settings', $id));
});
// Home > Setting Administration > Users
Breadcrumbs::for('users', function (BreadcrumbTrail $trail) {
    $trail->parent('system-admin');
    $trail->push('User Management', route('settings.index'));
});

// Home > Suppliers
Breadcrumbs::for('suppliers.index', function (BreadcrumbTrail $trail) {
    $trail->parent('supplies');
    $trail->push('Suppliers', route('suppliers.index'));
});

// Home > Supplies
Breadcrumbs::for('supplies', function (BreadcrumbTrail $trail) {
    $trail->parent('uitems');
    $trail->push('Supplies', route('supplies'));
});

// Home > Inventory
Breadcrumbs::for('inventory', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Inventories', route('inventory'));
});

// Home > Inventory
Breadcrumbs::for('inventories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('All Item Inventory', route('inventories.index'));
});

// Home > Inventory
Breadcrumbs::for('inventorycategory', function (BreadcrumbTrail $trail,$category) {
    $trail->parent('inventory');
    $trail->push("Inventory of $category", route('inventorycategory', $category));
});


// Home > Inventory > Add New Item
Breadcrumbs::for('add_item', function (BreadcrumbTrail $trail) {
    $trail->parent('inventory');
    $trail->push('Add New Item', route('add_item'));
});

// Home > Item
Breadcrumbs::for('uitems', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push("Items", route('uitems'));
});

// Home > Item > Requests
Breadcrumbs::for('requests', function (BreadcrumbTrail $trail) {
    $trail->parent('uitems');
    $trail->push("Requests", route('requests'));
});

// Home > Item > Requests
Breadcrumbs::for('request', function (BreadcrumbTrail $trail,$id) {
    $trail->parent('requests');
    $trail->push("Attend to a Request", route('request',$id));
});

// Home > Item > Requests
Breadcrumbs::for('update-tagnumbers', function (BreadcrumbTrail $trail) {
    $trail->parent('uitems');
    $trail->push("Update Tag Numbers", route('update-tagnumbers'));
});



// Home > Facilities
Breadcrumbs::for('facilities.index', function (BreadcrumbTrail $trail) {
    $trail->parent('system-admin');
    $trail->push('Facilities', route('facilities.index'));
});

// Home > Facilities > Add Facility
Breadcrumbs::for('add_facility', function (BreadcrumbTrail $trail) {
    $trail->parent('facilities.index');
    $trail->push('Add Facility', route('add_facility'));
});

// Home > Movements
Breadcrumbs::for('movements.index', function (BreadcrumbTrail $trail) {
    $trail->parent('inventory');
    $trail->push('Movements', route('movements.index'));
});

// Home > Audits
Breadcrumbs::for('audits.index', function (BreadcrumbTrail $trail) {
    $trail->parent('system-admin');
    $trail->push('Audits', route('audits.index'));
});

// Home > Departments
Breadcrumbs::for('add_department', function (BreadcrumbTrail $trail) {
    $trail->parent('departments.index');
    $trail->push('Add Department', route('add_department'));
});

// Home > Departments
Breadcrumbs::for('departments.index', function (BreadcrumbTrail $trail) {
    $trail->parent('facilities.index');
    $trail->push('Departments', route('departments.index'));
});

// Home > Categories
Breadcrumbs::for('categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('uitems');
    $trail->push('Categories', route('categories.index'));
});

// Home > Units
Breadcrumbs::for('units.index', function (BreadcrumbTrail $trail) {
    $trail->parent('departments.index');
    $trail->push('Units', route('units.index'));
});

// Home > Units
Breadcrumbs::for('add_unit', function (BreadcrumbTrail $trail) {
    $trail->parent('units.index');
    $trail->push('Add Units', route('add_unit'));
});

// Help
Breadcrumbs::for('help', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Help', route('help'));
});
