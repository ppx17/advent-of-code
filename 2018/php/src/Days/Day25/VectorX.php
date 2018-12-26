<?php

namespace Ppx17\Aoc2018\Days\Day25;


class VectorX
{
    private $points;

    public function __construct(array $points)
    {
        $this->points = array_map(function($x) { return intval($x); }, $points);
    }

    public function manhattanDistance(VectorX $other)
    {
        $dist = 0;
        foreach ($this->points as $index => $point) {
            $dist += abs($point - $other->getPoint($index));
        }
        return $dist;
    }

    public function getPoint(int $index): ?int {
        return $this->points[$index] ?? null;
    }

    public function __toString()
    {
        return implode(', ', $this->points);
    }
}