<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Uri;

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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereImageFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereLevelSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereNote1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereNote2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereNote3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereNote4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereNote5($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereRoundNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LevelRound whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LevelRound extends Model
{
    public function levelSet(): BelongsTo
    {
        return $this->belongsTo(LevelSet::class);
    }

    public function getImageUrl(): string
    {
        $original = Storage::disk('round-images')->url(rawurlencode($this->image_file_name));

        return Uri::of($original)
            ->withQuery(['time' => $this->updated_at->unix()]);
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
