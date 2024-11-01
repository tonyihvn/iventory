<?php

// routes/breadcrumbs.php

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

// Home > Inventory
Breadcrumbs::for('inventory', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Inventories', route('inventory'));
});

// Home > Inventories > Add New Item
Breadcrumbs::for('add_item', function (BreadcrumbTrail $trail) {
    $trail->parent('inventory');
    $trail->push('Add New Item', route('add_item'));
});
