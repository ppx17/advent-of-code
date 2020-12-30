<?php

namespace Ppx17\Aoc2020\Aoc\Days;

class Day3 extends AbstractDay
{
    private array $map;
    private int $width;
    private int $height;

    public function dayNumber(): int
    {
        return 3;
    }

    public function setUp(): void
    {
        $this->map = $this->getInputLines();
        $this->height = count($this->map);
        $this->width = strlen($this->map[0]);
    }

    public function part1(): string
    {
        return $this->treesOnSlope(3, 1);
    }

    public function part2(): string
    {
        return array_product(array_map(fn($s) => $this->treesOnSlope(...$s), [[1, 1], [3, 1], [5, 1], [7, 1], [1, 2]]));
    }

    private function treesOnSlope($xStep, $yStep)
    {
        for ($x = $y = $trees = 0; $y < $this->height; $x += $xStep, $y += $yStep) {
            $trees += ($this->map[$y][$x % $this->width] === '#' ? 1 : 0);
        }

        return $trees;
    }
}