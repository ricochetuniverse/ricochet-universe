<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Spatie\Url\Url;

/**
 * App\LevelRound
 *
 * @property int $id
 * @property int $level_set_id
 * @property string $name
 * @property string $author
 * @property string $note1
 * @property string $note2
 * @property string $note3
 * @property string $note4
 * @property string $note5
 * @property string $source
 * @property string $image_file_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $round_number
 * @property-read \App\LevelSet $levelSet
 *
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound query()
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereImageFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereLevelSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereNote1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereNote2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereNote3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereNote4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereNote5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereRoundNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LevelRound whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LevelRound extends Model
{
    public function levelSet()
    {
        return $this->belongsTo(LevelSet::class);
    }

    public function getImageUrl(): string
    {
        $original = Storage::disk('round-images')->url(rawurlencode($this->image_file_name));

        return Url::fromString($original)
            ->withQueryParameter('time', $this->updated_at->unix());
    }

    public function shouldShowViewNotesButton(): bool
    {
        if (strlen($this->note2) > 0 || strlen($this->note3) > 0 || strlen($this->note4) > 0 || strlen($this->note5) > 0) {
            return true;
        }

        $common = [
            'http://www.ricochetinfinity.com',
            'http://www.ricochetinfinity.com/',
            'ricochetinfinity.com',
            'www.ricochetinfinity.com',
        ];

        return strlen($this->note1) > 0 && ! in_array(strtolower($this->note1), $common);
    }
}
