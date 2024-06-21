<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class multifacilities extends Model
{
    protected $guarded = [];

    public function facilityName()
    {
        return $this->hasOne('App\facilities', 'id','facility_id');
    }

}
