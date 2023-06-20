<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDcdistributionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dcdistributions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('dctools')->onDelete('cascade');
            $table->double('quantity_sent',10,2)->default(0)->nullable();
            $table->date('date_sent')->nullable();

            $table->unsignedBigInteger('sent_from');
            $table->foreign('sent_from')->references('id')->on('facilities')->onDelete('cascade');
            $table->string('sentfrom_state',30)->nullable();
            $table->unsignedBigInteger('sent_to');
            $table->foreign('sent_to')->references('id')->on('facilities')->onDelete('cascade');
            $table->string('sentto_state',30)->nullable();
            $table->string('documents',80)->nullable();
            $table->string('remarks',80)->nullable();
            $table->string('sent_by')->nullable();
            $table->string('received_by')->nullable();

            $table->string('batchno',80)->nullable();
            $table->string('rdocuments',80)->nullable();
            $table->string('rremarks',80)->nullable();
            $table->double('quantity_received',10,2)->default(0)->nullable();

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
        Schema::dropIfExists('dcdistributions');
    }
}
