<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaterializeColorsToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Add missing color columns
            if (!Schema::hasColumn('settings', 'materialize_primary_color')) {
                $table->string('materialize_primary_color')->default('#26a69a')->nullable();
            }
            if (!Schema::hasColumn('settings', 'primary_color')) {
                $table->string('primary_color')->default('#2196F3')->nullable();
            }
            if (!Schema::hasColumn('settings', 'secondary_color')) {
                $table->string('secondary_color')->default('#FFC107')->nullable();
            }
            if (!Schema::hasColumn('settings', 'background_color')) {
                $table->string('background_color')->default('#FFFFFF')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sidebar_color')) {
                $table->string('sidebar_color')->default('#263238')->nullable();
            }
            if (!Schema::hasColumn('settings', 'text_color')) {
                $table->string('text_color')->default('#333333')->nullable();
            }
            if (!Schema::hasColumn('settings', 'text_light_color')) {
                $table->string('text_light_color')->default('#777777')->nullable();
            }
            
            // Add missing typography columns
            if (!Schema::hasColumn('settings', 'font_family')) {
                $table->string('font_family')->default('Roboto')->nullable();
            }
            if (!Schema::hasColumn('settings', 'heading_size')) {
                $table->integer('heading_size')->default(32)->nullable();
            }
            if (!Schema::hasColumn('settings', 'body_size')) {
                $table->integer('body_size')->default(14)->nullable();
            }
            if (!Schema::hasColumn('settings', 'small_size')) {
                $table->integer('small_size')->default(12)->nullable();
            }
            
            // Add missing dashboard component columns
            if (!Schema::hasColumn('settings', 'show_dashboard_stats')) {
                $table->boolean('show_dashboard_stats')->default(1)->nullable();
            }
            if (!Schema::hasColumn('settings', 'show_dashboard_charts')) {
                $table->boolean('show_dashboard_charts')->default(1)->nullable();
            }
            if (!Schema::hasColumn('settings', 'show_recent_activities')) {
                $table->boolean('show_recent_activities')->default(1)->nullable();
            }
            if (!Schema::hasColumn('settings', 'show_quick_actions')) {
                $table->boolean('show_quick_actions')->default(1)->nullable();
            }
            if (!Schema::hasColumn('settings', 'show_notifications')) {
                $table->boolean('show_notifications')->default(1)->nullable();
            }
            
            // Add missing layout columns
            if (!Schema::hasColumn('settings', 'dashboard_layout')) {
                $table->string('dashboard_layout')->default('grid')->nullable();
            }
            if (!Schema::hasColumn('settings', 'sidebar_width')) {
                $table->integer('sidebar_width')->default(250)->nullable();
            }
            if (!Schema::hasColumn('settings', 'sticky_navbar')) {
                $table->boolean('sticky_navbar')->default(0)->nullable();
            }
            if (!Schema::hasColumn('settings', 'show_breadcrumbs')) {
                $table->boolean('show_breadcrumbs')->default(1)->nullable();
            }
            
            // Add missing animation columns
            if (!Schema::hasColumn('settings', 'enable_animations')) {
                $table->boolean('enable_animations')->default(1)->nullable();
            }
            if (!Schema::hasColumn('settings', 'animation_speed')) {
                $table->string('animation_speed')->default('normal')->nullable();
            }
            
            // Add missing theme column
            if (!Schema::hasColumn('settings', 'theme_mode')) {
                $table->string('theme_mode')->default('light')->nullable();
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
                'materialize_primary_color',
                'primary_color',
                'secondary_color',
                'background_color',
                'sidebar_color',
                'text_color',
                'text_light_color',
                'font_family',
                'heading_size',
                'body_size',
                'small_size',
                'show_dashboard_stats',
                'show_dashboard_charts',
                'show_recent_activities',
                'show_quick_actions',
                'show_notifications',
                'dashboard_layout',
                'sidebar_width',
                'sticky_navbar',
                'show_breadcrumbs',
                'enable_animations',
                'animation_speed',
                'theme_mode'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('settings', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
