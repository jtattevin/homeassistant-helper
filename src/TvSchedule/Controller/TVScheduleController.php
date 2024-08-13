<?php

namespace App\TvSchedule\Controller;

use App\TvSchedule\Model\Schedule;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Clock\Clock;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/tv-schedule')]
class TVScheduleController extends AbstractController
{
    public function __invoke(
        Schedule $schedule
    ): Response {
        if (false) {
            $value = 5;
            exit;
        }

        return $this->json($schedule->getShowScheduledAt(Clock::get()->now()));
    }
}
