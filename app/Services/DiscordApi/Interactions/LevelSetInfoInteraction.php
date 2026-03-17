<?php

declare(strict_types=1);

namespace App\Services\DiscordApi\Interactions;

use App\LevelSet;
use App\Services\DiscordApi\Enums\ComponentType;
use App\Services\DiscordApi\Enums\MessageFlag;
use App\Services\DiscordApi\InteractionResponse;
use App\Services\DiscordApi\InteractsWithAttachments;
use App\Services\DiscordApi\SessionDataHandler;
use App\Services\DiscordApi\SessionType;
use App\Services\DiscordApi\UserFacingInteractionException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Uri;

class LevelSetInfoInteraction
{
    use InteractsWithAttachments;

    public static function handleApplicationCommand(array $json): JsonResponse
    {
        $message = array_first($json['data']['resolved']['messages']);
        $attachments = self::getAttachments($message);

        defer(static function () use ($json, $message, $attachments) {
            if (count($attachments) > 1) {
                $sessionId = SessionDataHandler::setUp(SessionType::LEVEL_SET_INFO, [
                    'attachments' => $attachments,
                    'messageTimestamp' => $message['timestamp'],
                ]);

                InteractionResponse::editInteractionResponse($json['token'], [
                    'flags' => MessageFlag::IS_COMPONENTS_V2,
                    'components' => [
                        [
                            'type' => ComponentType::TEXT_DISPLAY,
                            'content' => 'Select a file:',
                        ],
                        [
                            'type' => ComponentType::ACTION_ROW,
                            'components' => [
                                [
                                    'type' => ComponentType::STRING_SELECT,
                                    'custom_id' => SessionDataHandler::getSessionWithComponentKey($sessionId, 'attachment_id'),
                                    'options' => array_map(static function ($attachment) {
                                        return [
                                            'label' => self::getNameFromAttachment($attachment),
                                            'value' => $attachment['id'],
                                        ];
                                    }, $attachments),
                                ],
                            ],
                        ],
                    ],
                ]);

                return;
            }

            try {
                InteractionResponse::editInteractionResponse(
                    $json['token'],
                    self::sendLevelInfoResponse(attachment: $attachments[0], messageTimestamp: $message['timestamp'])
                );
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
    public static function handleComponentResponse(array $request, array $sessionData): JsonResponse
    {
        if (SessionDataHandler::splitSessionAndComponentKey($request['data']['custom_id'])[1] !== 'attachment_id') {
            throw new \DomainException('Unknown component key');
        }

        $attachment = null;
        if (count($request['data']['values']) === 1) {
            $attachment = array_find($sessionData['attachments'], function ($attachment) use ($request) {
                return $request['data']['values'][0] === $attachment['id'];
            });
        }
        if ($attachment === null) {
            throw new UserFacingInteractionException('No attachment is selected');
        }

        $response = self::sendLevelInfoResponse(attachment: $attachment, messageTimestamp: $sessionData['messageTimestamp']);

        return InteractionResponse::ephemeralMessage($response['content'], $response['embeds']);
    }

    private static function sendLevelInfoResponse(array $attachment, string $messageTimestamp): array
    {
        $levelSetName = self::getNameFromAttachment($attachment);
        $levelSets = self::findLevelSets($attachment);
        if ($levelSets->isEmpty()) {
            throw new UserFacingInteractionException('No level sets found matching “'.$levelSetName.'”');
        }

        return [
            'content' => $levelSets->count() > 1 ? 'Found '.$levelSets->count().' level sets named “'.$levelSetName.'”' : '',
            'embeds' => $levelSets->map(function (LevelSet $levelSet) use ($messageTimestamp, $attachment) {
                $attachmentDownloadUrl = self::cleanUrlQueryStrings($attachment['url']);
                $levelSetDownloadUrl = self::cleanUrlQueryStrings($levelSet->alternate_download_url);

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
                            'value' => $levelSet->created_at->unix() === Carbon::parse($messageTimestamp)->unix() ? 'Yes' : 'No',
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
    }

    /**
     * @return Collection<int, LevelSet>
     */
    private static function findLevelSets(array $attachment): Collection
    {
        $downloadUrl = self::cleanUrlQueryStrings($attachment['url']);
        $levelSets = LevelSet::where('alternate_download_url', 'LIKE', \App\Helpers\Str::escapeLike($downloadUrl).'%')->get();
        if (! $levelSets->isEmpty()) {
            return $levelSets;
        }

        // Try imperfect name search
        return LevelSet::where('name', self::getNameFromAttachment($attachment))->get();
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

    private static function cleanUrlQueryStrings(string $url): string
    {
        return (string) (Uri::of($url)->replaceQuery([]));
    }
}
