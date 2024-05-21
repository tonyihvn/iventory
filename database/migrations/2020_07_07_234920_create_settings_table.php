<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('organization_name')->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('copyright')->nullable();
            $table->timestamps();
        });

        DB::table('settings')->insert(
            array(
                'organization_name' => 'IHVN SI',
                'description' => 'Public Health Institution',
                'logo' => 'ihvnlogo.png',
                'address' => 'Jabi, Abuja',
                'phone_number' => '234803000000',
                'copyright' => 'IHVN SI'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
