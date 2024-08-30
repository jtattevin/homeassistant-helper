<?php

namespace App\Tests\TvSchedule\Controller;

use App\TvSchedule\Services\TimerFinder;
use App\TvSchedule\Controller\TVScheduleAtController;
use App\TvSchedule\Model\Schedule;
use App\TvSchedule\Model\ShowSchedule;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TVScheduleAtControllerTest extends KernelTestCase
{
    /**
     * @dataProvider invokeProvider
     */
    public function testInvoke(Schedule $schedule, string $requestedTime, string $time, string $expected): void
    {
        self::bootKernel();
        $controller = self::getContainer()->get(TVScheduleAtController::class);
        assert($controller instanceof TVScheduleAtController);

        Clock::set(new MockClock($time));

        $response = $controller($schedule, $requestedTime);

        $this->assertEquals($expected, $response->getContent());
    }

    public function invokeProvider(): \Generator
    {
        $schedule = new Schedule();
        $schedule->showSchedules = [];
        yield [$schedule, '21:00', '2024-01-01 09:15', json_encode([], JSON_THROW_ON_ERROR)];

        $schedule = new Schedule();
        $schedule->showSchedules = [
            $this->getShowSchedule('channel1', '2024-01-01 09:00', '2024-01-01 09:30', 'show1'),
        ];
        yield [
            $schedule, '9:20', '2024-01-01 09:15', json_encode([
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
        yield [
            $schedule, '21:00', '2024-01-01 09:15', json_encode([
                'channel1' => null,
            ], JSON_THROW_ON_ERROR),
        ];
        yield [$schedule, '9:20', '2024-01-02 09:15', json_encode([
            'channel1' => null,
        ], JSON_THROW_ON_ERROR),
        ];
        yield [$schedule, '21:00', '2024-01-02 09:15', json_encode([
            'channel1' => null,
        ], JSON_THROW_ON_ERROR),
        ];

        $schedule = new Schedule();
        $schedule->showSchedules = [
            $this->getShowSchedule('channel1', '2024-01-01 09:00', '2024-01-01 09:30', 'show1'),
            $this->getShowSchedule('channel1', '2024-01-01 09:30', '2024-01-01 09:40', 'show2'),
            $this->getShowSchedule('channel2', '2024-01-01 09:10', '2024-01-01 09:30', 'show3'),
            $this->getShowSchedule('channel1', '2024-01-01 20:00', '2024-01-01 21:30', 'show4'),
        ];
        yield [
            $schedule, '9:20', '2024-01-01 09:15', json_encode([
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
            $schedule, '21:00', '2024-01-01 09:15', json_encode([
                'channel1' => [
                    'title' => 'show4',
                    'desc' => '',
                    'categories' => [],
                    'icon' => '',
                    'episode' => '',
                    'rating' => '',
                    'channel' => 'channel1',
                    'start' => '2024-01-01T20:00:00+00:00',
                    'stop' => '2024-01-01T21:30:00+00:00',
                ],
                'channel2' => null,
            ], JSON_THROW_ON_ERROR),
        ];
        yield [
            $schedule, '21:00', '2024-01-01 09:35', json_encode([
                'channel1' => [
                    'title' => 'show4',
                    'desc' => '',
                    'categories' => [],
                    'icon' => '',
                    'episode' => '',
                    'rating' => '',
                    'channel' => 'channel1',
                    'start' => '2024-01-01T20:00:00+00:00',
                    'stop' => '2024-01-01T21:30:00+00:00',
                ],
                'channel2' => null,
            ], JSON_THROW_ON_ERROR),
        ];
    }

    public function testInvokeWithBadFormat(): void
    {
        self::bootKernel();
        $controller = self::getContainer()->get(TVScheduleAtController::class);
        assert($controller instanceof TVScheduleAtController);

        Clock::set(new MockClock('2024-01-01 09:15'));

        $schedule = new Schedule();
        $schedule->showSchedules = [];

        $this->expectException(BadRequestHttpException::class);
        $response = $controller($schedule, '2100');
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
