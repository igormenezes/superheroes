<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuperHeroes extends Model
{
    protected $table = 'superheroes';
    public $timestamps = false;

    protected $fillable = ['nickname', 'real_name', 'origin_description', 'superpowers', 'catch_phrase'];

    public function images()
    {
        return $this->hasMany('App\Models\Images');
    }
}
