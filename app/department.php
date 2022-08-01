<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class department extends Model
{
    protected $guarded = [];

    public function inventory()
    {
        return $this->hasMany('App\inventory','department_id');
    }
}
