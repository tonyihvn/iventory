<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dctoolutilizations extends Model
{
    protected $guarded = [];

    public function facilityName()
    {
        return $this->hasOne('App\facilities','id','facility_id');
    }

    public function toolName()
    {
        return $this->hasOne('App\dctools','id','item_id');
    }
}
