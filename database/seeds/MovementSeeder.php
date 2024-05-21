<?php

use Illuminate\Database\Seeder;
use App\movement;
class MovementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 100;
        factory(movement::class, $count)->create();
    }
}
