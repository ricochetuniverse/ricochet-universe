<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Mod extends Model
{
    public function levelSets(): BelongsToMany
    {
        return $this->belongsToMany(LevelSet::class);
    }
}
