<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->string('quantity_requested')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('location',50)->nullable();
            $table->string('state',50)->nullable();
            $table->string('request_status',50)->nullable();
            $table->text('request_reason',150)->nullable();
            $table->string('comments',100)->nullable();
            $table->string('remarks',150)->nullable();
            $table->string('type',30)->nullable();
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
        Schema::dropIfExists('requests');
    }
}
