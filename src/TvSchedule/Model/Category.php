<?php

namespace App\TvSchedule\Model;

use JsonSerializable;
use Symfony\Component\Serializer\Annotation\SerializedName;

class Category implements JsonSerializable
{
    #[SerializedName("#")]
    public string $title;

    public function jsonSerialize(): mixed
    {
        return $this->title;
    }
}
