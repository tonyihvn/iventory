<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDcstocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dcstocks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('dctools')->onDelete('cascade');
            $table->double('quantity_remaining',10,2)->default(0)->nullable();
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
        Schema::dropIfExists('dcstocks');
    }
}
