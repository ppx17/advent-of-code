<?php


namespace Ppx17\Aoc2020\Aoc\Days\Common;


class Vector2
{
    private int $x;
    private int $y;

    public function __construct(int $x = 0, int $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public static function north(): self
    {
        return new self(0, -1);
    }

    public static function east(): self
    {
        return new self(1, 0);
    }

    public static function west(): self
    {
        return new self(-1, 0);
    }

    public static function south(): self
    {
        return new self(0, 1);
    }

    public function move(Vector2 $direction, int $distance = 1): self
    {
        return new Vector2(
            $this->x + ($direction->x * $distance),
            $this->y + ($direction->y * $distance)
        );
    }

    public function rotateRightTimes(int $times = 1): self
    {
        $times %= 4;
        switch ($times) {
            case 1:
                return $this->rotateRight();
            case 2:
                return $this->reverse();
            case 3:
                return $this->rotateLeft();
            case 0:
            default:
                return clone $this;
        }
    }

    public function rotateRight(): self
    {
        return new Vector2(-$this->y, $this->x);
    }

    public function reverse(): self
    {
        return new Vector2(-$this->x, -$this->y);
    }

    public function rotateLeft(): self
    {
        return new Vector2($this->y, -$this->x);
    }

    public function rotateLeftTimes(int $times = 1): self
    {
        $times %= 4;
        switch ($times) {
            case 1:
                return $this->rotateLeft();
            case 2:
                return $this->reverse();
            case 3:
                return $this->rotateRight();
            case 0:
            default:
                return clone $this;
        }
    }

    public function manhattan(?Vector2 $other = null): int
    {
        $other ??= new Vector2();

        return abs($this->x - $other->x) + abs($this->y + $other->y);
    }

}