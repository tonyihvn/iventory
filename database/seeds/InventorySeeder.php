<?php

use Illuminate\Database\Seeder;
use App\inventory;
class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(inventory::class, $count)->create();
    }
}
