<?php

namespace App\Tests\TvSchedule\Service;

use App\TvSchedule\Services\TimerFinder;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Clock\Clock;
use Symfony\Component\Clock\MockClock;

class TimerFinderTest extends TestCase
{
    /**
     * @dataProvider invokeProvider
     */
    public function testNextTime(string $time, string $requestFormat, string $expectedDate): void
    {
        Clock::set(new MockClock($time));

        $timeFinder = new TimerFinder();
        $resultTime = $timeFinder->nextTime($requestFormat)->format("c");
        $this->assertEquals($expectedDate, $resultTime);
    }

    public function invokeProvider(): \Generator
    {
        yield ['2024-01-01 09:15', '21:00', '2024-01-01T21:00:00+00:00'];
        yield ['2024-01-01 20:59', '21:00', '2024-01-01T21:00:00+00:00'];
        yield ['2024-01-01 21:00', '21:00', '2024-01-01T21:00:00+00:00'];
        yield ['2024-01-01 21:01', '21:00', '2024-01-02T21:00:00+00:00'];
        yield ['2024-01-01 09:15', '00:00', '2024-01-02T00:00:00+00:00'];
        yield ['2024-01-01 09:15', '09:00', '2024-01-02T09:00:00+00:00'];
        yield ['2024-01-01 09:15', '09:16', '2024-01-01T09:16:00+00:00'];
    }

    public function testInvalidFormat(): void
    {
        $timeFinder = new TimerFinder();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage("Bad request format, expected HH:mm got 2100.");
        $timeFinder->nextTime("2100");
    }
}
