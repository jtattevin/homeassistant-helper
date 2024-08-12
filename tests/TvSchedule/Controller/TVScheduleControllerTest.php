<?php

namespace App\Tests\TvSchedule\Controller;

use App\TvSchedule\Controller\TVScheduleController;
use App\TvSchedule\Model\Schedule;
use App\TvSchedule\Model\ShowSchedule;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;

class TVScheduleControllerTest extends KernelTestCase
{
    /**
     * @dataProvider invokeProvider
     */
    public function testInvoke(Schedule $schedule, string $time, string $expected)
    {
        self::bootKernel();
        $controller = self::getContainer()->get(TVScheduleController::class);

        Clock::set(new MockClock($time));

        $response = $controller($schedule);

        $this->assertEquals($expected, $response->getContent());
    }

    public function invokeProvider(): \Generator
    {
        $schedule = new Schedule();
        $schedule->showSchedules = [];
        yield [$schedule, '2024-01-01 09:15', json_encode([], JSON_THROW_ON_ERROR)];

        $schedule = new Schedule();
        $schedule->showSchedules = [
            $this->getShowSchedule('channel1', '2024-01-01 09:00', '2024-01-01 09:30', 'show1'),
        ];
        yield [
            $schedule, '2024-01-01 09:15', json_encode([
                'channel1' => [
                    'title' => 'show1',
                    'desc' => '',
                    'categories' => [],
                    'icon' => '',
                    'episode' => '',
                    'rating' => '',
                    'channel' => 'channel1',
                    'start' => '2024-01-01T09:00:00+00:00',
                    'stop' => '2024-01-01T09:30:00+00:00',
                ],
            ], JSON_THROW_ON_ERROR),
        ];
        yield [$schedule, '2024-01-02 09:15', json_encode([
            'channel1' => null,
        ], JSON_THROW_ON_ERROR),
        ];

        $schedule = new Schedule();
        $schedule->showSchedules = [
            $this->getShowSchedule('channel1', '2024-01-01 09:00', '2024-01-01 09:30', 'show1'),
            $this->getShowSchedule('channel1', '2024-01-01 09:30', '2024-01-01 09:40', 'show2'),
            $this->getShowSchedule('channel2', '2024-01-01 09:10', '2024-01-01 09:30', 'show3'),
        ];
        yield [
            $schedule, '2024-01-01 09:15', json_encode([
                'channel1' => [
                    'title' => 'show1',
                    'desc' => '',
                    'categories' => [],
                    'icon' => '',
                    'episode' => '',
                    'rating' => '',
                    'channel' => 'channel1',
                    'start' => '2024-01-01T09:00:00+00:00',
                    'stop' => '2024-01-01T09:30:00+00:00',
                ],
                'channel2' => [
                    'title' => 'show3',
                    'desc' => '',
                    'categories' => [],
                    'icon' => '',
                    'episode' => '',
                    'rating' => '',
                    'channel' => 'channel2',
                    'start' => '2024-01-01T09:10:00+00:00',
                    'stop' => '2024-01-01T09:30:00+00:00',
                ],
            ], JSON_THROW_ON_ERROR),
        ];
        yield [
            $schedule, '2024-01-01 09:35', json_encode([
                'channel1' => [
                    'title' => 'show2',
                    'desc' => '',
                    'categories' => [],
                    'icon' => '',
                    'episode' => '',
                    'rating' => '',
                    'channel' => 'channel1',
                    'start' => '2024-01-01T09:30:00+00:00',
                    'stop' => '2024-01-01T09:40:00+00:00',
                ],
                'channel2' => null,
            ], JSON_THROW_ON_ERROR),
        ];
    }

    private function getShowSchedule(string $channel, string $start, string $stop, string $title): ShowSchedule
    {
        $showSchedule = new ShowSchedule();
        $showSchedule->channel = $channel;
        $showSchedule->start = new \DateTime($start);
        $showSchedule->stop = new \DateTime($stop);
        $showSchedule->title = $title;
        $showSchedule->description = '';
        $showSchedule->categories = [];

        return $showSchedule;
    }
}
