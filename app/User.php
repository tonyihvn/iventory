<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone_number', 'unit', 'department', 'facility', 'role'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function inventory()
    {
        return $this->hasMany('App\inventory', 'user_id');
    }

    public function user()
    {
        return $this->hasMany('App\user');
    }

    public function isAdmin(){
        if ($this->role==="Admin"){
            return true;
        }
        return false;
    }

    public function hasRole($role)
    {
        return User::where('role', $role)->first();
    
    }

    public function requests()
    {
        return $this->hasMany('App\requests', 'user_id');
    }

}
