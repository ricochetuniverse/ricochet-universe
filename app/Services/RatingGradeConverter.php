<?php

namespace App\Services;

class RatingGradeConverter
{
    public const HIGHEST_RATING = 15;

    public const LOWEST_RATING = 2;

    public static function getGrade(float $rating): string
    {
        if ($rating >= 14.5001) {
            return 'A+';
        } elseif ($rating >= 13.5000) {
            return 'A';
        } elseif ($rating >= 12.5001) {
            return 'A-';
        } elseif ($rating >= 11.5000) {
            return 'B+';
        } elseif ($rating >= 10.5001) {
            return 'B';
        } elseif ($rating >= 9.5000) {
            return 'B-';
        } elseif ($rating >= 8.5001) {
            return 'C+';
        } elseif ($rating >= 7.5000) {
            return 'C';
        } elseif ($rating >= 6.5001) {
            return 'C-';
        } elseif ($rating >= 5.5000) {
            return 'D+';
        } elseif ($rating >= 4.5001) {
            return 'D';
        } elseif ($rating >= 3.5000) {
            return 'D-';
        } elseif ($rating > 0) {
            return 'F';
        }

        return '';
    }

    public static function getUserGrade(int $rating): string
    {
        $grades = [
            2 => 'F',
            4 => 'D-',
            5 => 'D',
            6 => 'D+',
            7 => 'C-',
            8 => 'C',
            9 => 'C+',
            10 => 'B-',
            11 => 'B',
            12 => 'B+',
            13 => 'A-',
            14 => 'A',
            15 => 'A+',
        ];

        return $grades[$rating];
    }

    public static function getScaledRatingPercentage(float $scaled): float
    {
        return 100 / (static::HIGHEST_RATING - static::LOWEST_RATING) * ($scaled - static::LOWEST_RATING);
    }
}
