<?php

use Illuminate\Database\Seeder;
use App\inventoryspec;
class InventoryspecSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 10;
        factory(inventoryspec::class, $count)->create();
    }
}
