<?php


namespace Ppx17\Aoc2015\Aoc\Common;

class Vector
{
    public int $x;
    public int $y;

    public static function create(int $x = 0, int $y = 0): Vector
    {
        return new self($x, $y);
    }

    public function __construct(int $x = 0, int $y = 0)
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function up(): self
    {
        $this->y--;
        return $this;
    }

    public function down(): self
    {
        $this->y++;
        return $this;
    }

    public function left(): self
    {
        $this->x--;
        return $this;
    }

    public function right(): self
    {
        $this->x++;
        return $this;
    }

    public function add(Vector $vector): self
    {
        $this->x += $vector->x;
        $this->y += $vector->y;
        return $this;
    }

    public function __toString()
    {
        return "{$this->x}:{$this->y}";
    }
}