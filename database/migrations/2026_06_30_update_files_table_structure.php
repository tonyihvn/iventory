<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FilesTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function (Blueprint $table) {
            if (!Schema::hasColumn('files', 'category')) {
                $table->string('category')->nullable()->after('id');
            }
            if (!Schema::hasColumn('files', 'details')) {
                $table->text('details')->nullable()->after('category');
            }
            if (!Schema::hasColumn('files', 'state')) {
                $table->string('state', 50)->nullable()->after('details');
            }
            if (!Schema::hasColumn('files', 'facility_id')) {
                $table->unsignedBigInteger('facility_id')->nullable()->after('state');
            }
            if (!Schema::hasColumn('files', 'item_id')) {
                $table->unsignedBigInteger('item_id')->nullable()->after('facility_id');
            }
            if (!Schema::hasColumn('files', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('item_id');
            }
        });

        Schema::table('files', function (Blueprint $table) {
            try {
                $table->foreign('facility_id')->references('id')->on('facilities')->onDelete('set null');
                $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function (Blueprint $table) {
            try {
                $table->dropForeign(['facility_id']);
                $table->dropForeign(['item_id']);
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // Foreign key might not exist
            }
            $table->dropColumn(['category', 'details', 'state', 'facility_id', 'item_id', 'user_id']);
        });
    }
}
