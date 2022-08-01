<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class unit extends Model
{
    protected $guarded = [];

    public function inventory()
    {
        return $this->hasMany('App\inventory','unit_id');
    }
}
