<?php

use Illuminate\Database\Seeder;
use App\supplier;
class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(supplier::class, $count)->create();
    }
}
