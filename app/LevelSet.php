<?php

namespace App;

use App\Services\LevelSetRatingsCalculator;
use Conner\Tagging\Taggable;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
 * @property bool $prerelease
 * @property string $similar_levels
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
 * @method static Builder<static>|LevelSet newModelQuery()
 * @method static Builder<static>|LevelSet newQuery()
 * @method static Builder<static>|LevelSet published()
 * @method static Builder<static>|LevelSet query()
 * @method static Builder<static>|LevelSet whereAlternateDownloadUrl($value)
 * @method static Builder<static>|LevelSet whereAuthor($value)
 * @method static Builder<static>|LevelSet whereCreatedAt($value)
 * @method static Builder<static>|LevelSet whereDescription($value)
 * @method static Builder<static>|LevelSet whereDownloadedFileName($value)
 * @method static Builder<static>|LevelSet whereDownloads($value)
 * @method static Builder<static>|LevelSet whereFeatured($value)
 * @method static Builder<static>|LevelSet whereFunRating($value)
 * @method static Builder<static>|LevelSet whereFunRatingCount($value)
 * @method static Builder<static>|LevelSet whereGameVersion($value)
 * @method static Builder<static>|LevelSet whereGraphicsRating($value)
 * @method static Builder<static>|LevelSet whereGraphicsRatingCount($value)
 * @method static Builder<static>|LevelSet whereId($value)
 * @method static Builder<static>|LevelSet whereImageUrl($value)
 * @method static Builder<static>|LevelSet whereLegacyId($value)
 * @method static Builder<static>|LevelSet whereName($value)
 * @method static Builder<static>|LevelSet whereOverallRating($value)
 * @method static Builder<static>|LevelSet whereOverallRatingCount($value)
 * @method static Builder<static>|LevelSet wherePrerelease($value)
 * @method static Builder<static>|LevelSet whereRating($value)
 * @method static Builder<static>|LevelSet whereRoundToGetImageFrom($value)
 * @method static Builder<static>|LevelSet whereRounds($value)
 * @method static Builder<static>|LevelSet whereSimilarLevels($value)
 * @method static Builder<static>|LevelSet whereUpdatedAt($value)
 * @method static Builder<static>|LevelSet withAllTags($tagNames)
 * @method static Builder<static>|LevelSet withAnyTag($tagNames)
 * @method static Builder<static>|LevelSet withoutTags($tagNames)
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
        'prerelease' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'legacy_id',
    ];

    private const int MIN_RATING_COUNT = 5;

    private const int MAX_SIMILAR_LEVELS_LIMIT = 10;

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

    public function getDownloadUrl(): string
    {
        return action('API\LevelDownloadController@download', ['File' => 'downloads/raw/'.$this->name.$this->getFileExtension()]);
    }

    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query->where('prerelease', false);
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

    public function hasAnyPublicRatings(): bool
    {
        return $this->overall_rating || $this->fun_rating || $this->graphics_rating;
    }

    public function recalculateRatings(): void
    {
        $result = LevelSetRatingsCalculator::calculate($this);

        if ($result['overall']['count'] >= self::MIN_RATING_COUNT) {
            $this->overall_rating = $result['overall']['grade'];
            $this->overall_rating_count = $result['overall']['count'];
        } else {
            $this->overall_rating = 0;
            $this->overall_rating_count = 0;
        }

        if ($result['fun']['count'] >= self::MIN_RATING_COUNT) {
            $this->fun_rating = $result['fun']['grade'];
            $this->fun_rating_count = $result['fun']['count'];
        } else {
            $this->fun_rating = 0;
            $this->fun_rating_count = 0;
        }

        if ($result['graphics']['count'] >= self::MIN_RATING_COUNT) {
            $this->graphics_rating = $result['graphics']['grade'];
            $this->graphics_rating_count = $result['graphics']['count'];
        } else {
            $this->graphics_rating = 0;
            $this->graphics_rating_count = 0;
        }

        $this->save();
    }

    public function getSimilarLevels(): array
    {
        // I don't know the exact algorithm used by Reflexive
        // So I'm just guessing + some common sense
        $newLevelSets = self::select(['legacy_id'])
            ->whereNot('id', $this->id)
            ->where('author', $this->author)
            ->where('created_at', '>=', $this->created_at)
            ->published()
            ->orderBy('created_at')
            ->orderBy('id')
            ->limit(self::MAX_SIMILAR_LEVELS_LIMIT)
            ->get()
            ->map(function (LevelSet $levelSet) {
                return $levelSet->legacy_id;
            });

        return $newLevelSets->toArray();
    }
}
