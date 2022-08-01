<?php

use Illuminate\Database\Seeder;
use App\facilities;
class FacilitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(facilities::class, $count)->create();
    }
}
