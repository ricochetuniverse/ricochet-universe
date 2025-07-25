<?php

namespace App;

use Conner\Tagging\Taggable;
use DomainException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;

/**
 * A Ricochet level set
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
 * @property \Carbon\CarbonInterface|null $created_at
 * @property \Carbon\CarbonInterface|null $updated_at
 * @property string $alternate_download_url
 * @property string $downloaded_file_name
 * @property int $round_to_get_image_from
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\LevelSetDownloadLog> $downloadLogs
 * @property-read int|null $download_logs_count
 * @property array $tag_names
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Conner\Tagging\Model\Tag> $tags
 * @property-read \App\LevelSetLegacyRating|null $legacyRating
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\LevelRound> $levelRounds
 * @property-read int|null $level_rounds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Mod> $mods
 * @property-read int|null $mods_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Conner\Tagging\Model\Tagged> $tagged
 * @property-read int|null $tagged_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\LevelSetUserRating> $userRatings
 * @property-read int|null $user_ratings_count
 *
 * @method static \Database\Factories\LevelSetFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereAlternateDownloadUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereDownloadedFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereFunRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereFunRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereGameVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereGraphicsRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereGraphicsRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereLegacyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereOverallRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereOverallRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereRoundToGetImageFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereRounds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet withAllTags($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet withAnyTag($tagNames)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelSet withoutTags($tagNames)
 *
 * @mixin \Eloquent
 */
class LevelSet extends Model
{
    /** @use HasFactory<\Database\Factories\LevelSetFactory> */
    use HasFactory, Taggable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'featured' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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
        // Optimize version 2 of the image URLs as we get redirected at the end anyway
        if (str_starts_with($this->image_url, 'cache/')) {
            $disk = Storage::disk('round-images');
            $fileName = Str::after($this->image_url, 'cache/');

            $uri = Uri::of($disk->url($fileName));
        } else {
            // This leads to LevelSetImageController
            $uri = Uri::of(config('app.url').'/levels/'.$this->image_url);
        }

        return $uri->withQuery(['time' => $this->updated_at->unix()]);
    }

    public function levelRounds(): HasMany
    {
        return $this->hasMany(LevelRound::class);
    }

    public function mods(): BelongsToMany
    {
        return $this->belongsToMany(Mod::class);
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(LevelSetDownloadLog::class);
    }

    public function legacyRating(): HasOne
    {
        return $this->hasOne(LevelSetLegacyRating::class);
    }

    public function userRatings(): HasMany
    {
        return $this->hasMany(LevelSetUserRating::class);
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
