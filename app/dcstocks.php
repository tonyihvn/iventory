<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dcstocks extends Model
{
    protected $guarded = [];
  
  public function Item()
    {
        return $this->hasOne('App\dctools', 'id','item_id');
    }
}
