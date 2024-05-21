<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone_number')->nullable();
            $table->string('unit')->nullable();
            $table->string('department')->nullable();
            $table->string('facility');
            $table->string('role')->nullable();
            $table->string('state')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        DB::table('users')->insert(
            array(
                'name' => 'iVentory Admin',
                'email' => 'admin@ihvnhi.org.ng',
                'password' => '$2y$10$kbHiVY5Tt31WQj/lTCTjoOQUGHFc7..6KxU8p8Vu0koVZsk3vK77.',
                'role' => 'Super',
                'facility' => 'Central',
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
        Schema::dropIfExists('users');
    }
}
