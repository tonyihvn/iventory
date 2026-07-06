<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OptimizeInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            // Add indexes on frequently queried columns
            $table->index('facility_id');
            $table->index('user_id');
            $table->index('state');
            $table->index('item_name');
            $table->index('status');
            $table->index('category');
            $table->index(['facility_id', 'state']); // Composite index for common filters
            $table->index(['state', 'user_id']); // Composite index for user-state queries
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropIndex(['facility_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['state']);
            $table->dropIndex(['item_name']);
            $table->dropIndex(['status']);
            $table->dropIndex(['category']);
            $table->dropIndex(['facility_id', 'state']);
            $table->dropIndex(['state', 'user_id']);
        });
    }
}
