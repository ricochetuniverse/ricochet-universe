<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Attributes\WithoutTimestamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'overall_rating',
    'overall_weight',
    'fun_rating',
    'fun_weight',
    'graphics_rating',
    'graphics_weight',
])]
#[Table(key: 'level_set_id')]
#[WithoutTimestamps]
class LevelSetLegacyRating extends Model
{
    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }
}
