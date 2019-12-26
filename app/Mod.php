<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Mod
 *
 * @property int $id
 * @property string $name
 * @property string $author
 * @property string $description
 * @property string $video_embed_source
 * @property string $download_link
 * @property string $trigger_codename
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LevelSet[] $levelSets
 * @property-read int|null $level_sets_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereDownloadLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereTriggerCodename($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Mod whereVideoEmbedSource($value)
 * @mixin \Eloquent
 */
class Mod extends Model
{
    public function levelSets()
    {
        return $this->belongsToMany(LevelSet::class);
    }
}
