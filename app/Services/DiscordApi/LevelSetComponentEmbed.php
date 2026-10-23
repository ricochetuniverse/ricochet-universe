<?php

declare(strict_types=1);

namespace App\Services\DiscordApi;

use App\LevelSet;
use App\Services\DiscordApi\Enums\ComponentType;

class LevelSetComponentEmbed
{
    public static function getContainerComponents(LevelSet $levelSet): array
    {
        return [
            [
                'type' => ComponentType::SECTION,
                'components' => [
                    [
                        'type' => ComponentType::TEXT_DISPLAY,
                        'content' => sprintf(
                            "# [%s](%s)\nBy [%s](%s), posted on %s",
                            $levelSet->name, // Discord Markdown parser is lax as long the display text doesn't have colons
                            $levelSet->getPermalink(),
                            $levelSet->author,
                            action('LevelController@index', ['author' => $levelSet->author]),
                            $levelSet->created_at->format('Y-m-d'),
                        ),
                    ], [
                        'type' => ComponentType::TEXT_DISPLAY,
                        'content' => $levelSet->description."\n\n".self::getSubtitle($levelSet),
                    ],
                ],
                'accessory' => [
                    'type' => ComponentType::THUMBNAIL,
                    'media' => [
                        'url' => $levelSet->getImageUrl(),
                    ],
                ],
            ],
        ];
    }

    private static function getSubtitle(LevelSet $levelSet): string
    {
        if ($levelSet->downloads === 0) {
            return sprintf(
                '-# (%d rounds)',
                $levelSet->rounds,
            );
        }

        return sprintf(
            '-# (%d rounds, %s downloads)',
            $levelSet->rounds,
            number_format($levelSet->downloads),
        );
    }
}
