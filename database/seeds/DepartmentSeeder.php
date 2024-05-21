<?php

use Illuminate\Database\Seeder;
use App\department;
class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(department::class, $count)->create();
    }
}
