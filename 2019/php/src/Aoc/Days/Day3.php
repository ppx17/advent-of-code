<?php

namespace Ppx17\Aoc2019\Aoc\Days;

use Illuminate\Support\Collection;

class Day3 extends AbstractDay
{
    private array $instructions;
    private Collection $crossings;
    private array $grids;

    public function setUp(): void
    {
        parent::setUp();
        $this->instructions = array_map(fn($l) => explode(",", $l), $this->getInputLines());

        $this->grids = array_map(function ($line) {
            $x = 0;
            $y = 0;
            $stepCount = 0;
            $visited = [];
            foreach ($line as $instruction) {
                $direction = substr($instruction, 0, 1);
                $times = (int)substr($instruction, 1);
                $velocityX = 0;
                $velocityY = 0;
                switch ($direction) {
                    case 'U':
                        $velocityY = -1;
                        break;
                    case 'D':
                        $velocityY = 1;
                        break;
                    case 'L':
                        $velocityX = -1;
                        break;
                    case 'R':
                        $velocityX = 1;
                        break;
                }
                for ($i = 0; $i < $times; $i++) {
                    $x += $velocityX;
                    $y += $velocityY;
                    $stepCount++;
                    $visited[$x . ':' . $y] = $stepCount;
                }
            }
            return $visited;
        }, $this->instructions);

        $this->crossings = collect(array_intersect_key($this->grids[0], $this->grids[1]));
    }

    public function dayNumber(): int
    {
        return 3;
    }

    public function part1(): string
    {
        return $this
            ->crossings
            ->map(function($steps, $location) {
                $parts = explode(':', $location);
                return abs((int)$parts[0]) + abs((int)$parts[1]);
            })
            ->min();
    }

    public function part2(): string
    {
        return $this
            ->crossings
            ->map(function($steps, $location) {
                return $this->grids[0][$location] + $this->grids[1][$location];
            })
            ->min();
    }
}
