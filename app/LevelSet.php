<?php

namespace App;

use App\Services\LevelSetRatingsCalculator;
use Conner\Tagging\Taggable;
use Database\Factories\LevelSetFactory;
use DomainException;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Attributes\UseFactory;
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
 */
#[Fillable(['legacy_id'])]
#[UseFactory(LevelSetFactory::class)]
class LevelSet extends Model
{
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

    /** @return HasMany<LevelRound, $this> */
    public function levelRounds(): HasMany
    {
        return $this->hasMany(LevelRound::class);
    }

    public function mods(): BelongsToMany
    {
        return $this->belongsToMany(Mod::class)->orderBy('name');
    }

    public function downloadLogs(): HasMany
    {
        return $this->hasMany(LevelSetDownloadLog::class);
    }

    /** @return HasOne<LevelSetLegacyRating, $this> */
    public function legacyRating(): HasOne
    {
        return $this->hasOne(LevelSetLegacyRating::class);
    }

    /** @return HasMany<LevelSetUserRating, $this> */
    public function userRatings(): HasMany
    {
        return $this->hasMany(LevelSetUserRating::class);
    }

    /** @return BelongsToMany<LevelSetTag, $this> */
    public function visibleTagged(): BelongsToMany
    {
        return $this->belongsToMany(LevelSetTag::class, 'level_set_visible_tagged', 'level_set_id', 'tag_id')
            ->withPivot('position')
            ->withTimestamps()
            ->orderByPivot('position');
    }

    /** @return BelongsToMany<LevelSetTag, $this> */
    public function legacyTagged(): BelongsToMany
    {
        return $this->belongsToMany(LevelSetTag::class, 'level_set_legacy_tagged', 'level_set_id', 'tag_id')
            ->withPivot('position')
            ->orderByPivot('position');
    }

    public function userTagged(): BelongsToMany
    {
        return $this->belongsToMany(LevelSetTag::class, 'level_set_user_tagged', 'level_set_id', 'tag_id')
            ->withPivot('player_name')
            ->withTimestamps();
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
