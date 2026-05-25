<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelSetUserRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'player_name',
        'overall_grade',
        'fun_grade',
        'graphics_grade',
    ];

    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }
}
