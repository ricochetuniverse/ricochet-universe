<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LevelSetLegacyRating extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'overall_rating',
        'overall_weight',
        'fun_rating',
        'fun_weight',
        'graphics_rating',
        'graphics_weight',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'level_set_id';

    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }
}
