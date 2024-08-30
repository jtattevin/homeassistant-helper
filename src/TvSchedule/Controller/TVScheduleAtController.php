<?php

namespace App\TvSchedule\Controller;

use App\TvSchedule\Model\Schedule;
use App\TvSchedule\Services\TimerFinder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tv-schedule/at/{requestedTime}')]
class TVScheduleAtController extends AbstractController
{
    public function __construct(
        private readonly TimerFinder $timeFinder,
    ) {
    }

    public function __invoke(
        Schedule $schedule,
        string $requestedTime
    ): Response {
        try {
            return $this->json($schedule->getShowScheduledAt($this->timeFinder->nextTime($requestedTime)));
        } catch (BadRequestException $exception) {
            throw new BadRequestHttpException($exception->getMessage(), $exception);
        }
    }
}
