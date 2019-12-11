<?php


namespace Ppx17\Aoc2019\Aoc\Days\Day10;

class Vector
{
    public int $x;
    public int $y;

    public function __construct(int $x = 0, int $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function offset(Vector $other): Vector
    {
        return new Vector($other->x - $this->x, $other->y - $this->y);
    }

    public function equals(Vector $other)
    {
        return $other->x === $this->x && $other->y === $this->y;
    }

    public function isBetween(Vector $a, Vector $b)
    {
        $cross = ($this->y - $a->y) * ($b->x - $a->x) - ($this->x - $a->x) * ($b->y - $a->y);

        if (abs($cross) !== 0) {
            return false;
        }

        $dotProduct = ($this->x - $a->x) * ($b->x - $a->x) + ($this->y - $a->y) * ($b->y - $a->y);

        if ($dotProduct < 0) {
            return false;
        }

        $squaredLength = ($b->x - $a->x) * ($b->x - $a->x) + ($b->y - $a->y) * ($b->y - $a->y);

        if ($dotProduct > $squaredLength) {
            return false;
        }
        return true;
    }

    public function manhattanTo(Vector $other): int
    {
        return abs($this->x - $other->x) + abs($this->y - $other->y);
    }

    public function add(Vector $other): Vector
    {
        return new Vector($this->x + $other->x, $this->y + $other->y);
    }

    public function subtract(Vector $other): Vector
    {
        return new Vector($this->x - $other->x, $this->y - $other->y);
    }

    public function angleTo(Vector $other)
    {
        $target = $other->subtract($this);
        return ((rad2deg(atan2($target->y, $target->x)) * 100) + 45000) % 36000;
    }

    public function __toString()
    {
        return "{$this->x}:{$this->y}";
    }
}
