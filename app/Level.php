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

    public $author = '';

    /**
     * @var \DateTime
     */
    public $date;

    /**
     * @var bool
     */
    public $featured = false;
    public $gameVersion;
//    public $prerelease = false;
//    public $requiredBuild;
    public $imageUrl = '';
    public $rating;

    /**
     * @var int
     */
    public $downloads = 0;
    public $description = '';

    /**
     * @var string[]
     */
    public $tags = [];
    public $overallRatings;

    /**
     * @var int
     */
    public $overallRatingCount = 0;
    public $funRatings;

    /**
     * @var int
     */
    public $funRatingCount = 0;
    public $graphicsRatings;

    /**
     * @var int
     */
    public $graphicsRatingCount = 0;
    public $similarLevels = [];
}
