<?php
/**
 * Created by PhpStorm.
 * User: niels
 * Date: 20-12-18
 * Time: 13:27
 */

namespace Ppx17\Aoc2018\Days\Common;


class Vector
{
    public $x;
    public $y;

    public function __construct(int $x, int $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function up(): Vector
    {
        return new Vector($this->x, $this->y - 1);
    }

    public function down(): Vector
    {
        return new Vector($this->x, $this->y + 1);
    }

    public function left(): Vector
    {
        return new Vector($this->x - 1, $this->y);
    }

    public function right(): Vector
    {
        return new Vector($this->x + 1, $this->y);
    }

    public function add(Vector $point): void
    {
        $this->x += $point->x;
        $this->y += $point->y;
    }

    public function manhattanDistance(Vector $point): int
    {
        return abs($this->x - $point->x) + abs($this->y - $point->y);
    }

    public function equals(Vector $vector): bool
    {
        return $this->x === $vector->x && $this->y === $vector->y;
    }

    public function turnLeft(): void
    {
        if ($this->x === 0) {
            $this->x = $this->y;
            $this->y = 0;
        } else {
            $this->y = -$this->x;
            $this->x = 0;
        }
    }

    public function turnRight(): void
    {
        if ($this->x === 0) {
            $this->x = -$this->y;
            $this->y = 0;
        } else {
            $this->y = $this->x;
            $this->x = 0;
        }
    }

    public function isLeft(): bool
    {
        return $this->x === -1 && $this->y === 0;
    }

    public function isRight(): bool
    {
        return $this->x === 1 && $this->y === 0;
    }

    public function isUp(): bool
    {
        return $this->x === 0 && $this->y === -1;
    }

    public function isDown(): bool
    {
        return $this->x === 0 && $this->y === 1;
    }
}