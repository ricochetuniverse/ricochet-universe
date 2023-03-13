<?php

namespace App\Jobs;

use App\LevelRound;
use App\LevelSet;
use App\Services\LevelSetDecompressService;
use App\Services\LevelSetParser\Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ParseLevelSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected LevelSet $levelSet;

    /**
     * Create a new job instance.
     */
    public function __construct(LevelSet $levelSet)
    {
        $this->levelSet = $levelSet;
    }

    /**
     * Execute the job.
     *
     *
     * @throws \Exception
     */
    public function handle(): void
    {
        // Can't do anything if the level set file isn't available
        if (! $this->levelSet->downloaded_file_name) {
            return;
        }

        $disk = Storage::disk('levels');

        if (! $disk->exists($this->levelSet->downloaded_file_name)) {
            return;
        }

        // Level rounds already processed?
        if ($this->levelSet->levelRounds->count()) {
            return;
        }

        $file = $disk->path($this->levelSet->downloaded_file_name);

        $decompressor = new LevelSetDecompressService;
        $levelSetData = $decompressor->decompress($file);

        $parser = new Parser;
        $result = $parser->parse($levelSetData);

//        if ($results['levelSet']['author'] !== $this->levelSet->author) {
//            throw new \Exception("Level set author stored on the database is not the same as the downloaded file\n\nDatabase:  ".$this->levelSet->author."\nLevel set: ".$results['levelSet']['author']);
//        }

//        if ($results['levelSet']['description'] !== $this->levelSet->description) {
//            throw new \Exception("Level set description stored on the database is not the same as the downloaded file\n\nDatabase:  ".$this->levelSet->description."\nLevel set: ".$results['levelSet']['description']);
//        }

        $count = 0;
        $rounds = [];
        foreach ($result->getRounds() as $round) {
            $count += 1;

            $imageFileName = '';
            if ($round->thumbnail !== '') {
                $imageFileName = $this->levelSet->name.'/'.$count.'.jpg';
            }

            $roundToSave = new LevelRound;
            $roundToSave->name = $round->name;
            $roundToSave->author = $round->author;
            $roundToSave->note1 = $round->notes[0];
            $roundToSave->note2 = $round->notes[1];
            $roundToSave->note3 = $round->notes[2];
            $roundToSave->note4 = $round->notes[3];
            $roundToSave->note5 = $round->notes[4];
            $roundToSave->source = $round->source;
            $roundToSave->image_file_name = $imageFileName;
            $roundToSave->round_number = $count;

            if ($imageFileName) {
                Storage::disk('round-images')->put($imageFileName, $round->thumbnail);
            }

            $rounds[] = $roundToSave;
        }

        $this->levelSet->round_to_get_image_from = $result->roundToGetImageFrom;
        $this->levelSet->levelRounds()->saveMany($rounds);
        $this->levelSet->save();
    }
}
