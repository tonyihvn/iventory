<?php

use Illuminate\Database\Seeder;
use App\unit;
class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(unit::class, $count)->create();
    }
}
