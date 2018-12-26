<?php

namespace Ppx17\Aoc2018\Days\Day25;

class Constellation
{
    private const BONDING_DISTANCE = 3;
    private $points = [];

    public function fitsIn(VectorX $newPoint): bool
    {
        if (count($this->points) === 0) {
            return true;
        }
        foreach ($this->points as $point) {
            if ($newPoint->manhattanDistance($point) <= self::BONDING_DISTANCE) {
                return true;
            }
        }
        return false;
    }

    public function add(VectorX $newPoint): void
    {
        $this->points[] = $newPoint;
    }
}