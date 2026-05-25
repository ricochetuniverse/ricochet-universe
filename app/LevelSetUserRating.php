<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'player_name',
    'overall_grade',
    'fun_grade',
    'graphics_grade',
])]
class LevelSetUserRating extends Model
{
    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }
}
