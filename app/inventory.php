<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class inventory extends Model
{

    protected $guarded = [];
    
    public function inventoryspec()
    {
        return $this->hasMany('App\inventoryspec','itemid','id');
    }

    public function category()
    {
        return $this->belongsTo('App\category');
    }

    public function facilities()
    {
        return $this->belongsTo('App\facilities','facility_id');
    }

    public function department()
    {
        return $this->belongsTo('App\department','department_id');
    }

    public function unit()
    {
        return $this->belongsTo('App\unit','unit_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User','user_id');
    }

    public function movement()
    {
        return $this->hasMany('App\movement','inventories_id');
    }

}
