<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $table = 'tags';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    public function stations() {
        return $this->belongsToMany(\App\Station::class, 'tags_stations', 'station_id', 'tag_id');
    }

    public static function boot()
    {
        parent::boot();

        Tag::deleting(function($tag)
        {
            $tag->stations()->detach();
        });
    }
}
