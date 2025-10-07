<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Interactions;

use App\LevelSet;
use App\Services\DiscordApi\InteractionResponse;
use App\Services\DiscordApi\UserFacingInteractionException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Uri;

class LevelSetInfoInteraction
{
    public static function handle(array $json): JsonResponse
    {
        $message = array_first($json['data']['resolved']['messages']);
        $attachment = self::getAttachment($message);

        defer(static function () use ($json, $message, $attachment) {
            try {
                $levelSetName = self::getNameFromAttachment($attachment);
                $levelSets = self::findLevelSets($attachment);
                if ($levelSets->isEmpty()) {
                    throw new UserFacingInteractionException('No level sets found matching “'.$levelSetName.'”');
                }

                $response = [
                    'content' => $levelSets->count() > 1 ? 'Found '.$levelSets->count().' level sets named “'.$levelSetName.'”' : '',
                    'embeds' => $levelSets->map(function (LevelSet $levelSet) use ($message, $attachment) {
                        $attachmentDownloadUrl = (string) (Uri::of($attachment['url'])->replaceQuery([]));
                        $levelSetDownloadUrl = (string) (Uri::of($levelSet->alternate_download_url)->replaceQuery([]));

                        return [
                            'title' => $levelSet->name.($levelSet->prerelease ? ' (PRERELEASE)' : ''),
                            'url' => $levelSet->getPermalink(),
                            'fields' => [
                                [
                                    'name' => 'Download URL match',
                                    'value' => $attachmentDownloadUrl === $levelSetDownloadUrl ? 'Yes' : 'No',
                                ],
                                [
                                    'name' => 'Timestamp match',
                                    'value' => $levelSet->created_at->unix() === Carbon::parse($message['timestamp'])->unix() ? 'Yes' : 'No',
                                ],
                                [
                                    'name' => 'File checksum match',
                                    'value' => self::compareLocalFileWithAttachment($levelSet, $attachment) ? 'Yes' : 'No',
                                ],
                            ],
                            'image' => [
                                'url' => $levelSet->getImageUrl(),
                            ],
                        ];
                    })->toArray(),
                ];
                InteractionResponse::editInteractionResponse($json['token'], $response);
            } catch (UserFacingInteractionException $exception) {
                InteractionResponse::editInteractionResponse($json['token'], [
                    'content' => '⚠️ '.$exception->getMessage(),
                ]);
            }
        });

        return InteractionResponse::deferredEphemeralMessage();

    }

    /**
     * @throws UserFacingInteractionException
     */
    private static function getAttachment(array $message): array
    {
        if (count($message['attachments']) === 0) {
            throw new UserFacingInteractionException('This message has no attachments');
        } elseif (count($message['attachments']) > 1) {
            throw new UserFacingInteractionException('Multiple attachments are not supported yet');
        }

        $attachment = $message['attachments'][0];
        if (! str_ends_with($attachment['filename'], '.RicochetLW') && ! str_ends_with($attachment['filename'], '.RicochetI')) {
            throw new UserFacingInteractionException('This attachment is not a Ricochet level');
        }

        return $attachment;
    }

    /**
     * @return Collection<int, LevelSet>
     */
    private static function findLevelSets(array $attachment): Collection
    {
        $downloadUrl = (string) (Uri::of($attachment['url'])->replaceQuery([]));
        $levelSets = LevelSet::where('alternate_download_url', 'LIKE', \App\Helpers\Str::escapeLike($downloadUrl).'%')->get();
        if (! $levelSets->isEmpty()) {
            return $levelSets;
        }

        // Try imperfect name search
        return LevelSet::where('name', self::getNameFromAttachment($attachment))->get();
    }

    private static function getNameFromAttachment(array $attachment): string
    {
        // `title` key is only available on some attachments
        if (isset($attachment['title'])) {
            return $attachment['title'];
        }

        $levelSetName = \Illuminate\Support\Str::beforeLast($attachment['filename'], '.RicochetLW');
        $levelSetName = \Illuminate\Support\Str::beforeLast($levelSetName, '.RicochetI');

        return str_replace('_', ' ', $levelSetName);
    }

    private static function compareLocalFileWithAttachment(LevelSet $levelSet, array $attachment): bool
    {
        $fileName = $levelSet->downloaded_file_name;

        $disk = Storage::disk('levels');
        if (! $disk->exists($fileName)) {
            return false;
        }

        if ($disk->size($fileName) !== $attachment['size']) {
            return false;
        }

        $response = Http::get($attachment['url']);
        if (! $response->successful()) {
            return false;
        }

        return hash_file('sha256', $disk->path($fileName)) === hash('sha256', $response->getBody()->getContents());

    }
}
