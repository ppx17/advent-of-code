<?php

namespace Ppx17\Aoc2018\Days\Day23;


class Vector3
{
    public $x;
    public $y;
    public $z;

    public function __construct(int $x, int $y, int $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function manhattanDistanceInt(int $x, int $y, int $z): int
    {
        return abs($this->x - $x) + abs($this->y - $y) + abs($this->z - $z);
    }

    public function manhattanDistance(Vector3 $point): int
    {
        return $this->manhattanDistanceInt($point->x, $point->y, $point->z);
    }

    public function add(Vector3 $point): void
    {
        $this->x += $point->x;
        $this->y += $point->y;
        $this->z += $point->z;
    }

    public function divide(int $divisor)
    {
        $this->x /= $divisor;
        $this->y /= $divisor;
        $this->z /= $divisor;
    }
}