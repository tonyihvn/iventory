<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NoteAttachment extends Model
{
    protected $fillable = [
        'note_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the note that owns the attachment
     */
    public function note()
    {
        return $this->belongsTo('App\Note');
    }

    /**
     * Get the file URL
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
