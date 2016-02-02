<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $table = 'stations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'unique_id', 'lat', 'lng', 'user_id'];

    protected $visible = ['id', 'name', 'description', 'lat', 'lng'];

    public function user() {
        return $this->belongsTo(\App\User::class, 'user_id');
    }

    public function tags() {
        return $this->belongsToMany(\App\Tag::class, 'tags_stations', 'tag_id', 'station_id');
    }

    public function aerometrics() {
        return $this->hasMany(\App\Aerometric::class, 'station_id');
    }

    public static function boot()
    {
        parent::boot();

        Station::deleting(function($station)
        {
            foreach ($station->aerometrics as $aerometric) {
                $aerometric->delete();
            }
            $station->tags()->detach();
        });
    }

}
