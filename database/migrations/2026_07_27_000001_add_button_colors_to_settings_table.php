<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddButtonColorsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Add button color columns
            if (!Schema::hasColumn('settings', 'button_primary_color')) {
                $table->string('button_primary_color')->default('#26a69a')->nullable();
            }
            if (!Schema::hasColumn('settings', 'button_primary_text_color')) {
                $table->string('button_primary_text_color')->default('#ffffff')->nullable();
            }
            if (!Schema::hasColumn('settings', 'button_secondary_color')) {
                $table->string('button_secondary_color')->default('#7e57c2')->nullable();
            }
            if (!Schema::hasColumn('settings', 'button_secondary_text_color')) {
                $table->string('button_secondary_text_color')->default('#ffffff')->nullable();
            }
            if (!Schema::hasColumn('settings', 'button_danger_color')) {
                $table->string('button_danger_color')->default('#f44336')->nullable();
            }
            if (!Schema::hasColumn('settings', 'button_danger_text_color')) {
                $table->string('button_danger_text_color')->default('#ffffff')->nullable();
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
            $columns = [
                'button_primary_color',
                'button_primary_text_color',
                'button_secondary_color',
                'button_secondary_text_color',
                'button_danger_color',
                'button_danger_text_color'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
