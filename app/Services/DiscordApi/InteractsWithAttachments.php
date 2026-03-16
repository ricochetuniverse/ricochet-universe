<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use Illuminate\Support\Str;

/**
 * @phpstan-type Attachment array{content_scan_version: int, content_type?: string, description?: string, duration_secs?: float, ephemeral?: bool, filename: string, flags?: int, height?: int, id: string, proxy_url: string, size: int, title?: string, url: string, waveform?: string, width?: int}
 */
trait InteractsWithAttachments
{
    /**
     * @deprecated Use getAttachments()
     *
     * @return Attachment
     *
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
     * @return array<Attachment>
     *
     * @throws UserFacingInteractionException
     */
    private static function getAttachments(array $message): array
    {
        if (count($message['attachments']) === 0) {
            throw new UserFacingInteractionException('This message has no attachments');
        }

        $filtered = array_filter($message['attachments'], static function ($attachment) {
            /** @var Attachment $attachment */
            return str_ends_with($attachment['filename'], '.RicochetLW') || str_ends_with($attachment['filename'], '.RicochetI');
        });

        if (count($filtered) === 0) {
            throw new UserFacingInteractionException('This message has no Ricochet level attachments');
        }

        return $filtered;
    }

    /**
     * @param  Attachment  $attachment
     */
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
