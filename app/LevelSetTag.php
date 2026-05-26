<?php

declare(strict_types=1);

namespace App;

use Database\Factories\LevelSetTagFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable(['name'])]
#[UseFactory(LevelSetTagFactory::class)]
class LevelSetTag extends Model
{
    use HasFactory;

    /** @return BelongsToMany<LevelSet, $this> */
    public function levelSetsVisibleTagged(): BelongsToMany
    {
        return $this->belongsToMany(LevelSet::class, 'level_set_visible_tagged', 'tag_id', 'level_set_id')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }
}
