<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddItReviewAndEstimatedCostToRepairRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->text('it_review')->nullable()->after('admin_remarks');
            $table->decimal('estimated_cost', 15, 2)->nullable()->after('it_review');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repair_requests', function (Blueprint $table) {
            $table->dropColumn(['it_review', 'estimated_cost']);
        });
    }
}
