<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dctools extends Model
{
    protected $guarded = [];

    public function stock()
    {
        return $this->hasOne('App\dcstocks','item_id');
    }
}
