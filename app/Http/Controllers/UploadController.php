<?php

namespace App\Http\Controllers;

use App\LevelSet;
use App\Rules\ValidTimestamp;
use App\Services\LevelSetUploadProcessor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Sentry\Laravel\Facade as Sentry;

class UploadController extends Controller
{
    public function index()
    {
        return view('upload');
    }

    public function store(Request $request)
    {
        // url and name parameters are validated inside the processor
        $this->validate($request, [
            'url' => ['required', 'string', 'unique:App\\LevelSet,alternate_download_url'],
            'name' => ['required', 'string'],
            'timestamp' => ['required', 'integer', new ValidTimestamp],
        ], [
            'url.unique' => 'The level set URL is already submitted.',
        ], [
            'url' => 'level set URL',
        ]);

        $processor = new LevelSetUploadProcessor;
        $processor->setUrl($request->input('url'));
        $processor->setName($request->input('name'));
        $processor->setDatePosted(Carbon::createFromTimestampUTC($request->input('timestamp')));

        $levelSet = $processor->process();

        self::postToDiscord($levelSet);

        return redirect($levelSet->getPermalink());
    }

    public static function postToDiscord(LevelSet $levelSet): void
    {
        $webhookUrl = config('ricochet.discord_upload_webhook');
        if (! $webhookUrl) {
            return;
        }

        try {
            $notify = Http::post($webhookUrl, [
                'content' => 'New level set uploaded',
                'embeds' => [
                    [
                        'author' => [
                            'name' => 'By '.$levelSet->author,
                            'url' => action('LevelController@index', ['author' => $levelSet->author]),
                        ],
                        'title' => $levelSet->name,
                        'url' => $levelSet->getPermalink(),
                        'description' => $levelSet->description,
                        'fields' => [
                            [
                                'name' => 'Number of rounds',
                                'value' => $levelSet->rounds,
                            ],
                        ],
                        'image' => [
                            'url' => $levelSet->getImageUrl(),
                        ],
                        'timestamp' => $levelSet->created_at->toIso8601String(),
                    ],
                ],
            ]);

            $notify->throw();
        } catch (\Exception $exception) {
            // Fail silently, do not crash just because Discord is inaccessible
            Sentry::captureException($exception);
        }
    }
}
