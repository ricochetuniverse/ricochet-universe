<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $level_set_id
 * @property string $ip_address
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property-read \App\LevelSet $levelSet
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog whereLevelSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSetDownloadLog whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LevelSetDownloadLog extends Model
{
    use Prunable;

    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }

    /**
     * Get the prunable model query.
     */
    public function prunable(): Builder
    {
        return static::where('created_at', '<=', Carbon::now()->subDays(5));
    }
}
