<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id',
        'inventory_id',
        'title',
        'content',
        'is_public'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the note
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the inventory item associated with the note (optional)
     */
    public function inventory()
    {
        return $this->belongsTo('App\inventory', 'inventory_id');
    }

    /**
     * Get all attachments for the note
     */
    public function attachments()
    {
        return $this->hasMany('App\NoteAttachment');
    }

    /**
     * Scope to get notes accessible by the current user
     */
    public function scopeAccessible($query)
    {
        $user = auth()->user();
        
        if ($user->role == 'Admin' || $user->role == 'Super') {
            return $query; // Admins can see all notes
        }
        
        // Users can only see their own notes
        return $query->where('user_id', $user->id);
    }
}
