<?php

declare(strict_types=1);

namespace App\Services\RatingDataParser;

use App\Helpers\Str;
use App\Helpers\TextEncoderForGame;
use App\Services\RatingGradeConverter;

/**
 * Parser for CRatingData sent from game client
 */
final class Parser
{
    /**
     * @return list<RatingData>
     *
     * @throws RatingDataParserException
     */
    public static function parse(string $data): array
    {
        $result = [];

        $data = TextEncoderForGame::toUtf8($data);
        foreach (Str::readTextAsStream($data) as $i => $line) {
            // Ensure first line starts with a known header
            if ($i === 0) {
                if ($line !== 'player_name,roundset_name,overall_rating,fun_rating,graphics_rating,tags,percent_complete') {
                    throw new RatingDataParserException('Invalid header for rating data');
                }

                continue;
            }

            $data = explode(',', $line);
            if (count($data) !== 7) {
                // @todo Unfortunately, if the player name or level set has commas, this breaks the
                //       expectation that there are only 7 fields (like a CSV comma injection)
                //
                // This may be resolved in the future, but we will skip for now, similar to these
                // level sets on the old catalog are already lacking ratings/tags
                //
                // Custom tags are not an issue as the game blocks typing comma and semicolon
                //
                // Example:
                //   PlayerAAA,Shamaar,s No Help Levels,9,9,9,Bombs,100
                continue;
            }

            if (strlen($data[0]) <= 0) {
                throw new RatingDataParserException('Player name is invalid');
            }

            if (strlen($data[0]) > 13) {
                throw new RatingDataParserException('Player name is too long');
            }

            if (strlen($data[1]) <= 0) {
                throw new RatingDataParserException('Level set name is invalid');
            }

            $ratingData = new RatingData;
            $ratingData->player = $data[0];
            $ratingData->levelSetName = $data[1];
            $ratingData->overallRating = self::processUserGrade($data[2]);
            $ratingData->funRating = self::processUserGrade($data[3]);
            $ratingData->graphicsRating = self::processUserGrade($data[4]);
            $ratingData->tags = self::processTags($data[5]);
            $ratingData->percentComplete = self::processPercentComplete($data[6]);

            $result[] = $ratingData;
        }

        return $result;
    }

    /**
     * @throws RatingDataParserException
     */
    private static function processUserGrade(string $raw): ?int
    {
        if (! ctype_digit($raw)) {
            throw new RatingDataParserException('Grade rating is invalid');
        }

        $rating = (int) $raw;
        if ($rating === 0) {
            // Rating can be 0 if the player did not rate this level set
            return null;
        }

        if (RatingGradeConverter::getUserGrade($rating) == null) {
            throw new RatingDataParserException('Grade rating is invalid');
        }

        return $rating;
    }

    private static function processTags(string $raw): array
    {
        if ($raw === '') {
            return [];
        }

        $tags = explode(';', $raw);

        return array_filter($tags, function ($value) {
            // Prevent adding tags with "Mod:" prefix as this tag is automatically added
            // and there is no way to prevent people from adding in-game anyway
            return ! str_starts_with($value, 'Mod:');
        });
    }

    /**
     * @throws RatingDataParserException
     */
    private static function processPercentComplete(string $raw): int
    {
        if (! ctype_digit($raw)) {
            throw new RatingDataParserException('Percent complete is invalid');
        }

        $percentComplete = (int) $raw;

        // Game shows this message if the first level is not completed, so 0% completion seems wrong
        //
        // Rating Not Allowed: You must finish the first level of a level set in order to rate it.
        if ($percentComplete <= 0 || $percentComplete > 100) {
            throw new RatingDataParserException('Percent complete is invalid');
        }

        return $percentComplete;
    }
}

class RatingDataParserException extends \Exception {}
