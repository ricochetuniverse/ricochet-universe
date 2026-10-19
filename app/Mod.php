<?php

namespace App;

use Database\Factories\ModFactory;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[UseFactory(ModFactory::class)]
class Mod extends Model
{
    use HasFactory;

    public function levelSets(): BelongsToMany
    {
        return $this->belongsToMany(LevelSet::class);
    }
}
