<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class items extends Model
{
    protected $guarded = [];

    public function stock()
    {
        return $this->hasOne('App\stocks', 'item_id','id');
    }
}
