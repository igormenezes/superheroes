<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'images';
    public $timestamps = false;

    protected $fillable = ['super_heroes_id', 'file'];

    public function superheroes()
    {
        return $this->belongsTo('App\Models\SuperHeroes');
    }
}
