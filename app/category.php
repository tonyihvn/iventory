<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class category extends Model
{
    protected $guarded = [];

    public function inventory()
    {
        return $this->hasMany('App\inventory');
    }
}
