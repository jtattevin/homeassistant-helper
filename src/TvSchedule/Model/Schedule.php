<?php

namespace App\TvSchedule\Model;

use DateTime;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Schedule
{
    /** @var ShowSchedule[] */
    #[SerializedName("programme")]
    public array $showSchedules;

    public function getShowScheduledAt(DateTime $targetDate): array
    {
        $programmesMapped = [];

        foreach ($this->showSchedules as $showSchedule) {
            $programmesMapped[$showSchedule->slug()] ??= null;
            if ($showSchedule->start < $targetDate && $targetDate < $showSchedule->stop) {
                $programmesMapped[$showSchedule->slug()] = $showSchedule;
            }
        }

        return $programmesMapped;
    }
}
