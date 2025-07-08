<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property int $id
 * @property string $name
 * @property string $author
 * @property string $description
 * @property string $video_embed_source
 * @property string $download_link
 * @property string $trigger_codename
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\LevelSet> $levelSets
 * @property-read int|null $level_sets_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereDownloadLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereTriggerCodename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Mod whereVideoEmbedSource($value)
 *
 * @mixin \Eloquent
 */
class Mod extends Model
{
    public function levelSets(): BelongsToMany
    {
        return $this->belongsToMany(LevelSet::class);
    }
}
