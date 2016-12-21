<?php

namespace App;

use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers; // We imported this
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Sluggable;
    use SluggableScopeHelpers; // Here we import the trait


    /**
     * Sluggable configuration.
     *
     * @var array
     */
    public function sluggable() {
        return [
            'slug' => [
                'source'         => 'title',
                'separator'      => '-',
                'includeTrashed' => true,
            ]
        ];
    }


    //Mass Assigment

    protected  $fillable = [
        'category_id',
        'photo_id',
        'title',
        'body'


    ];

    protected $primaryKey = 'id';

    public  function user()
    {
        return $this->belongsTo('App\User');
    }

    public  function photo(){
        return $this->belongsTo('App\Photo');
    }

    public  function  category()
    {
        return $this->belongsTo('App\Category');
    }

    public function comments()
    {
        //return $this->hasMany('App\Post');
        return $this->hasMany('App\Comment');
    }


}