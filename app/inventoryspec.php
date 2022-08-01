<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inventoryspec extends Model
{

    protected $guarded = [];
    
    public function inventory()
    {
        return $this->belongsTo('App\inventory','id','itemid');
    }
}
