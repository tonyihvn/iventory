<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->string('item_name');
            $table->string('state')->nullable();
            $table->string('facility')->nullable();
            $table->string('assigned_to')->nullable();
            $table->text('description')->nullable();
            $table->string('serial_no')->nullable();

            $table->text('ihvn_no')->nullable();
            $table->string('tag_no')->nullable();


            $table->string('category');
            $table->string('type')->nullable();

            $table->date('date_purchased')->nullable();

            $table->string('supplier')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('quantity')->nullable();
            $table->string('status')->nullable();

            $table->unsignedBigInteger('department_id');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');

            $table->unsignedBigInteger('unit_id');
            $table->foreign('unit_id')->references('id')->on('units')->onDelete('cascade');

            $table->unsignedBigInteger('facility_id');
            $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('cascade');

            $table->string('added_by')->nullable();
            $table->string('internal_location')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('inventories');
    }
}
