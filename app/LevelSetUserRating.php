<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $level_set_id
 * @property string $player_name
 * @property int|null $overall_grade
 * @property int|null $fun_grade
 * @property int|null $graphics_grade
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\LevelSet $levelSet
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereFunGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereGraphicsGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereLevelSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereOverallGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating wherePlayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetUserRating whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
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
