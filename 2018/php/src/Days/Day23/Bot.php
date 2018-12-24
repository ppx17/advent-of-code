<?php

namespace Ppx17\Aoc2018\Days\Day23;


class Bot
{

    private $location;
    private $range;

    public function __construct(Vector3 $location, int $range)
    {
        $this->location = $location;
        $this->range = $range;
    }

    public function inRange(Vector3 $otherLocation)
    {
        return $this->location->manhattanDistance($otherLocation) <= $this->range;
    }

    public function inRangeInt(int $x, int $y, int $z)
    {
        return $this->location->manhattanDistanceInt($x, $y, $z) <= $this->range;
    }

    public function getRange(): int
    {
        return $this->range;
    }

    public function getLocation(): Vector3
    {
        return $this->location;
    }


}