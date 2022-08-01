<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoryspecsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventoryspecs', function (Blueprint $table) {
            $table->id();                       
            $table->string('property');
            $table->string('value');
            $table->timestamps();
        });

        Schema::table('inventoryspecs', function (Blueprint $table) {
            $table->unsignedBigInteger('itemid')->nullable();
            $table->foreign('itemid')->references('id')->on('inventories')->onDelete('cascade');
        });
     
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventoryspecs');

        Schema::table('inventoryspecs', function(Blueprint $table) {
            $table->dropColumn('item_id');
         });
    }
}
