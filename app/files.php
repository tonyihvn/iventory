<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    protected $guarded = [];

    /**
     * Relationship with User
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Relationship with Facility
     */
    public function facility()
    {
        return $this->belongsTo('App\facilities', 'facility_id');
    }

    /**
     * Relationship with Item
     */
    public function item()
    {
        return $this->belongsTo('App\items', 'item_id');
    }
}
