<?php


namespace Ppx17\Aoc2020\Aoc\Days;


use Illuminate\Support\Collection;

class Day3 extends AbstractDay
{
    private Collection $map;
    private int $width;
    private int $height;

    public function dayNumber(): int
    {
        return 3;
    }

    public function setUp(): void
    {
        $this->map = collect($this->getInputLines())
            ->map(fn($l) => str_split($l));
        $this->height = count($this->map);
        $this->width = count($this->map[0]);
    }

    public function part2(): string
    {
        return array_product(
            collect([
                [1, 1],
                [3, 1],
                [5, 1],
                [7, 1],
                [1, 2]
            ])
                ->map(fn($s) => $this->treesOnSlope(...$s))
                ->toArray());
    }

    public function part1(): string
    {
        return $this->treesOnSlope(3);
    }

    private function treesOnSlope($xStep = 3, $yStep = 1)
    {
        $trees = 0;
        for ($x = 0, $y = 0; $y < $this->height; $x += $xStep, $y += $yStep) {
            $trees += ($this->getPos($x, $y) === '#' ? 1 : 0);
        }

        return $trees;
    }

    private function getPos($x, $y): string
    {
        return $this->map[$y][$x % $this->width];
    }
}