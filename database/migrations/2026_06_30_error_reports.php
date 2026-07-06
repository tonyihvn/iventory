<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Reports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('error_reports')) {
            Schema::create('error_reports', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('user_name')->nullable();
                $table->string('user_email')->nullable();
                $table->longText('user_description');
                $table->longText('error_message')->nullable();
                $table->string('error_page')->nullable();
                $table->string('error_file')->nullable();
                $table->string('error_line')->nullable();
                $table->string('screenshot_path')->nullable();
                $table->text('user_agent')->nullable();
                $table->string('ip_address')->nullable();
                $table->string('status')->default('new');
                $table->text('admin_notes')->nullable();
                $table->unsignedBigInteger('reviewed_by')->nullable();
                $table->timestamps();

                $table->index('user_id');
                $table->index('status');
                $table->index('created_at');
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
        Schema::dropIfExists('error_reports');
    }
}
