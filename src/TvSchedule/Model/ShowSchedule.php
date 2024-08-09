<?php

namespace App\TvSchedule\Model;

use DateTime;
use JsonSerializable;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Serializer\Attribute\SerializedPath;

class ShowSchedule implements JsonSerializable
{
    #[SerializedPath("[title][#]")]
    public string $title;

    #[SerializedPath("[desc][#]")]
    public string $description;

    /** @var Category[] */
    #[SerializedName("category")]
    public array $categories;

    #[SerializedPath("[icon][@src]")]
    public string $icon = "";

    #[SerializedPath("[episode-num][#]")]
    public string $episode = "";

    #[SerializedPath("[rating][value]")]
    public string $rating = "";

    #[SerializedName("@channel")]
    public string $channel;

    #[SerializedName("@start")]
    public DateTime $start;

    #[SerializedName("@stop")]
    public DateTime $stop;

    public function slug() : string {
        return preg_replace(
            "#[^a-z0-9]#",
            "_",
            strtolower($this->channel)
        );
    }

    public function jsonSerialize(): mixed
    {
        return [
            "title" => $this->title,
            "desc" => $this->description,
            "categories" => $this->categories,
            "icon" => $this->icon,
            "episode" => $this->episode,
            "rating" => $this->rating,
            "channel" => $this->channel,
            "start" => $this->start,
            "stop" => $this->stop,
        ];
    }
}
