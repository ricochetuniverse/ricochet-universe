<?php

namespace App;

use Conner\Tagging\Taggable;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Url\Url;

/**
 * App\LevelSet
 *
 * @property int $id
 * @property int $legacy_id
 * @property string $name
 * @property int $rounds
 * @property string $author
 * @property bool $featured
 * @property int $game_version
 * @property string $image_url
 * @property float $rating
 * @property int $downloads
 * @property string $description
 * @property float $overall_rating
 * @property int $overall_rating_count
 * @property float $fun_rating
 * @property int $fun_rating_count
 * @property float $graphics_rating
 * @property int $graphics_rating_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $alternate_download_url
 * @property string $downloaded_file_name
 * @property int $round_to_get_image_from
 * @property mixed $tag_names
 * @property-read \Illuminate\Database\Eloquent\Collection|\Tagged[] $tags
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LevelRound[] $levelRounds
 * @property-read int|null $level_rounds_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Mod[] $mods
 * @property-read int|null $mods_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Conner\Tagging\Model\Tagged[] $tagged
 * @property-read int|null $tagged_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereAlternateDownloadUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereDownloadedFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereFunRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereFunRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereGameVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereGraphicsRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereGraphicsRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereLegacyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereOverallRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereOverallRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereRoundToGetImageFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereRounds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet withAllTags($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet withAnyTag($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelSet withoutTags($tagNames)
 * @method static \Database\Factories\LevelSetFactory factory($count = null, $state = [])
 *
 * @mixin \Eloquent
 */
class LevelSet extends Model
{
    use HasFactory, Taggable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'featured' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'legacy_id',
    ];

    public function getPermalink(): string
    {
        return action('LevelController@show', ['levelsetname' => $this->name]);
    }

    public function getImageUrl(): string
    {
        return Url::fromString(config('app.url').'/levels/'.$this->image_url)
            ->withQueryParameter('time', $this->updated_at->unix());
    }

    public function levelRounds()
    {
        return $this->hasMany(LevelRound::class);
    }

    public function mods()
    {
        return $this->belongsToMany(Mod::class);
    }

    public function isDesignedForLostWorlds(): bool
    {
        return $this->game_version === 2;
    }

    public function isDesignedForInfinity(): bool
    {
        return $this->game_version === 3;
    }

    public function getFileExtension(): string
    {
        if ($this->isDesignedForInfinity()) {
            return '.RicochetI';
        } elseif ($this->isDesignedForLostWorlds()) {
            return '.RicochetLW';
        }

        throw new DomainException('Unknown game version');
    }
}
