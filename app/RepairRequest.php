<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RepairRequest extends Model
{
    protected $guarded = [];

    public function facility()
    {
        return $this->belongsTo('App\facilities', 'facility_id');
    }

    public function item()
    {
        return $this->belongsTo('App\items', 'item_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
