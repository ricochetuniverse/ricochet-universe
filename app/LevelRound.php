<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\LevelSet $levelSet
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereImageFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereLevelSetId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereNote1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereNote2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereNote3($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereNote4($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereNote5($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereSource($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\LevelRound whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class LevelRound extends Model
{
    public function levelSet()
    {
        return $this->belongsTo(LevelSet::class);
    }
}
