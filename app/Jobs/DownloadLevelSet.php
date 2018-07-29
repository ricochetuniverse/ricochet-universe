<?php

namespace App\Jobs;

use App\LevelSet;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DownloadLevelSet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var LevelSet
     */
    protected $levelSet;

    /**
     * Create a new job instance.
     *
     * @param LevelSet $levelSet
     */
    public function __construct(LevelSet $levelSet)
    {
        $this->levelSet = $levelSet;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle()
    {
        if (!$this->levelSet->alternate_download_url) {
            return;
        }

        // Level rounds already processed?
        if ($this->levelSet->levelRounds->count()) {
            return;
        }

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $this->levelSet->alternate_download_url);

        $filename = $response->getHeader('Content-Disposition')[0];
        $filename = str_after($filename, 'filename=');
        $filename = str_replace(['"', '\''], '', $filename);

        Storage::disk('levels')->put($filename, $response->getBody());

        $this->levelSet->downloaded_file_name = $filename;
        $this->levelSet->save();
    }
}
