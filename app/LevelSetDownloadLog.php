<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $level_set_id
 * @property string $ip_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }
}
