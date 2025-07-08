<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\LevelSet;
use App\Services\LevelSetRatingsCalculator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LevelSetRatingsCalculatorTest extends TestCase
{
    use RefreshDatabase;

    public function test_legacy_rating_calculation(): void
    {
        /** @var LevelSet $levelSet */
        $levelSet = LevelSet::factory()->create();

        $levelSet->legacyRating()->create([
            'overall_rating' => 11.1383,
            'overall_weight' => 753,
            'fun_rating' => 11.4466,
            'fun_weight' => 652,
            'graphics_rating' => 11.2495,
            'graphics_weight' => 646,
        ]);

        $result = LevelSetRatingsCalculator::calculate($levelSet);

        $this->assertEqualsWithDelta(11.1383, $result['overall']['grade'], 0.1);
        $this->assertEquals(753, $result['overall']['count']);
        $this->assertEqualsWithDelta(11.4466, $result['fun']['grade'], 0.1);
        $this->assertEquals(652, $result['fun']['count']);
        $this->assertEqualsWithDelta(11.2495, $result['graphics']['grade'], 0.1);
        $this->assertEquals(646, $result['graphics']['count']);
    }

    public function test_user_ratings_calculation(): void
    {
        /** @var LevelSet $levelSet */
        $levelSet = LevelSet::factory()->create();

        $levelSet->userRatings()->create([
            'player_name' => 'PlayerAAA',
            'overall_grade' => 15,
            'fun_grade' => 12,
            'graphics_grade' => 10,
        ]);

        $levelSet->userRatings()->create([
            'player_name' => 'PlayerBBB',
            'overall_grade' => 10,
            'fun_grade' => 8,
            'graphics_grade' => 5,
        ]);

        $result = LevelSetRatingsCalculator::calculate($levelSet);

        $this->assertEqualsWithDelta(12.5, $result['overall']['grade'], 0.1);
        $this->assertEquals(2, $result['overall']['count']);
        $this->assertEqualsWithDelta(10, $result['fun']['grade'], 0.1);
        $this->assertEquals(2, $result['fun']['count']);
        $this->assertEqualsWithDelta(7.5, $result['graphics']['grade'], 0.1);
        $this->assertEquals(2, $result['graphics']['count']);
    }

    public function test_legacy_and_user_ratings_calculation(): void
    {
        /** @var LevelSet $levelSet */
        $levelSet = LevelSet::factory()->create();

        $levelSet->legacyRating()->create([
            'overall_rating' => 15,
            'overall_weight' => 1,
            'fun_rating' => 15,
            'fun_weight' => 2,
            'graphics_rating' => 15,
            'graphics_weight' => 3,
        ]);

        $levelSet->userRatings()->create([
            'player_name' => 'PlayerAAA',
            'overall_grade' => 10,
            'fun_grade' => 10,
            'graphics_grade' => 10,
        ]);

        $levelSet->userRatings()->create([
            'player_name' => 'PlayerBBB',
            'overall_grade' => 10,
            'fun_grade' => 10,
            'graphics_grade' => 10,
        ]);

        $result = LevelSetRatingsCalculator::calculate($levelSet);

        $this->assertEqualsWithDelta(11.6667, $result['overall']['grade'], 0.1);
        $this->assertEquals(3, $result['overall']['count']);
        $this->assertEqualsWithDelta(12.5, $result['fun']['grade'], 0.1);
        $this->assertEquals(4, $result['fun']['count']);
        $this->assertEqualsWithDelta(13, $result['graphics']['grade'], 0.1);
        $this->assertEquals(5, $result['graphics']['count']);
    }
}
