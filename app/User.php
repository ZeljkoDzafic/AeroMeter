<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function stations() {
        return $this->hasMany(\App\Station::class);
    }

    public function isAdmin() {
        return (bool)$this->admin == true;
    }

    public static function boot()
    {
        parent::boot();

        User::deleting(function($user)
        {
            foreach ($user->stations as $station) {
                $station->delete();
            }
        });
    }
}
