<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class supplies extends Model
{
    protected $guarded = [];

    public function Item()
    {
        return $this->hasOne('App\items', 'id','item_id');
    }
}
