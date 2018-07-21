<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LevelTag extends Model
{
    public function level()
    {
        return $this->belongsToMany(Level::class);
    }
}
