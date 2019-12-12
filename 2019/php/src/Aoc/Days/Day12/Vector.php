<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day12;

class Vector
{
    public int $x;
    public int $y;
    public int $z;

    public function __construct(int $x, int $y, int $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function add(Vector $other): Vector
    {
        return new Vector(
            $this->x + $other->x,
            $this->y + $other->y,
            $this->z + $other->z,
        );
    }

    public function absoluteSum(): int
    {
        return abs($this->x) + abs($this->y) + abs($this->z);
    }

    public function toArray(): array
    {
        return [$this->x, $this->y, $this->z];
    }
}
