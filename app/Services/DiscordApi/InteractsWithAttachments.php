<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use Illuminate\Support\Str;

trait InteractsWithAttachments
{
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

    private static function getNameFromAttachment(array $attachment): string
    {
        // `title` key is only available on some attachments
        if (isset($attachment['title'])) {
            return $attachment['title'];
        }

        $levelSetName = Str::beforeLast($attachment['filename'], '.RicochetLW');
        $levelSetName = Str::beforeLast($levelSetName, '.RicochetI');

        return str_replace('_', ' ', $levelSetName);
    }
}
