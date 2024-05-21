<?php

use Illuminate\Database\Seeder;
use App\category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(category::class, $count)->create();
    }
}
