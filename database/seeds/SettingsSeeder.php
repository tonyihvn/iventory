<?php

use Illuminate\Database\Seeder;
use App\settings;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 1;
        factory(settings::class, $count)->create();
    }
}
