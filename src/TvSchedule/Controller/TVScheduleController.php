<?php

namespace App\TvSchedule\Controller;

use App\TvSchedule\Model\Schedule;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/tv-schedule")]
class TVScheduleController extends AbstractController
{
    public function __invoke(
        Schedule $schedule
    ) {
        return $this->json($schedule->getShowScheduledAt(new DateTime));
    }
}
