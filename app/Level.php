<?php

namespace App;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Level
 *
 * @property int $id
 * @property int $legacy_id
 * @property string $name
 * @property int $rounds
 * @property string $author
 * @property bool $featured
 * @property string $game_version
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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereDownloads($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereFeatured($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereFunRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereFunRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereGameVersion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereGraphicsRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereGraphicsRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereLegacyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereOverallRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereOverallRatingCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereRating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereRounds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Level whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Level extends Model
{
    use Taggable;

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
}
