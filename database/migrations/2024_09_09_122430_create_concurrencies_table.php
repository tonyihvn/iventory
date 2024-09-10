<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConcurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('concurrencies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('state', 50); // Required field
            $table->string('location', 200)->nullable();
            $table->string('model', 100)->nullable();
            $table->string('serial_number', 50)->nullable();
            $table->string('tag_number', 50)->nullable();
            $table->string('user', 100)->nullable();
            $table->date('date_of_purchase')->nullable();
            $table->string('grant', 50)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('batch', 50)->nullable();
            $table->string('condition', 80)->nullable(); // Overridden with unique name
            $table->string('date_delivered',50)->nullable();
            $table->string('received_by', 100)->nullable();
            $table->text('comments')->nullable(); // For longer text
            $table->text('other_info')->nullable();

            $table->timestamps(); // Adds created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('concurrencies');
    }
}
