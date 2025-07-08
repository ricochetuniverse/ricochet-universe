<?php

declare(strict_types=1);

namespace App\Services;

use App\LevelSet;

/**
 * @phpstan-type RatingResult array{overall: array{cumulative: float, count: int}, fun: array{cumulative: float, count: int}, graphics: array{cumulative: float, count: int}}
 */
final class LevelSetRatingsCalculator
{
    /**
     * @return array{overall: array{grade: float, count: int}, fun: array{grade: float, count: int}, graphics: array{grade: float, count: int}}
     */
    public static function calculate(LevelSet $levelSet): array
    {
        $legacyRating = self::calculateLegacyRating($levelSet);
        $merged = self::calculateUserRatings($levelSet, $legacyRating);

        return [
            'overall' => [
                'grade' => $merged['overall']['cumulative'] / $merged['overall']['count'],
                'count' => $merged['overall']['count'],
            ],
            'fun' => [
                'grade' => $merged['fun']['cumulative'] / $merged['fun']['count'],
                'count' => $merged['fun']['count'],
            ],
            'graphics' => [
                'grade' => $merged['graphics']['cumulative'] / $merged['graphics']['count'],
                'count' => $merged['graphics']['count'],
            ],
        ];
    }

    /**
     * @return RatingResult
     */
    private static function calculateLegacyRating(LevelSet $levelSet): array
    {
        $legacyRating = $levelSet->legacyRating;
        if ($legacyRating === null) {
            return [
                'overall' => [
                    'cumulative' => 0,
                    'count' => 0,
                ],
                'fun' => [
                    'cumulative' => 0,
                    'count' => 0,
                ],
                'graphics' => [
                    'cumulative' => 0,
                    'count' => 0,
                ],
            ];
        }

        return [
            'overall' => [
                'cumulative' => $legacyRating->overall_rating * $legacyRating->overall_weight,
                'count' => $legacyRating->overall_weight,
            ],
            'fun' => [
                'cumulative' => $legacyRating->fun_rating * $legacyRating->fun_weight,
                'count' => $legacyRating->fun_weight,
            ],
            'graphics' => [
                'cumulative' => $legacyRating->graphics_rating * $legacyRating->graphics_weight,
                'count' => $legacyRating->graphics_weight,
            ],
        ];
    }

    /**
     * @param  RatingResult  $result
     * @return RatingResult
     */
    private static function calculateUserRatings(LevelSet $levelSet, array $result): array
    {
        foreach ($levelSet->userRatings as $rating) {
            if ($rating->overall_grade > 0) {
                $result['overall']['cumulative'] += $rating->overall_grade;
                $result['overall']['count'] += 1;
            }

            if ($rating->fun_grade > 0) {
                $result['fun']['cumulative'] += $rating->fun_grade;
                $result['fun']['count'] += 1;
            }

            if ($rating->graphics_grade > 0) {
                $result['graphics']['cumulative'] += $rating->graphics_grade;
                $result['graphics']['count'] += 1;
            }
        }

        return $result;
    }
}
