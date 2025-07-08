<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $level_set_id
 * @property float $overall_rating
 * @property int $overall_weight
 * @property float $fun_rating
 * @property int $fun_weight
 * @property float $graphics_rating
 * @property int $graphics_weight
 * @property-read \App\LevelSet $levelSet
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereFunRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereFunWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereGraphicsRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereGraphicsWeight($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereLevelSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereOverallRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetLegacyRating whereOverallWeight($value)
 *
 * @mixin \Eloquent
 */
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
