<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BackgroundImagesToSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'login_bg_image')) {
                $table->string('login_bg_image')->nullable()->after('logo');
            }
            if (!Schema::hasColumn('settings', 'app_bg_image')) {
                $table->string('app_bg_image')->nullable()->after('login_bg_image');
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
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn(['login_bg_image', 'app_bg_image']);
        });
    }
}
