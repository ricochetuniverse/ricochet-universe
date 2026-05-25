<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
