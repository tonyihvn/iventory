<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoteAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop if exists to handle failed migration attempts
        Schema::dropIfExists('note_attachments');
        
        Schema::create('note_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('note_id');
            $table->string('file_name');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type');
            $table->timestamps();

            // Foreign key
            $table->foreign('note_id')->references('id')->on('notes')->onDelete('cascade');

            // Indexes
            $table->index('note_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('note_attachments');
    }
}
