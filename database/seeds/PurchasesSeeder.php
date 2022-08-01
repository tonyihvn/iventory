<?php

use Illuminate\Database\Seeder;
use App\purchases;
class PurchasesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 10;
        factory(purchases::class, $count)->create();
    }
}
