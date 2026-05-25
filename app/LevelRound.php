<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Uri;

/**
 * A round inside a Ricochet level set
 */
class LevelRound extends Model
{
    /** @use HasFactory<\Database\Factories\LevelRoundFactory> */
    use HasFactory;

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

    public function toRoundInfoJson(): string
    {
        return json_encode(array_filter([
            'name' => $this->name,
            'author' => strlen($this->author) > 0 ? $this->author : null,
            'note1' => strlen($this->note1) > 0 ? $this->note1 : null,
            'note2' => strlen($this->note2) > 0 ? $this->note2 : null,
            'note3' => strlen($this->note3) > 0 ? $this->note3 : null,
            'note4' => strlen($this->note4) > 0 ? $this->note4 : null,
            'note5' => strlen($this->note5) > 0 ? $this->note5 : null,
            'source' => strlen($this->source) > 0 ? $this->source : null,
            'imageUrl' => $this->image_file_name ? $this->getImageUrl() : null,
        ]));
    }
}
