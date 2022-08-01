<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventories_id');
            $table->foreign('inventories_id')->references('id')->on('inventories')->onDelete('cascade');
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->string('from_user');            
            $table->string('to_user');           
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('user_id');            
            $table->date('date_moved')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
}
