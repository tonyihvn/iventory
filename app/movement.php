<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class movement extends Model
{
    protected $guarded = [];

    public function inventory()
    {
        return $this->belongsTo('App\inventory','inventories_id','id');
    }

    public function user_id()
    {
        return $this->belongsTo('App\user','id','user_id');
    }
    
}
