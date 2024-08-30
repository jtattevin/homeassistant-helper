<?php

namespace App\TvSchedule\Services;

use Symfony\Component\Clock\Clock;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TimerFinder
{
    public function nextTime(string $requestFormat): \DateTimeInterface
    {
        if (1 !== preg_match("#(\d{1,2}):(\d{2})#", $requestFormat, $matches)) {
            throw new BadRequestException("Bad request format, expected HH:mm got $requestFormat.");
        }
        $hour = (int) $matches[1];
        $minutes = (int) $matches[2];

        $targetTime = Clock::get()->now();

        if ($targetTime->format('Hi') > ($hour * 100 + $minutes)) {
            $targetTime = $targetTime->add(new \DateInterval('P1D'));
        }

        return $targetTime->setTime($hour, $minutes, 0);
    }
}
