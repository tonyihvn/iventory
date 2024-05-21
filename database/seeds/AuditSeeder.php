<?php

use Illuminate\Database\Seeder;
use App\audit;
class AuditSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = 10;
        factory(audit::class, $count)->create();
    }
}
