<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class dcdistributions extends Model
{
    protected $guarded = [];

    public function dcTool()
    {
        return $this->hasOne('App\dctools','id','item_id');
    }

    public function sentFrom()
    {
        return $this->hasOne('App\facilities','id','sent_from');
    }

    public function sentTo()
    {
        return $this->hasOne('App\facilities','id','sent_to');
    }

    public function facilityName()
    {
        return $this->hasOne('App\facilities','id','sent_to');
    }
}
