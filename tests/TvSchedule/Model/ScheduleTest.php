<?php

namespace App\Tests\TvSchedule\Model;

use App\TvSchedule\Model\Schedule;
use App\TvSchedule\Model\ShowSchedule;
use PHPUnit\Framework\TestCase;

class ScheduleTest extends TestCase
{
    /**
     * @testWith ["channel1", "2024-01-01 09:00", "show1"]
     *           ["channel1", "2024-01-01 09:15", "show1"]
     *           ["channel1", "2024-01-01 09:30", "show2"]
     *            ["channel1", "2024-01-01 09:31", "show2"]
     *            ["channel1", "2024-01-01 09:40", null]
     *            ["channel1", "2024-01-02 09:15", null]
     *            ["channel2", "2024-01-01 09:00", null]
     *            ["channel2", "2024-01-01 09:15", "show3"]
     *            ["channel2", "2024-01-01 09:30", null]
     */
    public function testGetShowScheduledAt(string $channel, string $currentDateTime, ?string $expectedShow)
    {
        $schedule = new Schedule();
        $schedule->showSchedules[] = $this->getShowSchedule('channel1', '2024-01-01 09:00', '2024-01-01 09:30', 'show1');
        $schedule->showSchedules[] = $this->getShowSchedule('channel1', '2024-01-01 09:30', '2024-01-01 09:40', 'show2');
        $schedule->showSchedules[] = $this->getShowSchedule('channel2', '2024-01-01 09:10', '2024-01-01 09:30', 'show3');

        $showScheduled = $schedule->getShowScheduledAt(new \DateTime($currentDateTime));
        $this->assertCount(2, $showScheduled);

        $this->assertEquals($expectedShow, $showScheduled[$channel]?->title);
    }

    private function getShowSchedule(string $channel, string $start, string $stop, string $title): ShowSchedule
    {
        $showSchedule = new ShowSchedule();
        $showSchedule->channel = $channel;
        $showSchedule->start = new \DateTime($start);
        $showSchedule->stop = new \DateTime($stop);
        $showSchedule->title = $title;

        return $showSchedule;
    }
}
