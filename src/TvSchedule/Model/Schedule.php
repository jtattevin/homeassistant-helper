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
        $scheduledShow = [];

        foreach ($this->showSchedules as $showSchedule) {
            $scheduledShow[$showSchedule->slug()] ??= null;
            if ($showSchedule->start <= $targetDate && $targetDate < $showSchedule->stop) {
                $scheduledShow[$showSchedule->slug()] = $showSchedule;
            }
        }

        return $scheduledShow;
    }
}
