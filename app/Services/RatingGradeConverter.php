<?php

namespace App\Services;

class RatingGradeConverter
{
    public static function getGrade(float $rating)
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
}
