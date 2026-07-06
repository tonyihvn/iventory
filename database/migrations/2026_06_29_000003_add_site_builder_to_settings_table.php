<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSiteBuilderToSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settings', function (Blueprint $table) {
            // Theme and Layout
            $table->string('theme_mode')->default('light')->nullable(); // 'light' or 'dark'
            
            // Colors
            $table->string('primary_color')->default('#2196F3')->nullable(); // Primary brand color
            $table->string('secondary_color')->default('#FFC107')->nullable(); // Secondary accent color
            $table->string('background_color')->default('#FFFFFF')->nullable(); // Main background
            $table->string('sidebar_color')->default('#263238')->nullable(); // Sidebar background
            $table->string('text_color')->default('#333333')->nullable(); // Primary text color
            $table->string('text_light_color')->default('#777777')->nullable(); // Light text color
            
            // Typography
            $table->string('font_family')->default('Roboto')->nullable(); // Primary font
            $table->integer('heading_size')->default(32)->nullable(); // Heading font size
            $table->integer('body_size')->default(14)->nullable(); // Body font size
            $table->integer('small_size')->default(12)->nullable(); // Small font size
            
            // Dashboard Components
            $table->boolean('show_dashboard_stats')->default(1)->nullable(); // Show statistics cards
            $table->boolean('show_dashboard_charts')->default(1)->nullable(); // Show charts
            $table->boolean('show_recent_activities')->default(1)->nullable(); // Show activity log
            $table->boolean('show_quick_actions')->default(1)->nullable(); // Show quick action buttons
            $table->boolean('show_notifications')->default(1)->nullable(); // Show notification panel
            
            // Layout Configuration
            $table->string('dashboard_layout')->default('grid')->nullable(); // 'grid' or 'list'
            $table->integer('sidebar_width')->default(250)->nullable(); // Sidebar width in pixels
            $table->boolean('sticky_navbar')->default(1)->nullable(); // Keep navbar fixed at top
            $table->boolean('show_breadcrumbs')->default(1)->nullable(); // Show breadcrumb navigation
            
            // Effects and Animations
            $table->boolean('enable_animations')->default(1)->nullable(); // Enable UI animations
            $table->string('animation_speed')->default('normal')->nullable(); // 'slow', 'normal', 'fast'
            $table->boolean('enable_shadows')->default(1)->nullable(); // Enable box shadows
            
            // Advanced UI Options
            $table->string('card_style')->default('material')->nullable(); // Card design style
            $table->integer('border_radius')->default(4)->nullable(); // Corner rounding in pixels
            $table->string('table_style')->default('striped')->nullable(); // Table row styling
            $table->boolean('show_table_hover')->default(1)->nullable(); // Highlight on row hover
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
            $table->dropColumn([
                'theme_mode',
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
                'enable_shadows',
                'card_style',
                'border_radius',
                'table_style',
                'show_table_hover'
            ]);
        });
    }
}
