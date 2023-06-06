<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDctoolutilizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dctoolutilizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('dctools')->onDelete('cascade');
            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');
            $table->date('dated_from')->nullable();
            $table->date('dated_to')->nullable();
            $table->double('quantity_used',10,2)->default(0)->nullable();
            $table->double('benchmark',10,2)->default(0)->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('dctoolutilizations');
    }
}
