<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Services\RatingDataParser\Parser as RatingDataParser;
use PHPUnit\Framework\TestCase;

class RatingDataParserTest extends TestCase
{
    public function test_valid_level_data(): void
    {
        $data = <<<EOF
{$this->getHeader()}
PlayerAAA,10 auto levels of fun,0,0,0,Autoplay,100
PlayerBBB,Reflexive B Sides,14,11,8,Awesome;Classic Style;Strategy,5
PlayerCCC,Rico's High Sea Adventures,14,14,15,Movie;Artistic;Pictures;Creative,100

EOF;
        $ratings = RatingDataParser::parse($data);

        $this->assertCount(3, $ratings);
        $this->assertEquals('PlayerBBB', $ratings[1]->player);
        $this->assertEquals('Reflexive B Sides', $ratings[1]->levelSetName);
        $this->assertEquals(14, $ratings[1]->overallRating);
        $this->assertEquals(11, $ratings[1]->funRating);
        $this->assertEquals(8, $ratings[1]->graphicsRating);
        $this->assertEquals(['Awesome', 'Classic Style', 'Strategy'], $ratings[1]->tags);
        $this->assertEquals(5, $ratings[1]->percentComplete);
    }

    public function test_invalid_header(): void
    {
        $this->expectExceptionMessage('Invalid header for rating data');

        RatingDataParser::parse('nope');
    }

    public function test_invalid_grade(): void
    {
        $this->expectExceptionMessage('Grade rating is invalid');

        RatingDataParser::parse($this->getHeader()."\nPlayerAAA,Reflexive B Sides,14abc,11,8,Awesome,5");
    }

    // Unresolved for now
    public function test_level_set_name_with_commas(): void
    {
        $data = <<<EOF
{$this->getHeader()}
PlayerAAA,Shamaar,s No Help Levels,9,9,9,Bombs,100
PlayerCCC,Rico's High Sea Adventures,14,14,15,Movie;Artistic;Pictures;Creative,100

EOF;
        $ratings = RatingDataParser::parse($data);

        $this->assertCount(1, $ratings);
    }

    public function test_legacy_encoding(): void
    {
        $data = file_get_contents(__DIR__.'/../fixtures/rating-data/legacy-encoding.txt');
        $ratings = RatingDataParser::parse($data);

        $this->assertCount(2, $ratings);
        $this->assertEquals('#########Pack VL n°1', $ratings[0]->levelSetName);
        $this->assertEquals('Eragoncola³s levels', $ratings[1]->levelSetName);
    }

    private function getHeader(): string
    {
        return 'player_name,roundset_name,overall_rating,fun_rating,graphics_rating,tags,percent_complete';
    }
}
