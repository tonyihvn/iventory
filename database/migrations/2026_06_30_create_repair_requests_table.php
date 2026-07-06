<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RepairRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('repair_requests')) {
            Schema::create('repair_requests', function (Blueprint $table) {
                $table->id();
                $table->string('state', 50);
                $table->unsignedBigInteger('facility_id')->nullable();
                $table->unsignedBigInteger('item_id');
                $table->string('serial_number', 100);
                $table->string('tag_number', 100);
                $table->text('description');
                $table->enum('status', ['Need Repairs', 'Not Operational', 'Lost', 'Irreparable'])->default('Need Repairs');
                $table->unsignedBigInteger('user_id');
                $table->text('admin_remarks')->nullable();
                $table->enum('admin_status', ['Pending', 'Approved', 'In Progress', 'Completed', 'Rejected'])->default('Pending');
                $table->timestamps();

                $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
                $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repair_requests');
    }
}
