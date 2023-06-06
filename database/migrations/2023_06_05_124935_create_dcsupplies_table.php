<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDcsuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dcsupplies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('dctools')->onDelete('cascade');
            $table->double('quantity_supplied',10,2)->default(0)->nullable();
            $table->date('date_supplied')->nullable();
            $table->string('supplied_to',50)->nullable();
            $table->string('supplier',50)->nullable();
            $table->string('batchno',30)->nullable();
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
        Schema::dropIfExists('dcsupplies');
    }
}
