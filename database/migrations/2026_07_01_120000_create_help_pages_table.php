<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateHelpPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_pages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('support_text')->nullable();
            $table->string('inventory_sop_file')->nullable();
            $table->string('dct_sop_file')->nullable();
            $table->timestamps();
        });

        // Insert default record
        DB::table('help_pages')->insert([
            'support_text' => 'For support or issues using the SI Inventory System send a mail to;<br>support@vmss.ng<br>Or use any of the HI Whatsapp Group to discuss it.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('help_pages');
    }
}
