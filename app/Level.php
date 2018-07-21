<?php

namespace App;

class Level
{
    /**
     * @var int
     */
    public $legacyId;

    /**
     * @var string
     */
    public $name = '';

    /**
     * @var int
     */
    public $rounds = 0;

    /**
     * @var string
     */
    public $author = '';

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var bool
     */
    public $featured = false;

    /**
     * @var string
     */
    public $gameVersion;
//    public $prerelease = false;
//    public $requiredBuild;

    /**
     * @var string
     */
    public $imageUrl = '';

    /**
     * @var float A value from 0 - 5
     */
    public $rating = 0.0;

    /**
     * @var int
     */
    public $downloads = 0;

    /**
     * @var string
     */
    public $description = '';

    /**
     * @var string[]
     */
    public $tags = [];

    /**
     * @var float A value from 0 to 15
     */
    public $overallRating = 0.0;

    /**
     * @var int
     */
    public $overallRatingCount = 0;

    /**
     * @var float A value from 0 to 15
     */
    public $funRating = 0.0;

    /**
     * @var int
     */
    public $funRatingCount = 0;

    /**
     * @var float A value from 0 to 15
     */
    public $graphicsRating = 0.0;

    /**
     * @var int
     */
    public $graphicsRatingCount = 0;

    /**
     * @var int[]
     */
    public $similarLevels = [];
}
