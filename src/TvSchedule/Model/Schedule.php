<?php

namespace App\TvSchedule\Model;

use DateTimeInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Schedule
{
    /** @var ShowSchedule[] */
    #[SerializedName("programme")]
    public array $showSchedules;

    /**
     * @param DateTimeInterface $targetDate
     *
     * @return array<string,ShowSchedule|null>
     */
    public function getShowScheduledAt(DateTimeInterface $targetDate): array
    {
        $programmesMapped = [];

        foreach ($this->showSchedules as $showSchedule) {
            $programmesMapped[$showSchedule->slug()] ??= null;
            if ($showSchedule->start <= $targetDate && $targetDate < $showSchedule->stop) {
                $programmesMapped[$showSchedule->slug()] = $showSchedule;
            }
        }

        return $programmesMapped;
    }
}
