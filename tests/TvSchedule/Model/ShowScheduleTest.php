<?php

namespace App\Tests\TvSchedule\Model;

use App\TvSchedule\Model\Category;
use App\TvSchedule\Model\ShowSchedule;
use PHPUnit\Framework\TestCase;

class ShowScheduleTest extends TestCase
{
    /**
     * @testWith ["simple","simple"]
     *           ["allowUppercase","allowuppercase"]
     *           ["allowNumber0","allownumber0"]
     *           ["disallow.dot","disallow_dot"]
     *           ["disallow.multiple.dot","disallow_multiple_dot"]
     *           ["",""]
     */
    public function testSlug(string $channel, string $expected)
    {
        $showSchedule = new ShowSchedule();
        $showSchedule->channel = $channel;
        $this->assertEquals($expected, $showSchedule->slug());
    }

    public function testJsonSerialize()
    {
        $category = new Category();
        $category->title = 'category.title';

        $showSchedule = new ShowSchedule();
        $showSchedule->title = 'title';
        $showSchedule->description = 'desc';
        $showSchedule->categories = [
            $category,
        ];
        $showSchedule->icon = 'icon';
        $showSchedule->episode = 'episode';
        $showSchedule->rating = 'rating';
        $showSchedule->channel = 'channel';
        $showSchedule->start = new \DateTime('2024-01-01');
        $showSchedule->stop = new \DateTime('2024-01-01');

        $this->assertEquals(
            <<<JSON
            {"title":"title","desc":"desc","categories":["category.title"],"icon":"icon","episode":"episode","rating":"rating","channel":"channel","start":{"date":"2024-01-01 00:00:00.000000","timezone_type":3,"timezone":"UTC"},"stop":{"date":"2024-01-01 00:00:00.000000","timezone_type":3,"timezone":"UTC"}}
            JSON,
            json_encode($showSchedule)
        );
    }
}
